@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header">{{__('BÁO CÁO')}}</span>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                {{__('BÁO CÁO TỒN KHO')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        {{--                        @if(in_array('admin.report-service-staff.export-detail', session()->get('routeList')))--}}
                        <form action="{{route('report.product-inventory.export-detail')}}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="created_at" id="created_at_export">
                            <input type="hidden" name="warehouse_id" id="warehouse_id_export">
                            <input type="hidden" name="product_id" id="product_id_export">

                            <button type="submit"
                                    class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                        <span>
                            <i class="la la-files-o"></i>
                            <span>{{__('Export tồn kho')}}</span>
                        </span>
                            </button>
                        </form>
                        {{--                        @endif--}}
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group">
                                <select style="width: 100%" id="warehouse_id" name="warehouse_id" class="form-control">
                                    <option value="">@lang('Tất cả')</option>
                                    @if(count($optionWarehouse) > 0)
                                        @foreach($optionWarehouse as $v)
                                            <option value="{{$v['warehouse_id']}}">{{$v['name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <select style="width: 100%" id="product_id" name="product_id" class="form-control">
                                    {{--<option value="">@lang('Tất cả')</option>--}}
                                    {{--@if(count($optionWarehouse) > 0)--}}
                                        {{--@foreach($optionWarehouse as $v)--}}
                                            {{--<option value="{{$v['warehouse_id']}}">{{$v['name']}}</option>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="autotable">
                        <form class="frmFilter bg">
                            <input type="hidden" name="created_at" id="created_at_filter">
                            <input type="hidden" name="warehouse_id" id="warehouse_id_filter">
                            <input type="hidden" name="product_id" id="product_id_filter">

                            <div class="col-lg-2 form-group" style="display: none;">
                                <button class="btn btn-primary color_button btn-search">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </form>
                        <div class="table-content m--padding-top-30" id="list-detail">

                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/product-inventory/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        productInventory._init();
    </script>
@stop
