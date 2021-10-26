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

                <form action="{{$data->url}}" method="{{$data->method}}">
                    <script src="{{$data->src}}"
                            class="stripe-button"
                            @foreach($data->val as $key=> $value)
                            data-{{$key}}="{{$value}}"
                        @endforeach
                    >
                    </script>
                </form>

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        (function ($) {
            "use strict";
            $('button[type="submit"]').addClass("btn mt-4 btn--base w-100 btn-round custom-success text-center btn-lg");
        })(jQuery);
    </script>
@endpush
