<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountOffer extends Model
{
    protected $fillable = ['title','code','type','offer_value'];

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
