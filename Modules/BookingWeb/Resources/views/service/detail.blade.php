@extends('bookingweb::layout')

@section('content')
    <form action="{{ route('service') }}" method="POST" id="frm-detail">
        {{ csrf_field() }}
        <input type="hidden" name="service_category_id" value="{{ $serviceDetail['service_category_id'] }}">
        <div class="form-group">
            <div class="kt-subheader__breadcrumbs">
                <a href="{{route('service')}}" class="kt-subheader__breadcrumbs-link">
                    {{__('Dịch vụ')}}                        </a>
                <span class="kt-subheader__breadcrumbs-separator "></span>
                <a href="javascript:void(0);" class="kt-subheader__breadcrumbs-link" onclick="service.redirectToIndex()">
                    {{$serviceDetail['service_category_name']}}                      </a>
                <span class="kt-subheader__breadcrumbs-separator "></span>
                <a href="javascript:void(0);" class="kt-subheader__breadcrumbs-link">
                    {{$serviceDetail['service_name']}}                      </a>
            </div>
        </div>
        <div class="form-group">
            {!! $serviceDetail['detail_description'] !!}
        </div>
    </form>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/booking-template/css/custom.css')}}">
@endsection
@section('after_script')
    <script>
        var display = '{{$display}}';
    </script>
    <script src="{{asset('static/booking-template/js/booking/service.js?v='.time())}}" type="text/javascript"></script>
@stop