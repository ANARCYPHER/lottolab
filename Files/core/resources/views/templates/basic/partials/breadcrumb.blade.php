<section class="inner-hero bg_img overlay--one" style="background-image: url({{ asset('assets/images/frontend/breadcrumb/'.getContent('breadcrumb.content',true)->data_values->background_image) }});">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="page-title text-white">{{ __($pageTitle) }}</h2>
                <ul class="page-breadcrumb justify-content-center">
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li>{{ __($pageTitle) }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- inner hero end -->
