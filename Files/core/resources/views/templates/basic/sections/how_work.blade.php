@php
    $how_work_content = getContent('how_work.content', true);
    $how_work_elements = getContent('how_work.element', false, null, true);
@endphp
<!-- how work section start -->
<section class="pt-50 pb-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$how_work_content->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$how_work_content->data_values->sub_heading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row gy-4">

            @forelse($how_work_elements as $item)
                <div class="col-lg-3 col-sm-6 how-work-item wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <div class="how-work-card">
                        <div class="how-work-card__step text--base text-shadow--base">{{ $loop->iteration }}</div>
                        <h3 class="title mt-4">{{ __(@$item->data_values->title) }}</h3>
                        <p class="mt-2">{{ __(@$item->data_values->content) }}</p>
                    </div><!-- how-work-card end -->
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
<!-- how work section end -->
