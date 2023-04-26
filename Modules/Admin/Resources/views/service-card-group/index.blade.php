@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> {{__('THẺ DỊCH VỤ')}}</span>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <style>
        .form-control-feedback {
            color: red;
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
                        {{__('DANH SÁCH NHÓM THẺ DỊCH VỤ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.order-source.add',session('routeList')))
                    <button
                            data-toggle="modal"
                            data-target="#modalAdd"
                            onclick="serviceCardGroup.clearAdd()"
                            class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM NHÓM THẺ DỊCH VỤ')}}</span>
                        </span>
                    </button>
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modalAdd"
                       onclick="serviceCardGroup.clearAdd()"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                        color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <form class="frmFilter ss--background">
                <div class="row ss--bao-filter">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="name">
                                <button class="btn btn-primary btn-search" style="display: none">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên nhóm thẻ dịch vụ')}}">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 m--margin-bottom-10">
                        <button onclick="OrderSource.search()"
                                class="btn ss--btn-search">
                            {{__('TÌM KIẾM')}}
                            <i class="fa fa-search ss--icon-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!--end: Search Form -->
            <div class="table-content m--margin-top-30">
                @include('admin::service-card-group.list')
            </div>
        </div>
    </div>
    <!--end::Portlet-->
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::service-card-group.add')
        </div>
    </div>
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::service-card-group.edit')
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card-group/script.js?v='.time())}}" type="text/javascript"></script>
@stop
