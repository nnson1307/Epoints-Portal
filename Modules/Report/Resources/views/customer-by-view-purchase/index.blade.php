@extends('layout')
@section('title_header')
    <span class="title_header">
        {{__('BÁO CÁO')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <form action="{{route('report.customer-by-view-purchase.export')}}" method="POST">
            {{ csrf_field() }}
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="la la-th-list"></i>
                     </span>
                        <h2 class="m-portlet__head-text">
                            @lang('BÁO CÁO KHÁCH HÀNG THEO LƯỢT MUA LƯỢT XEM')
                        </h2>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    @if(in_array('report.customer-by-view-purchase.export',session('routeList')))
                        <button type="submit"
                                class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('Export')}}
                                            </span>
                                        </span>
                        </button>
                    @endif
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
                        <select class="form-control" id="product_category" name="product_category">
                            @foreach($optionProductCategory as $value)
                                <option value="{{$value['product_category_id']}}">{{$value['category_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-3 ss--col-xl-4 ss--col-lg-12 form-group">
                        <select class="form-control" id="type" name="type">
                            <option value="most_order">@lang('Lượt mua')</option>
                            <option value="most_view">@lang('Lượt xem')</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div id="container" style="min-width: 280px;" class="load_ajax"></div>
                </div>
            </div>
        </form>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/report/customer-by-view-purchase/script.js')}}"
            type="text/javascript">
    </script>
    <script>
        reportCustomer._init();
    </script>
@stop


