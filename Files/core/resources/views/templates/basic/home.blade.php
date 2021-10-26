@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
	$banner = getContent('banner.content', true);
@endphp
<!-- hero section start -->
<section class="hero bg_img" style="background-image: url('{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->background_image, '1920x983') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-7 col-xl-8 col-lg-10 text-center">
                <h2 class="hero__title wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">{{ __(@$banner->data_values->heading) }}</h2>
                <p class="hero__description mt-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">{{ __(@$banner->data_values->sub_heading) }}</p>
                <a href="{{ @$banner->data_values->button_link }}" class="btn btn--base btn--capsule mt-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">{{ __(@$banner->data_values->button) }}</a>
            </div>
        </div>
    </div>
</section>
<!-- hero section end -->
@php
    $overview_elements = getContent('overview.element', false, null, true);
@endphp
<!-- overview section start -->
<div class="overview-section pb-50">
    <div class="container">
        <div class="row gy-sm-0 gy-4 overview-wrapper wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">

            @forelse($overview_elements as $item)
                <div class="col-sm-4 overview-item">
                    <div class="overview-card">
                        <div class="overview-card__icon">
                            @php echo @$item->data_values->icon @endphp
                        </div>
                        <div class="overview-card__content">
                            <h3 class="amount text--base text-shadow--base">{{ __(@$item->data_values->number) }}</h3>
                            <p>{{ __(@$item->data_values->title) }}</p>
                        </div>
                    </div>
                </div><!-- overview-item end -->
            @empty
            @endforelse

        </div>
    </div>
</div>
<!-- overview section end -->


    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection
