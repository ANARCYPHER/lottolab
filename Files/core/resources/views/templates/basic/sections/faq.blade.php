@php
    $faq_content = getContent('faq.content', true);
    $faq_elements = getContent('faq.element');
@endphp
<!-- faq section start -->
<section class="pt-50 pb-50">
    <div class="container">
        <div class="row align-items-center justify-content-between gy-4">
            <div class="col-xxl-4 col-xl-5 col-lg-6 text-lg-start text-center wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="faq-thumb">
                    <img src="{{ getImage('assets/images/frontend/faq/' . @$faq_content->data_values->image, '364x500') }}" alt="image">
                </div>
            </div>
            <div class="col-xl-7 col-lg-6 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.5s">
                <div class="accordion custom--accordion" id="faqAccordion">

                    @forelse($faq_elements as $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="h-{{ $loop->iteration }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c-{{ $loop->iteration }}" aria-expanded="false" aria-controls="c-{{ $loop->iteration }}">
                                    {{ __(@$item->data_values->question) }}
                                </button>
                            </h2>
                            <div id="c-{{ $loop->iteration }}" class="accordion-collapse collapse" aria-labelledby="h-{{ $loop->iteration }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>{{ __(@$item->data_values->answer) }}</p>
                                </div>
                            </div>
                        </div><!-- accordion-item-->
                    @empty
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</section>
<!-- faq section end -->
