@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ REFRRAL')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .nav-item:hover {
            background-color: #4fc4cb;
            transition: 1s;

        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
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
                        {{__('QUẢN LÝ REFERRAL')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
{{--            <ul class="nav nav-pills nav-fill" role="tablist" style="margin-bottom: -8px">--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link " href="#m_tabs_5_1" style="font-weight: bold;;background-color: #4fc4cb;color: white;border-radius: 0px">Danh sách người giới thiệu</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.referrerCommission')}}" style="font-weight: bold">Hoa hồng cho người giới thiệu</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.policyCommission')}}"--}}
{{--                       style="font-weight: bold">Chính sách--}}
{{--                        hoa hồng</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.payment')}}" style="font-weight: bold">Thanh toán</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.multiLevelConfig')}}" style="font-weight: bold">Cấu--}}
{{--                        hình nhiều cấp</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.generalConfig')}}" style="font-weight: bold">Cấu hình--}}
{{--                        chung</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--            <hr style="    margin-top: 6px;margin-bottom: 10px;border: 0;border-top: 2px solid #4fc4cb">--}}
            @include('referral::layouts.tab-header')
            <div class="m-portlet__body" style="padding: 0px;padding-top: 12px;">
                <form class="frmFilter ss--background">
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control" name="search_keyword"
                                           placeholder="{{__('Nhập mã hoặc tên, sđt người giới thiệu')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <select class="form-control select2">
                                        <option value="">Loại</option>
                                        <option value="1">Tất cả</option>
                                        <option value="2">Khách hàng</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <select class="form-control select2">
                                        <option value="">Trạng thái</option>
                                        <option value="1">Tất cả</option>
                                        <option value="2">Hoạt động</option>
                                        <option value="3">Không hoạt động</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control m-input daterange-picker"
                                                   style="background-color: #fff"
                                                   id="created_at"
                                                   name="created_at"
                                                   autocomplete="off" placeholder="@lang('Ngày tham gia')">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                        <div class="text-right">
                            <a href="{{route('referral.listReferrer')}}"
                               class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                {{ __('XÓA BỘ LỌC') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </a>

                            <button href="javascript:void(0)" onclick="product.search()"
                                    class="btn ss--btn-search">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="table-content">
                @include('referral::ListReferrer.referrerTable')
            </div><!-- end table-content -->
        </div>
    </div>
    @include('admin::product.modal.excel-image')
@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/referral/add.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        $('.select2').select2();
    </script>
    <script >
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

            $("#created_at").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            });
            $("#closing_date").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            });
            $("#closing_due_date").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            });
        });
    </script>
@stop
