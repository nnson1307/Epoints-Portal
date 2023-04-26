@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ ĐƠN VỊ VẬN CHUYỂN')}}</span>
@stop
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-car "></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH ĐƠN VỊ VẬN CHUYỂN')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.transport.submitadd',session('routeList')))
                    <a href="javascript:void(0)"
                       onclick="transport.modal_add()"
                       class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM ĐƠN VỊ VẬN CHUYỂN')}}</span>
                        </span>
                    </a>
                    <a href="javascript:void(0)"
                       onclick="transport.modal_add()"
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
                                <input type="hidden" name="search_type" value="transport_name">
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên đơn vị vận chuyển')}}">
                                {{--<div class="input-group-append">--}}
                                {{--<a href="javascript:void(0)" onclick="transport.refresh()"--}}
                                {{--class="btn btn-primary m-btn--icon">--}}
                                {{--<i class="la la-refresh"></i>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary color_button btn-search">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                    @include('helpers.filter')
                </div>


            </form>


            @include('admin::transport.add')
            @include('admin::transport.edit')
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>Success : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content m--padding-top-30">
                @include('admin::transport.list')
            </div><!-- end table-content -->

        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{asset('static/backend/js/admin/transport/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/transport/jquery.masknumber.js')}}" type="text/javascript"></script>
@stop
