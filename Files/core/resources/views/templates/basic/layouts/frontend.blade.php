<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ $general->sitename(__($pageTitle)) }}</title>
@include('partials.seo')

<!-- bootstrap 5  -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/lib/bootstrap.min.css')}}">
    <!-- fontawesome 5  -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/all.min.css')}}">
    <!-- lineawesome font -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/line-awesome.min.css')}}">
    <!-- slick slider css -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/lib/slick.css')}}">
    <!-- main css -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/main.css')}}">

    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/bootstrap-fileinput.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/custom.css')}}">

    <link rel="stylesheet"
          href="{{asset($activeTemplateTrue.'css/color.php?color='.$general->base_color.'&secondColor='.$general->secondary_color)}}">

    @stack('style-lib')

    @stack('style')
</head>
<body>

@stack('fbComment')

<div class="preloader">
    <div class="preloader-container">
        <span class="animated-preloader"></span>
    </div>
</div>

<!-- scroll-to-top start -->
<div class="scroll-to-top">
    <span class="scroll-icon">
      <i class="fa fa-rocket" aria-hidden="true"></i>
    </span>
</div>
<!-- scroll-to-top end -->

<!-- header-section start  -->
<header class="header">
    <div class="header__bottom">
        <div class="container-fluid px-lg-5">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu me-auto">
                        <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                        <li><a href="{{ route('lottery') }}">@lang('Lotteries')</a></li>

                        @foreach($pages as $k => $data)
                            <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                        @endforeach

                        <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
                        <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                    </ul>
                    <div class="nav-right">
                        @auth
                            <a href="{{ route('user.home') }}" class="btn btn-sm btn--base me-sm-3 me-2 btn--capsule px-3">@lang('Dashboard')</a>
                            <a href="{{ route('user.logout') }}" class="text-white fs--14px me-sm-3 me-2">@lang('Logout')</a>
                        @else
                            <a href="{{ route('user.login') }}" class="btn btn-sm btn--base me-sm-3 me-2 btn--capsule px-3">@lang('Login')</a>
                            <a href="{{ route('user.register') }}" class="text-white fs--14px me-sm-3 me-2">@lang('Register')</a>
                        @endauth
                        <select class="language-select langSel">
                            <option value="">@lang('Select One')</option>
                            @foreach($language as $item)
                                <option value="{{$item->code}}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </nav>
        </div>
    </div><!-- header__bottom end -->
</header>
<!-- header-section end  -->

<div class="main-wrapper">

    @if(!request()->routeIs('home'))
        @include($activeTemplate.'partials.breadcrumb')
    @endif

    @yield('content')

</div><!-- main-wrapper end -->

@php
    $footer_content = getContent('footer.content', true);
    $footer_elements = getContent('footer.element');
    $extra_pages = getContent('extra.element');
@endphp
<!-- footer start -->
<footer class="footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2 col-md-3 text-md-start text-center">
                <a href="{{ route('home') }}" class="footer-logo"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image"></a>
            </div>
            <div class="col-lg-10 col-md-9 mt-md-0 mt-3">
                <ul class="inline-menu d-flex flex-wrap justify-content-md-end justify-content-center align-items-center">
                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                    <li><a href="{{ route('lottery') }}">@lang('Lotteries')</a></li>

                    @foreach($pages as $k => $data)
                        <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                    @endforeach

                    @forelse($extra_pages as $item)
                        <li><a href="{{ route('links',[$item->id,slug($item->data_values->title)]) }}">{{ __(@$item->data_values->title) }}</a></li>
                    @empty
                    @endforelse
                </ul>
            </div>
        </div><!-- row end -->
        <hr class="mt-3">
        <div class="row align-items-center">
            <div class="col-md-6 text-md-start text-center">
                <p>{{ __(@$footer_content->data_values->copyright) }}</p>
            </div>
            <div class="col-md-6 mt-md-0 mt-3">
                <ul class="inline-social-links d-flex align-items-center justify-content-md-end justify-content-center">
                    @forelse($footer_elements as $item)
                        <li><a href="{{ @$item->data_values->social_link }}" target="_blank">@php echo @$item->data_values->social_icon @endphp</a></li>
                    @empty
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->

{{--Cookie--}}
@php
    $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
@endphp
@if(@$cookie->data_values->status && !session('cookie_accepted'))
    <div class="cookie__wrapper">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <p class="txt my-2">

                    @php echo @$cookie->data_values->description @endphp

                    <a href="{{ @$cookie->data_values->link }}" target="_blank" class="text--base">@lang('Read Policy')</a>
                </p>
                <button class="btn btn--base btn-md my-2 acceptPolicy">Accept</button>
            </div>
        </div>
    </div>
@endif


<!-- jQuery library -->
<script src="{{asset($activeTemplateTrue.'js/lib/jquery-3.6.0.min.js')}}"></script>
<!-- bootstrap js -->
<script src="{{asset($activeTemplateTrue.'js/lib/bootstrap.bundle.min.js')}}"></script>
<!-- slick slider js -->
<script src="{{asset($activeTemplateTrue.'js/lib/slick.min.js')}}"></script>
<!-- scroll animation -->
<script src="{{asset($activeTemplateTrue.'js/lib/wow.min.js')}}"></script>
<!-- apex chart js -->
<script src="{{asset($activeTemplateTrue.'js/lib/jquery.countdown.js')}}"></script>
<!-- main js -->
<script src="{{asset($activeTemplateTrue.'js/app.js')}}"></script>

@stack('script-lib')

@stack('script')

@include('partials.plugins')

@include('partials.notify')


<script>
    (function ($) {
        "use strict";
        $(".langSel").on("change", function () {
            window.location.href = "{{route('home')}}/change/" + $(this).val();
        });

        //Cookie
        $(document).on('click', '.acceptPolicy', function () {
            $.ajax({
                url: "{{ route('cookie.accept') }}",
                method:'GET',
                success:function(data){
                    if (data.success){
                        $('.cookie__wrapper').addClass('d-none');
                        notify('success', data.success)
                    }
                },
            });
        });
    })(jQuery);
</script>

</body>
</html>
