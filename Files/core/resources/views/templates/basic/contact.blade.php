@extends($activeTemplate.'layouts.frontend')

@section('content')
    @php
        $contact_content = getContent('contact.content', true);
    @endphp
<!-- contact section start -->
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-between gy-5">
            <div class="col-lg-4">
                <h2 class="mb-3">{{ __(@$contact_content->data_values->title) }}</h2>
                <p>{{ __(@$contact_content->data_values->content) }}</p>

                <div class="row gy-4 mt-3">
                    <div class="col-lg-12">
                        <div class="contact-info-card rounded-3">
                            <h6 class="mb-3">@lang('Office Address')</h6>
                            <div class="contact-info d-flex">
                                <i class="las la-map-marked-alt"></i>
                                <p>{{ __(@$contact_content->data_values->address) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="contact-info-card rounded-3">
                            <h6 class="mb-3">@lang('Phone')</h6>
                            <div class="contact-info d-flex">
                                <i class="las la-phone-volume"></i>
                                <p><a href="tel:{{ @$contact_content->data_values->phone }}">{{ @$contact_content->data_values->phone }}</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="contact-info-card rounded-3">
                            <h6 class="mb-3">@lang('Email')</h6>
                            <div class="contact-info d-flex">
                                <i class="las la-envelope"></i>
                                <p><a href="mailto:{{ @$contact_content->data_values->email }}">{{ @$contact_content->data_values->email }}</a></p>
                            </div>
                        </div>
                    </div>
                </div><!-- row end -->

            </div>
            <div class="col-lg-8 ps-lg-5">
                <div class="contact-wrapper rounded-3">
                    <h2 class="mb-3">@lang('Contact Us')</h2>
                    <form class="contact-form" method="post" action="">
                        @csrf

                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label>@lang('Name')</label>
                                <div class="custom--field">
                                    <input name="name" type="text" placeholder="@lang('Your Name')" class="form--control" value="{{ old('name') }}" required>
                                    <i class="las la-user"></i>
                                </div>
                            </div><!-- form-group end -->
                            <div class="col-lg-6 form-group">
                                <label>@lang('Email')</label>
                                <div class="custom--field">
                                    <input name="email" type="text" placeholder="@lang('Enter E-Mail Address')" class="form--control" value="{{old('email')}}" required>
                                    <i class="las la-envelope"></i>
                                </div>
                            </div><!-- form-group end -->
                            <div class="col-lg-12 form-group">
                                <label>@lang('Subject')</label>
                                <div class="custom--field">
                                    <input name="subject" type="text" placeholder="@lang('Write your subject')" class="form--control" value="{{old('subject')}}" required>
                                    <i class="las la-pen"></i>
                                </div>
                            </div><!-- form-group end -->
                            <div class="col-lg-12 form-group">
                                <label>@lang('Message')</label>
                                <div class="custom--field">
                                    <textarea name="message" wrap="off" placeholder="@lang('Write your message')" class="form--control">{{old('message')}}</textarea>
                                    <i class="las la-envelope"></i>
                                </div>
                            </div><!-- form-group end -->
                            <div class="col-lg-12 form-group">
                                <button type="submit" class="btn btn--base">@lang('Submit Now')</button>
                            </div><!-- form-group end -->
                        </div><!-- row end -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- contact section end -->
@endsection
