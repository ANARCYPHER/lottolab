@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-md-6">
        <div class="card outline-primary mb-4">
            <div class="card-header bg--primary">
                <h4 class="card-title text-white m-0">@lang('Waiting For Draw')</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <select name="phase_id" class="form-control">
                        <option value="">@lang('-- Select One --')</option>
                        @forelse($manuals as $manual)
                            <option value="{{ $manual->id }}">{{ $manual->lottery->name }}</option>
                        @endforeach
                    </select>
                </div>
         </div>
     </div>
    </div>
    <div class="col-md-6">
        <div class="card outline-primary mb-4">
            <div class="card-header bg--primary">
                <h4 class="card-title text-white m-0">@lang('Details')</h4>
            </div>
            <div class="card-body text-center">
                @if($phase)
                    <ul class="list-group bonuses" data-bonuses="{{ $phase->lottery->bonuses }}">
                      <li class="list-group-item">@lang('Lottery Name'): <strong>{{ $phase->lottery->name }}</strong></li>
                      <li class="list-group-item">@lang('Phase Number'):  <strong>@lang('Phase') {{ $phase->phase_number }}</strong></li>
                      <li class="list-group-item">@lang('Lottery Price'):  <strong>{{ getAmount($phase->lottery->price) }} {{ __($general->cur_text) }}</strong></li>

                      <li class="list-group-item">@lang('Total Sell Ticket'): <strong>{{ $tickets->count() }}</strong></li>

                      <li class="list-group-item">@lang('Total Sell Amount'): <strong>{{ getAmount($tickets->sum('total_price')) }} {{ $general->cur_text }}</strong></li>

                      <li class="list-group-item">@lang('Winner'): <strong> {{ $phase->lottery->bonuses->count() }} @lang('Persons')</strong></li>
                      <li class="list-group-item">@lang('Win Bonus Amount'): <strong>{{ $phase->lottery->bonuses->sum('amount') }} {{ $general->cur_text }}</strong></li>
                    </ul>
                @else
                <h4 class="text-center">@lang('Please select a lottery')</h4>
                @endif
            </div>
        </div>
    </div>
</div>
    @if($phase)
    <form action="{{ route('admin.lottery.draw.win',$phase->id) }}" method="post">
        @csrf
        <div class="row result_panle">
            @foreach($tickets as $ticket)
                <div class="col-md-3 mb-3">
                    <div class="card ticket-card">
                        <div class="input-fields">

                        </div>
                        <div class="card-body text-center">
                            <ul class="list-group">
                                <li class="list-group-item">@lang('Username'): <strong class="text-info username" data-ticket="{{ $ticket->ticket_number }}">{{ @$ticket->user->username }}</strong></li>
                                <li class="list-group-item">@lang('Ticket Number'): <strong class="ticket-number text-primary">{{ $ticket->ticket_number }}</strong></li>
                                <li class="list-group-item">@lang('Selected Level'): <strong class="level text-danger"></strong></li>
                                <li class="list-group-item">@lang('Win Amount'): <strong class="amount text-danger"></strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-12">
                <button class="w-100 btn btn--primary drawBtn d-none">@lang('Draw Now')</button>
            </div>
        </div>
    </form>
    @endif


@endsection
@push('style')
<style type="text/css">
    .ticket-card{
        cursor: pointer;
    }
    .op-0-7{
        opacity: 0.7;
    }
</style>
@endpush
@push('script')
<script>
    (function($){
        "use strict";
        var selector = $('.ticket-card .username');
        var selected = $('.op-0-7');


        $('select[name=phase_id]').on("change", function() {
            if ($(this).val() == '') {
                console.log($(this).val())
                return false;
            }
            window.location.href = "{{url('/')}}/admin/lottery/find/ticket/"+$(this).val();
        });
        var levlen = 0;
        var level = 0;
        var mns = [];
        var userWinAmount = 0;
        var lotteryPrice = 0;
        var totalBonus = 0;
        var bonuses = $('.bonuses').data('bonuses');
        @if($phase)
        $('select[name=phase_id]').val({{ @$phase->id }});
        levlen = {{ $phase->lottery->bonuses->count() }};
        lotteryPrice = {{ $phase->lottery->price }};
        @endif
        $(document).on('click','.ticket-card',function(){

            if ($(this).hasClass('op-0-7')) {
                level--;
                $(this).removeClass('op-0-7');
                if (!$(this).hasClass('extra')) {

                $(this).addClass('test-card');
                }
                mns.push($(this).find('.level').text());
                $(this).find('.level').text('');
                $(this).find('.amount').text('');
                totalBonus -= parseFloat(bonuses[mns[mns.length - 1] - 1].amount);
                $(this).find('.input-fields').html('');
            }else{
                if (level < levlen) {
                    level++;
                    $(this).addClass('op-0-7');
                    if (!$(this).hasClass('extra')) {

                    $(this).removeClass('test-card');
                    }
                    if (mns.length > 0) {
                        var mnsVal = mns[0];
                        userWinAmount = bonuses[mnsVal-1].amount;
                        $(this).find('.level').text(mnsVal);
                        $(this).find('.level').attr('data-level',mnsVal);
                        $(this).find('.amount').text(userWinAmount+' {{ $general->cur_text }}');
                        mns.shift();
                        var html = `<input type="hidden" name="number[${mnsVal}]" class="tkNum" value="${$(this).find('.ticket-number').text()}">`
                    }else{
                        userWinAmount = bonuses[level-1].amount;
                        $(this).find('.level').text(level);
                        $(this).find('.level').attr('data-level',level);
                        $(this).find('.amount').text(userWinAmount+' {{ $general->cur_text }}');
                        var html = `<input type="hidden" name="number[${level}]" class="tkNum" value="${$(this).find('.ticket-number').text()}">`
                    }
                    totalBonus += parseFloat(userWinAmount);

                    $(this).find('.input-fields').html(html);
                }
            }
            $('.bonus-amount').text(totalBonus+' {{ $general->cur_text }}');
            @if($phase)
            if (level == {{ $phase->lottery->bonuses->count() }}) {
                $('.drawBtn').removeClass('d-none');
            }else{
                $('.drawBtn').addClass('d-none');
            }
            @endif

        });




    })(jQuery);
</script>
@endpush
