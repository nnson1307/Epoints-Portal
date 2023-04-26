@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
@endsection
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

    </style>

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"></i> {{__('DANH SÁCH KHO')}}</span>
                    </h2>
                    <h3 class="m-portlet__head-text">

                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
{{--                @if(in_array('admin.warehouse.create-store-ghn',session('routeList')))--}}
                    <a href="javascript:void(0)"
                       onclick="warehouse.createStore()"
                       class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc mr-3">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('Tạo cửa hàng GHN')}}</span>
                        </span>
                    </a>
{{--                @endif--}}
                @if(in_array('admin.warehouse.submitAdd',session('routeList')))
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#add"
                       class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM NHÀ KHO')}}</span>
                        </span>
                    </a>
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#add"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                        color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                {{--<input type="hidden" name="search_type" value="supplier_name">--}}
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{__('Nhập tên kho')}}">
                                {{--<div class="input-group-append">--}}
                                {{--<a href="javascript:void(0)" onclick="warehouse.refresh()"--}}
                                {{--class="btn btn-primary m-btn--icon">--}}
                                {{--<i class="la la-refresh"></i>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary btn-search color_button">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
                @include('helpers.filter')

            </form>


            @include('admin::warehouse.add')
            @include('admin::warehouse.edit')
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>{{__('Success')}} : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content m--padding-top-15">
                @include('admin::warehouse.list')
            </div><!-- end table-content -->

        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/warehouse/script.js?v='.time())}}" type="text/javascript"></script>

@stop
