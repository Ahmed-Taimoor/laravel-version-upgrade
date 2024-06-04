@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div style="float: left;padding-top: 10px;"><h5><strong>Discount Offers</strong></h5></div>
                        <div style="float: right;">
                            <a href="{!! route('discount-offers.create') !!}" class="btn btn-success"><i class="fa fa-plus"></i> Add Offer</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Offer Type</th>
                                    <th>Offer Value</th>
                                    <th>Created at</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($offers as $offer)
                                <tr class="text-center">
                                    <td>{!! $offer->title !!}</td>
                                    <td>{!! $offer->code !!}</td>
                                    <td>{!! $offer->type !!}</td>
                                    <td>{!! $offer->type ? $offer->offer_value : $offer->offer_value !!}</td>
                                    <td>{!! date('M d, Y h:i A', strtotime($offer->created_at)) !!}</td>
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="{!! route('discount-offers.edit',$offer->id) !!}">Edit</a>
                                        <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="removeItem('{!! $offer->id !!}')">Remove</a>
                                        <form id="form-id{!! $offer->id !!}" action="{!! route("discount-offers.destroy",$offer->id) !!}" method="POST">
                                            @csrf {!! method_field('DELETE') !!}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $offers->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function removeItem(id) {
            if (confirm('Are you sure? \nAll transaction will also be removed related to this offer.')) {
                $('#form-id'+id).submit();
            }
        }
    </script>
@endsection
