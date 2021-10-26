@extends($activeTemplate.'layouts.frontend')
@section('content')

    <!-- blog section start -->
    <section class="pt-100 pb-100 border-top-1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-header">
                        <h2 class="section-title"><span class="font-weight-normal">{{ __(@$blog_content->data_values->heading) }}</span></h2>
                        <p>{{ __(@$blog_content->data_values->sub_heading) }}</p>
                    </div>
                </div>
            </div><!-- row end -->
            <div class="row justify-content-center mb-none-30">

                @forelse($blogs as $item)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="blog-card">
                            <div class="blog-card__thumb">
                                <img src="{{ getImage('assets/images/frontend/blog/' . @$item->data_values->image, '768x512') }}" alt="image">
                            </div>
                            <div class="blog-card__content">
                                <h4 class="blog-card__title mb-3"><a href="{{ route('blog.details',[$item->id,slug($item->data_values->title)]) }}">{{ __(@$item->data_values->title) }}</a></h4>
                                <ul class="blog-card__meta d-flex flex-wrap mb-4">
                                    <li>
                                        <i class="las la-eye"></i>
                                        {{ $item->views }}
                                    </li>
                                    <li>
                                        <i class="las la-calendar"></i>
                                        {{ showDateTime($item->created_at) }}
                                    </li>
                                </ul>
                                <p>{{ __(@$item->data_values->short_description) }}</p>
                                <a href="{{ route('blog.details',[$item->id,slug($item->data_values->title)]) }}" class="btn btn--base mt-4">@lang('Read More')</a>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse

            </div>
        </div>
    </section>
    <!-- blog section end -->
@endsection
