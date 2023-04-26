@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ CÔNG NỢ')}}</span>
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
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH CÔNG NỢ')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="name">
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên khách hàng hoặc mã đơn hàng')}}">
                                {{--<div class="input-group-append">--}}
                                {{--<a href="javascript:void(0)" onclick="unit.refresh()"--}}
                                {{--class="btn btn-primary m-btn--icon">--}}
                                {{--<i class="la la-refresh"></i>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
{{--                    <div class="col-lg-2 form-group">--}}
{{--                        <button class="btn btn-primary color_button btn-search">--}}
{{--                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
                </div>
                <div class="padding_row row">
                    <div class="col-lg-12">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 4 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-3 form-group input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                        <span class="input-group-text">
                            {{ $item['text'] }}
                        </span>
                                    </div>
                                @endif
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                            </div>
                            @endforeach
                            <div class="col-lg-3 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly class="form-control m-input daterange-picker"
                                           style="background-color: #fff"
                                           id="created_at"
                                           name="created_at"
                                           autocomplete="off" placeholder="{{__('Ngày tạo')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-2 form-group">
                                <button class="btn btn-primary color_button btn-search" style="display: block">
                                    {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                @include('admin::receipt.list')
            </div><!-- end table-content -->

        </div>
    </div>
    <div id="div-detail"></div>
    <div id="div-receipt"></div>
    <form id="bill-receipt" target="_blank" action="{{route('admin.receipt.print-bill')}}" method="GET">
        <input type="hidden" id="amount_bill" name="amount_bill">
        <input type="hidden" id="customer_debt_id" name="customer_debt_id">
        <input type="hidden" id="receipt_id" name="receipt_id">
        <input type="hidden" id="amount_return_bill" name="amount_return_bill">
    </form>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/admin/receipt/script.js?v='.time())}}" type="text/javascript"></script>
    <script type="text/template" id="type-receipt-tpl">
        <div class="row">
            <label class="col-lg-6 font-15">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group col-lg-6" style="height: 30px;">
                <input onkeyup="indexDebt.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input amount" placeholder="{{__('Nhập giá tiền')}}"
                       aria-describedby="basic-addon1"
                       name="{name_cash}" id="{id_cash}" value="0">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
                </div>
            </div>
        </div>
    </script>
    <script>
        $(".m_selectpicker").selectpicker();

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
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",

                maxDate: moment().endOf("day"),
                startDate: moment().startOf("day"),
                endDate: moment().add(1, 'days'),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    // "applyLabel": "Đồng ý",
                    // "cancelLabel": "Thoát",
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
    <script type="text/template" id="payment_method_tpl">
        <div class="row mt-3 method payment_method_{id}" style="margin-bottom: 2rem">
            <label class="col-lg-4 font-15">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group col-lg-6" style="height: 30px;">
                <input onkeyup="indexDebt.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input" placeholder="{{__('Nhập giá tiền')}}"
                       aria-describedby="basic-addon1"
                       name="payment_method" id="payment_method_{id}" value="0">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
                </div>
            </div>
            <div class="col-lg-2" style="display:{displayQrCode};">
                <button type="button" onclick="indexDebt.genQrCode(this, '{id}')" class="btn btn-primary m-btn m-btn--custom color_button">
                    @lang('TẠO QR')
                </button>
            </div>
        </div>
    </script>
@stop
