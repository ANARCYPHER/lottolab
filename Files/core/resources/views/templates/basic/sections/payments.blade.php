@php
    $payments_content = getContent('payments.content', true);
    $payments_elements = getContent('payments.element');
    $subscribe = getContent('subscribe.content', true);
@endphp

<!-- payment brand section start -->
<section class="pt-50 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header">
                    <h2 class="section-title">{{ __(@$payments_content->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$payments_content->data_values->sub_heading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="payment-slider">

                    @forelse($payments_elements as $item)
                        <div class="single-slide">
                            <div class="brand-item">
                                <img src="{{ getImage('assets/images/frontend/payments/' . @$item->data_values->image, '64x50') }}" alt="image">
                            </div><!-- brand-item end -->
                        </div>
                    @empty
                    @endforelse

                </div><!-- payment-slider end -->
            </div>
            @guest
                <div class="col-lg-12 text-center mt-5">
                    <a href="{{ route('user.register') }}" class="btn btn--base btn--capsule">@lang('Create an Account')</a>
                </div>
            @endguest
        </div><!-- row end -->

        <div class="row justify-content-center mt-5">
            <div class="col-lg-10 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="subscribe-wrapper bg_img" data-background="assets/images/bg/bg-5.jpg">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <h2 class="title">{{ __(@$subscribe->data_values->heading) }}</h2>
                        </div>
                        <div class="col-lg-7 mt-lg-0 mt-4">
                            <form class="subscribe-form" action="{{ route('subscribe') }}" method="post">
                                @csrf

                                <input type="email" name="email" class="form--control" placeholder="Email Address">
                                <button class="btn btn-md btn--base btn--capsule">@lang('Subscribe')</button>
                            </form>
                        </div>
                    </div>
                </div><!-- subscribe-wrapper end -->
            </div>
        </div>
    </div>
</section>
<!-- payment brand section end -->

@push('script')
    <script type="text/javascript">
        $('.subscribe-form').on('submit',function(e){
            e.preventDefault();
            var url = '{{ route('subscribe') }}';
            var data = {email:$(this).find('input[name=email]').val()};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(url, data, function(response){
                if(response.errors){
                    for (var i = 0; i < response.errors.length; i++) {
                        iziToast.error({message: response.errors[i], position: "topRight"});
                    }
                }else{
                    $('.subscribe-form').trigger("reset");
                    iziToast.success({message: response.success, position: "topRight"});
                }
            });
        })
    </script>
@endpush
