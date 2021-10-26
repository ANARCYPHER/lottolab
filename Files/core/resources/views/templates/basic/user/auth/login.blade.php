@extends($activeTemplate.'layouts.frontend')

@section('content')
    <!-- account section start -->
    <section class=" pt-100 pb-100 position-relative z-index-2">
        <div class="ball-1"><img src="{{asset($activeTemplateTrue.'images/elements/ball-1.png')}}" alt="image"></div>
        <div class="ball-2"><img src="{{asset($activeTemplateTrue.'images/elements/ball-2.png')}}" alt="image"></div>
        <div class="ball-3"><img src="{{asset($activeTemplateTrue.'images/elements/ball-3.png')}}" alt="image"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="account-wrapper">
                        <form class="account-form" method="POST" action="{{ route('user.login')}}" onsubmit="return submitUserForm();">
                            @csrf

                            <div class="account-thumb-area text-center">
                                <h3 class="title">@lang('Welcome Back to') {{ $general->sitename }}</h3>
                            </div>
                            <div class="form-group">
                                <label>@lang('Username & Email') <sup class="text-danger">*</sup></label>
                                <input type="text" name="username" value="{{ old('username') }}" placeholder="@lang('Username & Email')" class="form--control" required>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Password') }} <sup class="text-danger">*</sup></label>
                                <input id="password" type="password" class="form--control" placeholder="@lang('Enter your password')" name="password" required>
                            </div>

                            <div class="form-group d-flex justify-content-center">
                                <div class="col-md-6">
                                    @php echo loadReCaptcha() @endphp
                                </div>
                            </div>
                            @include($activeTemplate.'partials.custom_captcha')

                            <div class="form-group text-end">
                                <a href="{{route('user.password.request')}}" class="text-white">@lang('Forget Password?')</a>
                            </div>
                            <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Login Now')</button>
                            <p class="text-center mt-3"><span class="text-white">@lang('New to') {{ $general->sitename }}?</span> <a href="{{ route('user.register') }}" class="text--base">@lang('Register here')</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- account section end -->
@endsection

@push('script')
    <script>
        "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
    </script>
@endpush
