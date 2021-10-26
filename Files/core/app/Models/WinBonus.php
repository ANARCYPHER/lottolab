<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WinBonus extends Model
{
    protected $guarded = ['id'];

    public function lottery()
    {
    	return $this->belongsTo(Lottery::class);
    }
}
