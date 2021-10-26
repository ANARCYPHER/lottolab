<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Phase;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\Winner;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    public function manual(){
    	$pageTitle = "Manual Draw";
    	$manuals = Phase::where('at_dr',0)->where('draw_status',0)->where('status',1)->where('end','<',Carbon::now()->toDateTimeString())->with('lottery')->paginate(getPaginate());
    	$empty_message = "Manual Draw Lottery not found";
        $phase = '';
    	return view('admin.draw.manual',compact('pageTitle','manuals','empty_message','phase'));
    }

    public function findTicket($id){
        $pageTitle = "Manual Draw";
        $manuals = Phase::where('at_dr',0)->where('draw_status',0)->where('status',1)->where('end','<',Carbon::now()->toDateTimeString())->with('lottery')->get();

        $phase = $manuals->where('id',$id)->first();

        $tickets = Ticket::where('phase_id',$id)->with('user')->get();

        $empty_message = "Manual Draw Lottery not found";
        return view('admin.draw.manual',compact('pageTitle','manuals','empty_message','phase','tickets'));
    }

    public function draw(Request $request,$id){
        $request->validate([
            'number'=>'nullable|array',
            'number.*'=>'required|integer'
        ]);
        $phase = Phase::find($id);
        if (!$phase) {
            $notify[] = ['error', 'Phase Not found'];
            return back()->withNotify($notify);
        }

        $general = GeneralSetting::first();
        $trx = getTrx();
        $nuGet = $request->number;
        $winBon = $phase->lottery->bonuses;


        if (collect($nuGet)->count() > $winBon->count() || collect($nuGet)->count() != $winBon->count()) {
            $notify[] = ['error', 'You have to select '.$winBon->count().' number tickets'];
            return back()->withNotify($notify);
        }


        foreach ($nuGet as $key => $nums) {

         $ticket = Ticket::where('ticket_number',$nums)->first();
         $user = $ticket->user;
         $bon = @$winBon->where('level',$key)->first();
         if (!$bon) {
             break;
         }
         $bonus = @$bon->amount;
         $winnerId = Winner::insertGetId([
            'ticket_id'=>$ticket->id,
            'user_id' => $user->id,
            'phase_id'=>$phase->id,
            'ticket_number'=>$nums,
            'level'=>$bon->level,
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
            'details' => 'You are winner '.$bon->level.' of '.@$ticket->lottery->name.' of phase '.@$ticket->phase->phase,
            'trx' => $trx,
            'remark' => 'win_bonus',
            'post_balance' => $user->balance,
        ]);
        $phase->draw_status = 1;
        $phase->updated_at = Carbon::now();
        $phase->save();

         if ($general->wc == 1) {
                levelCommision($user->id, $bonus, $commissionType = 'win');
            }
         notify($user, 'WIN_EMAIL', [
            'lottery'=>$ticket->lottery->name,
            'number'=>$nums,
            'amount'=>$bon->amount,
            'level'=>$bon->level,
            'currency'=>$general->cur_text
        ]);
     }
     $notify[] = ['success','Winner Created Successfully'];
     return back()->withNotify($notify);
  }

}
