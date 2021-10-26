@extends($activeTemplate.'layouts.frontend')

@section('content')
    <section class=" pt-100 pb-100 position-relative z-index-2">
        <div class="ball-1"><img src="{{asset($activeTemplateTrue.'images/elements/ball-1.png')}}" alt="image"></div>
        <div class="ball-2"><img src="{{asset($activeTemplateTrue.'images/elements/ball-2.png')}}" alt="image"></div>
        <div class="ball-3"><img src="{{asset($activeTemplateTrue.'images/elements/ball-3.png')}}" alt="image"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="account-wrapper">
                        <form class="account-form" method="POST" action="{{ route('user.password.email') }}">
                            @csrf

                            <div class="account-thumb-area text-center">
                                <h3 class="title">@lang('Reset Password')</h3>
                            </div>

                            <div class="form-group">
                                <label>@lang('Select One')</label>
                                <select class="form--control" name="type">
                                    <option value="email">@lang('E-Mail Address')</option>
                                    <option value="username">@lang('Username')</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="my_value"></label>
                                <input type="text" class="form--control @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" required autofocus="off">
                            </div>

                            <button type="submit" class="btn btn--base w-100">@lang('Send Password Code')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script>

    (function($){
        "use strict";

        myVal();
        $('select[name=type]').on('change',function(){
            myVal();
        });
        function myVal(){
            $('.my_value').text($('select[name=type] :selected').text());
        }
    })(jQuery)
</script>
@endpush
