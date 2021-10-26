<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CommissionLog;
use App\Models\EmailLog;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\UserLogin;
use App\Models\Winner;
use Illuminate\Http\Request;
class ReportController extends Controller
{
    public function transaction()
    {
        $pageTitle = 'Transaction Logs';
        $transactions = Transaction::with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No transactions.';
        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'emptyMessage'));
    }
    public function transactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = 'Transactions Search - ' . $search;
        $emptyMessage = 'No transactions.';
        $transactions = Transaction::with('user')->whereHas('user', function ($user) use ($search) {
            $user->where('username', 'like',"%$search%");
        })->orWhere('trx', $search)->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'emptyMessage','search'));
    }
    public function loginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'User Login History Search - ' . $search;
            $emptyMessage = 'No search result found.';
            $login_logs = UserLogin::whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            })->orderBy('id','desc')->paginate(getPaginate());
            return view('admin.reports.logins', compact('pageTitle', 'emptyMessage', 'search', 'login_logs'));
        }
        $pageTitle = 'User Login History';
        $emptyMessage = 'No users login found.';
        $login_logs = UserLogin::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }
    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login By - ' . $ip;
        $login_logs = UserLogin::where('user_ip',$ip)->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No users login found.';
        return view('admin.reports.logins', compact('pageTitle', 'emptyMessage', 'login_logs','ip'));
    }
    public function emailHistory(){
        $pageTitle = 'Email history';
        $logs = EmailLog::with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.reports.email_history', compact('pageTitle', 'emptyMessage','logs'));
    }
    public function commissionsDeposit()
    {
        $pageTitle = 'Deposit Commission Log';
        $logs = CommissionLog::where('commission_type','deposit')->with(['userTo','userFrom'])->latest()->paginate(getPaginate());
        $empty_message = 'No log.';
        return view('admin.reports.commission-log', compact('pageTitle', 'logs', 'empty_message'));
    }
    public function commissionsBuy()
    {
        $pageTitle = 'Buy Lotter Commission Log';
        $logs = CommissionLog::where('commission_type','buy')->with(['userTo','userFrom'])->latest()->paginate(getPaginate());
        $empty_message = 'No log.';
        return view('admin.reports.commission-log', compact('pageTitle', 'logs', 'empty_message'));
    }
    public function commissionsWin()
    {
        $pageTitle = 'Win Lottery Commission Log';
        $logs = CommissionLog::where('commission_type','win')->with(['userTo','userFrom'])->latest()->paginate(getPaginate());
        $empty_message = 'No log.';
        return view('admin.reports.commission-log', compact('pageTitle', 'logs', 'empty_message'));
    }
    public function commissionsSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = 'Commission Log Search -'.$search;
        $logs = CommissionLog::whereHas('userTo', function ($user) use ($search) {
            $user->where('username', 'like',"%$search%");
        })->orWhere('trx', $search)->with(['userTo','userFrom'])->latest()->paginate(getPaginate());
        $empty_message = 'No log.';
        return view('admin.reports.commission-log', compact('pageTitle', 'logs', 'empty_message','search'));
    }
    public function winners(){
        $pageTitle = "Winners";
        $winners = Winner::orderBy('id','desc')->with(['tickets','tickets.user','tickets.lottery','tickets.phase'])->paginate(getPaginate());
        $empty_message = "Winners Not Found";
        return view('admin.reports.winners',compact('pageTitle','winners','empty_message'));
    }
    public function winnerSearch(Request $request){
        $search = $request->search;
        $winners = Winner::where(function ($ticket) use ($search) {
            $ticket->whereHas('user',function($query) use ($search){
                $query->where('username',$search);
            })
                ->orWhere('ticket_number', 'like', "%$search%");
        })->with('user','lotteries','tickets')->paginate(getPaginate());
        $pageTitle = 'Ticket Search: '.$request->search;
        $empty_message = "Winners Not Found";
        return view('admin.reports.winners', compact('pageTitle', 'search', 'empty_message', 'winners'));
    }
    public function tickets()
    {
        $pageTitle = 'Sold Lottery Tickets';
        $tickets = Ticket::where('status',1)->orderBy('id','desc')->with('user','lottery','phase')->paginate(getPaginate());
        $empty_message = "Tickets Not Found";
        return view('admin.reports.tickets', compact('pageTitle','tickets','empty_message'));
    }
    public function lotterySearch(Request $request)
    {
        $search = $request->search;
        $tickets = Ticket::where(function ($ticket) use ($search) {
            $ticket->whereHas('user',function($query) use ($search){
                $query->where('username',$search);
            })
                ->orWhere('ticket_number', 'like', "%$search%");
        })->with('user','lottery','phase')->paginate(getPaginate());
        $pageTitle = 'Ticket Search: '.$request->search;
        $empty_message = 'No search result found';
        return view('admin.reports.tickets', compact('pageTitle', 'search', 'empty_message', 'tickets'));
    }
}