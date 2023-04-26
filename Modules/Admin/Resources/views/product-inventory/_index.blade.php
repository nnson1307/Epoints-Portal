@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('before_style')
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        .select2-selection__rendered {
            line-height: 10px !important;
        }

        .select2-selection {
            height: 14px !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" class="search-warehouse" value="">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head head_s">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 style=" white-space:nowrap;color: #02898f;"
                                class="ss--title m--font-bold m-portlet__head-text">
                                <i class="la la-th-list ss--icon-title m--margin-right-5"></i>{{__('QUẢN LÝ KHO')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand  m-tabs-line--right m-tabs-line-danger"
                            role="tablist">
                            <li class="nav-item ss--m-tabs__item">
                                <a class="nav-link m-tabs__linkcus active" data-toggle="tab" href="#1" role="tab">
                                    <span>{{__('TỒN KHO')}}</span>
                                </a>
                            </li>
                            <li class="nav-item ss--m-tabs__item">
                                <a onclick="GetList.getListInventoryInput()" class="nav-link m-tabs__linkcus"
                                   data-toggle="tab" href="#input-inventory" role="tab">
                                    {{__('NHẬP KHO')}}
                                </a>
                            </li>
                            <li class="nav-item ss--m-tabs__item">
                                <a onclick="GetList.getListInventoryOutput()" class="nav-link m-tabs__linkcus"
                                   data-toggle="tab" href="#output-inventory" role="tab">
                                    {{__('XUẤT KHO')}}
                                </a>
                            </li>
                            <li class="nav-item ss--m-tabs__item">
                                <a onclick="GetList.getListInventoryTransfer()" class="nav-link m-tabs__linkcus"
                                   data-toggle="tab" href="#inventory-transfer" role="tab">
                                    {{__('CHUYỂN KHO')}}
                                </a>
                            </li>
                            <li class="nav-item ss--m-tabs__item">
                                <a onclick="GetList.getListInventoryChecking()" class="nav-link m-tabs__linkcus"
                                   data-toggle="tab" href="#inventory-checking" role="tab">
                                    {{__('KIỂM KHO')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="ss--m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="1" role="tabpanel">
                            <div class="ss--background">
                                <div class="row ss--bao-filter">
                                    <div class="col-lg-3 form-group">
                                        <div class="m-form__group">
                                            <div class="input-group">
                                                <input type="hidden" name="search_type" value="group_name">
                                                <button id="search" class="btn btn-primary btn-search"
                                                        style="display: none"></button>
                                                <input type="text" class="form-control m--margin-right-15"
                                                       id="search-keyword"
                                                       placeholder="{{__('Nhập tên hoặc mã sản phẩm')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group m-form__group">
                                            <button href="javascript:void(0)" onclick="filterProduct()"
                                                    class="btn ss--btn-search">
                                                {{__('TÌM KIẾM')}}
                                                <i class="fa fa-search ss--icon-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group list-product-inventory m--margin-top-30">
                                @include('admin::product-inventory.list-product-inventory')
                            </div>
                        </div>
                        <div class="tab-pane " id="input-inventory" role="tabpanel">

                        </div>
                        <div class="tab-pane " id="output-inventory" role="tabpanel">

                        </div>
                        <div class="tab-pane " id="inventory-transfer" role="tabpanel">

                        </div>
                        <div class="tab-pane " id="inventory-checking" role="tabpanel">

                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->

        </div>
    </div>
    <div class="modal fade show" id="history" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-history" role="document">

        </div>
    </div>
    <input type="hidden" value="" id="keyword-hidden">
@endsection
{{--@section("after_style")--}}
{{--<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">--}}
{{--@endsection--}}
@section('after_script')
    <!--begin::Page Scripts -->
    {{--    <script src="{{asset('static/backend/js/admin/product-inventory/horizontal.js')}}" type="text/javascript"></script>--}}
    <!--end::Page Scripts -->
    <script src="{{asset('static/backend/js/admin/product-inventory/list.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product-inventory/filter.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product-inventory/tab.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product-inventory/tableHeadFixer.js?v='.time())}}"
            type="text/javascript"></script>
@endsection