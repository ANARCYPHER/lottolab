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
                        <form class="account-form" method="POST" action="{{ route('user.password.verify.code') }}">
                            @csrf

                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="form-group">
                                <label>@lang('Verification Code')</label>
                                <input type="text" name="code" id="code" class="form--control">
                            </div>

                            <button type="submit" class="btn btn--base w-100">@lang('Verify Code') <i class="las la-sign-in-alt"></i></button>
                            <p class="text-center mt-3"><span class="text-white">@lang('Please check including your Junk/Spam Folder. if not found, you can')</span> <a href="{{ route('user.password.request') }}" class="text--base">@lang('Try to send again')</a></p>
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
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;
          $(this).val(function (index, value) {
             value = value.substr(0,7);
              return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
          });
      });
    })(jQuery)
</script>
@endpush
