@extends($activeTemplate.'layouts.master')

@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center mt-2">
                <div class="col-md-12">
                    <table class="table table-responsive--md custom--table">
                        <thead>
                        <tr>
                            <th>@lang('S.L')</th>
                            <th>@lang('Lottery Name')</th>
                            <th>@lang('Phase Number')</th>
                            <th class="text-center">@lang('Ticket Number')</th>
                            <th class="text-center">@lang('Win Bonus')</th>
                            <th class="text-center">@lang('Winning Level')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($wins as $win)
                            <tr>
                                <td data-label="@lang('S.L')">{{ __($loop->iteration) }}</td>
                                <td data-label="@lang('Lottery Name')">{{ __($win->tickets->lottery->name) }}</td>
                                <td data-label="@lang('Phase Number')">@lang('Phase '.$win->tickets->phase->phase_number)</td>
                                <td class="text-center"
                                    data-label="@lang('Ticket Number')">{{ __($win->ticket_number) }}</td>
                                <td class="text-center"
                                    data-label="@lang('Win Bonus')">{{ __(getAmount($win->win_bonus)) }} {{ __($general->cur_sym) }}</td>
                                <td class="text-center"
                                    data-label="@lang('Winning Level')">@lang('Level') {{ $win->level }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">@lang('No lottery you win still now')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $wins->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
