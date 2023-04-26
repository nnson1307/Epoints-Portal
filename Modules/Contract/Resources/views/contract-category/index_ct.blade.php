@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('CẤU HÌNH HỢP ĐỒNG')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="fas fa-cog"></i> {{__('DANH SÁCH LOẠI HỢP ĐỒNG')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('contract.contract-category.create', session()->get('routeList')))
                    <a href="{{route('contract.contract-category.create')}}"
                       class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> {{__('THÊM LOẠI HỢP ĐỒNG')}}</span>
                            </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-12 form-group row">
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="{{__('Nhập tên loại hợp đồng')}}">
                                </div>
                            </div>
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
                        <div class="col-lg-3 form-group">
                            <select class="form-control m-input select2" name="is_actived">
                                <option value="" selected="selected">{{__('Chọn trạng thái')}}</option>
                                <option value="1">{{__('Đang hoạt động')}}</option>
                                <option value="0">{{__('Ngưng hoạt động')}}</option>
                            </select>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button class="btn btn-primary btn-search color_button">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-content m--padding-top-15">
                @include('contract::contract-category.list')
            </div><!-- end table-content -->
        </div>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>
        $('.select2').select2();
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/contract-category/script.js')}}"
            type="text/javascript"></script>
@stop
