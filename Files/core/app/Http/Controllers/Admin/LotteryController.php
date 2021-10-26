<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lottery;
use App\Http\Controllers\Controller;
use App\Models\Phase;
use App\Models\WinBonus;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use HTMLPurifier;

class LotteryController extends Controller
{
    public function index(){
    	$pageTitle = "Lotteries";
    	$lotteries = Lottery::orderBy('id','desc')->paginate(getPaginate());
    	$empty_message = "No Data Found";
    	return view('admin.lottery.index',compact('pageTitle','lotteries','empty_message'));
    }


    public function create(){
        $pageTitle = "Lottery Create";
        return view('admin.lottery.create',compact('pageTitle'));
    }

    public function store(Request $request){
    	$request->validate([
    		'name'=>'required',
            'price'=>'required|numeric|gt:0',
            'detail'=>'required',
            'image'=>'required',
    	]);
        $validation_rule['image'] = ['image', new FileTypeValidate(['jpeg', 'jpg','png'])];
        $request->validate($validation_rule);

    	if ($request->hasFile('image')) {
    		try {
    			$size = imagePath()['lottery']['size'];
    			$path = imagePath()['lottery']['path'];
    			$image = uploadImage($request->image, $path,$size);
    		} catch (\Exception $e) {
    			$notify[] = ['error','Image Could not be uploaded'];
    		}
    	}
        $purifier = new HTMLPurifier();

        $detail = $purifier->purify($request->detail);
    	$lottery = Lottery::create([
    		'name'=>$request->name,
            'image'=>$image,
            'price'=>$request->price,
            'detail'=>$detail,
    	]);
    	$notify[] = ['success','Lottery created successfully'];
    	return redirect()->route('admin.lottery.winBonus',$lottery->id)->withNotify($notify);
    }

    public function update($id){
        $pageTitle = "Update Lottery";
        $lottery = Lottery::findOrFail($id);
        return view('admin.lottery.update',compact('lottery','pageTitle'));
    }
    public function edit(Request $request,$id){
    	$request->validate([
    		'name'=>'required',
    		'price'=>'required|numeric|gt:0',
            'detail'=>'required',
    	]);
        $validation_rule['image'] = ['image', new FileTypeValidate(['jpeg', 'jpg','png'])];
        $request->validate($validation_rule);

    	$lottery = Lottery::find($id);
    	$image = $lottery->image;
    	if ($request->hasFile('image')) {
    		try {
    			$size = imagePath()['lottery']['size'];
                $path = imagePath()['lottery']['path'];
    			$image = uploadImage($request->image, $path,$size,$lottery->image);
    		} catch (\Exception $e) {
    			$notify[] = ['error','Image Could not be uploaded'];
    		}
    	}
        $purifier = new HTMLPurifier();
        $detail = $purifier->purify($request->detail);
    	$lottery->update([
    		'name'=>$request->name,
    		'image'=>$image,
            'price'=>$request->price,
            'detail'=>$detail,
    	]);
    	$notify[] = ['success','Lottery uploaded successfully'];
    	return back()->withNotify($notify);

    }

    public function status($id){
    	$lottery = Lottery::find($id);
    	if ($lottery->status == 1) {
    		$lottery->update(['status'=>0]);
    	}else{
    		$lottery->update(['status'=>1]);
    	}
    	$notify[] = ['success','Lottery Status Updated'];
    	return back()->withNotify($notify);
    }

    public function phases(){
        $pageTitle = "Lottery Phases";
        $lotteries = Lottery::where('status',1)->get();
        $phases = Phase::with('lottery')->orderBy('id','desc')->whereHas('lottery',function($lottery){
            $lottery->where('status',1);
        })->paginate(getPaginate(15));
        $empty_message = "No Data Found";

        return view('admin.lottery.phases',compact('pageTitle','lotteries','phases', 'empty_message'));
    }

    public function lotteryPhase($lottery_id){
        $lottery = Lottery::findOrFail($lottery_id);
        $phases = Phase::where('lottery_id',$lottery_id)->with('lottery')->orderBy('id','desc')->paginate(getPaginate());
        $pageTitle = "Lottery Phase: ".$lottery->name;
        $empty_message = "No Data Found";
        $lotteries = Lottery::orderBy('name')->get();

        return view('admin.lottery.phases',compact('pageTitle','lottery','phases', 'empty_message', 'lotteries'));
    }

