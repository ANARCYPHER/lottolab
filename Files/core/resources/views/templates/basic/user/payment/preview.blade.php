@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container pt-100 pb-100">
        <div class="row  justify-content-center">
            <div class="col-lg-4 col-md-6 custom__bg p-3">
                <ul class="list-group text-center">
                    <li class="list-group-item bg--transparent">
                        <img src="{{ $data->gatewayCurrency()->methodImage() }}" alt="@lang('Image')" />
                    </li>
                    <p class="list-group-item bg--transparent">
                        @lang('Amount'):
                        <strong>{{showAmount($data->amount)}} </strong> {{__($general->cur_text)}}
                    </p>
                    <p class="list-group-item bg--transparent">
                        @lang('Charge'):
                        <strong>{{showAmount($data->charge)}}</strong> {{__($general->cur_text)}}
                    </p>
                    <p class="list-group-item bg--transparent">
                        @lang('Payable'): <strong> {{showAmount($data->amount + $data->charge)}}</strong> {{__($general->cur_text)}}
                    </p>
                    <p class="list-group-item bg--transparent">
                        @lang('Conversion Rate'): <strong>1 {{__($general->cur_text)}} = {{showAmount($data->rate)}}  {{__($data->baseCurrency())}}</strong>
                    </p>
                    <p class="list-group-item bg--transparent">
                        @lang('In') {{$data->baseCurrency()}}:
                        <strong>{{showAmount($data->final_amo)}}</strong>
                    </p>


                    @if($data->gateway->crypto==1)
                        <p class="list-group-item bg--transparent">
                            @lang('Conversion with')
                            <b> {{ __($data->method_currency) }}</b> @lang('and final value will Show on next step')
                        </p>
                    @endif
                </ul>

                @if( 1000 >$data->method_code)
                    <a href="{{route('user.deposit.confirm')}}" class="btn btn--base w-100 font-weight-bold">@lang('Pay Now')</a>
                @else
                    <a href="{{route('user.deposit.manual.confirm')}}" class="btn btn--base w-100 font-weight-bold">@lang('Pay Now')</a>
                @endif
            </div>
        </div>
    </div>
@endsection


