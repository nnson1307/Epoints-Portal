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
            </div>
        </div>
    </div>
    <input name="message_chat" hidden id="message_chat" value="">
    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>
    <div id="my-modal"></div>
    <input name="view_mode" hidden id="view_mode" value="chathub_popup">
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
@stop

@section('after_script')
    <script src="{{ asset('static/backend/js/manager-work/managerWork/list.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/chathub/chat/index.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/customer-lead/customer-deal/script.js?v=' . time()) }}"
            type="text/javascript"></script>

    <script>
        chatInternal.domain = '{{$domain}}';
        chatInternal._init();
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

    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="view.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25% !important;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="view.changeObject(this)">
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
                <a href="javascript:void(0)" onclick="view.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop
