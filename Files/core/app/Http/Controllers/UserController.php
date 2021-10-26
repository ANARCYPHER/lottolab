<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\CommissionLog;
use App\Models\GeneralSetting;
use App\Models\Lottery;
use App\Models\Phase;
use App\Models\Referral;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Winner;
use App\Models\WithdrawMethod;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = \auth()->user();
        $time = now()->toDateTimeString();
        $phases = Phase::where('status',1)->where('draw_status',0)->where('start','<',$time)->orderBy('end')->whereHas('lottery',function($lottery){
            $lottery->where('status',1);
        })->limit(6)->with(['lottery'])->get();

        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'user', 'phases'));
    }

    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = Auth::user();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'address' => 'sometimes|required|max:80',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
        ]);

        $user = Auth::user();

        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;

        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];


        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change password';
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);


        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Deposit History
     */
    public function depositHistory()
    {
        $pageTitle = 'Deposit History';
        $emptyMessage = 'No history found.';
        $logs = auth()->user()->deposits()->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'emptyMessage', 'logs'));
    }

    /*
     * Withdraw Operation
     */

    public function withdrawMoney()
    {
        $withdrawMethod = WithdrawMethod::where('status',1)->get();
        $pageTitle = 'Withdraw Money';
        return view($this->activeTemplate.'user.withdraw.methods', compact('pageTitle','withdrawMethod'));
    }

    public function withdrawStore(Request $request)
    {
        $this->validate($request, [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your requested amount is smaller than minimum amount.'];
            return back()->withNotify($notify);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your requested amount is larger than maximum amount.'];
            return back()->withNotify($notify);
        }

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'You do not have sufficient balance for withdraw.'];
            return back()->withNotify($notify);
        }


        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return redirect()->route('user.withdraw.preview');
    }

    public function withdrawPreview()
    {
        $withdraw = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id','desc')->firstOrFail();
        $pageTitle = 'Withdraw Preview';
        return view($this->activeTemplate . 'user.withdraw.preview', compact('pageTitle','withdraw'));
    }


    public function withdrawSubmit(Request $request)
    {
        $general = GeneralSetting::first();
        $withdraw = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id','desc')->firstOrFail();

        $rules = [];
        $inputField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($withdraw->method->user_data as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], new FileTypeValidate(['jpg','jpeg','png']));
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $this->validate($request, $rules);

        $user = auth()->user();
        if ($user->ts) {
            $response = verifyG2fa($user,$request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify);
            }
        }


        if ($withdraw->amount > $user->balance) {
            $notify[] = ['error', 'Your request amount is larger then your current balance.'];
            return back()->withNotify($notify);
        }

        $directory = date("Y")."/".date("m")."/".date("d");
        $path = imagePath()['verify']['withdraw']['path'].'/'.$directory;
        $collection = collect($request);
        $reqField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($collection as $k => $v) {
                foreach ($withdraw->method->user_data as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $withdraw['withdraw_information'] = $reqField;
        } else {
            $withdraw['withdraw_information'] = null;
        }


        $withdraw->status = 2;
        $withdraw->save();
        $user->balance  -=  $withdraw->amount;
        $user->save();



        $transaction = new Transaction();
        $transaction->user_id = $withdraw->user_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = $withdraw->charge;
        $transaction->trx_type = '-';
        $transaction->details = showAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
        $transaction->trx =  $withdraw->trx;
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New withdraw request from '.$user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.details',$withdraw->id);
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount),
            'amount' => showAmount($withdraw->amount),
            'charge' => showAmount($withdraw->charge),
            'currency' => $general->cur_text,
            'rate' => showAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($user->balance),
            'delay' => $withdraw->method->delay
        ]);

        $notify[] = ['success', 'Withdraw request sent successfully'];
        return redirect()->route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog()
    {
        $pageTitle = "Withdraw Log";
        $withdraws = Withdrawal::where('user_id', Auth::id())->where('status', '!=', 0)->with('method')->orderBy('id','desc')->paginate(getPaginate());
        $data['emptyMessage'] = "No Data Found!";
        return view($this->activeTemplate.'user.withdraw.log', compact('pageTitle','withdraws'));
    }



    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor';
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function buyTicket(Request $request){
        $request->validate([
            'lottery_id'=>'required',
            'number'=>'required|array',
            'number.*'=>'required|integer',
        ],[
            'number.*.required'=>'All Ticket Number Field is required',
        ]);

        $ticket_quantity = collect($request->number)->count();

        $phase = Phase::where('id',$request->phase_id)->where('draw_status',0)->where('status',1)->where('end','>=',Carbon::now()->toDateTimeString())->where('quantity','>=',$ticket_quantity)->first();

        //Check phase is exist or not
        if (!$phase) {
            $notify[] = ['error','Oops! Phase not found'];
            return back()->withNotify($notify);
        }

        $lottery = Lottery::where('id',$request->lottery_id)->where('status',1)->first();

        //Check lottery is exist or not
        if (!$lottery) {
            $notify[] = ['error','Oops! Lottery Not Found'];
            return back()->withNotify($notify);
        }

        $total_price = $phase->lottery->price * $ticket_quantity;

        $user = auth()->user();
        //Check Balance is available or not
        if ($user->balance < $total_price) {
            $notify[] = ['error','Oops! You have no sufficient balance'];
            return back()->withNotify($notify);
        }

        //Check Ticket quantity available or not
        if ($phase->available < $ticket_quantity) {
            $notify[] = ['error','Oops! quantity is not available'];
            return back()->withNotify($notify);
        }

        //Check End time
        if ($phase->end <= Carbon::now()) {
            $notify[] = ['error','Oops! Time Out'];
            return redirect()->route('home')->withNotify($notify);
        }

        //Check Start time
        if (($phase->start > Carbon::now())) {
            $notify[] = ['error','Oops! That\'s not started'];
            return redirect()->route('home')->withNotify($notify);
        }

        $error = 0;

        foreach ($request->number as $value) {

            for ($i=0; $i < strlen($value); $i++) {
                $sval = $value[$i];
                $intval = intval($sval);
                if ($intval == 0) {
                    if ($sval == '0') {
                        continue;
                    }
                    $notify[] = ['error','Oops! ticket number must be an integer value'];
                    return back()->withNotify($notify);
                }
            }
            Ticket::insert([
                'lottery_id'=>$request->lottery_id,
                'phase_id'=>$request->phase_id,
                'user_id'=>$user->id,
                'ticket_number'=>$value,
                'total_price'=>$phase->lottery->price,
                'status'=>1,
            ]);
        }

        $user->balance -= $total_price;
        $user->save();
        $phase->available -= $ticket_quantity;
        $phase->salled += $ticket_quantity;
        $phase->save();

        Transaction::create([
            'user_id' => auth()->user()->id,
            'amount' => $total_price,
            'charge' => 0,
            'trx_type' => '-',
            'remark' => 'Buy Ticket',
            'details' => 'Payment from user balance for '.$ticket_quantity.' ticket of lottery '.$phase->lottery->name,
            'trx' => getTrx(),
            'post_balance' => auth()->user()->balance,
        ]);
        $gnl = GeneralSetting::first();
        if ($gnl->bc == 1) {
            levelCommision($user->id, $total_price, $commissionType = 'buy');
        }
        notify($user,'BUY_LOTTERY',[
            'lottery_name'=>$phase->lottery->name,
            'quantity'=>$ticket_quantity,
            'price'=>getAmount($phase->lottery->price),
            'total_price'=>getAmount($total_price),
            'draw_date'=>$phase->end,
            'site_currency'=>$gnl->cur_text
        ]);
        $notify[] = ['success','You have buy ticket successfully'];
        return back()->withNotify($notify);
    }

    public function tickets(){
        $pageTitle = 'All Tickets You Had Bought';
        $tickets = Ticket::where('user_id',auth()->user()->id)->with('lottery','phase')->orderByDesc('id')->paginate(getPaginate());
        $empty_message = 'No Ticket You bought';
        return view($this->activeTemplate.'user.tickets',compact('pageTitle','tickets','empty_message'));
    }

    public function transactions()
    {
        $pageTitle = 'Transactions';
        $logs = auth()->user()->transactions()->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No transaction history';
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'logs', 'empty_message'));
    }

    public function wins(){
        $pageTitle = 'Your Winning Tickets';
        $wins = Winner::where('user_id',auth()->user()->id)->with('tickets','tickets.phase','tickets.lottery')->paginate(getPaginate());
        return view($this->activeTemplate.'user.wins',compact('pageTitle','wins'));
    }

    public function commissionsDeposit(){
        $pageTitle = "Deposit Commissions";
        $commissions = CommissionLog::where('to_id',auth()->user()->id)->where('commission_type','deposit')->orderBy('id','desc')->with('userFrom')->paginate(getPaginate());
        return view($this->activeTemplate.'user.commissions',compact('pageTitle','commissions'));
    }

    public function commissionsBuy(){
        $pageTitle = "Buy Commissions";
        $commissions = CommissionLog::where('to_id',auth()->user()->id)->where('commission_type','buy')->orderBy('id','desc')->with('userFrom')->paginate(getPaginate());
        return view($this->activeTemplate.'user.commissions',compact('pageTitle','commissions'));
    }

    public function commissionsWin(){
        $pageTitle = "Win Commissions";
        $commissions = CommissionLog::where('to_id',auth()->user()->id)->where('commission_type','win')->orderBy('id','desc')->with('userFrom')->paginate(getPaginate());
        return view($this->activeTemplate.'user.commissions',compact('pageTitle','commissions'));
    }

    public function referredUsers($level = 1){
        $id = auth()->user()->id;
        $myref = showBelow($id);
        $nxt = $myref;
        $firstActive = 0;
        if ($level == 1) {
            $firstActive = 1;
        }
        for ($i = 1; $i < $level; $i++) {
            $nxt = array();
            foreach ($myref as $uu) {
                $n = showBelow($uu);
                $nxt = array_merge($nxt, $n);
            }
            $myref = $nxt;
        }
        $users = User::whereIn('id',$nxt)->paginate(getPaginate());

        $pageTitle = "Referred Users";
        $lev = Referral::max('level');
        return view(activeTemplate().'user.referred',compact('pageTitle','level','lev','users','firstActive'));
    }
}
