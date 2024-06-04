<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'discount_offer_id','package',
        'item_name','item_amount','total_amount',
        'first_name','last_name','email','contact_number',
        'company','address','country','city','state','zip_code'
    ];

    public function discountOffer(){
        return $this->belongsTo(DiscountOffer::class,'discount_offer_id');
    }
}
