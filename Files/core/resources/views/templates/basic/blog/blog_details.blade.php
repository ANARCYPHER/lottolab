@extends($activeTemplate.'layouts.frontend')
@section('content')
    <!-- blog-details-section start -->
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-details-wrapper">
                        <div class="blog-details__thumb">
                            <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '') }}" alt="image">
                            <div class="post__date">
                                <span class="date">{{ showDateTime($blog->created_at, 'd') }}</span>
                                <span class="month">{{ showDateTime($blog->created_at, 'M') }}</span>
                            </div>
                        </div><!-- blog-details__thumb end -->
                        <div class="blog-details__content">
                            <h4 class="blog-details__title">{{ __(@$blog->data_values->title) }}</h4>

                            @php echo @$blog->data_values->description_nic @endphp

                        </div><!-- blog-details__content end -->
                    </div><!-- blog-details-wrapper end -->

                    @if(\App\Models\Extension::where('act', 'fb-comment')->where('status',1)->first())
                        <div class="comment-form-area">
                            <h3 class="title">@lang('Live a Comment')</h3>

                            <div class="fb-comments" data-href="{{ route('blog.details',[$blog->id,slug($blog->data_values->title)]) }}" data-numposts="5"></div>
                        </div><!-- comment-form-area end -->
                    @endif

                </div>
                <div class="col-lg-4 pl-lg-5">
                    <div class="sidebar">

                        <div class="widget">
                            <h3 class="widget-title">@lang('Recent Blog Posts')</h3>
                            <ul class="small-post-list">

                                @forelse($recent_blogs as $item)
                                    <li class="small-post-single">
                                        <div class="thumb"><img src="{{ getImage('assets/images/frontend/blog/' . @$item->data_values->image, '') }}" alt="image"></div>
                                        <div class="content">
                                            <h6 class="post-title"><a href="{{ route('blog.details',[$item->id,slug($item->data_values->title)]) }}"> {{ __(@$item->data_values->title) }}</a></h6>
                                            {{ showDateTime($item->created_at) }}
                                        </div>
                                    </li><!-- small-post-single end -->
                                @empty
                                @endforelse

                            </ul>
                        </div><!-- widget end -->

                    </div><!-- sidebar end -->
                </div>
            </div>
        </div>
    </section>
    <!-- blog-details-section end -->
@endsection

@push('fbComment')
	@php echo loadFbComment() @endphp
@endpush
