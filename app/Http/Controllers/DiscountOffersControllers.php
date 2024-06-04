<?php

namespace App\Http\Controllers;

use App\DiscountOffer;
use Illuminate\Http\Request;

class DiscountOffersControllers extends Controller
{
    protected $offer;

    public function __construct(DiscountOffer $offer)
    {
        $this->offer = $offer;
    }

    public function index()
    {
        $offers = $this->offer->paginate(30);
        return view('discount-offers.index',compact('offers'));
    }

    public function create()
    {
        return view('discount-offers.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required|max:100',
            'code'=>'required|max:100|unique:discount_offers,code',
            'type'=>'required|in:fixed-amount,percentage',
            'offer_value'=>'required',
        ]);

        $this->offer->create($request->all());

        return redirect()->route('discount-offers.index')->with('message','Offer created successfully!');
    }

    public function edit($id)
    {
        $offer = $this->offer->find($id);
        return view('discount-offers.edit',compact('offer'));
    }

    public function update(Request $request,$id)
    {
        $offer = $this->offer->find($id);

        $this->validate($request,[
            'title'=>'required|max:100',
            'code'=>'required|max:100|unique:discount_offers,code,'.$offer->id,
            'type'=>'required|in:fixed-amount,percentage',
            'offer_value'=>'required',
        ]);

        $offer->update($request->all());

        return redirect()->route('discount-offers.index')->with('message','Offer updated successfully!');
    }

    public function destroy($id)
    {
        $this->offer->destroy($id);
        return redirect()->back()->with('message','Offer removed successfully!');
    }

    public function verifyDiscountCode(Request $request)
    {
        $amount = (float)$request->amount;
        $offer = $this->offer->where('code',$request->code)->first();

        if($offer)
        {
            $discount = null;
            if($offer->type == 'percentage'){
                $discount = $this->percentageOf($offer->offer_value, $amount);
                $totalAmount = $amount - $discount;
            } else{
                $totalAmount = $amount - (float)$offer->offer_value;
            }

            $offer->offer_value = $offer->type == 'percentage' ? $offer->offer_value.'%' : '$'.$offer->offer_value;
            if($discount){
                $offer->offer_value .= '($'.number_format($discount,2).')';
            }

            $data = [
                'total_amount' => $totalAmount,
                'amount_text' => '$'.number_format($totalAmount,2),
                'offer_id' => $offer->id,
                'offer_name' => $offer->title,
                'offer_value' => '-'.$offer->offer_value
            ];
            return response()->json([
                'status' =>true,
                'data' =>$data,
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => null,
        ]);
    }

    private function percentageOf($number, $total){
        $number = (float)$number;
        $total = (float)$total;

        return round(($total/(100/$number)), 2);
    }
}
