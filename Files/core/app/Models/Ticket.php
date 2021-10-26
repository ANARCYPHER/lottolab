<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $cast = ['ticket_number'=>'object'];

    protected $guarded = ['id'];

    public function lottery()
    {
    	return $this->belongsTo(Lottery::class);
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class,'user_id','id');
    }

    public function winners()
    {
    	return $this->hasMany(Winner::class);
    }
}
