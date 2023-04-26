@extends('bookingweb::layout')

@section('content')
    <form action="{{ route('product') }}" method="POST" id="frm-detail">
        {{ csrf_field() }}
        <input type="hidden" name="product_category_id" value="{{$productDetail['product_category_id']}}">
        <div class="form-group">
            <div class="kt-subheader__breadcrumbs">
                <a href="{{route('product')}}" class="kt-subheader__breadcrumbs-link">
                    {{__('Sản phẩm')}}                        </a>
                <span class="kt-subheader__breadcrumbs-separator "></span>
                <a href="javascript:void(0);" class="kt-subheader__breadcrumbs-link" onclick="product.redirectToIndex()">
                    {{$productDetail['category_name']}}                      </a>
                <span class="kt-subheader__breadcrumbs-separator "></span>
                <a href="javascript:void(0);" class="kt-subheader__breadcrumbs-link">
                    {{$productDetail['product_name']}}                      </a>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-12 col-sm-6">
                    @if($productDetail['avatar'] != null)
                        <img src="/{{$productDetail['avatar']}}" style="width: 100%">
                    @else
                        <img src="{{ asset('static/booking-template/image/default-placeholder.png')}}"  style="width: 100%">
                    @endif
                </div>
                <div class="col-12 col-sm-6">
                    <p>{{__('Tên sản phẩm')}} : {{$productDetail['product_name']}}</p>
                    <p>{{__('Giá')}} : {{$productDetail['price_standard']}}</p>
                    <p>{{__('Nhóm sản phẩm')}} : {{$productDetail['category_name']}}</p>
                </div>
                <div class="col-12 padd-top">
                    <div>
                        <h3>{{__('Chi tiết')}}</h3>
                        {!! $productDetail['description'] !!}
                    </div>
                </div>
            </div>
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
    <script src="{{asset('static/booking-template/js/booking/product.js?v='.time())}}" type="text/javascript"></script>
@stop