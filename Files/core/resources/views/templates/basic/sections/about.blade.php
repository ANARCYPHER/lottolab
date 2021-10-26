@php
    $about_content = getContent('about.content', true);
@endphp

<!-- about section start -->
<section class="pt-100 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-thumb">
                    <img src="{{ getImage('assets/images/frontend/about/' . @$about_content->data_values->image, '363x358') }}" alt="image">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5 mt-lg-0 mt-4">
                <h2 class="section-title">{{ __(@$about_content->data_values->title) }}</h2>

                @php echo @$about_content->data_values->content @endphp
            </div>
        </div>
    </div>
</section>
<!-- about section end -->
