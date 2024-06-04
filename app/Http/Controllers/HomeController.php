<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function countryStates($id){
        $country = Country::find($id);

        return response()->json($country->states);
    }

    public function thankYouPage(){
        return view('thank-you');
    }
}
