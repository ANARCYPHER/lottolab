@extends($activeTemplate.'layouts.master')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-30">
                    <table class="table table-responsive--md custom--table">
                        <thead>
                        <tr>
                            <th>@lang('S.L')</th>
                            <th>@lang('Lottery Name')</th>
                            <th>@lang('Phase Number')</th>
                            <th class="text-center">@lang('Ticket')</th>
                            <th>@lang('Result')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td data-label="@lang('S.L')">{{ __($loop->iteration) }}</td>
                                <td data-label="@lang('Lottery Name')">{{ __($ticket->lottery->name) }}</td>
                                <td data-label="@lang('Phase Number')">@lang('Phase '.$ticket->phase->phase_number)</td>
                                <td data-label="@lang('Tickets')" class="text-center">
                                    {{ $ticket->ticket_number }}
                                </td>
                                <td data-label="@lang('Result')">
                                    @if($ticket->phase->draw_status == 1)
                                        <span class="badge badge--success">@lang('Published')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Will be Publish')</span>
                                    @endif
                                </td>
                                @empty
                                    <td colspan="100%" class="text-center">@lang('Data Not Found')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
