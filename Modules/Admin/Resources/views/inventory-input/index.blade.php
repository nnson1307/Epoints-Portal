@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <form class="frmFilter">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="ss--title m--font-bold m-portlet__head-text">
                            <i class="la la-th-list ss--icon-title m--margin-right-5"></i>
                            {{__('DANH SÁCH PHIẾU NHẬP KHO')}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="javascript:void(0)"
{{--                       onclick="location.href='{{route('admin.inventory-input.add')}}'"--}}
                       onclick="InventoryInput.showPopup()"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('TẠO PHIẾU NHẬP KHO')}}</span>
                        </span>
                    </a>
{{--                    <a  onclick="location.href='{{route('admin.inventory-input.add')}}'" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill--}}
                    <a href="javascript:void(0)"  onclick="InventoryInput.showPopup()" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                color_button btn_add_mobile" style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="ss--background">
                    <div class="row ss--bao-filter2">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="pi_code">
                                    <button id="search" class="btn btn-primary btn-search"
                                            style="display: none"></button>
                                    <input type="text" class="form-control" name="search_keyword"
                                           placeholder="{{__('Nhập mã phiếu')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input class="form-control m-input daterange-picker" id="created_at"
                                       onkeyup="InventoryInput.removeAllInput(this)" name="created_at" autocomplete="off"
                                       placeholder="{{__('Chọn ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                         <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="row m--padding-left-15 m--padding-right-15">
                        <div class="col-lg-10">
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
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input']) !!}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button href="javascript:void(0)" onclick="InventoryInput.search()"
                                    class="btn ss--btn-search pull-right">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-content m--margin-top-30">
                    @include('admin::inventory-input.list')
                </div><!-- end table-content -->
            </div>
        </form>
    </div>
    <input type="hidden" id="totalInput" value="">
    <div id="showPopup"></div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/inventory-input/list.js')}}" type="text/javascript"></script>
    <script>
    $.getJSON(laroute.route('translate'), function (json) {

        //
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
@stop
