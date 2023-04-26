@extends('layout')
@section('title_header')
    <span class="title_header">
        {{__('BÁO CÁO')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="la la-th-list"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('BÁO CÁO DANH MỤC SẢN PHẨM')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <div class="m-portlet__body">
            <div class="form-group row">
                <div class="col-xl-3 ss--col-xl-4 ss--col-lg-12 form-group">
                    <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                        <input readonly="" class="form-control m-input daterange-picker"
                               id="time" name="time" autocomplete="off"
                               placeholder="{{__('Từ ngày - đến ngày')}}">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
                <div class="col-xl-3 ss--col-xl-4 ss--col-lg-12 form-group">
                    <select class="form-control" id="type">
                        <option value="most_order">@lang('Được mua nhiều nhất')</option>
                        <option value="most_view">@lang('Được xem nhiều nhất')</option>
                    </select>
                </div>
                <div class="col-xl-3 ss--col-xl-4 ss--col-lg-12 form-group">
                    <select class="form-control" id="product_category_id">
                        <option value="">@lang('Chọn danh mục sản phẩm')</option>
                        @foreach($productCategory as $item)
                            <option value="{{$item['product_category_id']}}">{{$item['category_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div id="container" class="load_ajax" style="min-width: 280px;"></div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/report/highcharts.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/report/product-category/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        reportProductCategory._init();
    </script>
@stop


