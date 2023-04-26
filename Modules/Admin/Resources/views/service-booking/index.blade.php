@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-calendar.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ LỊCH HẸN')}}</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH DỊCH VỤ ĐÃ ĐẶT')}}
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
                        <div class="form-group">
                            <input type="text" class="form-control" name="search"
                                   placeholder="{{__('Nhập thông tin tìm kiếm')}}">
                        </div>
                    </div>
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
                @include('admin::service-booking.list')
            </div><!-- end table-content -->

        </div>
    </div>

    <div id="show-modal"></div>
    @if(in_array('admin.customer_appointment.receipt',session('routeList')))
        <input type="hidden" id="role-receipt-appointments" value="1">
    @else
        <input type="hidden" id="role-receipt-appointments" value="0">
    @endif
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service-booking/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service-booking/script.js?v='.time())}}" type="text/javascript"></script>
    <script type="text/template" id="append-status-other-tpl">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info  color_button active" id="new"
                   onclick="customer_appointment.new_click()">
                <input type="radio" name="status" id="option1" value="new"
                       autocomplete="off" checked=""> {{__('MỚI')}}
            </label>
            <label class="btn btn-default" id="confirm"
                   onclick="customer_appointment.confirm_click()">
                <input type="radio" name="status" id="option2" value="confirm"
                       autocomplete="off"> {{__('XÁC NHẬN')}}
            </label>
        </div>
    </script>
    <script type="text/template" id="append-status-live-tpl">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info color_button active" id="wait">
                <input type="radio" name="status" id="option1" value="wait"
                       autocomplete="off" checked=""> {{__('CHỜ PHỤC VỤ')}}
            </label>
        </div>
    </script>
    <script type="text/template" id="table-card-tpl">
        <tr class="tr_quantity tr_card">
            <td>{name}
                <input type="hidden" name="customer_order" id="customer_order_{stt}" value="{stt}">
                <input type="hidden" name="object_type" id="object_type" value="{type}">
            </td>
            <td>
                <select class="form-control service_id" name="service_id" id="service_id_{stt}"
                        style="width: 100%" multiple="multiple">
                    <option></option>
                </select>
            </td>
            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                <select class="form-control staff_id" name="staff_id" id="staff_id_{stt}"
                        title="{{__('Chọn nhân viên phục vụ')}}" style="width: 100%" disabled>
                    <option></option>
                </select>
            </td>
            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                <select class="form-control room_id" name="room_id" id="room_id_{stt}"
                        title="{{__('Chọn phòng')}}" style="width: 100%" disabled>
                    <option></option>
                </select>
            </td>
        </tr>
    </script>
    <script type="text/template" id="to-date-tpl">
        @if($configToDate == 1)
        <div class="form-group m-form__group row">
            <div class="form-group col-lg-6">
                <label class="black-title">{{__('Ngày kết thúc')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group">
                    <div class="m-input-icon m-input-icon--right">
                        <input class="form-control m-input" name="end_date"
                               id="end_date"
                               readonly
                               placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                               value="">
                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                        class="la la-calendar"></i></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group col-lg-6">
                <label class="black-title">{{__('Giờ kết thúc')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group m-input-group">
                    <input id="end_time" name="end_time" class="form-control"
                           placeholder="{{__('Chọn giờ hẹn')}}" readonly>
                </div>
            </div>
        </div>
        @endif
    </script>
    <script type="text/template" id="w-m-y-tpl">
        <div class="form-group m-form__group">
            <label class="black-title">{{__('Số tuần/tháng/năm')}}:<b class="text-danger">*</b></label>
            <input class="form-control" id="type_number" name="type_number" value="1"
                   onchange="customer_appointment.changeNumberTime()">
        </div>
        @if($configToDate == 1)
        <div class="form-group m-form__group row">
            <div class="form-group col-lg-6">
                <label class="black-title">{{__('Ngày kết thúc')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group">
                    <div class="m-input-icon m-input-icon--right">
                        <input class="form-control m-input" name="end_date"
                               id="end_date"
                               readonly
                               placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                               value="">
                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                        class="la la-calendar"></i></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group col-lg-6">
                <label class="black-title">{{__('Giờ kết thúc')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group m-input-group">
                    <input id="end_time" name="end_time" class="form-control"
                           placeholder="{{__('Chọn giờ hẹn')}}" readonly>
                </div>
            </div>
        </div>
        @endif
    </script>
@stop
