@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <style>
        .form-control-feedback {
            color: red;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('DANH SÁCH NHÀ CUNG CẤP')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.supplier.add',session('routeList')))
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modalAdd"
                       onclick="Supplier.clearAdd()"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM NHÀ CUNG CẤP')}}</span>
                        </span>
                    </a>
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modalAdd"
                       onclick="Supplier.clearAdd()"
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
                <div class="row ss--bao-filter">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="supplier_name">
                                <button class="btn btn-primary btn-search" style="display: none">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên nhà cung cấp')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <button onclick="Supplier.search()"
                                    class="btn ss--btn-search m-btn--icon">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--margin-top-30">
                @include('admin::supplier.list')
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::supplier.add')
        </div>
    </div>
    <div class="modal fade" id="modalEditSupplier" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::supplier.edit')
        </div>
    </div>
@stop
@section('after_script')
    <script type="text/javascript">
        $(function () {
            $('#datetimepicker1').timepicker({
                timeFormat: 'HH:mm:ss',
                minTime: '11:45:00', // 11:45:00 AM,
                maxHour: 20,
                maxMinutes: 30,
                startTime: new Date(0, 0, 0, 15, 0, 0), // 3:00:00 PM - noon
                interval: 15 // 15 minutes
            });
        });

    </script>
    <script src="{{asset('static/backend/js/admin/supplier/list.js?v='.time())}}" type="text/javascript"></script>
@stop
