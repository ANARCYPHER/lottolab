@php
    $cta_content = getContent('cta.content', true);
@endphp

<!-- cta section start -->
<section class="pt-100 pb-100 bg_img" style="background-image: url({{ getImage('assets/images/frontend/cta/' . @$cta_content->data_values->background_image, '1920x999') }});">
    <div class="container">
        <div class="row justify-content-center wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
            <div class="col-lg-7 text-center">
                <h2 class="section-title">{{ __(@$cta_content->data_values->headign) }}</h2>
                <p class="mt-3">{{ __(@$cta_content->data_values->sub_heading) }}</p>
                <a href="{{ @$cta_content->data_values->button_link }}" class="btn btn--base btn--capsule mt-4">{{ __(@$cta_content->data_values->button) }}</a>
            </div>
        </div>
    </div>
</section>
<!-- cta section end -->
