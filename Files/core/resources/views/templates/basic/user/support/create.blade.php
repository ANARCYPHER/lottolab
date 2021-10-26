@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container pt-100 pb-100">
        <div class="text-end">
            <a href="{{route('ticket') }}" class="btn btn--base">
                @lang('My Support Ticket')
            </a>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card custom__bg">
                    <div class="card-body">
                        <form  action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">@lang('Name')</label>
                                    <input type="text" name="name" value="{{@$user->firstname . ' '.@$user->lastname}}" class="form--control form-control-lg" placeholder="@lang('Enter your name')" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">@lang('Email address')</label>
                                    <input type="email"  name="email" value="{{@$user->email}}" class="form--control form--control-lg" placeholder="@lang('Enter your email')" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="website">@lang('Subject')</label>
                                    <input type="text" name="subject" value="{{old('subject')}}" class="form--control form-control-lg" placeholder="@lang('Subject')" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="priority">@lang('Priority')</label>
                                    <select name="priority" class="form--control form-control-lg">
                                        <option value="3">@lang('High')</option>
                                        <option value="2">@lang('Medium')</option>
                                        <option value="1">@lang('Low')</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="inputMessage">@lang('Message')</label>
                                    <textarea name="message" id="inputMessage" rows="6" class="form--control form-control-lg">{{old('message')}}</textarea>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-9 file-upload">
                                    <div class="position-relative">
                                        <input type="file" name="attachments[]" id="inputAttachments" class="form--control custom--file-upload my-1"/>
                                        <label for="inputAttachments">@lang('Attachments')</label>
                                    </div>

                                    <div id="fileUploadsContainer"></div>
                                    <p class="ticket-attachments-message text-muted">
                                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                    </p>
                                </div>

                                <div class="col-sm-1 mt-1">
                                    <button type="button" class="btn btn--base btn-sm addFile">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row form-group justify-content-center">
                                <div class="col-md-12">
                                    <button class="btn btn--base" type="submit" id="recaptcha" ><i class="fa fa-paper-plane"></i>&nbsp;@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.addFile').on('click',function(){
                $("#fileUploadsContainer").append(`
                    <div class="position-relative">
                        <input type="file" name="attachments[]" id="inputAttachments" class="form--control custom--file-upload my-1"/>
                        <label for="inputAttachments">@lang('Attachments')</label>
                    </div>
                `)
            });
        })(jQuery);
    </script>
@endpush
