@extends('admin.layouts.app')

@section('panel')
<div class="row">

    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Lottery Name')</th>
                                <th>@lang('Phase Number')</th>
                                <th>@lang('Ticket Quantity')</th>
                                <th>@lang('Remaining Quantity')</th>
                                <th>@lang('Sale Ticket')</th>
                                <th>@lang('Start Date')</th>
                                <th>@lang('Draw Date')</th>
                                <th>@lang('Draw Status')</th>
                                <th>@lang('Draw Type')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse($phases as $phase)
                            <tr>
                                <td data-label="@lang('Lottery Name')">{{ $phase->lottery->name }}</td>
                                <td data-label="@lang('Phase Number')">Phase {{ $phase->phase_number }}</td>
                                <td data-label="@lang('Ticket Quantity')">{{ $phase->quantity }}</td>
                                <td data-label="@lang('Remaining Quantity')">{{ $phase->available }}</td>
                                <td data-label="@lang('Sale Ticket')">{{ $phase->salled }}</td>
                                <td data-label="@lang('Start Date')">{{ $phase->start }}</td>
                                <td data-label="@lang('Draw Date')">{{ $phase->end }}</td>
                                <td data-label="@lang('Draw Status')">
                                    @if($phase->draw_status == 1)
                                        <span class="text--small badge font-weight-normal badge--success">
                                           @lang('Draw Complete')
                                        </span>
                                    @elseif($phase->end < Carbon\Carbon::now())
                                        <span class="text--small badge font-weight-normal badge--warning">
                                          @lang('Waiting For Draw')
                                        </span>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--primary">
                                          @lang('running')
                                        </span>
                                    @endif
                                </td>
                                <td data-label="Draw Type">
                                    @if($phase->at_dr == 1)
                                        <span class="text--small badge font-weight-normal badge--primary">
                                          @lang('Auto Draw')
                                        </span>
                                    @else
                                    <span class="text--small badge font-weight-normal badge--warning">
                                          @lang('Manual Draw')
                                    </span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <button type="button" class="icon-btn @if($phase->draw_status == 0) editBtn @endif" data-lottery="{{ $phase->lottery->id }}" data-quantity="{{ $phase->quantity }}" data-end="{{ $phase->end }}" data-start="{{ $phase->start }}" data-draw_type="{{ $phase->at_dr }}" data-action="{{ route('admin.lottery.phase.update',$phase->id) }}" @if($phase->draw_status == 1) disabled @endif><i class="la la-pencil"></i></button>
                                    @if($phase->status == 1)
                                        <a href="{{ route('admin.lottery.phase.status',$phase->id) }}" class="icon-btn bg--danger"><i class="la la-eye-slash"></i></a>
                                    @else
                                        <a href="{{ route('admin.lottery.phase.status',$phase->id) }}" class="icon-btn bg--success"><i class="la la-eye"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">@lang('Phase Not Found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $phases->links('admin.partials.paginate') }}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('admin.lottery.phase.create') }}" method="post">
            @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">@lang('Lottery Phase')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                @if(!request()->routeIs('admin.phases.lottery'))
                <label>@lang('Select Lottery')</label>
                <select class="form-control" name="lottery_id" required>
                    <option value=""> @lang('-- Select One --') </option>
                    @foreach($lotteries as $lottery)
                        <option value="{{ $lottery->id }}">{{ $lottery->name }}</option>
                    @endforeach
                </select>
                @else
                <label>@lang('Lottery Name')</label>
                <input type="hidden" name="lottery_id" value="{{ $lottery->id }}">
                <input type="text" class="form-control" value="{{ $lottery->name }}" disabled>
                @endif
            </div>
            <div class="form-group">
                <label>@lang('Start Date')</label>
                <input type="text" class="form-control timepicker" autofocus="off" placeholder="@lang('Start Date')" autocomplete="off" name="start" required>
            </div>
            <div class="form-group">
                <label>@lang('Draw Date')</label>
                <input type="text" class="form-control timepicker" placeholder="@lang('Draw Date')" name="end" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label>@lang('Quantity')</label>
                <input type="number" class="form-control" placeholder="@lang('Quantity')" name="quantity" required>
            </div>
            <div class="form-group">
                <label>@lang('Draw Type')</label>
                <select class="form-control" name="draw_type" required>
                    <option value="">@lang('-- Select One --')</option>
                    <option value="1">@lang('Auto Draw')</option>
                    <option value="0">@lang('Manual Draw')</option>
                </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
            <button type="submit" class="btn btn--primary">@lang('Create')</button>
          </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Edit Phase')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Start Date')</label>
                        <input type="text" class="form-control timepicker" placeholder="@lang('Start Date')" name="start" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Draw Date')</label>
                        <input type="text" class="form-control timepicker" placeholder="@lang('Draw Date')" name="end" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Quantity')</label>
                        <input type="number" class="form-control" placeholder="@lang('Quantity')" name="quantity" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Draw Type')</label>
                        <select class="form-control" name="draw_type" required>
                            <option value="">@lang('-- Select One --')</option>
                            <option value="1">@lang('Auto Draw')</option>
                            <option value="0">@lang('Manual Draw')</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
@if(request()->routeIs('admin.phases.lottery'))
    @if($lottery->status == 1)
        <button class="icon-btn" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> @lang('Create Lottery Phase')</button>
    @endif
@else
    <button class="icon-btn" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> @lang('Create Lottery Phase')</button>
@endif
@endpush
@push('style')
<link rel="stylesheet" href="{{ asset('assets/admin/css/datepicker.min.css') }}">
@endpush
@push('script')
<script type="text/javascript" src="{{ asset('assets/admin/js/datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/datepicker.en.js') }}"></script>
    <script>
        (function($){
            "use strict";
            // Create start date
            var start = new Date(),
                prevDay,
                startHours = 12;
            // 012:00 AM
            start.setHours(12);
            start.setMinutes(0);
            // If today is Saturday or Sunday set 10:00 AM
            if ([6, 0].indexOf(start.getDay()) != -1) {
                start.setHours(12);
                startHours = 12
            }
            $('.timepicker').datepicker({
                timepicker: true,
                language: 'en',
                startDate: start,
                minHours: startHours,
                maxHours: 24,
                dateFormat: 'yyyy-mm-dd',
                onSelect: function (fd, d, picker) {
                    // Do nothing if selection was cleared
                    if (!d) return;
                    var day = d.getDay();
                    // Trigger only if date is changed
                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;
                    // If chosen day is Saturday or Sunday when set
                    // hour value for weekends, else restore defaults
                    if (day == 6 || day == 0) {
                        picker.update({
                            minHours: 24,
                            maxHours: 24
                        })
                    } else {
                        picker.update({
                            minHours: 24,
                            maxHours: 24
                        })
                    }
                }
            })
            $('input[type=number]').on('keydown',function(e){
                var keys = [189,109];
                var keyCode = e.keyCode;
                return stopInput(keyCode,keys);
            });

            $('.editBtn').click(function(){
                var modal = $('#editModal');
                modal.find('select[name=lottery_id]').val($(this).data('lottery'));
                modal.find('input[name=end]').val($(this).data('end'));
                modal.find('input[name=start]').val($(this).data('start'));
                modal.find('input[name=quantity]').val($(this).data('quantity'));
                modal.find('select[name=draw_type]').val($(this).data('draw_type'));
                modal.find('form').attr('action',$(this).data('action'));
                modal.modal('show');
            });
        })(jQuery);
</script>
@endpush
