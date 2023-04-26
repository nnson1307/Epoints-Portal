@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> @lang('QUẢN LÝ KHÁCH HÀNG')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("LỊCH SỬ THAY ĐỔI")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="row padding_row">
                        <div class="col-lg-4 form-group">
                            <input type="text" class="form-control" name="search"
                                   placeholder="@lang("Nhập tên người thay đổi")">

                            <input type="hidden" class="form-control" name="object_id" value="{{$id}}">
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input readonly class="form-control m-input daterange-picker"
                                       style="background-color: #fff"
                                       id="created_at"
                                       name="created_at"
                                       autocomplete="off" placeholder="@lang('NGÀY THAY ĐỔI')">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                            <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button class="btn btn-primary color_button btn-search">
                                @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
{{--                    @include('customer-lead::customer-deal.list')--}}
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/admin/customer-log/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        customerLog._init();
    </script>
@stop