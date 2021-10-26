@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container pt-100 pb-100">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 custom__bg p-3">
                <ul class="list-group text-center">
                    <li class="list-group-item bg-transparent">
                        <h2 class="title"><span>@lang('Payment Preview')</span></h2>
                    </li>
                    <li class="list-group-item bg-transparent">
                        <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')">
                    </li>
                    <li class="list-group-item bg-transparent">
                        <h3>@lang('Please Pay') {{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</h3>
                    </li>
                    <li class="list-group-item bg-transparent">
                        <h3>@lang('To Get') {{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</h3>
                    </li>
                </ul>

                <button type="button" class="btn mt-4 btn--base w-100 custom-success text-center btn-lg" id="btn-confirm">@lang('Pay Now')</button>

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="//pay.voguepay.com/js/voguepay.js"></script>
    <script>
        "use strict";
        var closedFunction = function() {
        }
        var successFunction = function(transaction_id) {
            window.location.href = '{{ route(gatewayRedirectUrl()) }}';
        }
        var failedFunction=function(transaction_id) {
            window.location.href = '{{ route(gatewayRedirectUrl()) }}' ;
        }

        function pay(item, price) {
            //Initiate voguepay inline payment
            Voguepay.init({
                v_merchant_id: "{{ $data->v_merchant_id}}",
                total: price,
                notify_url: "{{ $data->notify_url }}",
                cur: "{{$data->cur}}",
                merchant_ref: "{{ $data->merchant_ref }}",
                memo:"{{$data->memo}}",
                recurrent: true,
                frequency: 10,
                developer_code: '60a4ecd9bbc77',
                custom: "{{ $data->custom }}",
                customer: {
                  name: 'Customer name',
                  country: 'Country',
                  address: 'Customer address',
                  city: 'Customer city',
                  state: 'Customer state',
                  zipcode: 'Customer zip/post code',
                  email: 'example@example.com',
                  phone: 'Customer phone'
                },
                closed:closedFunction,
                success:successFunction,
                failed:failedFunction
            });
        }

        (function ($) {

            $('#btn-confirm').on('click', function (e) {
                e.preventDefault();
                pay('Buy', {{ $data->Buy }});
            });

        })(jQuery);
    </script>
@endpush
