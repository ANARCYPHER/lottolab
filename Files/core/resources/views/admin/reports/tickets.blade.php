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
                                <th class="text-center">@lang('Tickets')</th>
                                <th>@lang('Result')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td data-label="@lang('User')">
                                        <span class="font-weight-bold">{{ @$ticket->user->fullname }}</span>
                                        <br>
                                        <span class="small"> <a
                                                href="{{ route('admin.users.detail', $ticket->user_id) }}"><span>@</span>{{ @$ticket->user->username }}</a> </span>
                                    </td>
                                    <td data-label="@lang('Lottery - Phase')">
                                      {{ __($ticket->lottery->name) }} <br>
                                      @lang('Phase') {{ __($ticket->phase->phase_number) }} 
                                    </td>
                                    <td class="text-center" data-label="@lang('Tickets')">
                                        <strong>{{ $ticket->ticket_number }}</strong>
                                    </td>
                                    <td data-label="@lang('Result')">
                                        @if($ticket->phase->draw_status == 1)
                                            <span
                                                class="text--small badge font-weight-normal badge--success">@lang('Published')</span>
                                        @else
                                            <span
                                                class="text--small badge font-weight-normal badge--danger">@lang('Will be Publish')</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $tickets->appends($_GET)->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <form action="{{ route('admin.report.lotTick.search') }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Username or ticket number')"
                   value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush