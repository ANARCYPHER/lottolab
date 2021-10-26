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
                                <th>@lang('Price')</th>
                                <th>@lang('Total Phase')</th>
                                <th>@lang('Total Draw')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse($lotteries as $lottery)
                            <tr>
                                <td data-label="@lang('Lottery Name')">{{ $lottery->name }}</td>
                                <td data-label="@lang('Price')">{{ getAmount($lottery->price) }} {{ $general->cur_text }}</td>
                                <td data-label="@lang('Total Phase')">{{ $lottery->phase->count() }}</td>
                                <td data-label="@lang('Total Draw')">{{ $lottery->phase->where('draw_status',1)->count() }}</td>
                                <td data-label="@lang('Status')">
                                    @if($lottery->status == 1)
                                        <span class="text--small badge font-weight-normal badge--success">
                                            @lang('active')
                                        </span>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--danger">
                                            @lang('inactive')
                                        </span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.lottery.edit',$lottery->id) }}" class="icon-btn"><i class="la la-pencil"></i></a>
                                    <a href="{{ route('admin.lottery.winBonus',$lottery->id) }}" class="icon-btn bg--info" data-toggle="tooltip" data-placement="top" title="@lang('Set Win Bonus')"><i class="las la-trophy"></i></a>
                                    <a href="{{ route('admin.lottery.phase.singleLottery',$lottery->id) }}" class="icon-btn bg--7" data-toggle="tooltip" data-placement="top" title="@lang('See Phases')"><i class="fas fa-layer-group"></i></a>
                                    @if($lottery->status == 1)
                                        <a href="{{ route('admin.lottery.status',$lottery->id) }}" class="icon-btn bg--danger"><i class="la la-eye-slash"></i></a>
                                    @else
                                        <a href="{{ route('admin.lottery.status',$lottery->id) }}" class="icon-btn bg--success"><i class="la la-eye"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">@lang('Ticket Not Found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-4">
                {{ $lotteries->links('admin.partials.paginate') }}
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
<a href="{{ route('admin.lottery.create') }}" class="icon-btn"><i class="fa fa-plus"></i> @lang('Create Lottery')</a> 
@endpush