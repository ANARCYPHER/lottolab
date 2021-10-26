@extends($activeTemplate.'layouts.frontend')
@section('content')
    <!-- lottery details section start -->
    <section class="pt-50 pb-50">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="lottery-details-header">
                        <div class="thumb"><img src="{{ getImage('assets/images/lottery/'.$phase->lottery->image, imagePath()['lottery']['size']) }}" alt="image"></div>
                        <div class="content text-center">
                            <h3 class="game-name mb-4">{{ __($phase->lottery->name) }}</h3>
                            <div class="clock" data-clock="{{ showDateTime($phase->end,'Y/m/d h:i:s') }}" data-title="@lang('This lottery expired')"></div>
                        </div>
                    </div><!-- lottery-details-header ed -->
                </div>
            </div><!-- row end -->
            <div class="row mt-5">
                <div class="col-lg-12">

                    <form method="post" action="{{ route('user.buyTicket') }}">
                        @csrf

                        <input type="hidden" name="lottery_id" value="{{ $phase->lottery->id }}">
                        <input type="hidden" name="phase_id" value="{{ $phase->id }}">

                        <div class="lottery-details-body">
                            <div class="top-part">
                                <div class="left">
                                    <h4>@lang('Available Ticket'): {{ __($phase->available) }}</h4>
                                    <h4 class="mt-2">@lang('Price'):  {{__($general->cur_sym)}}{{ __(showAmount($phase->lottery->price)) }}</h4>
                                </div>
                                @auth
                                    <div class="middle">
                                        <div class="balance">@lang('Balance'):  {{__($general->cur_sym)}}{{ showAmount(auth()->user()->balance) }}</div>
                                    </div>
                                @endauth
                                <div class="right">
                                    <button type="button" class="btn btn-md btn--base addMore">+ @lang('Add New')</button>
                                </div>
                            </div>
                            <div class="body-part">
                                <div class="row gy-4" id="tickets">

                                    <div class="col-xl-4 col-md-6">
                                        <div class="ticket-card">
                                            <div class="ticket-card__header">
                                                <h4>@lang('Your Ticket Number')</h4>
                                            </div>
                                            <div class="ticket-card__body elements">
                                                <input type="hidden" class="numVal" name="number[]">
                                                <div class="numbers uniqueNumbers mb-4">
                                                    <span>0</span>
                                                    <span>0</span>
                                                    <span>0</span>
                                                    <span>0</span>
                                                    <span>0</span>
                                                    <span>0</span>
                                                    <span>0</span>
                                                    <span>0</span>
                                                    <span>0</span>
                                                    <span>0</span>
                                                </div>
                                                <button type="button" class="btn btn-md btn--base w-100 generate">@lang('Generate Number')</button>
                                            </div>
                                        </div><!-- ticket-card end -->
                                    </div>

                                </div>
                            </div>
                            <div class="footer-part">
                                <div class="left">
                                    <p>@lang('1 Draw with') <span class="qnt">1</span> @lang('ticket') : <span class="qnt">1</span> x {{ getAmount($phase->lottery->price) }} {{ __($general->cur_text) }}</p>
                                    <p class="mt-2">@lang('Total Amount') : <span class="tam">{{ getAmount($phase->lottery->price) }}</span> <span>{{ $general->cur_text }}</span></p>
                                </div>
                                <div class="right">
                                    <button type="submit" class="btn btn-md btn--base">@lang('Buy Now')</button>
                                </div>
                            </div>
                        </div><!-- lottery-details-body end -->
                    </form>

                    <div class="lottery-details-instruction mt-5">
                        <ul class="nav nav-tabs cumtom--nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-4 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">@lang('Instruction')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-4" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">@lang('Win Bonuses')</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-4" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <h3 class="mb-3">@lang('Introduction')</h3>

                                @php echo $phase->lottery->detail @endphp

                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <table class="table level-table">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase font-weight-bold text-white">@lang('Winners')</th>
                                        <th class="text-uppercase font-weight-bold text-white">@lang('Win Bonus')</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($phase->lottery->bonuses as $bonus)
                                        <tr>
                                            <td class="text-white">@lang('Winner')- {{ $bonus->level }}</td>
                                            <td class="text-white">{{ $bonus->amount }} {{ __($general->cur_text) }}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- row end -->
        </div>
    </section>
    <!-- lottery details section end -->
@endsection

@push('script')
<script type="text/javascript">
  (function($){
        "use strict";
      	$( window ).on('load',function(){
              var element = $('.elements').length;
              addMoreBtn(element);
            });

      	$('.addMore').click(function(){
                var element = $('.elements').length + 1

                	var html = `

                        <div class="col-xl-4 col-md-6 elem">
                            <div class="ticket-card">
                                <button type="button" class="ticket-card-del removeBtn"><i class="las la-times"></i></button>
                                <div class="ticket-card__header">
                                    <h4>@lang('Your Ticket Number')</h4>
                                </div>
                                <div class="ticket-card__body elements">
                                    <input type="hidden" class="numVal" name="number[]">
                                    <div class="numbers uniqueNumbers mb-4">
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                    </div>
                                    <button type="button" class="btn btn-md btn--base w-100 generate">@lang('Generate Number')</button>
                                </div>
                            </div>
                        </div>


                	`;
                  $('#tickets').append(html);
                  $('.qnt').html(element);
                  $('.tam').html(element * {{ $phase->lottery->price }});
                  $('input[name=ticket_quantity]').val(element);
                  $('input[name=total_price]').val(element * {{ $phase->lottery->price }});
                  rAndOm();
                  remove();
                  addMoreBtn(element);
              });

      	function remove(){
                $('.removeBtn').click(function(){
                  $(this).parents('.elem').remove();
                  var elem = $('.elements').length;
                  addMoreBtn(elem);
                  $('.qnt').html(elem);
                  $('.tam').html(elem * {{ $phase->lottery->price }});
                  $('input[name=ticket_quantity]').val(elem);
                  $('input[name=total_price]').val(elem * {{ $phase->lottery->price }});
                });
              }


      	function addMoreBtn(count){
                if (count >= {{ $phase->available }}) {
                  $('.addMore').addClass('d-none');
                }else{
                  $('.addMore').removeClass('d-none');
                }
              }

              function rAndOm(){
                  $('.generate').click(function(){

                      var tendigitrandom = Math.floor(1000000000 + Math.random() * 9000000000);
                      var array = tendigitrandom.toString().split('');
                      var newArray = [];

                      $.each(array, function( index, value ) {
                          newArray[index] =`<span>${value}</span>`;
                      });

                      $(this).parents('.elements').children('.numbers').html(newArray);

                      $(this).parents('.elements').children('.numbers').addClass('active');
                      $(this).parents('.elements').children('.numbers').removeClass('op-0-3');
                      $(this).parents('.elements').children('.numVal').val(tendigitrandom);
                  });
              }
              $('.generate').click(function(){

                  var tendigitrandom = Math.floor(1000000000 + Math.random() * 9000000000);
                  var array = tendigitrandom.toString().split('');
                  var newArray = [];

                  $.each(array, function( index, value ) {
                      newArray[index] =`<span>${value}</span>`;
                  });

                  $(this).parents('.elements').children('.numbers').html(newArray);

                  $(this).parents('.elements').children('.numbers').addClass('active');
                  $(this).parents('.elements').children('.numbers').removeClass('op-0-3');
                  $(this).parents('.elements').children('.numVal').val(tendigitrandom);
              });
    })(jQuery);
</script>
@endpush
