<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['state_id','name'];

    public function state(){
        return $this->belongsTo(State::class);
    }
}
