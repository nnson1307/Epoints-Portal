@extends('layout')
<style>
    .fc-unthemed .fc-event.fc-start.m-fc-event--success .fc-content:before {
        background: #D8D8D8 !important;
    }
    .fc-unthemed .fc-event.fc-start.m-fc-event--accent .fc-content:before {
        background: #29B8F8 !important;
    }
    .fc-unthemed .fc-event.fc-start.m-fc-event--warning .fc-content:before {
        background: #FFAD33 !important;
    }
    .fc-unthemed .fc-event.fc-start.m-fc-event--primary .fc-content:before {
        background: #5F948E !important;
    }
    .fc-unthemed .fc-event.fc-start.m-fc-event--info .fc-content:before {
        background: #00AF09 !important;
    }
</style>

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
                        <i class="la la-calendar"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('LỊCH THÁNG')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.customer_appointment.list-day',session('routeList')))
                    <a href="{{route('admin.customer_appointment.list-day')}}"
                       class="btn m-btn--pill  btn-info btn-sm color_button ">
                        <i class="la la-clock-o"></i> {{__('Xem lịch ngày')}}
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="m-form m-form--fit m-form--label-align-right frmFilter">
                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center m--margin-bottom-10">
                        <div class="col-xl-6 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center row">
                            </div>
                        </div>

                    </div>

                </div>
            </form>


            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>{{__('Thông báo')}} : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content">

            </div><!-- end table-content -->
            @include('admin::customer-appointment.list-calendar')
            {{--@include('admin::customer-appointment.detail-list-index')--}}
        </div>
    </div>
    @if(in_array('admin.customer_appointment.submitModalEdit',session('routeList')))
        <input type="hidden" id="role-edit-appointments" value="1">
    @else
        <input type="hidden" id="role-edit-appointments" value="0">
    @endif
    @if(in_array('admin.customer_appointment.receipt',session('routeList')))
        <input type="hidden" id="role-receipt-appointments" value="1">
    @else
        <input type="hidden" id="role-receipt-appointments" value="0">
    @endif
    <div id="show-modal"></div>
