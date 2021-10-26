@extends($activeTemplate.'layouts.master')
@section('content')
    <!-- dashboard section start -->
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>@lang('Referral Link')</label>
                        <div class="input-group">
                            <input type="text" value="{{ route('home') }}?reference={{ auth()->user()->username }}"
                                   class="form--control" id="referralURL"
                                   readonly>
                            <span class="input-group-text copytext" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-4 align-items-center mt-2">
                <div class="col-lg-3 col-sm-6">
                    <div class="balance-card">
                        <span class="text--dark">@lang('Total Balance')</span>
                        <h3 class="number text--dark">{{ __($general->cur_sym) }}{{ getAmount($user->balance) }}</h3>
                    </div><!-- dashboard-card end -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="dashboard-card">
                        <span>@lang('Total Win')</span>
                        <a href="{{ route('user.wins') }}" class="view--btn">@lang('View log')</a>
                        <h3 class="number">{{ $user->wins->count() }}</h3>
                        <i class="las la-trophy icon"></i>
                    </div><!-- dashboard-card end -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="dashboard-card">
                        <span>@lang('Total Deposit')</span>
                        <a href="{{ route('user.deposit.history') }}" class="view--btn">@lang('View log')</a>
                        <h3 class="number">{{ __($general->cur_sym) }}{{ $user->deposits->sum('amount') + 0 }}</h3>
                        <i class="las la-dollar-sign icon"></i>
                    </div><!-- dashboard-card end -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="dashboard-card">
                        <span>@lang('Total Withdraw')</span>
                        <a href="{{ route('user.withdraw.history') }}" class="view--btn">@lang('View log')</a>
                        <h3 class="number">{{ __($general->cur_sym) }}{{ $user->withdrawals->where('status',1)->sum('amount') + 0 }}</h3>
                        <i class="las la-hand-holding-usd icon"></i>
                    </div><!-- dashboard-card end -->
                </div>
            </div><!-- row end -->
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Start Date')</th>
                                <th>@lang('End Date')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Sold')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($phases as $phase)
                                <tr>
                                    <td data-label="Title">
                                        <div class="table-game">
                                            <img src="{{ getImage('assets/images/lottery/'.$phase->lottery->image,imagePath()['lottery']['size']) }}" alt="image">
                                            <h6 class="name">{{ __($phase->lottery->name) }}</h6>
                                        </div>
                                    </td>
                                    <td data-label="Start Date">{{ @showDateTime($phase->start, 'Y-m-d') }}</td>
                                    <td data-label="End Date">{{ @showDateTime($phase->end, 'Y-m-d') }}</td>
                                    <td data-label="Price">{{ showAmount($phase->lottery->price) }} {{ $general->cur_text }}</td>
                                    <td data-label="Sold">
                                        <div class="progress lottery--progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                 role="progressbar" aria-valuenow="{{ ($phase->salled/$phase->quantity)*100 }}" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: {{ ($phase->salled/$phase->quantity)*100 }}%"></div>
                                        </div>
                                        <span class="fs--14px">{{ ($phase->salled/$phase->quantity)*100 }}%</span>
                                    </td>
                                    <td data-label="Status">
                                        @if($phase->draw_status == 1)
                                            @lang('Draw Complete')
                                        @elseif($phase->end < Carbon\Carbon::now())
                                            @lang('Waiting For Draw')
                                        @else
                                            @lang('Running')
                                        @endif
                                    </td>
                                    <td data-label="Action"><a href="{{ route('lotterySingle',$phase->id) }}" class="btn btn-sm btn-outline--base">@lang('Play Now')</a></td>
                                </tr>
                            @empty
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- dashboard section end -->
@endsection

@push('style')
    <style>
        .copytext{
            cursor: pointer;
        }
    </style>
@endpush

@push('script')
    <script type="text/javascript">
        (function ($) {
            "use strict";
            $('#copyBoard').click(function(){
                var copyText = document.getElementById("referralURL");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                iziToast.success({message: "Copied: " + copyText.value, position: "topRight"});
            });
        })(jQuery);
    </script>
@endpush
