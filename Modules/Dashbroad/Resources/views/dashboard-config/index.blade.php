@extends('layout')
@section('title_header')
    <span class="title_header">@lang('DANH SÁCH CẤU HÌNH BỐ CỤC TỔNG QUAN')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("DANH SÁCH CẤU HÌNH BỐ CỤC TỔNG QUAN")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--@if(in_array('contract.contract.create',session('routeList')))--}}
                    <a href="{{route('dashbroad.dashboard-config.create')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM CẤU HÌNH')</span>
                                    </span>
                    </a>
                {{--@endif--}}
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">

                <form class="frmFilter bg">
                    <div class="row padding_row">
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="{{__('Nhập tên bố cục')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select class="form-control m-input select" name="is_actived">
                                <option value="" selected="selected">{{__('Chọn trạng thái')}}</option>
                                <option value="1">{{__('Đang hoạt động')}}</option>
                                <option value="0">{{__('Vô hiệu hoá')}}</option>
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text"
                                       class="form-control m-input daterange-picker" id="created_at"
                                       name="created_at"
                                       autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button class="btn btn-primary btn-search color_button">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                    {{--                @include('helpers.filter')--}}
                </form>
                <div class="table-content m--padding-top-30">
                    @include('dashbroad::dashboard-config.list')
                </div>
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
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/dashbroad/dashboard-config/script.js?v='.time())}}" type="text/javascript"></script>
@stop