@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('THANH ĐIỀU HƯỚNG')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }
    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THANH ĐIỀU HƯỚNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)"
                   onclick="listMenu.popupAdd()"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                                <span>
                                    <i class="fa fa-plus-circle"></i>
                                    <span> {{__('THÊM')}}</span>
                                </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="table-content">
                @include('admin::menu-horizontal.list')
            </div><!-- end table-content -->
        </div>
    </div>
    <div id="my-modal"></div>
@endsection

@section('after_script')
    <script src="{{asset('static/backend/js/admin/menu-horizontal/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        listMenu._init();
    </script>
@stop