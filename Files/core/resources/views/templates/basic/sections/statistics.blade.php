@php
    $statistics_content = getContent('statistics.content', true);
    $time = \Carbon\Carbon::now()->toDateTimeString();
    $phases = \App\Models\Phase::where('status',1)->where('draw_status',1)->where('start','<',$time)->orderBy('end')->whereHas('lottery',function($lottery){
          $lottery->where('status',1);
      })->limit(3)->with(['lottery'])->get();
@endphp
<!-- statistics section start -->
<section class="pt-50 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-header">
                    <h2 class="section-title">{{ __(@$statistics_content->data_values->heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row gy-4 justify-content-center">

            @forelse($phases as $phase)
                <div class="col-lg-4 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card__header">
                            <div class="thumb"><img src="{{ getImage('assets/images/lottery/'.$phase->lottery->image,imagePath()['lottery']['size']) }}" alt="image"></div>
                            <div class="content">
                                <h3 class="title">{{ __($phase->lottery->name) }}</h3>
                            </div>
                        </div>
                        <ul class="caption-list-two mt-3">
                            <li>
                                <span class="caption">@lang('Total Bet')</span>
                                <span class="value text-end text--base">{{ $phase->salled }}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Draw Date')</span>
                                <span class="value text-end text--base">{{ showDateTime($phase->end,'d/m/y') }}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Winners')</span>
                                <span class="value text-end text--base">{{ @$phase->winners->count() }}</span>
                            </li>
                        </ul>
                    </div><!-- stat-card end -->
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
<!-- statistics section end -->
