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
        .bg{
            white-space: normal;
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
                        {{__('DANH SÁCH TẤT CẢ ĐƠN HÀNG')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <form class="form-export-order-all" action="{{route('admin.order-all.exportList')}}" method="get">
                    {{ csrf_field() }}
                    <button type="submit"
                            class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-10">
                    <span>
                        <i class="la la-files-o"></i>
                        <span> {{__('EXPORT')}}</span>
                    </span>
                    </button>
                </form>
                @if(in_array('admin.order.add',session('routeList')) || in_array('admin.order-app.create',session('routeList')))
                    <div class="form-group" style="    width: 320px;">
                        <select class="form-control m-input m_selectpicker_add_order" id="order_source_id_for_add"
                                name="order_source_id_for_add" style="width: 220px">
                            <option value="">{{__('Thêm đơn hàng')}}</option>
                            @if(!empty($orderSource))
                                @foreach($orderSource as $key => $value)
                                    <option value="{{$value['order_source_id']}}">
                                        @if($value['order_source_id'] == 1 )
                                            {{__('Đơn hàng trực tiếp')}}
                                        @elseif($value['order_source_id'] == 2)
                                            {{__('Đơn hàng online')}}
                                        @elseif($value['order_source_id'] == 3)
                                            {{__('Đơn hàng FNB')}}
                                        @endif
                                    </option>
                                @endforeach
                            @endif
                            <option value="orderFromAppointment">{{__('Đơn hàng từ lịch hẹn')}}</option>
                            <option value="orderFromDeal">{{__('Đơn hàng từ deal')}}</option>
                        </select>
                    </div>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg" id="order_all_filter">
                    <div class="padding_row">
                        <div class="m-form m-form--label-align-right">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="search"
                                               placeholder="{{__('Nhập tên khách hàng hoặc mã đơn hàng')}}">
                                    </div>
                                </div>
                                @foreach ($FILTER as $name => $item)
                                    <div class="form-group col-lg-3">
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
                                <div class="form-group col-lg-3">
                                    <select class="form-control m-input m_selectpicker" name="order_source_id">
                                        <option value="">{{__('Chọn nguồn đơn hàng')}}</option>
                                        @if(!empty($orderSource))
                                            @foreach($orderSource as $key => $value)
                                                <option value="{{$value['order_source_id']}}">{{$value['order_source_name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-lg-3">
                                    <select class="form-control m-input m_selectpicker" name="receive_at_counter">
                                        <option value="">{{__('Chọn cách thức nhận hàng')}}</option>
                                        <option value="1">{{__('Nhận hàng tại quầy')}}</option>
                                        <option value="0">{{__('Địa chỉ khách hàng')}}</option>
                                    </select>
                                </div>
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
                                <div class="form-group col-lg-6">

                                    <button class="btn btn-refresh btn-primary color_button m-btn--icon mr-3">
                                        {{ __('XÓA BỘ LỌC') }}
                                        <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-primary color_button btn-search">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible">
                            <strong>Success : </strong> {!! session('status') !!}.
                        </div>
                    @endif
                </form>
                <div class="table-content m--padding-top-30">
                    @include('admin::orders-all.list')
                </div><!-- end table-content -->
            </div>
        </div>
    </div>

    <form id="form-order-ss" target="_blank" action="{{route('admin.order.print-bill-not-receipt')}}" method="GET">
        <input type="hidden" name="ptintorderid" id="orderiddd" value="">
    </form>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script type="text/template" id="type-receipt-tpl">
        <label>{label}:<span style="color:red;font-weight:400">(*)</span></label>
        <div class="input-group m-input-group">
            <input class="form-control m-input" placeholder="{{__('Nhập giá tiền')}}" aria-describedby="basic-addon1"
                   name="{name_cash}" id="{id_cash}">
            <div class="input-group-append"><span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}</span>
            </div>
        </div>
    </script>
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
    <script>
          $(document).ready(function () {
            var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
            var arrRange = {};
            arrRange[jsonLang["Hôm nay"]] = [moment(), moment()];
            arrRange[jsonLang["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[jsonLang["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[jsonLang["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[jsonLang["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[jsonLang["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

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
                    "customRangeLabel": jsonLang['Tùy chọn ngày'],
                    daysOfWeek: [
                        jsonLang["CN"],
                        jsonLang["T2"],
                        jsonLang["T3"],
                        jsonLang["T4"],
                        jsonLang["T5"],
                        jsonLang["T6"],
                        jsonLang["T7"]
                    ],
                    "monthNames": [
                        jsonLang["Tháng 1 năm"],
                        jsonLang["Tháng 2 năm"],
                        jsonLang["Tháng 3 năm"],
                        jsonLang["Tháng 4 năm"],
                        jsonLang["Tháng 5 năm"],
                        jsonLang["Tháng 6 năm"],
                        jsonLang["Tháng 7 năm"],
                        jsonLang["Tháng 8 năm"],
                        jsonLang["Tháng 9 năm"],
                        jsonLang["Tháng 10 năm"],
                        jsonLang["Tháng 11 năm"],
                        jsonLang["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            });

            $("#created_at").val("");

        })
    </script>
    <script src="{{asset('static/backend/js/admin/order-all/index.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order-all/print-bill.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order-all/cancel.js?v='.time())}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script>
        $(".m_selectpicker").selectpicker().on("change", function(e) {
            $('.btn-search').submit();
        });
        $('.select-fix').select2().on("change", function(e) {
            $('.btn-search').submit();
        });
        $(".m_selectpicker_add_order").selectpicker().on("change", function(e) {
            let order_source_id_for_add =  $('#order_source_id_for_add').val();
            if(order_source_id_for_add != ''){
                if(order_source_id_for_add == 1){
                    window.open("{{route('admin.order.add')}}",'_blank')
                }else if(order_source_id_for_add == 2){
                    window.open("{{route('admin.order-app.create')}}",'_blank')
                }else if(order_source_id_for_add == 3){
                    window.open("{{route('fnb.orders.add-orders')}}",'_blank')
                } else if(order_source_id_for_add == "orderFromAppointment"){
                    window.open("{{route('admin.customer_appointment.list-day')}}",'_blank')
                }else if(order_source_id_for_add == "orderFromDeal"){
                    window.open("{{route('customer-lead.customer-deal')}}",'_blank')
                }
            }
        });
    </script>
@stop
