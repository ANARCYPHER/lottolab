<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    protected $guarded = ['id'];

    public function tickets()
    {
    	return $this->hasMany(Ticket::class);
    }

    public function phase()
    {
        return $this->hasMany(Phase::class);
    }

    public function bonuses()
    {
    	return $this->hasMany(WinBonus::class);
    }

    public function scopeActive(){
    	return $this->where('status',1);
    }
}
