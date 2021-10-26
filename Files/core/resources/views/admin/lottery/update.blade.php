@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="{{ route('admin.lottery.update',$lottery->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">                                
                            <div class="form-group">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage('assets/images/lottery/'.$lottery->image,imagePath()['lottery']['size']) }})">
                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg" requierd>
                                            <label for="profilePicUpload1" class="bg-primary">@lang('Lottery image')</label>
                                            <small class="mt-2 text-facebook">@lang('Supported files:') <b>@lang('jpeg, jpg, png')</b>. @lang('Image will be resized into') <b>{{ imagePath()['lottery']['size'] }}@lang('px')</b></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>@lang('Lottery Name')</label>
                                <input type="text" class="form-control" placeholder="@lang('Your Lottery Title')" name="name" value="{{ $lottery->name }}" requierd/>
                            </div>
                            <div class="form-group">
                                <label>@lang('Price')</label>
                                <div class="input-group mb-3">
                                  <input type="number" class="form-control" placeholder="@lang('Price')" name="price" value="{{ getAmount($lottery->price) }}" required>
                                  <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">{{ $general->cur_sym }}</span>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Lottery Instruction')</label>
                                <textarea rows="8" class="form-control nicEdit" placeholder="@lang('Lottery Instruction')" name="detail" requierd>@php echo $lottery->detail @endphp</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-block btn-primary mr-2">@lang('Post')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.lottery.index') }}" class="icon-btn" ><i class="fa fa-fw fa-reply"></i>@lang('Back')</a> 
@endpush