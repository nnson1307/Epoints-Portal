@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> @lang('chathub::attribute.index.ATTRIBUTE')</span>
@endsection
@section('head_data')
    <base href="/chat-hub/chat/new/">
@endsection
@section('after_style')
    <link href="{{ asset('chat/css/app.css?v='.time()) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
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
    <div class="m-portlet">
        <div id="epoints_chat"></div>
    </div>

    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/manager-work/managerWork/list.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('chat/js/index.js?v='.time()) }}"></script>
    <script>
        {{--if (window.addEventListener) {--}}
        {{--    window.addEventListener("message", onMessage, false);--}}
        {{--} else if (window.attachEvent) {--}}
        {{--    window.attachEvent("onmessage", onMessage, false);--}}
        {{--}--}}

        {{--function onMessage(event) {--}}
        {{--    // Check sender origin to be trusted--}}
        {{--    console.log(event.origin);--}}
        {{--    if (event.origin !== '{{$domain}}') return;--}}

        {{--    var data = event.data;--}}
        {{--    if (typeof(window[data.func]) == "function") {--}}
        {{--        window[data.func].call(null, data.message);--}}
        {{--    }--}}
        {{--}--}}

        // toastr.options = {
        //     "closeButton": false,
        //     "debug": false,
        //     "newestOnTop": false,
        //     "progressBar": false,
        //     "positionClass": "toast-top-right",
        //     "preventDuplicates": false,
        //     "onclick": null,
        //     "showDuration": "300",
        //     "hideDuration": "1000",
        //     "timeOut": "5000",
        //     "extendedTimeOut": "1000",
        //     "showEasing": "swing",
        //     "hideEasing": "linear",
        //     "showMethod": "fadeIn",
        //     "hideMethod": "fadeOut"
        // }
        //
        // // Function to be called from iframe
        // function parentFuncName(message) {
        //     toastr["info"](message)
        // }

        function showAddManagerWork(message){
            WorkChild.showPopup();
            if(message){
                alert(message)
                setTimeout(function(){
                    $("input[name='manage_work_title']").val(message);
                }, 500)
            }
        }

        // function resizeIframe(obj) {
        //     console.log(obj.contentWindow.document.documentElement.scrollHeight);
        //     obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        // }
    </script>

@stop
