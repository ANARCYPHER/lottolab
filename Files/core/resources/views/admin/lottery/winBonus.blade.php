@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-md-12">


        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-0">@lang('CURRENT SETTING')</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                          <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th>@lang('Winner')</th>
                                    <th>@lang('Win Bonus')</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($lottery->bonuses as $key => $p)
                                    <tr>
                                        <td data-label="Level">@lang('Winner#') {{ $p->level }}</td>
                                        <td data-label="Win Bonus">{{ $p->amount }} {{ __($general->cur_text) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-0">@lang('CHANGE SETTING')</h4>
                    </div>
                   <div class="card-body">
                       <div class="row">
                           <div class="col-md-6">
                               <input type="number" name="level" id="levelGenerate" placeholder="@lang('HOW MANY WINNER')" class="form-control input-lg">
                           </div>
                           <div class="col-md-6">
                               <button type="button" id="generate" class="btn btn--success btn-block btn-md">@lang('GENERATE')</button>
                           </div>
                       </div>
                       <br>
                       <form action="{{route('admin.lottery.bonus')}}" class="d-none" id="prantoForm" method="post">
                           {{csrf_field()}}
                           <input type="hidden" name="lottery_id" value="{{ $lottery->id }}">
                           <div class="form-group">
                               <label class="text-success"> @lang('Winner & Win Bonus '): <small>@lang('(Old Winners Level will Remove After Generate)')</small> </label>
                               <div class="row">
                                   <div class="col-md-12">
                                       <div class="description ref-desc">
                                           <div class="row">
                                               <div class="col-md-12" id="planDescriptionContainer">
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <hr>
                           <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                       </form>

                   </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
@push('style')
<style type="text/css">
  .ref-desc{
    width: 100%;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px
  }
</style>
@endpush
@push('script')
    <script>
      (function($){
        "use strict";
        var max = 1;
        $(document).ready(function () {
            $("#generate").on('click', function () {

                var da = $('#levelGenerate').val();
                var a = 0;
                var val = 1;
                var guu = '';
                if (da !== '' && da >0)
                {
                    $('#prantoForm').addClass('d-block');
                    for (a; a < parseInt(da);a++){
                        console.log()
                        guu += '<div class="input-group mt-2">\n' +
                            '<input name="level[]" class="form-control margin-top-10" type="number" readonly value="'+val+++'" required placeholder="Level">\n' +
                            '<input name="amount[]" class="form-control margin-top-10" type="text" required placeholder="Win Bonus In {{ $general->cur_text }}">\n' +
                            '<span class="input-group-btn">\n' +
                            '<button class="btn btn-danger margin-top-10 delete_desc" type="button"><i class=\'fa fa-times\'></i></button></span>\n' +
                            '</div>'
                    }
                    $('#planDescriptionContainer').html(guu);

                }else {
                    alert('Level Field Is Required')
                }

            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.input-group').remove();
            });
        });
      })(jQuery);

    </script>
@endpush
@push('breadcrumb-plugins')
<a href="{{ route('admin.lottery.index') }}" class="icon-btn" ><i class="fa fa-fw fa-reply"></i>@lang('Back')</a> 
@endpush