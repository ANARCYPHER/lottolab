@extends($activeTemplate.'layouts.master')

@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="text-end mb-3">


                        @if($general->dc)
                            <a href="
                            @if(request()->routeIs('user.commissions.deposit'))
                                javascript:void(0)
@else
                            {{ route('user.commissions.deposit') }}
                            @endif                            " class="btn btn--base mb-md-0 mb-2
                            @if(request()->routeIs('user.commissions.deposit'))
                                btn-disabled
@endif
                                ">
                                @lang('Deposit Commission')
                            </a>
                        @endif
                        @if($general->bc)
                            <a href="
                            @if(request()->routeIs('user.commissions.buy'))
                                javascript:void(0)
@else
                            {{ route('user.commissions.buy') }}
                            @endif
                                " class="btn btn--base mb-md-0 mb-2
                            @if(request()->routeIs('user.commissions.buy'))
                                btn-disabled
@endif
                                ">
                                @lang('Lottery Buy Commission')
                            </a>
                        @endif
                        @if($general->wc)
                            <a href="@if(request()->routeIs('user.commissions.win'))
                                javascript:void(0)
@else
                            {{ route('user.commissions.win') }}
                            @endif                            " class="btn btn--base mb-md-0
                            @if(request()->routeIs('user.commissions.win'))
                                btn-disabled
@endif
                                ">
                                @lang('Win Commission')
                            </a>
                        @endif

                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-responsive--md custom--table">
                        <thead>
                        <tr>
                            <th>@lang('Commission From')</th>
                            <th>@lang('Commission Level')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Transaction')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($commissions) >0)
                            @forelse($commissions as $log)
                                <tr>
                                    <td data-label="@lang('Commission From')">{{ $log->userFrom->username }}</td>
                                    <td data-label="@lang('Level')">{{ $log->level }}</td>
                                    <td data-label="@lang('Amount')">{{ getAmount($log->amount) }} {{ $general->cur_text }}</td>
                                    <td data-label="@lang('Title')">{{ $log->title }}</td>
                                    <td data-label="@lang('Transaction')">{{ $log->trx }}</td>
                                </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="100%"> @lang('No results found')!</td>
                                    </tr>
                                @endif
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $commissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

