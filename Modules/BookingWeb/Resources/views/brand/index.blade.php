@extends('bookingweb::layout')

@section('content')
    {{session('service_category_id')}}
    <div class="form-group">
        <div class="kt-subheader__breadcrumbs">
            <a href="{{route('brand')}}" class="kt-subheader__breadcrumbs-link">
                {{__('Chi Nhánh')}}                      </a>
{{--            <span class="kt-subheader__breadcrumbs-separator "></span>--}}
            <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
        </div>
    </div>
    <div class="form-group">
        <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
            <div class="kt-grid__item">
                <div id="list-brand">

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
    </script>
    <script src="{{asset('static/booking-template/js/booking/brand.js?v='.time())}}" type="text/javascript"></script>
@stop