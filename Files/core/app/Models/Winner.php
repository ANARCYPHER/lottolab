<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $guarded = ['id'];

    public function tickets()
    {
    	return $this->belongsTo(Ticket::class,'ticket_id','id');
    }

    public function lotteries()
    {
    	return $this->belongsTo(Ticket::class,'ticket_id','lottery_id');
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
