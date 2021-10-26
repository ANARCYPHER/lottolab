
@php
    $recent_winners_content = getContent('recent_winners.content', true);
    $recent_winners = \App\Models\Winner::orderBy('id','desc')->limit(8)->with('user')->get();
@endphp

<!-- latest winner section start -->
<section class="pt-50 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-header">
                    <h2 class="section-title">{{ __(@$recent_winners_content->data_values->heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row justify-content-center gy-4">

            @forelse($recent_winners as $winner)
                <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.1s">
                    <div class="winner-card rounded-3">
                        <div class="winner-card__thumb">
                            <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$winner->user->image,imagePath()['profile']['user']['size'])}}" alt="image">
                        </div>
                        <div class="winner-card__content">
                            <h5 class="name">{{ @$winner->user->username }}</h5>
                            <span class="fs--14px text-white-50">{{ showDateTime($winner->created_at, 'd.m.Y') }}</span>
                            <div class="amount h--font text--base">0.05 BTC</div>
                        </div>
                    </div><!-- winner-card end -->
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
<!-- latest winner section end -->
