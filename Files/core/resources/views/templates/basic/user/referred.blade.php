@extends($activeTemplate.'layouts.master')

@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center mt-2">
                @for($i = 1; $i <= $lev; $i++)
                    <div class="col-md-2 col-sm-4 col-6 pb-3">
                        <a href="
                        @if(request()->url() == route('user.referred',$i,auth()->user()->id || ($firstActive == 1 && $i == 1)))
                            javascript:void(0)
@else
                        {{route('user.referred',$i)}}
                        @endif
                            "
                           class="btn btn--base w-100 btn-block mb-3 text-center @if(request()->url() == route('user.referred',$i,auth()->user()->id) || ($firstActive == 1 && $i == 1)) btn-disabled @endif">@lang('Level '.$i)</a>
                    </div>
                @endfor
                <div class="col-md-12">
                    <table class="table table-responsive--md custom--table">
                        <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Full Name')</th>
                            <th>@lang('User Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Mobile')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td data-label="@lang('SL')">{{ $loop->iteration }}</td>
                                <td data-label="@lang('Fullname')">{{ __($user->fullname) }}</td>
                                <td data-label="@lang('Username')">{{ __($user->username) }}</td>
                                <td data-label="@lang('Email')">{{ showDateTime($user->created_at) }}</td>
                                <td data-label="@lang('Mobile')">{{ __($user->mobile) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">@lang('Data not found')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('style')
    <style type="text/css">
        .copytextDiv {
            border: 1px solid rgba(255, 255, 255, 0.15);
            cursor: pointer;
        }
    </style>
@endpush