@stop
@section("after_style")
    <link href="{{asset('static/backend/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}"
            type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/vi.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/vi.js"></script>

    <script src="{{asset('static/backend/js/admin/customer-appointment/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="detail-tpl">
        <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true"
             style="overflow: hidden;">
            <div id="load_ajax">
                <div class="form-group row">
                    <div class="col-lg-2 m-widget3__user-img img">
                        <img src="{img}" height="90px" width="70px">
                    </div>
                    <div class="col-lg-6">
                        <input type="hidden" name="id_hidden" id="id_hidden" value="{id}">
                        <strong><a style="font-size:20px">{full_name}</a></strong><br>
                        <a><i class="la la-phone"></i>{phone}</a><br>
                        <a><i class="la la-map-marker"></i>{address}</a>
                    </div>
                    <div class="col-lg-4">
                        {{--<span class="{clas}"></span>{status}--}}
                        <div class="input-group m-input-group m-input-group--solid div-status-detail">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="{class_new}" id="new">
                                    <input type="radio" name="status_detail" id="option1" value="new"
                                           autocomplete="off" checked=""> {{__('Mới')}}
                                </label>
                                <label class="{class_confirm}" id="confirm">
                                    <input type="radio" name="status_detail" id="option2" value="confirm"
                                           autocomplete="off"> {{__('Xác nhận')}}
                                </label>
                                <label class="{class_cancel}" id="cancel">
                                    <input type="radio" name="status_detail" id="option3" value="cancel"
                                           autocomplete="off"> {{__('Hủy')}}
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label><i class="la la-calendar"></i>{{__('Ngày hẹn')}}:</label>
                        <input readonly disabled="disabled" type="text" class="form-control m-input" value="{day}">
                    </div>
                    <div class="col-lg-6">
                        <label><i class="la la-clock-o"></i>{{__('Giờ hẹn')}}:</label>
                        <select class="form-control m-input time" id="time" name="time" style="width: 100%">
                            {{--<option></option>--}}
                            <option value="07:00">07:00</option>
                            <option value="07:15">07:15</option>
                            <option value="07:30">07:30</option>
                            <option value="07:45">07:45</option>
                            <option value="08:00">08:00</option>
                            <option value="08:15">08:15</option>
                            <option value="08:30">08:30</option>
                            <option value="08:45">08:45</option>
                            <option value="09:00">09:00</option>
                            <option value="09:15">09:15</option>
                            <option value="09:30">09:30</option>
                            <option value="09:45">09:45</option>
                            <option value="10:00">10:00</option>
                            <option value="10:15">10:15</option>
                            <option value="10:30">10:30</option>
                            <option value="10:45">10:45</option>
                            <option value="11:00">11:00</option>
                            <option value="11:15">11:15</option>
                            <option value="11:30">11:30</option>
                            <option value="11:45">11:45</option>
                            <option value="12:00">12:00</option>
                            <option value="12:15">12:15</option>
                            <option value="12:30">12:30</option>
                            <option value="12:45">12:45</option>
                            <option value="13:00">13:00</option>
                            <option value="13:15">13:15</option>
                            <option value="13:30">13:30</option>
                            <option value="13:45">13:45</option>
                            <option value="14:00">14:00</option>
                            <option value="14:15">14:15</option>
                            <option value="14:30">14:30</option>
                            <option value="14:45">14:45</option>
                            <option value="15:00">15:00</option>
                            <option value="15:15">15:15</option>
                            <option value="15:30">15:30</option>
                            <option value="15:45">15:45</option>
                            <option value="16:00">16:00</option>
                            <option value="16:15">16:15</option>
                            <option value="16:30">16:30</option>
                            <option value="16:45">16:45</option>
                            <option value="17:00">17:00</option>
                            <option value="17:15">17:15</option>
                            <option value="17:30">17:30</option>
                            <option value="17:45">17:45</option>
                            <option value="18:00">18:00</option>
                            <option value="18:15">18:15</option>
                            <option value="18:30">18:30</option>
                            <option value="18:45">18:45</option>
                            <option value="19:00">19:00</option>
                            <option value="19:15">19:15</option>
                            <option value="19:30">19:30</option>
                            <option value="19:45">19:45</option>
                            <option value="20:00">20:00</option>
                            <option value="20:15">20:15</option>
                            <option value="20:30">20:30</option>
                            <option value="20:45">20:45</option>
                            <option value="21:00">21:00</option>
                            <option value="21:15">21:15</option>
                            <option value="21:30">21:30</option>
                            <option value="21:45">21:45</option>
                            <option value="22:00">22:00</option>
                        </select>
                    </div>

                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label><i class="la la-users"></i>Kỹ thuật viên:</label>
                        <select class="form-control m-input staff" style="width: 100%">
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class=""><i class="la la-home"></i>{{__('Phòng')}}:</label>
                        <select class="form-control m-input room" style="width: 100%">
                        </select>
                    </div>

                </div>
                <div class="table-responsive">
                    <table style="text-align: center"
                           class="table table-striped m-table m-table--head-bg-primary table-list">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th width="40%">{{__('Dịch vụ')}}</th>
                            <th>{{__('Thời gian')}}</th>
                            <th width="25%">{{__('Số lượng')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">

                        <div class=" m--align-right id-nut-index">
                            <button type="button" class="btn btn-primary save">Đồng ý
                            </button>
                            <button type="button" class="btn btn-danger m-btn m-btn--icon m-btn--wide m-btn--md huy">
                                <i class="la la-arrow-left"></i>{{__('Thoát')}}
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </script>
    <script type="text/template" id="table-tpl">
        <tr class="detail_tb">
            <td>{stt}</td>
            <td>
                {service_name}
                <input type="hidden" name="id_hidden" id="id_hidden" value="{appointment_service_id}">
            </td>
            <td>
                {service_time}
            </td>
            <td>
                <input style="text-align: center" type="number" class="quantity" name="quantity" value="{quantity}">
            </td>
            <td>
                <a class='remove m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill'><i
                            class='la la-trash'></i></a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="append-status-other-tpl">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info  color_button active" id="new"
                   onclick="customer_appointment.new_click()">
                <input type="radio" name="status" id="option1" value="new"
                       autocomplete="off" checked=""> {{__('MỚI')}}
            </label>
            <label class="btn btn-default " id="confirm"
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
            <label class="btn btn-info  color_button active" id="wait">
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
            {{--<td>--}}
            {{--<a href="javascript:void(0)" onclick="customer_appointment.remove_tb(this)" class="remove m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill">--}}
            {{--<i class="la la-trash"></i>--}}
            {{--</a>--}}
            {{--</td>--}}
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
            {{--<div class="btn-group btn-group-toggle status_edit_2" data-toggle="buttons">--}}
            {{----}}
            {{--</div>--}}
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
            <label class="btn btn-info color_button " id="finish_stt"
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
    <script type="text/template" id="mouse-over-tpl">
        <div class="tooltipevent" style="position:absolute;z-index:10001;">
            <div class="m-section m-section--last">
                <div class="m-section__content">

                    <div class="m-demo">
                        <div class="m-demo__preview">
                            <div class="m-list-search">
                                <div class="m-list-search__results">
                                    <a href="#" class="m-list-search__result-item">
                                        <span class="m-list-search__result-item-icon">
                                            <i class="la la-user"></i>
                                        </span>
                                        <span class="m-list-search__result-item-text">{full_name}</span>
                                    </a>
                                    <a href="#" class="m-list-search__result-item">
                                        <span class="m-list-search__result-item-icon">
                                            <i class="la la-phone"></i>
                                        </span>
                                        <span class="m-list-search__result-item-text">{phone}</span>
                                    </a>
                                    <a href="#" class="m-list-search__result-item">
                                        <span class="m-list-search__result-item-icon">
                                            <span class="m-badge {class_status} m-badge--dot m--margin-left-5"></span>
                                        </span>
                                        <span class="m-list-search__result-item-text">
                                            <span class="{class_status_text} m--font-bold">{status}</span>
                                        </span>
                                    </a>
                                    <a href="#" class="m-list-search__result-item">
                                        <span class="m-list-search__result-item-icon">
                                           <i class="la la-user-plus"></i>
                                        </span>
                                        <span class="m-list-search__result-item-text">{customer_quantity} khách</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
                   onchange="customer_appointment.changeNumberTime(this)">
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

