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
                                <th>@lang('User Name')</th>
                                <th>@lang('Lottery - Phase')</th>
                                <th>@lang('Ticket Number')</th>
                                <th>@lang('Win Bonus - Win Level')</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse($winners as $winner)
                            <tr>
                                <td data-label="@lang('User')">
                                    <span class="font-weight-bold">{{ @$winner->tickets->user->fullname }}</span>
                                    <br>
                                    <span class="small"> <a href="{{ route('admin.users.detail', $winner->tickets->user->id) }}"><span>@</span>{{ @$winner->tickets->user->username }}</a> </span>
                                </td>
                                <td data-label="@lang('Lottery Name')">
                                    {{ $winner->tickets->lottery->name }}
                                    <br>
                                    @lang('Phase') - {{ $winner->tickets->phase->phase_number }}
                                </td>
                                <td data-label="@lang('Ticket Number')"><strong>{{ $winner->ticket_number }}</strong></td>
                                <td data-label="@lang('Win Bonus - Win Level')">
                                    <span class="font-weight-bold">{{ $general->cur_sym }}{{ getAmount($winner->win_bonus) }}</span><br>
                                    @lang('Level') {{ $winner->level }}
                                </td>
                                @empty
                                <td colspan="5" class="text-center">{{ $empty_message }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-4">
                {{ $winners->appends($_GET)->links('admin.partials.paginate') }}
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
    <form action="{{ route('admin.report.winners.search') }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Username or ticket number')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush