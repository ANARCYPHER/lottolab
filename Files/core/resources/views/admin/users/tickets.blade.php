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
                              <th>@lang('S.L')</th>
                              <th>@lang('Lottery Name')</th>
                              <th>@lang('Phase Number')</th>
                              <th class="text-center">@lang('Tickets')</th>
                              <th>@lang('Result')</th>
                          </tr>

                          </thead>
                          <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                              <th scope="row" data-label="S.L">{{ __($loop->iteration) }}</th>
                              <td data-label="@lang('Lottery Name')">{{ __($ticket->lottery->name) }}</td>
                              <td data-label="@lang('Phase Number')">@lang('Phase') {{ __($ticket->phase->phase_number) }}</td>
                              <td class="text-center" data-label="@lang('Tickets')">
                                {{ $ticket->ticket_number }}
                              </td>
                              <td data-label="@lang('Result')">
                                @if($ticket->phase->draw_status == 1)
                                <span class="text--small badge font-weight-normal badge--success">@lang('Published')</span>
                                @else
                                <span class="text--small badge font-weight-normal badge--danger">@lang('Will be Publish')</span>
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
    <form action="{{ route('admin.users.tickets.search',$user->id) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Ticket Number')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
