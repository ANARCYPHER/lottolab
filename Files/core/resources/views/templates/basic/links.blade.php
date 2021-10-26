@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-md-12">
                @php echo $data->data_values->description @endphp
            </div>
        </div><!-- row end -->
    </div>
</section>
@endsection
