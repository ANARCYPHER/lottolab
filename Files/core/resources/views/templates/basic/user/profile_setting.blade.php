@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container pt-100 pb-100">
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card custom__bg">
                    <div class="card-body">
                        <form class="register prevent-double-click" action="" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="profile-thumb-wrapper text-center mb-4">
                                <div class="profile-thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }})"></div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" class="profilePicUpload" id="profilePicUpload1" name="image" accept="image/*">
                                        <label for="profilePicUpload1"><i class="la la-pencil"></i></label>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="InputFirstname" class="col-form-label">@lang('First Name'):</label>
                                    <input type="text" class="form--control" id="InputFirstname" name="firstname" placeholder="@lang('First Name')" value="{{$user->firstname}}" minlength="3">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="lastname" class="col-form-label">@lang('Last Name'):</label>
                                    <input type="text" class="form--control" id="lastname" name="lastname" placeholder="@lang('Last Name')" value="{{$user->lastname}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="email" class="col-form-label">@lang('E-mail Address'):</label>
                                    <input class="form--control" id="email" placeholder="@lang('E-mail Address')" value="{{$user->email}}" readonly>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="phone" class="col-form-label">@lang('Mobile Number')</label>
                                    <input class="form--control" id="phone" value="{{$user->mobile}}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="address" class="col-form-label">@lang('Address'):</label>
                                    <input type="text" class="form--control" id="address" name="address" placeholder="@lang('Address')" value="{{@$user->address->address}}" required="">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="state" class="col-form-label">@lang('State'):</label>
                                    <input type="text" class="form--control" id="state" name="state" placeholder="@lang('state')" value="{{@$user->address->state}}" required="">
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="zip" class="col-form-label">@lang('Zip Code'):</label>
                                    <input type="text" class="form--control" id="zip" name="zip" placeholder="@lang('Zip Code')" value="{{@$user->address->zip}}" required="">
                                </div>

                                <div class="form-group col-sm-4">
                                    <label for="city" class="col-form-label">@lang('City'):</label>
                                    <input type="text" class="form--control" id="city" name="city" placeholder="@lang('City')" value="{{@$user->address->city}}" required="">
                                </div>

                                <div class="form-group col-sm-4">
                                    <label class="col-form-label">@lang('Country'):</label>
                                    <input class="form--control" value="{{@$user->address->country}}" disabled>
                                </div>

                            </div>

                            <div class="form-group row pt-5">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn--base w-100">@lang('Update Profile')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style-lib')
    <link href="{{ asset($activeTemplateTrue.'css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endpush
@push('style')
    <link rel="stylesheet" href="{{asset('assets/admin/build/css/intlTelInput.css')}}">
    <style>
        .intl-tel-input {
            position: relative;
            display: inline-block;
            width: 100%;!important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
                    $(preview).css('background-image', 'url(' + e.target.result + ')');
                    $(preview).addClass('has-image');
                    $(preview).hide();
                    $(preview).fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".profilePicUpload").on('change', function() {
            proPicURL(this);
        });

        $(".remove-image").on('click', function(){
            $(".profilePicPreview").css('background-image', 'none');
            $(".profilePicPreview").removeClass('has-image');
        })
    </script>
@endpush
