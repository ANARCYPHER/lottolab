<?php

namespace App\Http\Controllers;
use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Page;
use App\Models\Phase;
use App\Models\Subscriber;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\Winner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SiteController extends Controller
{
    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function index(){
        $count = Page::where('tempname',$this->activeTemplate)->where('slug','home')->count();
        if($count == 0){
            $page = new Page();
            $page->tempname = $this->activeTemplate;
            $page->name = 'HOME';
            $page->slug = 'home';
            $page->save();
        }

        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle = 'Home';
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','home')->first();
        return view($this->activeTemplate . 'home', compact('pageTitle','sections'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        return view($this->activeTemplate . 'contact',compact('pageTitle'));
    }


    public function contactSubmit(Request $request)
    {

        $attachments = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);


        $random = getNumber();
        
        $ticket = new SupportTicket();
        $ticket->user_id = auth()->user() ? 1 : 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->id() ?? 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }

    public function blog()
    {
        $data['pageTitle'] = 'Blog';
        $data['blogs'] = Frontend::where('data_keys','blog.element')->orderBy('id','desc')->paginate(getPaginate());
        $data['blog_content'] = getContent('blog.content', true);
        return view($this->activeTemplate . 'blog.blogs', $data);
    }

    public function blogDetails($id,$slug){
        $blog = Frontend::where('id',$id)->where('data_keys','blog.element')->firstOrFail();
        $blog->increment('views');

        $recent_blogs = Frontend::where('id', '!=', $id)->where('data_keys', 'blog.element')->latest('id')->take(5)->get();

        $pageTitle = 'Blog Details';
        return view($this->activeTemplate.'blog.blog_details',compact('blog','pageTitle', 'recent_blogs'));
    }


    public function cookieAccept(){
        session()->put('cookie_accepted',true);

        return response()->json(['success' => 'Cookie accepted successfully']);
    }

    public function placeholderImage($size = null){
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function links($id,$slug){
        $data = Frontend::where('id',$id)->where('data_keys','extra.element')->firstOrFail();
        $pageTitle = $data->data_values->title;
        return view($this->activeTemplate.'links',compact('data','pageTitle'));
    }

    public function lottery()
    {
        $pageTitle = "Lotteries";
        $phases = Phase::where('status',1)->where('draw_status',0)->where('start','<',Carbon::now()->toDateTimeString())->orderBy('end','asc')->whereHas('lottery',function($lottery){
            $lottery->where('status',1);
        })->with(['lottery'])->paginate(getPaginate());
        $empty_message = "Data Not Found";
        return view($this->activeTemplate . 'lottery', compact('pageTitle','phases','empty_message'));
    }

    public function lotterySingle($id)
    {
        $phase = Phase::findOrFail($id);
        $pageTitle = $phase->lottery->name." Details";
        if ($phase->end <= Carbon::now()) {
            $notify[] = ['error','Oops! Time Out'];
            return redirect()->route('home')->withNotify($notify);
        }
        if (($phase->start > Carbon::now())) {
            $notify[] = ['error','Oops! Thats not started'];
            return redirect()->route('home')->withNotify($notify);
        }
        return view($this->activeTemplate . 'lotterySingle', compact('pageTitle','phase'));
    }

    public function subscribe(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255|unique:subscribers',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        Subscriber::create([
            'email'=>$request->email
        ]);

        $notify[] = ['success','Subscribed Successfully'];
        return response()->json(['success'=>'Subscribe successfully']);
    }

    //Cron
    public function cron(){
        $phases = Phase::where('status',1)->with('tickets')->where('draw_status',0)->where('at_dr',1)->where('end','<=',Carbon::now())->get();
        $now = Carbon::now();
        $general = GeneralSetting::first();
        $general->last_cron = $now;
        $general->save();
        $trx = getTrx();
        foreach ($phases as $phase) {
            $phase->draw_status = 1;
            $phase->updated_at = Carbon::now();
            $phase->save();
            if ($phase->tickets->count() <= 0) {
                continue;
            }
            foreach ($phase->tickets as $ticket) {
                $numbers[] = $ticket->ticket_number;
            }

            $num = collect($numbers);
            $winBon = $phase->lottery->bonuses;
            if ($winBon->count() > $num->count()) {
                $nuGet = $num;
            }else{
                $nuGet = $num->random($winBon->count());
            }
            foreach ($nuGet as $key => $nums) {
                $ticket = Ticket::where('ticket_number',$nums)->first();
                $user = $ticket->user;
                $bonus = $winBon[$key]->amount;
                $winnerId = Winner::insertGetId([
                    'ticket_id'=>$ticket->id,
                    'user_id' => $user->id,
                    'phase_id'=>$phase->id,
                    'ticket_number'=>$nums,
                    'level'=>$winBon[$key]->level,
                    'win_bonus'=>$bonus,
                    'created_at'=>Carbon::now(),
                ]);
                $user->balance += $bonus;
                $user->save();
                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $bonus,
                    'charge' => 0,
                    'trx_type' => '+',
                    'details' => 'You are winner '.$winBon[$key]->level.' of '.@$ticket->lottery->name.' of phase '.@$ticket->phase->phase,
                    'trx' => $trx,
                    'remark' => 'win_bonus',
                    'post_balance' => $user->balance,
                ]);
                if ($general->wc == 1) {
                    levelCommision($user->id, $bonus, $commissionType = 'win');
                }
                notify($user, 'WIN_EMAIL', [
                    'lottery'=>$ticket->lottery->name,
                    'number'=>$nums,
                    'amount'=>getAmount($bonus),
                    'level'=>$winBon[$key]->level,
                    'currency'=>$general->cur_text
                ]);
            }
        }
    }
}
