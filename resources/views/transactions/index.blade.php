@extends('layouts.app')

@section('styles')
    <style>
        .modal-body ul{
            padding: 10px;
        }
        .modal-body ul > li {
            padding: 4px 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            display: inline-block;
            width: 100%;
            vertical-align: top;
        }
        .modal-body li span.field{
            font-weight: bold;
            padding-right: 10px;
            color: #009ce9;
            float: left;
            width: 35%;
        }
        .modal-body li span.field-value{
            float:right;
            width: 65%;
        }
        @media(max-width: 480px){
            .modal-body li span.field,.modal-body li span.field-value{
                width: 100%;
                padding: 2px 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Transactions</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{!! route('transactions') !!}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="date" name="date" class="form-control" value="{!! request()->get('date') !!}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{!! route('transactions') !!}" class="btn btn-default">Clear</a>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Date Time</th>
                                    <th>Status</th>
                                    <th>Item</th>
                                    <th>Package</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Total Bill</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="text-center">
                                    <td>{!! date('M d, Y h:i A', strtotime($transaction->created_at)) !!}</td>
                                    <td><span class="{!! ['text-danger','text-success'][$transaction->status] !!}">
                                            <strong>{!! ['Not completed','Completed'][$transaction->status] !!}</strong></span>
                                    </td>
                                    <td>{!! $transaction->item_name !!}</td>
                                    <td>{!! $transaction->package !!}</td>
                                    <td>{!! $transaction->first_name .' '. $transaction->last_name !!}</td>
                                    <td>{!! $transaction->email !!}</td>
                                    <td>${!! number_format($transaction->total_amount,2) !!}</td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                                           data-toggle="modal" data-target="#view{!! $transaction->id !!}">View</a>
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($transactions) == 0)
                                <tr class="text-center">
                                    <td colspan="8"><strong class="text-danger">No Record Found</strong></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {!! $transactions->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($transactions as $transaction)
        <div id="view{!! $transaction->id !!}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Transaction {!! date('M d, Y h:i A', strtotime($transaction->created_at)) !!}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php $offer = $transaction->discountOffer ?>
                        <ul style="list-style-type: none;">
                            <li>
                                <span class="field">Stripe Transaction Id</span>
                                <span class="field-value">{!! $transaction->stripe_transaction_id !!}</span>
                            </li>
                            <li>
                                <span class="field">Status</span>
                                <span class="field-value {!! ['text-danger','text-success'][$transaction->status] !!}">
                                    <strong>{!! ['Not completed','Completed'][$transaction->status] !!}</strong></span>
                            </li>
                            <li>
                                <span class="field">Item Name</span>
                                <span class="field-value">{!! $transaction->item_name !!}</span>
                            </li>
                            <li>
                                <span class="field">Item Price</span>
                                <span class="field-value">${!! number_format($transaction->item_amount,2) !!}</span>
                            </li>
                            <li>
                                <span class="field">Discount Offer</span>
                                <span class="field-value">{!! $offer ? $offer->title : '' !!}
                                    @if($offer and $offer->type == 'percentage')
                                        {!! $offer ? ' ('.number_format($offer->offer_value,1).'%)' : '' !!}
                                    @else
                                        {!! $offer ? ' ($'.number_format($offer->offer_value,1).')' : '' !!}
                                    @endif
                                </span>
                            </li>
                            <li>
                                <span class="field">Total Bill</span>
                                <span class="field-value">${!! number_format($transaction->total_amount,2) !!}</span>
                            </li>
                            <li>
                                <span class="field">Name On Card</span>
                                <span class="field-value">{!! $transaction->name_on_card !!}</span>
                            </li>
                            <li>
                                <span class="field">Client First Name</span>
                                <span class="field-value">{!! $transaction->first_name !!}</span>
                            </li>
                            <li>
                                <span class="field">Client last Name</span>
                                <span class="field-value">{!! $transaction->last_name !!}</span>
                            </li>
                            <li>
                                <span class="field">Client Email Address</span>
                                <span class="field-value">{!! $transaction->email !!}</span>
                            </li>
                            <li>
                                <span class="field">Client Company</span>
                                <span class="field-value">{!! $transaction->company !!}</span>
                            </li>
                            <li>
                                <span class="field">Street Address</span>
                                <span class="field-value">{!! $transaction->city !!}</span>
                            </li>
                            <li>
                                <span class="field">City</span>
                                <span class="field-value">{!! $transaction->city !!}</span>
                            </li>
                            <li>
                                <span class="field">State</span>
                                <span class="field-value">{!! $transaction->state !!}</span>
                            </li>
                            <li>
                                <span class="field">Country</span>
                                <span class="field-value">{!! $transaction->country !!}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection