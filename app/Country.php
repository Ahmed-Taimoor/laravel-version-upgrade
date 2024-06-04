<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['short_name','name'];

    public function states(){
        return $this->hasMany(State::class);
    }
}
