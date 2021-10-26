@php
	$captcha = loadCustomCaptcha();
@endphp
@if($captcha)
    <div class="form-group @if(request()->routeIs('user.register')) col-lg-6 @endif">
        @php echo $captcha @endphp
    </div>
    <div class="form-group @if(request()->routeIs('user.register')) col-lg-6 @endif">
        <input type="text" name="captcha" placeholder="@lang('Enter Code')" class="form--control" required>
    </div>
@endif
