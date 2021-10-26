@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body">
                    @if(request()->routeIs('admin.users.transactions'))
                        <form action="" method="GET" class="form-inline float-sm-right bg--white">
                            <div class="input-group has_append">
                                <input type="text" name="search" class="form-control" placeholder="@lang('TRX / Username')" value="{{ $search ?? '' }}">
                                <div class="input-group-append">
                                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('admin.report.commissions.search') }}" method="GET" class="form-inline float-sm-right bg--white mb-3">
                            <div class="input-group has_append">
                                <input type="text" name="search" class="form-control" placeholder="@lang('TRX / Username')" value="{{ $search ?? '' }}">
                                <div class="input-group-append">
                                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    @endif
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('User')</th>
                                <th scope="col">@lang('Date')</th>
                                <th scope="col">@lang('Percent - Amount')</th>
                                <th scope="col">@lang('Type - Transaction')</th>
                                <th scope="col">@lang('Level')</th>
                                <th scope="col">@lang('Description')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $data)
                                <tr @if($data->amount < 0) class="halka-golapi" @endif>
                                    <td data-label="@lang('User')">
                                        <span class="font-weight-bold">{{ @$data->userTo->fullname }}</span>
                                        <br>
                                        <span class="small"> <a href="{{ route('admin.users.detail', $data->to_id) }}"><span>@</span>{{ @$data->userTo->username }}</a> </span>
                                    </td>
                                    <td data-label="@lang('Date')">{{ showDateTime($data->created_at) }}<br>{{ diffForHumans($data->created_at) }}</td>
                                    <td data-label="@lang('Percent - Amount')">
                                        <span class="font-weight-bold">{{getAmount($data->percent)}}%</span>
                                        <br>
                                        {{__($general->cur_sym)}}{{getAmount($data->amount)}}
                                    </td>
                                    <td data-label="@lang('Type - Transaction')">
                                        @if($data->commission_type == 'deposit')
                                        <span class="badge badge--success">@lang('Deposit')</span>
                                        @elseif($data->commission_type == 'interest')
                                        <span class="badge badge--info">@lang('Interest')</span>
                                        @else
                                        <span class="badge badge--primary">@lang('Invest')</span>
                                        @endif
                                        <br>
                                        <strong>{{__($data->trx)}}</strong>
                                    </td>
                                    <td data-label="@lang('Level')">{{__($data->level) }}</td>
                                    <td data-label="@lang('Description')">{{__($data->title)}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ trans($empty_message) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $logs->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
<div class="mb-3">
    <a href="
    @if(request()->routeIs('admin.report.commissions.deposit'))
    javascript:void(0)
    @else
    {{ route('admin.report.commissions.deposit') }}
    @endif
    " class="btn btn--primary mb-2
    @if(request()->routeIs('admin.report.commissions.deposit'))
    btn-disabled
    @endif
    ">@lang('Deposit Commission')</a>
    <a href="
    @if(request()->routeIs('admin.report.commissions.buy'))
    javascript:void(0)
    @else
    {{ route('admin.report.commissions.buy') }}
    @endif
    " class="btn btn--primary mb-2
    @if(request()->routeIs('admin.report.commissions.buy'))
    btn-disabled
    @endif
    ">@lang('Buy Commission')</a>
    <a href="
    @if(request()->routeIs('admin.report.commissions.win'))
    javascript:void(0)
    @else
    {{ route('admin.report.commissions.win') }}
    @endif
    " class="btn btn--primary mb-2 mr-2
    @if(request()->routeIs('admin.report.commissions.win'))
    btn-disabled
    @endif
    ">@lang('Win Commission')</a>
</div>
<div>
</div>
@endpush