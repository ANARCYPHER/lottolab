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
                        <h3>@lang('PLEASE SEND EXACTLY') <span class="text-success"> {{ $data->amount }}</span> {{__($data->currency)}}</h3>
                    </li>
                    <li class="list-group-item bg-transparent">
                        <h3>@lang('To') <span class="text-success"> {{ $data->sendto }}</span></h3>
                    </li>
                    <li class="list-group-item bg-transparent">
                        <img src="{{$data->img}}" alt="@lang('Image')">
                    </li>
                    <li class="list-group-item bg-transparent">
                        <h4 class="text-white bold my-4">@lang('SCAN TO SEND')</h4>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
