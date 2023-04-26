@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ ĐƠN HÀNG')}}</span>
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
                        {{__('DANH SÁCH ĐƠN HÀNG ONLINE')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <form action="{{route('admin.order-app.exportList')}}" method="post">
                    {{ csrf_field() }}
                    <button type="submit"
                            class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-10">
                    <span>
                        <i class="la la-files-o"></i>
                        <span> {{__('EXPORT')}}</span>
                    </span>
                    </button>
                </form>
{{--                <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-sync"--}}
{{--                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">--}}
{{--                    <span>--}}
{{--                        <i class="la la-gear"></i>--}}
{{--                        <span> @lang('ĐỒNG BỘ ĐƠN HÀNG')</span>--}}
{{--                    </span>--}}
{{--                </a>--}}
                {{--                @if(in_array('admin.order.add',session('routeList')))--}}
                <a href="{{route('admin.order-app.create')}}"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{__('THÊM ĐƠN HÀNG')}}</span>
                    </span>
                </a>
                <a href="{{route('admin.order-app.create')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
                   style="display: none">
                    <i class="fa fa-plus-circle" style="color: #fff"></i>
                </a>
                {{--                @endif--}}
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="row padding_row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{__('Nhập tên khách hàng hoặc mã đơn hàng')}}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <select name="receive_at_counter" class="form-control m-input m_selectpicker">
                                    <option value="">{{__('Chọn cách thức nhận hàng')}}</option>
                                    <option value="1">{{__('TNhận hàng tại quầy')}}</option>
                                    <option value="0">{{__('Địa chỉ khách hàng')}}</option>
                                </select>
                            </div>
                        </div>
                        @foreach ($FILTER as $name => $item)
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
                </form>


                <div class="table-content m--padding-top-30">
                    @include('admin::order-app.list')
            
                </div><!-- end table-content -->

            </div>
        </div>
    </div>
    {{--    @include('admin::orders.detail')--}}
    <form id="form-order-ss" target="_blank" action="{{route('admin.order.print-bill-not-receipt')}}" method="GET">
        <input type="hidden" name="ptintorderid" id="orderiddd" value="">
    </form>
    <div id="my-modal"></div>
    @include('admin::order-app.pop-sync-order')
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/toastr/build/toastr.css" rel="stylesheet"
          type="text/css"/>
@stop
@section('after_script')
    <script type="text/template" id="order-detail-tpl">
        <tr>
            <td>{stt}</td>
            <td>{object_name}</td>
            <td>{price}</td>
            <td>{quantity}</td>
            <td>{discount}</td>
            <td>{voucher}</td>
            <td>{amount}</td>
        </tr>
    </script>
    <script type="text/template" id="receipt-detail-tpl">
        <tr>
            <td>{type}</td>
            <td>{code}</td>
            <td>{amount}</td>
        </tr>
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/order-app/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/print-bill.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/cancel.js?v='.time())}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script>
        $(".m_selectpicker").selectpicker();
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json['Hôm nay']] = [moment(), moment()],
                arrRange[json['Hôm qua']] = [moment().subtract(1, "days"), moment().subtract(1, "days")],
                arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()],
                arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()],
                arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")],
                arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]

            $("#created_at").daterangepicker({
                autoUpdateInput: true,
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
                    "customRangeLabel": json["Tùy chọn ngày"],
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

            $("#created_at").val("");
        });
    </script>

    @if(session('error_receipt'))
        <script>
            $.getJSON(laroute.route('translate'), function (json) {
                setTimeout(function () {
                    toastr.error("Đơn hàng đã có phiếu giao hàng đang giao hoặc hoàn thành rồi")
                }, 60);
            });
        </script>
    @endif
@stop