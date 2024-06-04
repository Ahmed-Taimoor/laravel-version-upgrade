@extends('layouts.app')

@section('content')
    <?php function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add New Offer</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{!! route('discount-offers.update',$offer->id) !!}" method="POST">
                            @csrf {!! method_field("PUT") !!}

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Offer Title</label>
                                        <input type="text" name="title" placeholder="Offer Title" class="form-control" value="{!! old('title') ? old('title') : $offer->title !!}">
                                    </div>
                                    <div class="form-group">
                                        <label>Offer Type</label>
                                        <select name="type" class="form-control">
                                            <option value="fixed-amount" {!! $offer->type == 'fixed-amount' ? 'selected' : '' !!}>Fixed Amount</option>
                                            <option value="percentage" {!! $offer->type == 'percentage'  ? 'selected' : '' !!}>Percentage</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Offer Code</label>
                                        <input type="text" name="code" placeholder="Offer Code" class="form-control" value="{!! old('code') ? old('code') : $offer->code !!}">
                                    </div>
                                    <div class="form-group">
                                        <label>Offer Value</label>
                                        <input type="number" name="offer_value" placeholder="Offer Value" class="form-control" min="0" value="{!! old('offer_value') ? old('offer_value') : $offer->offer_value !!}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="text-right">
                                    <a class="btn btn-default" href="{!! route('discount-offers.index') !!}">Cancel</a>
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
