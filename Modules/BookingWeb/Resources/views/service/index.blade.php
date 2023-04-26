@extends('bookingweb::layout')

@section('content')
    {{session('service_category_id')}}
    <div class="form-group">
        <div class="kt-subheader__breadcrumbs fix-select2">
            <a href="{{route('service')}}" class="kt-subheader__breadcrumbs-link">
                {{__('Dịch vụ')}}                        </a>
            <span class="kt-subheader__breadcrumbs-separator "></span>
            <select class="service-select " onchange="service.change()" id="service_category">
                <?php $tmp = 0; ?>
                    <option value="all" selected>{{__('Tất cả')}}</option>
                @foreach($service['service_category'] as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
            <div class="kt-grid__item">
                <div id="list-service">

                </div>
            </div>
        </div>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/booking-template/css/custom.css?v='.time())}}">
@endsection
@section('after_script')
    <script>
        var display = '{{$display}}';
        $('#service_category').select2({
            minimumResultsForSearch: -1,
            allowClear: false
        });
    </script>
    <script src="{{asset('static/booking-template/js/booking/service.js?v='.time())}}" type="text/javascript"></script>
    @if (isset($service_category_id))
        <script>
            $('#service_category').val({{ $service_category_id }});
            $('#service_category').trigger('change');
        </script>
    @endif
@stop