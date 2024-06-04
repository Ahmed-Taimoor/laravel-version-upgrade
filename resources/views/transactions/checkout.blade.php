<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{!! csrf_token() !!}">
        <title>Checkout</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://logoengine.net/wp-content/themes/logo-mart/assets/css/style.css">

        <link href="{!! asset('assets/css/style.css') !!}" rel="stylesheet">
        <link href="{!! asset('css/spinning-loader.css') !!}" rel="stylesheet">
        <style>
            .form-group label.error{
                color: #d41919 !important;
                font-weight: 800 !important;
            }
            .form-group input.error{
                border: 2px solid #d41919 !important;
            }
            select::-ms-expand {
                display: none;
            }
        </style>
    </head>

    <body>
        <header id="main-header" class="header layout-header checkout-header">
            <div class="header-content">
                <div class="container">
                    <div class="row">
                        <div class="logo">
                            <a href="{!! request()->getRequestUri() !!}"><img src="https://cdn.shortpixel.ai/client/q_lossless,ret_img/https://logoengine.net/wp-content/themes/logo-mart/assets/images/logo-sticked.png" alt="logo"></a>
                        </div>
                        <div class="header-right-content hidden-mob-collapse">
                            <div class="contact-number">
                                <a href="tel:1(888)386-3852"><strong><i class="fa fa-phone"></i> 1 (888) 386-3852</strong></a>
                            </div>
                            <div class="process-status">
                                <div class="step current">
                                    <div class="step-status">
                                        <span>1</span>
                                    </div>
                                    <div class="step-text">
                                        <span>Select Item & Package</span>
                                    </div>
                                </div>
                                <div class="step current">
                                    <div class="step-status">
                                        <span>2</span>
                                    </div>
                                    <div class="step-text">
                                        <span>Checkout</span>
                                    </div>
                                </div>
                                <div class="step finished">
                                    <div class="step-status">
                                        <span>3</span>
                                    </div>
                                    <div class="step-text">
                                        <span>Finish</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div id="spinner" style="display: none;">
            <div class="loader-content">
                <div id="loader_1" class="loader"></div>
                <div id="loader_2" class="loader"></div>
                <div id="loader_3" class="loader"></div>
                <div id="loader_4" class="loader"></div>
                <div id="loader_5" class="loader"></div>
            </div>
        </div>

        <?php
        $offer = null;
        $totalAmount = 0.0;
        function percentageOf($number, $total){
            $number = (float)$number;
            $total = (float)$total;

            return round(($total/(100/$number)), 2);
        }

        if(request()->get('discount_code')){
            $offer = App\DiscountOffer::where('code',request()->get('discount_code'))->first();
        }

        if(request()->input('amount')){
            $amount = (float)request()->input('amount');
            if($offer){
                if($offer->type == 'percentage'){
                    $totalAmount = $amount - percentageOf($offer->offer_value, $amount);
                } else{
                    $totalAmount = $amount - (float)$offer->offer_value;
                }
            } else {
                $totalAmount = $amount;
            }
        }

        ?>

        <div class="checkout-processing-sec">
            <div class="visible-mob-collapse">
                <div class="contact-number">
                    <a href="tel:1(888)386-3852"><strong><i class="fa fa-phone"></i> 1 (888) 386-3852</strong></a>
                </div>
                <div class="process-status">
                    <div class="step current">
                        <div class="step-status">
                            <span>1</span>
                        </div>
                        <div class="step-text">
                            <span>Shopping cart</span>
                        </div>
                    </div>
                    <div class="step current">
                        <div class="step-status">
                            <span>2</span>
                        </div>
                        <div class="step-text">
                            <span>Checkout</span>
                        </div>
                    </div>
                    <div class="step finished">
                        <div class="step-status">
                            <span>3</span>
                        </div>
                        <div class="step-text">
                            <span>Finish</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="checkout-content">
                        <form class="form-content" id="checkout-form" action="{!! route('transaction') !!}" method="POST">
                            @csrf
                            <input type="hidden" name="item_amount" class="form-control" value="{!! request()->input('amount') !!}">
                            <input type="hidden" name="total_amount" class="form-control" value="{!! $totalAmount !!}">
                            <input type="hidden" name="item_name" class="form-control" value="{!! request()->input('item') !!}">
                            <input type="hidden" name="package" value="{!! request()->input('package') !!}">
                            <input type="hidden" name="discount_offer_id" value="{!! $offer ? $offer->id : '' !!}">

                            @if (Session::has('message'))
                                <div class="alert alert-danger">
                                    {!! Session::get('message') !!}
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

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="login-options">
                                        <div class="heading col-xs-12">
                                            <h5 class="sec-color">Billing Details</h5>
                                        </div>
                                        <div class="form-block col-xs-12">
                                            <div class="form-group field-group">
                                                <input type="text" name="first_name" value="{!! old('first_name') ? old('first_name') : request()->input('first_name') !!}" placeholder="First Name" maxlength="190">
                                            </div>
                                            <div class="form-group field-group">
                                                <input type="text" name="last_name" value="{!! old('last_name') ? old('last_name') : request()->input('last_name') !!}" placeholder="Last Name" maxlength="190">
                                            </div>
                                            <div class="form-group field-group">
                                                <input type="text" name="email" value="{!! old('email') ? old('email') : request()->input('email') !!}" placeholder="Email"   maxlength="190">
                                            </div>
                                            <div class="form-group field-group">
                                                <input type="text" name="contact_number" value="{!! old('contact_number') ? old('contact_number') : request()->input('contact_number') !!}" placeholder="Mobile" maxlength="30">
                                            </div>
                                            <div class="form-group field-group">
                                                <input type="text" name="company" value="{!! old('company') ? old('company') : request()->input('company') !!}" placeholder="Company" maxlength="190">
                                            </div>
                                            <div class="form-group field-group">
                                                <input type="text" name="address" value="{!! old('address') !!}" placeholder="Address" maxlength="190">
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group field-group">
                                                    <div class="c-select-field full-width">
                                                        <select name="country" id="country">
                                                            <?php
                                                            $country = $countries->where('short_name', 'US')->first();
                                                            echo '<option value="'.$country->name.'" data-id="'.$country->id.'" selected>'.$country->name.'</option>';
                                                            $country = $countries->where('short_name', 'CA')->first();
                                                            echo '<option value="'.$country->name.'" data-id="'.$country->id.'">'.$country->name.'</option>';
                                                            $country = $countries->where('short_name', 'GB')->first();
                                                            echo '<option value="'.$country->name.'" data-id="'.$country->id.'">'.$country->name.'</option>';
                                                            $country = $countries->where('short_name', 'AU')->first();
                                                            echo '<option value="'.$country->name.'" data-id="'.$country->id.'">'.$country->name.'</option>';

                                                            foreach($countries as $country){
                                                                if(!in_array($country->short_name,['US','CA','GB','AU']))
                                                                    echo '<option value="'.$country->name.'" data-id="'.$country->id.'">'.$country->name.'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group field-group">
                                                    <div class="c-select-field full-width">
                                                        <select name="state" id="state">
                                                            <option value=""> --state-- </option>
                                                            <?php
                                                            foreach($states as $state){
                                                                echo '<option value="'.$state->name.'">'.$state->name.'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group field-group">
                                                    <div class="full-width">
                                                        <input type="text" name="city" value="{!! old('city') !!}" placeholder="City" maxlength="190">
                                                    </div>
                                                </div>
                                                <div class="form-group field-group">
                                                    <input type="text" name="zip_code" value="{!! old('zip_code') !!}" placeholder="Zip Code" maxlength="50">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-offset-1 col-md-5 aside-block">
                                <div class="discount-promo-block">
                                    <div class="block-content">
                                        <h5>Promo Code</h5>
                                        <div class="form-group field-group">
                                            <div class="has-error text-danger" style="display: none;" id="discount-code-error"><strong>Invalid</strong></div>
                                            <div class="has-error text-success" style="display: none;" id="discount-code-success"><strong>Valid</strong></div>
                                            <input name="discount_code" id="discountCode" class="form-control" value="{!! request()->get('discount_code') !!}" placeholder="Enter Promo Code" autocomplete="off">
                                            <a class="btn" id="applyDiscountCode" href="javascript:void(0);" style="background-color: #009ce9;color: #fff;margin-top: 10px">Apply</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-summary-block">
                                    <div class="block-content">
                                        <h5>Order Summary</h5>
                                        <div class="cart-items-list">
                                            <div class="cart-item">
                                                <div class="top-content">
                                                    <div class="title full-widths">
                                                        <h6><strong>Package</strong></h6>
                                                    </div>
                                                    <div class="price full-width">
                                                        <span>{!! request()->input('package')!!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cart-item">
                                                <div class="top-content">
                                                    <div class="title">
                                                        <h6>{!! request()->input('item') ? request()->input('item') : '{item-name}'!!}</h6>
                                                    </div>
                                                    <div class="price" id="item-price">
                                                        <span>${!! request()->input('amount') ? number_format(request()->input('amount'),2) : '0.0' !!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cart-item" id="set-offer" style="{!! $offer ? '' : 'display: none;' !!}">
                                                <div class="top-content">
                                                    <div class="title">
                                                        <h6 class="offer-name">{!! $offer ? $offer->title : '' !!}</h6>
                                                    </div>
                                                    <div class="price">
                                                        <span class="offer-value">
                                                            {!! $offer ? $offer->type == 'percentage' ? '-'.$offer->offer_value.'%' : '-$'.$offer->offer_value : '' !!}
                                                            {!! $offer ? $offer->type == 'percentage' ? '($'.number_format(percentageOf($offer->offer_value, $amount),2).')' : '' : '' !!}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="commulative-amount">
                                            <span>Total: <strong id="total-amount-text">${!! number_format($totalAmount,2) !!}</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="heading col-xs-12">
                                    <h5 class="sec-color">Payment Method</h5>
                                </div>
                                <div class="form-block col-xs-12">
                                    <div class="radio-fields-content payment-radio" style="margin-bottom: 15px;">
                                        <div class="fields-group">
                                            <div class="radio-inline">
                                                <input name="payment" type="radio" checked readonly>
                                                <span></span>
                                                Credit Card
                                            </div>
                                        </div>
                                        <div class="payment-method-logo">
                                            <img src="{!! asset('assets/images/creditcards-list.png') !!}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">--}}
                                {{--<input type="hidden" name="quantity" value="1">--}}
                                {{--<input type="hidden" name="item_name" value="{!! request()->input('item') !!}">--}}
                                {{--<input type="hidden" name="currency_code" value="USD">--}}
                                {{--<input type="hidden" name="amount" value="{!! request()->input('amount'); !!}">--}}
                                {{--<input type="hidden" name="cmd" value="_s-xclick">--}}
                                {{--<input type="hidden" name="hosted_button_id" value="Y68W8T4CNCKMY">--}}
                                {{--<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">--}}
                                {{--<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">--}}
                            {{--</form>--}}
                            <div class="note">
                                <span>By clicking the button, you agree to <a href="https://logoengine.net/terms-of-service/" target="_blank">Terms & Conditions</a></span>
                            </div>
                            <div style="padding-top: 5px; display: inline-block;width: 100%;" class="btn-content">
                                <a class="c-btn lg-btn" href="javascript:void(0);" id="checkout_btn">Place Order</a>
                            </div>
                        </div>
                        <div class="col-md-6"></div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <!--Start of Zendesk Chat Script-->
    <script type="text/javascript">
        window.$zopim||(function(d,s){var z=$zopim=function(c){
            z._.push(c)},$=z.s=
            d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
        _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
            $.src='https://v2.zopim.com/?69uhXFNCSTUM0GyApUHRc3HEuBnn8BLE';z.t=+new Date;$.
                type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');

        $zopim(function() {
            $zopim.livechat.setOnUnreadMsgs(function(numUnread){
                if(numUnread > 0 && !$zopim.livechat.window.getDisplay()) {
                    $zopim.livechat.window.show();
                }
            });
        });
    </script>
    <!--End of Zendesk Chat Script-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.18.0/jquery.validate.min.js" type="text/javascript"></script>
    <script src="https://checkout.stripe.com/checkout.js" type="text/javascript"></script>
    <script>
        function setPromoCode(){
            $('#discount-code-error').hide();
            $('#discount-code-success').hide();
            var code = $('#discountCode').val();
            var amount = parseFloat('{!! request()->input('amount'); !!}');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            if(code == ''){
                $('input[name=amount]').val(amount);
                $('input[name=total_amount]').val(amount);
                $('input[name=discount_offer_id]').val(null);
                $('.offer-name').text(null);
                $('.offer-value').text(null);
                $('#total-amount-text').html($('#item-price').text());

                var offer = $('#set-offer');
                if(offer.data("filled") === 'auto'){
                    $('.auto-filled').hide();
                } else {
                    offer.hide();
                }
            }

            if(code != "" && amount > 0){
                $('#spinner').show();
                $.ajax({
                    url:"{!! route('verify-discount-code') !!}",
                    type:'post',
                    dataType: 'JSON',
                    data: {
                        code:code,
                        amount: amount,
                        _token: CSRF_TOKEN
                    },
                    success: function (result)
                    {
                        $('#spinner').hide();
                        if(result.status === true){
                            var data = result.data;
                            $('#set-offer').show();
                            $('.offer-name').text(data.offer_name);
                            $('.offer-value').text(data.offer_value);
                            $('#total-amount-text').html(data.amount_text);

                            $('input[name=amount]').val(data.total_amount);
                            $('input[name=total_amount]').val(data.total_amount);
                            $('input[name=discount_offer_id]').val(data.offer_id);
                            $('#discount-code-success').show();
                        } else {
                            $('#discount-code-error').show();
                            $('#discount-code-success').hide();
                        }
                    }
                });
            }
        }
        $('#applyDiscountCode').click(function (){
            setPromoCode();
        });
        $('input#discountCode').keypress(function (event) {
            if(event.keyCode == 13) {
                event.preventDefault();
                setPromoCode();
                return false;
            }
        });
        $('#country').change(function () {
            var stateSelector = $('#state');
            var country_id = $(this).find(':selected').attr('data-id');
            if(country_id){
                $.ajax({
                    type : 'GET',
                    url : "{!! url('') !!}/country/"+country_id+"/states",
                    dataType : 'json',
                    success : function (states) {
                        stateSelector.html('<option value=""> --state-- </option>');
                        $.each(states,function (key, value) {
                            stateSelector.append('<option value="'+value.name+'">'+value.name+'</option>');
                        });
                    }
                });
            }
        });
    </script>
    <script>
        $(document).on('click', '#checkout_btn', function () {
            var form = $("#checkout-form");
            var validator = form.validate({
                rules: {
                    email: { required: true },
                    address: { required: true },
                },
                messages: {
                    email: "The email field is required.",
                    address: "The address field is required.",
                }
            });

            //if (validator.form()) {
                // $('#spinner').show();

                var email = $("input[name=email]").val();
                var amount = parseFloat($("input[name=total_amount]").val()) * 100;

                StripeCheckout.open({
                    email: email,
                    amount: amount,
                    name: 'LogoEngine',
                    panelLabel: 'Checkout',
                    key: '{{ env('STRIPE_API_KEY') }}',
                    image: '{!! asset('assets/images/logo-icon.png') !!}',
                    description: 'Purchasing: {!! request()->input('item') !!}',
                    token: function (responce) {
                        var $id = $('<input type=hidden name=stripeToken />').val(responce.id);
                        $('#checkout-form').append($id).submit();
                    }
                });
            /*}
            else {
                validator.focusInvalid();
            }*/
        });
    </script>
</html>
