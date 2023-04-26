@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> @lang('chathub::attribute.index.ATTRIBUTE')</span>
@endsection
@section('after_style')
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">

    <style>
        select[readonly].select2-hidden-accessible + .select2-container {
            pointer-events: none;
            touch-action: none;
        }

        select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
            background: #eee;
            box-shadow: none;
        }

        select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow,
        select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
            display: none;
        }

        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .timepicker {
            border: 1px solid rgb(163, 175, 251);
            text-align: center;
            /* display: inline; */
            border-radius: 4px;
            padding: 2px;
            height: 38px;
            line-height: 30px;
            width: 130px;
        }

        .timepicker .hh, .timepicker .mm {
            width: 50px;
            outline: none;
            border: none;
            text-align: center;
        }

        .timepicker.valid {
            border: solid 1px springgreen;
        }

        .timepicker.invalid {
            border: solid 1px red;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .custom-remind-item {
            color: #575962 !important;
            border: 1px solid #4bb072 !important;
            position: relative;
        }

        .custom-remind-item strong {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .custom-remind-item button {
            color: #575962 !important;
        }

        .custom-remind-item::before {
            content: '';
            position: absolute;
            left: -1px;
            background: #79cca8;
            width: 9px;
            height: calc(100% + 2px);
            top: -1px;
            /* border-radius: 0px 5px 5px 0px; */
            border-radius: 5px;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        .modal .modal-content .modal-body-config {
            padding: 25px;
            max-height: 400px;
            overflow-y: scroll;
        }

        .weekDays-selector input {
            display: none !important;
        }

        .weekDays-selector input[type=checkbox] + label {
            display: inline-block;
            border-radius: 6px;
            background: #dddddd;
            height: 40px;
            width: 30px;
            margin-right: 3px;
            line-height: 40px;
            text-align: center;
            cursor: pointer;
        }

        .weekDays-selector input[type=checkbox]:checked + label {
            background: #2AD705;
            color: #ffffff;
        }
        .m-body .m-content {
            padding: 15px 15px 5px 5px !important;
        }

        .full-screen-chat {
            height: 85vh !important;
        }
    </style>
@endsection
@section('content')
    <style>
        .form-control-feedback {
            color: red;
        }
        .m-wrapper {
            margin: 0px!important;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 full-screen-chat">
            <div style="height: 108%" class="embed-responsive embed-responsive-21by9">
                <iframe id="if_chathub_inbox" class="embed-responsive-item" src="{{$domain}}" allowfullscreen></iframe>

{{--                <iframe id="if_chathub_inbox" class="embed-responsive-item" src="https://chat.epoints-brandportal.com/room/630ed774c616b434b5c00797" allowfullscreen></iframe>--}}
            </div>
        </div>
    </div>

    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>
    <div id="my-modal"></div>
    <div id="show-modal"></div>
    <input name="view_mode" type="hidden" id="view_mode" value="chathub_popup">
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <form id="fr_chathub">
        <input type="hidden" name="fr_ch_customer_id" id="fr_ch_customer_id">
        <input type="hidden" name="fr_customer_lead_id" id="fr_customer_lead_id">
        <input type="hidden" name="fr_customer_lead_code" id="fr_customer_lead_code">
        <input type="hidden" name="fr_customer_lead_full_name" id="fr_customer_lead_full_name">
        <input type="hidden" name="fr_customer_id" id="fr_customer_id">
        <input type="hidden" name="fr_customer_code" id="fr_customer_code">
        <input type="hidden" name="fr_customer_full_name" id="fr_customer_full_name">
        <input type="hidden" name="fr_full_name" id="fr_full_name">
        <input type="hidden" name="fr_phone" id="fr_phone">
        <input type="hidden" name="fr_message_chat" id="fr_message_chat" value="">
        <input type="hidden" name="show_pop_cus_lead" id="show_pop_cus_lead" value="">
    </form>
    <div id="append-popup"></div>
@stop

@section('after_script')
    <script src="{{ asset('static/backend/js/manager-work/managerWork/list.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/customer-lead/customer-deal/script.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/customer-lead/customer-lead/script.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/admin/customer-appointment/script.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/chathub/inbox/my-work/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        chathubInbox.domain = '{{$domain}}';
        chathubInbox._init();
    </script>
    <script>


        function reloadCountNoti(noti){
            notification.chatNoti();
        }

        function resizeIframe(obj) {
            console.log(obj.contentWindow.document.documentElement.scrollHeight);
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }
    </script>

    <!--
    showAppointmentSchedule
    -->

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

    <script type="text/template" id="to-date-tpl">
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
    </script>

    <script type="text/template" id="append-status-live-tpl">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info  color_button active" id="wait">
                <input type="radio" name="status" id="option1" value="wait"
                       autocomplete="off" checked=""> {{__('CHỜ PHỤC VỤ')}}
            </label>
        </div>
    </script>

    <!--
    END showAppointmentSchedule
    -->

    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="viewCusDeal.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25% !important;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="viewCusDeal.changeObject(this)">
                    <option></option>
                </select>
                <span class="error_object color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input object_price" name="object_price" style="background-color: white;"
                       id="object_price_{stt}" value="" readonly>
                <input type="hidden" class="object_id" name="object_id">
            </td>
            <td style="width: 9%">
                <input type="text" class="form-control m-input btn-ct-input object_quantity" name="object_quantity"
                       id="object_quantity_{stt}" style="text-align: center" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_discount" name="object_discount"
                       id="object_discount_{stt}" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_amount" name="object_amount" style="background-color: white;"
                       id="object_amount_{stt}" value="" readonly>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="viewCusDeal.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop
