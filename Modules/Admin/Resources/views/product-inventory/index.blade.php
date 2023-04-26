@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
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
        <div class="col-xl-12">
            <input type="hidden" class="search-warehouse" value="">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                             <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                {{__('QUẢN LÝ KHO')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--right m-tabs-line-danger fix-tab-inventory"
                            role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#product-ivtr"
                                   role="tab" aria-selected="false">
                                    {{__('TỒN KHO')}}
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                @if(in_array('admin.product-inventory.list-inventory-input',session('routeList')))
                                    <a onclick="GetList.getListInventoryInput()" class="nav-link m-tabs__link"
                                       data-toggle="tab" href="#input-inventory" role="tab" aria-selected="false">
                                        {{__('NHẬP KHO')}}
                                    </a>
                                @else
                                    <a class="nav-link m-tabs__link"
                                       data-toggle="tab" href="#input-inventory" role="tab" aria-selected="false">
                                        {{__('NHẬP KHO')}}
                                    </a>
                                @endif
                            </li>
                            <li class="nav-item m-tabs__item">
                                @if(in_array('admin.product-inventory.list-inventory-input',session('routeList')))
                                    <a onclick="GetList.getListInventoryOutput()" class="nav-link m-tabs__link"
                                       data-toggle="tab" href="#output-inventory" role="tab" aria-selected="true">
                                        {{__('XUẤT KHO')}}
                                    </a>
                                @else
                                    <a class="nav-link m-tabs__link"
                                       data-toggle="tab" href="#output-inventory" role="tab" aria-selected="true">
                                        {{__('XUẤT KHO')}}
                                    </a>
                                @endif
                            </li>
                            <li class="nav-item m-tabs__item">
                                @if(in_array('admin.product-inventory.list-inventory-transfer',session('routeList')))
                                    <a onclick="GetList.getListInventoryTransfer()" class="nav-link m-tabs__link"
                                       data-toggle="tab" href="#inventory-transfer" role="tab" aria-selected="true">
                                        {{__('CHUYỂN KHO')}}
                                    </a>
                                @else
                                    <a class="nav-link m-tabs__link"
                                       data-toggle="tab" href="#inventory-transfer" role="tab" aria-selected="true">
                                        {{__('CHUYỂN KHO')}}
                                    </a>
                                @endif
                            </li>
                            <li class="nav-item m-tabs__item">
                                @if(in_array('admin.product-inventory.list-inventory-checking',session('routeList')))
                                    <a class="nav-link m-tabs__link" onclick="GetList.getListInventoryChecking()"
                                       data-toggle="tab" href="#inventory-checking" role="tab" aria-selected="true">
                                        {{__('KIỂM KHO')}}
                                    </a>
                                @else
                                    <a class="nav-link m-tabs__link"
                                       data-toggle="tab" href="#inventory-checking"
                                       role="tab" aria-selected="true">
                                        {{__('KIỂM KHO')}}
                                    </a>
                                @endif
                            </li>
                            <li class="nav-item m-tabs__item">
                                @if(in_array('admin.product-inventory.list-inventory-checking',session('routeList')))
                                    <a class="nav-link m-tabs__link" onclick="GetList.getInventoryConfig()"
                                       data-toggle="tab" href="#inventory-config" role="tab" aria-selected="true">
                                        {{__('CẤU HÌNH')}}
                                    </a>
                                @else
                                    <a class="nav-link m-tabs__link"
                                       data-toggle="tab" href="#inventory-config"
                                       role="tab" aria-selected="true">
                                        {{__('CẤU HÌNH')}}
                                    </a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body padding-5p2rem">
                    <div class="tab-content">
                        <div class="tab-pane active" id="product-ivtr" role="tabpanel">
                            <div class="ss--background">
                                <div class="row ss--bao-filter">
                                    <div class="col-lg-3 form-group">
                                        <div class="m-form__group">
                                            <div class="input-group">
                                                <input type="hidden" name="search_type" value="group_name">
                                                <button id="search" class="btn btn-primary btn-search"
                                                        style="display: none"></button>
                                                <input type="text" class="form-control"
                                                       id="search-keyword"
                                                       onkeyup="GetList.listProductInventory()"
                                                       placeholder="{{__('Nhập tên hoặc mã sản phẩm')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group m-form__group">
                                            <button href="javascript:void(0)"
                                                    onclick="GetList.listProductInventory()"
                                                    class="btn ss--btn-search">
                                                {{__('TÌM KIẾM')}}
                                                <i class="fa fa-search ss--icon-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group m-form__group">
                                            <form method="POST" action="{{route('admin.product-inventory.export-excel')}}">
                                                {{ csrf_field() }}
                                                <input type="hidden" id="hidden-keyword" name="keyword">
                                                <button type="submit"
                                                        class="btn ss--btn-search">
                                                    {{__('XUẤT FILE')}}
                                                    <i class="fa fa-file-download ss--icon-search"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group list-product-inventory m--margin-top-30">
{{--                                @include('admin::product-inventory.list-product-inventory')--}}
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
                        <div class="tab-pane " id="inventory-config" role="tabpanel">

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
    <div id="showPopup"></div>
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