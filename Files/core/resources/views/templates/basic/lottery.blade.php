@extends($activeTemplate.'layouts.frontend')
@section('content')
    <section class="pt-50 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Start Date')</th>
                                <th>@lang('End Date')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Sold')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($phases as $phase)
                                <tr>
                                    <td data-label="@lang('Title')">
                                        <div class="table-game">
                                            <img src="{{ getImage('assets/images/lottery/'.$phase->lottery->image,imagePath()['lottery']['size']) }}" alt="image">
                                            <h6 class="name">{{ __($phase->lottery->name) }}</h6>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Start Date')">{{ @showDateTime($phase->start, 'Y-m-d') }}</td>
                                    <td data-label="@lang('End Date')">{{ @showDateTime($phase->end, 'Y-m-d') }}</td>
                                    <td data-label="@lang('Price')">{{ showAmount($phase->lottery->price) }} {{ $general->cur_text }}</td>
                                    <td data-label="@lang('Sold')">
                                        <div class="progress lottery--progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                 role="progressbar" aria-valuenow="{{ ($phase->salled/$phase->quantity)*100 }}" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: {{ ($phase->salled/$phase->quantity)*100 }}%"></div>
                                        </div>
                                        <span class="fs--14px">{{ ($phase->salled/$phase->quantity)*100 }}%</span>
                                    </td>
                                    <td data-label="@lang('Status')">
                                        @if($phase->draw_status == 1)
                                            @lang('Draw Complete')
                                        @elseif($phase->end < Carbon\Carbon::now())
                                            @lang('Waiting For Draw')
                                        @else
                                            @lang('Running')
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')"><a href="{{ route('lotterySingle',$phase->id) }}" class="btn btn-sm btn-outline--base">@lang('Buy Ticket')</a></td>
                                </tr>
                            @empty
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                {{ $phases->links() }}
            </div>
        </div>
    </section>
@endsection
