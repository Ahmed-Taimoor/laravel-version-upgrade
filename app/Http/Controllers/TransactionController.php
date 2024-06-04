<?php

namespace App\Http\Controllers;

use App\Country;
use App\Mail\TransactionCompleted;
use App\Transaction;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function checkout(){
        $countries = Country::all();
        $states = Country::where('short_name','US')->first()->states;

        return view('transactions.checkout',compact('countries','states'));
    }

    public function index(Request $request)
    {
        $transactions = $this->transaction->with('discountOffer');

        if($request->has('date')){
            $transactions = $this->transaction->whereDate('created_at',date('Y-m-d',strtotime($request->date)));
        }

        $transactions = $transactions->orderBy('created_at','Desc')->paginate(30);

        return view('transactions.index',compact('transactions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|max:190',
            'address' => 'required|max:190',
            'item_name' => 'required',
            'total_amount' => ['required',function ($attribute, $value, $fail) {
                if ((float)$value <= 0.0 ) {
                    $fail($attribute.' should be greater than zero.');
                }
            }],
        ]);

        $oldRequest = $request->all();
        $transaction = $this->transaction->create($request->all());

        $description = "Name: $request->first_name $request->last_name, Email:$request->email, Item:$request->item_name, package: $request->package";

        $token = $request->get('stripeToken');

        if(!$token){
            $request->session()->keep($oldRequest);
            return redirect()->back()->with('message','Transaction Failed! Something went wrong error.');
        }
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
			$pay_amount = $request->total_amount * 100;
            $charge = \Stripe\Charge::create([
                'currency' => 'usd',
                'amount' => $pay_amount,
                'description' => $description,
                'source' => $token,
            ]);

            if ($charge['status'] == 'succeeded')
            {
                $transaction->stripe_transaction_id = $charge['id'];
                $transaction->status = true;
                $transaction->save();

                $this->sendMail($transaction);

                return redirect('thank-you');
            } else {
                $request->session()->keep($oldRequest);
                return redirect()->back()->with('message','Transaction Failed! Something went wrong error.');
            }
        }
        catch (\Exception $e) {
            return redirect()->back()->with('message',$e->getMessage())->withInput();
        }
    }

    public function mail(){
        return view('emails.transaction-invoice',compact('transaction'))->withInput();
    }

    private function sendMail($transaction){
        Mail::to($transaction->email)->send(new TransactionCompleted($transaction));
    }

}
