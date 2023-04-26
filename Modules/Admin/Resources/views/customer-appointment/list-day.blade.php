@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-calendar.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ LỊCH HẸN')}}</span>
@stop
@section('sub-header')
    <div class="m-subheader ">
        <div class="align-items-center m-box-heade-calendar-timline">
            <div class="mr-auto m-preview--btn">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="m-input-icon  m-input-icon--right">
                                    <input type="text" class="form-control m-input"
                                           placeholder="{{__('Nhập thông tin tìm kiếm')}}" id="search_name"
                                           name="search_name">
                                    <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                    class="la la-search"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="m-input-icon m-input-icon--right date">
                                    <input readonly type="text" id="search" name="search"
                                           class="form-control m-input"
                                           placeholder="{{__('Chọn ngày')}}"
                                           value="{{date("d/m/Y",strtotime($day))}}"
                                           onchange="customer_appointment.search_time(this)">
                                    <span class="m-input-icon__icon m-input-icon__icon--right"><span>
                                         <i class="la la-calendar"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        @if(in_array('admin.customer_appointment',session('routeList')))
                            <a href="{{route('admin.customer_appointment')}}"
                               class="btn btn-outline-primary son m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="la la-calendar"></i>
                                <span>{{__('Xem lịch tháng')}}</span>
                            </span>
                            </a>
                        @endif
                        @if(in_array('admin.customer_appointment.submitModalAdd',session('routeList')))
                            <a href="javascript:void(0)" onclick="customer_appointment.click_modal()"
                               class="btn btn-success m-btn m-btn--icon m-btn--pill">
                            <span>
                                <i class="la la-calendar-plus-o"></i>
                                <span>{{__('Thêm lịch hẹn')}}</span>
                            </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-7">
            <div class="m-portlet  m-portlet--full-height ">
                <div class="m-portlet__body">

                    <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="460"
                         data-mobile-height="300" style="height: 460px; overflow: hidden;">
                        <div id="timeline-list">
                            @include('admin::customer-appointment.inc.timeline-list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="m-portlet m-portlet--head-sm">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon ">
                                <i class="la la-info-circle"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                {{__('THÔNG TIN CHI TIẾT LỊCH HẸN')}}
                            </h3>
                        </div>
                    </div>
                </div>
                <div id="m-info-cus">

                </div>
            </div>

        </div>
    </div>

    {{--    @include('admin::customer-appointment.modal-add')--}}
    {{--    @include('admin::customer-appointment.modal-edit')--}}
    <div id="show-modal"></div>
    <input type="hidden" name="day_hidden" id="day_hidden" value="{{date("d/m/Y",strtotime($day))}}">
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
    <script id="button-tpl" type="text/template">
        <a href="javascript:void(0)"
           onclick="customer_appointment.click_modal_edit('{id}')"
           class="btn btn-danger bold-huy  bte_app class_edit"><i class="la 	la-pencil"></i> {{__('CHỈNH SỬA')}} </a>
        <button onclick="click_detail.save()" type="button"
                class="btn btn-info  color_button son-mb class_save"><i
                    class="la la-check"></i> {{__('XÁC NHẬN LỊCH HẸN')}}
        </button>
    </script>
    <script id="service-detail-tpl" type="text/template">
        <tr class="tr_detail">
            <th>{stt}</th>
            <th>{service_name}</th>
            <th>{service_time} {{__('phút')}}</th>
            <th>
                <input type="hidden" name="appointment_service_id" id="appointment_service_id"
                       value="{appointment_service_id}">
                <input style="text-align: center" class="form-control quantity" type="number" id="quantity"
                       name="quantity" value="{quantity}">
            </th>
            <th>
                <a class='remove m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill'><i
                            class='la la-trash'></i></a>
            </th>
        </tr>
    </script>
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
            <label class="btn btn-default" id="processing"
                   onclick="customer_appointment.processing_click()">
                <input type="radio" name="status" id="option2" value="processing"
                       autocomplete="off"> {{__('ĐANG THỰC HIỆN')}}
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
    <script type="text/template" id="table-quantity-edit-tpl">
        <tr class="tr_quantity">
            <td>{name}
                <input type="hidden" name="customer_order_edit" id="customer_order_edit_{stt}" value="{stt}">
            </td>
            <td>
                <select class="form-control service_id_edit" name="service_id_edit" id="service_id_edit_{stt}"
                        style="width: 70%" multiple="multiple">
                    <option></option>
                </select>
            </td>
            <td>
                <select class="form-control staff_id_edit" name="staff_id_edit" id="staff_id_edit_{stt}"
                        title="{{__('Chọn nhân viên phục vụ')}}" style="width: 100%" disabled>
                    <option></option>
                </select>
            </td>
            <td>
                <select class="form-control room_id_edit" name="room_id_edit" id="room_id_edit_{stt}"
                        title="{{__('Chọn phòng')}}" style="width: 100%" disabled>
                    <option></option>
                </select>
            </td>
        </tr>
    </script>
    <script type="text/template" id="status-appointment-edit-tpl">
        <div class="form-group m-form__group">
            <div class="btn-group btn-group-toggle status_edit" data-toggle="buttons">
                <label class="btn btn-default" id="new_stt"
                       onclick="customer_appointment.status_edit(this)">
                    <input type="radio" name="status" id="option1" value="new"
                           autocomplete="off" checked=""> {{__('MỚI')}}
                </label>
                <label class="btn btn-default" id="confirm_stt"
                       onclick="customer_appointment.status_edit(this)">
                    <input type="radio" name="status" id="option2" value="confirm"
                           autocomplete="off"> {{__('XÁC NHẬN')}}
                </label>
                <label class="btn btn-default" id="wait_stt"
                       onclick="customer_appointment.status_edit(this)">
                    <input type="radio" name="status" id="option2" value="wait"
                           autocomplete="off"> {{__('CHỜ PHỤC VỤ')}}
                </label>
                <label class="btn btn-default" id="cancel_stt"
                       onclick="customer_appointment.status_edit(this)">
                    <input type="radio" name="status" id="option2" value="cancel"
                           autocomplete="off"> {{__('HỦY')}}
                </label>
            </div>
        </div>
    </script>
    <script type="text/template" id="status-direct-edit-tpl">
        <div class="btn-group btn-group-toggle status_edit" data-toggle="buttons">
            <label class="btn btn-default" id="wait_stt"
                   onclick="customer_appointment.status_edit(this)">
                <input type="radio" name="status" id="option1" value="wait"
                       autocomplete="off" checked=""> {{__('CHỜ PHỤC VỤ')}}
            </label>
            <label class="btn btn-default" id="cancel_stt"
                   onclick="customer_appointment.status_edit(this)">
                <input type="radio" name="status" id="option2" value="cancel"
                       autocomplete="off"> {{__('HỦY')}}
            </label>
        </div>
    </script>
    <script type="text/template" id="status-finish-edit-tpl">
        <div class="btn-group btn-group-toggle status_edit" data-toggle="buttons">
            <label class="btn btn-info color_button" id="finish_stt"
                   onclick="customer_appointment.status_edit(this)">
                <input type="radio" name="status" id="option1" value="finish"
                       autocomplete="off" checked=""> {{__('ĐÃ HOÀN THÀNH')}}
            </label>
        </div>

    </script>
    <script type="text/template" id="type-modal-edit-tpl">
        <div class="input-group m-input-group m-input-group--solid">
            <div class="btn-group m-btn-group type" role="group"
                 aria-label="...">
                <button type="button" id="appointment_type"
                        class="btn btn-info  color_button">{{__('ĐẶT LỊCH TRƯỚC')}}
                </button>
                <button type="button" id="direct_type"
                        class="btn btn-default ">{{__('ĐẾN TRỰC TIẾP')}}
                </button>
            </div>
        </div>
    </script>
    <script type="text/template" id="button-edit-tpl">
        <button type="button"
                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit"
                onclick="customer_appointment.submit_edit()">
							<span>
							<i class="la la-pencil"></i>
							<span>{{__('CẬP NHẬT')}}</span>
							</span>
        </button>
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
                               id="end_date" readonly
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
    <script src="{{asset('static/backend/js/admin/customer-appointment/list-calendar.js?t='.time())}}"
            type="text/javascript"></script>
    
    <script>
        $('.m_selectpicker').val('default').selectpicker("refresh");
        @if(isset($list_default) && count($list_default) > 0)
            @foreach($list_default as $v)
                click_detail.detail_click({{$v['customer_appointment_id']}});
            @endforeach
        @endif
    </script>
@stop
