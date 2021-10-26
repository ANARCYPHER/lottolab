@php
    $feature_content = getContent('feature.content', true);
    $feature_elements = getContent('feature.element', false, null, true);
@endphp

<!-- feature section start -->
<section class="pt-100 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-header">
                    <h2 class="section-title">{{ __(@$feature_content->data_values->heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row justify-content-center gy-4">

            @forelse($feature_elements as $item)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <div class="feature-card rounded-3">
                        <div class="feature-card__icon text--base text-shadow--base">
                            @php echo @$item->data_values->icon @endphp
                        </div>
                        <div class="feature-card__content mt-4">
                            <h3 class="title">{{ __(@$item->data_values->title) }}</h3>
                            <p class="mt-3">{{ __(@$item->data_values->content) }}</p>
                        </div>
                    </div><!-- feature-card end -->
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
<!-- feature section end -->
