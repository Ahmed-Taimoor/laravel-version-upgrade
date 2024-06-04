@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-header"><a href="{!! route('transactions') !!}">Transactions</a></div>
                <div class="card-body">Total {!! \App\Transaction::count(); !!}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-header"><a href="{!! route('discount-offers.index') !!}">Discount Offers</a></div>
                <div class="card-body">Total {!! \App\DiscountOffer::count(); !!}</div>
            </div>
        </div>
    </div>
</div>
@endsection
