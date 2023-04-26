@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-member.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ KHÁCH HÀNG')}}</span>
@endsection
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

        .form-control-feedback {
            color: red;
        }

        .title_header {
            color: #008990;
            font-weight: 400;
        }
    </style>
    <!--begin::Portlet-->
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                   <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{__('DANH SÁCH NHÓM KHÁCH HÀNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('customer-group.add',session('routeList')))
                    <a href="{{route('admin.customer-group-filter.add-group-define')}}"
                            class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM NHÓM KHÁCH HÀNG TỰ ĐỊNH NGHĨA')}}</span>
                        </span>
                    </a>
                    <a href="{{route('admin.customer-group-filter.add-group-define')}}"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                        color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                    <a href="{{route('admin.customer-group-filter.add-customer-group-auto')}}"
                            class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm m--margin-left-5">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM NHÓM KHÁCH HÀNG ĐỘNG')}}</span>
                        </span>
                    </a>
                    <a href="javascript:void(0)"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                        color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background">
                <div class="row padding_row">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="name">
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên nhóm')}}">
                                {{--<div class="input-group-append">--}}
                                {{--<a href="javascript:void(0)" onclick="unit.refresh()"--}}
                                {{--class="btn btn-primary m-btn--icon">--}}
                                {{--<i class="la la-refresh"></i>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <select name="filter_group_type" id="filter_group_type" class="form-control ss--select-2" style="width: 100%;">
                            <option value="">
                                {{__('Chọn loại nhóm')}}
                            </option>
                            <option value="user_define">
                                {{__('Tự định nghĩa')}}
                            </option>
                            <option value="auto">
                                {{__('Tự động')}}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary color_button btn-search" onclick="listUserGroup.search()">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
                <!--begin: Search Form -->
            </form>
            <!--end: Search Form -->
            <div class="table-content m--margin-top-30">
                @include('admin::customer-group-filter.list')
            </div>
        </div>

    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/user-group/script.js')}}"
            type="text/javascript"></script>
@stop
