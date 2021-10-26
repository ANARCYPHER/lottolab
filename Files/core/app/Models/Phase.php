<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $guarded = ['id'];

    public function lottery()
    {
    	return $this->belongsTo(Lottery::class);
    }

    public function tickets()
    {
    	return $this->hasMany(Ticket::class);
    }

    public function winners()
    {
    	return $this->hasMany(Winner::class);
    }
}
