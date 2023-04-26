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
                        <span><i class="la la-th-list"></i> {{__('DANH SÁCH THUẾ VAT')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('contract.vat.show-pop-create', session()->get('routeList')))
                    <a href="javascript:void(0)" onclick="viewVat.showPopCreate()"
                       class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> {{__('THÊM VAT')}}</span>
                            </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="table-content m--padding-top-15">
                @include('contract::vat.list')
            </div><!-- end table-content -->
        </div>
    </div>

    <div id="my-modal"></div>
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
    <script src="{{asset('static/backend/js/contract/vat/script.js?v='.time())}}" type="text/javascript"></script>
@stop