    public function phaseCreate(Request $request){
        $request->validate([
            'lottery_id'=>'required',
            'start'=>'required',
            'end'=>'required',
            'quantity'=>'required|integer|min:1',
            'draw_type'=>'required',
        ]);
        $lottery = Lottery::where('status',1)->findOrFail($request->lottery_id);
        if(collect($lottery->bonuses)->count() < 1){
            $notify[] = ['error','Create Lottery Bonus First'];
            return back()->withNotify($notify);
        }
        $exist = Phase::where('lottery_id',$request->lottery_id)->where('draw_status',0)->first();
        if ($exist) {
            $notify[] = ['error','Already 1 phase is running of this lottery'];
            return back()->withNotify($notify);
        }
        $start = Carbon::parse($request->start)->toDateTimeString();
        $end = Carbon::parse($request->end)->toDateTimeString();
        if(Carbon::now() > $end){
            $notify[] = ['error','End date must be a future date'];
            return back()->withNotify($notify);
        }
        if($start > $end){
            $notify[] = ['error','End date must be greater than start date'];
            return back()->withNotify($notify);
        }
        $phase_number = $lottery->phase->count();
        Phase::create([
            'lottery_id'=>$request->lottery_id,
            'phase_number'=>$phase_number + 1,
            'end'=>$end,
            'start'=>$start,
            'quantity'=>$request->quantity,
            'available'=>$request->quantity,
            'at_dr'=>$request->draw_type,
        ]);
        $notify[] = ['success','Lottery Phase Created Successfully'];
        return back()->withNotify($notify);
    }

    public function phaseUpdate(Request $request,$id){
        $request->validate([
            'end'=>'required',
            'start'=>'required',
            'quantity'=>'required|integer|min:1',
            'draw_type'=>'required',
        ]);
        $phase = Phase::where('status',1)->findOrFail($id);
        $start = Carbon::parse($request->start)->toDateTimeString();
        $end = Carbon::parse($request->end)->toDateTimeString();
        if(Carbon::now() > $end){
            $notify[] = ['error','End date must be a future date'];
            return back()->withNotify($notify);
        }
        if($start > $end){
            $notify[] = ['error','End date must be greater than start date'];
            return back()->withNotify($notify);
        }
        if ($request->quantity < $phase->salled) {
            $notify[] = ['error','Quantity must be greater than salled ticket'];
            return back()->withNotify($notify);
        }
        $phase->update([
            'end'=>Carbon::parse($request->end)->toDateTimeString(),
            'start'=>Carbon::parse($request->start)->toDateTimeString(),
            'quantity'=>$request->quantity,
            'available'=>$request->quantity - $phase->salled,
            'at_dr'=>$request->draw_type,
        ]);
        $notify[] = ['success','Lottery Phase Updated Successfully'];
        return redirect()->route('admin.lottery.phase.index')->withNotify($notify);
    }

    public function phaseStatus($id){
        $phase = Phase::find($id);
        if (!$phase) {
            $notify[] = ['error','Lottery Phase Not Found'];
            return back()->withNotify($notify);
        }
        if ($phase->status == 1) {
            $phase->update(['status'=>0]);
        }else{
            $phase->update(['status'=>1]);
        }

        $notify[] = ['success','Lottery Phase Status Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function winBonus($id){
        $lottery = Lottery::find($id);
        $pageTitle = "Set Win Bonus For ".$lottery->name;
        return view('admin.lottery.winBonus',compact('lottery','pageTitle'));
    }

    public function bonus(Request $request){
        $this->validate($request, [
            'level.*' => 'required|integer|min:1',
            'amount.*' => 'required|numeric',
            'lottery_id' => 'required',
        ]);
        $bonuses = WinBonus::where('lottery_id',$request->lottery_id)->get();
        if ($bonuses->count() > 0) {
            foreach ($bonuses as $bonus) {
                $bonus->delete();
            }
        }
        for ($a = 0; $a < count($request->level); $a++){
            WinBonus::create([
                'level' => $request->level[$a],
                'amount' => $request->amount[$a],
                'lottery_id' => $request->lottery_id,
                'status' => 1,
            ]);
        }
        $notify[] = ['success', 'Create Successfully'];
        return back()->withNotify($notify);

    }
}
