@php
    $testimonial_content = getContent('testimonial.content', true);
    $testimonial_elements = getContent('testimonial.element');
@endphp

<!-- testimonial section start -->
<section class="pt-50 pb-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$testimonial_content->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$testimonial_content->data_values->sub_heading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="testimonial-slider">

            @forelse($testimonial_elements as $item)
                <div class="single-slide">
                    <div class="testimonial-item rounded-3">
                        <div class="ratings">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < $item->data_values->rating)
                                    <i class="las la-star"></i>
                                @else
                                    <i class="lar la-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-white mt-2">{{ __(@$item->data_values->review) }}</p>
                        <div class="client-details d-flex align-items-center mt-4">
                            <div class="thumb">
                                <img src="{{ getImage('assets/images/frontend/testimonial/' . @$item->data_values->image, '75x75') }}" alt="image">
                            </div>
                            <div class="content">
                                <h4 class="name text-white">{{ __(@$item->data_values->name) }}</h4>
                                <span class="designation text-white-50 fs--14px">{{ __(@$item->data_values->designation) }}</span>
                            </div>
                        </div>
                    </div><!-- testimonial-item end -->
                </div><!-- single-slide end -->
            @empty
            @endforelse

        </div><!-- testimonial-slider end -->
    </div>
</section>
<!-- testimonial section end -->
