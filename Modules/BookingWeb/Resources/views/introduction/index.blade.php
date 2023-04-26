@extends('bookingweb::layout')

@section('content')
    {{session('service_category_id')}}
    <div class="form-group">
        <div class="kt-subheader__breadcrumbs fix-select2">
            <a href="javascript:void(0)" class="kt-subheader__breadcrumbs-link">
                {{__('Giới thiệu')}}                     </a>
        </div>
    </div>
    <div class="form-group">
        <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
            <div class="kt-grid__item">
                {!! $introduction['introduction'] !!}
            </div>
        </div>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/booking-template/css/custom.css?v='.time())}}">
@endsection
@section('after_script')
{{--    <script src="{{asset('static/booking-template/js/booking/product.js?v='.time())}}" type="text/javascript"></script>--}}
@stop