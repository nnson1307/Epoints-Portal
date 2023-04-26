@extends('layout')
@section("after_style")
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('THÔNG TIN DỰ ÁN')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .nav-item:hover {
            /*background-color: #4fc4cb;*/
            transition: 1s;

        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }

        .m-portlet .m-portlet__body {
            padding: 1.2rem 2.2rem;
            background-color: white;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both
        }

        .m-portlet {
            margin-bottom: 0.2rem;
        }

        .column-pie-chart {
            width: 100%;
            font-weight: bold;
        }

        .chart-name {
            font-size: 20px;
            font-weight: bold;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 660px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 100%;
            border-radius: 5px;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        img {
            border-radius: 5px 5px 0 0;
        }

        .container {
            padding: 2px 16px;
        }

        table, th, td {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .table-message {
            border-top: 0 !important;
            border-bottom: 0 !important;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        .statistical td {
            border: none;
            /*display:flex*/
        }

        .card-title {
            padding: 10px 20px;
            margin: 0;
        }

        .card-status {
            font-size: 15px;
            color: #5CACEE;
            border: 1px solid #CAE1FF;
            border-radius: 4px;
            background: #CAE1FF;
            margin: 5px;
            padding: 5px 10px !important;
            margin-top: -5px;
        }


        .hight-risk {
            border: 1px solid #FFF0F5;
            background: #FFF0F5;
            border-radius: 5px;
            color: #A0522D;
            font-weight: 600;
        }

        .fs-15 {
            font-size: 15px;
        }

        .style-icon-statistical {
            font-size: 2rem;
            padding: 7px
        }

        .issue {
            border: 1px solid;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            position: relative;
        }

        .display-flex {
            display: flex;
        }

        .inline-block {
            display: inline-block;
        }

        .edit-name {
            border: none;
            background-color: white;
            color: #66CCFF;
        }

        .edit-name:hover {
            border: none;
            background-color: #66CCFF;
            color: white;
            border-radius: 5px;
            transition: 1s;
            cursor: pointer
        }
        .fa-trash-alt{
            font-weight: 900;
            color: red;
            border: 1px solid white;
            width: 30px;
            height: 30px;
            padding: 7px;
            border-radius: 50%;
            background-color: white;
        }
        .fa-trash-alt:hover{
            cursor:pointer;
            background-color: red;
            color: white;
            transition: 0.5s
        }
        .card-status-important{
            font-size: 15px;
            color: #FFCC00;
            border: 1px solid #FAFAD2;
            border-radius: 4px;
            background: #FAFAD2;
            margin: 5px;
            margin-top: -5px !important;


        }
        .card-status-red{
            font-size: 15px;
            color: red;
            border: 1px solid #EEB4B4;
            border-radius: 4px;
            background: #EEB4B4;
            margin: 5px;
            margin-top: -5px !important;
        }
    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THÔNG TIN DỰ ÁN')}}
                    </h3>
                </div>
                <div style="    right: 1%;position: absolute;">
                    <a href="{{route('manager-project.project')}}" type="button" class="btn btn-secondary" data-dismiss="modal" style="    color: black;font-weight: bold;">
                        <span class="la 	la-arrow-left"></span>
                        {{__('TRỞ VỀ')}}
                    </a>
                </div>
            </div>
        </div>
        @include('manager-project::project-info.block-project-info-master')
    </div>
    <div class="m-portlet" id="autotable" style="margin-bottom: 0.15rem">
        <div class="m-portlet__head m-portlet__head-update">
            @include('manager-project::layouts.project-info-tab-header')
        </div>
        <div class="m-portlet__body">
            <div class="col-12">
                <div class="row scroll-chat">
                    <table class="table table-message table-message-main">
                        <thead>
                        <tr>
                            <th width="2%" style="padding:0 !important;"></th>
                            <th width="90%" style="padding:0 !important;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($listComment as $item)
                            <tr class="tr_{{$item['manage_project_comment_id']}}">
                                <td>
                                    <img tabindex="-1" style="height: 40px;border-radius: 50%" src="{{$item['staff_avatar']}}"
                                         onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($item['staff_name']),0,1))}}';">
                                </td>
                                <td>
                                    <p>{{$item['staff_name']}}</p>
                                    <div class="message_work_detail">
                                        {!! $item['message'] !!}
                                        @if(isset($item['path']))
                                            <p class="message_work_path">
                                                <img src="{{$item['path']}}" style="width:200px">
                                            </p>
                                        @endif
                                    </div>
                                    <p class="mb-0">
                                        @if(\Illuminate\Support\Facades\Session::has('is_staff_work_project') == false || \Illuminate\Support\Facades\Session::get('is_staff_work_project') == 1)
                                            <a href="javascript:void(0)" class="reply_message" onclick="Comment.showFormChat({{$item['manage_project_comment_id']}})">{{ __('managerwork::managerwork.answer') }} </a>
                                        @endif
                                        {{\Carbon\Carbon::parse($item['created_at'])->diffForHumans(\Carbon\Carbon::now()) }}</p>
                                </td>
                            </tr>
{{--                            @if(count($item['child_comment']) != 0)--}}
                            <tr>
                                <td></td>
                                <td>
                                    <table class="table-message">
                                        <thead>
                                        <tr>
                                            <th width="3%" style="padding:0 !important;"></th>
                                            <th width="90%" style="padding:0 !important;"></th>
                                        </tr>
                                        </thead>
                                        <tbody class="tr_child_{{$item['manage_project_comment_id']}}">
                                        @foreach($item['child_comment'] as $itemChild)
                                            <tr>
                                                <td>
                                                    <img tabindex="-1" style="height: 40px;border-radius: 50%" src="{{$itemChild['staff_avatar']}}"
                                                         onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($itemChild['staff_name']),0,1))}}';">
                                                </td>
                                                <td>
                                                    <p>{{$itemChild['staff_name']}}</p>
                                                    <div class="message_work_detail">
                                                        {!! $itemChild['message'] !!}
                                                        @if(isset($itemChild['path']))
                                                            <p class="message_work_path">
                                                                <img src="{{$itemChild['path']}}" style="width:200px">
                                                            </p>
                                                        @endif
                                                    </div>

                                                    <p class="mb-0">{{\Carbon\Carbon::parse($itemChild['created_at'])->diffForHumans(\Carbon\Carbon::now()) }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
{{--                            @endif--}}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if(\Illuminate\Support\Facades\Session::has('is_staff_work_project') == false || \Illuminate\Support\Facades\Session::get('is_staff_work_project') == 1)
                <div class="col-12 mb-1">
                        <textarea id="description-en" name="description" type="text" class="form-control m-input class description"
                                  placeholder="{{ __('managerwork::managerwork.enter_comment') }}"
                                  aria-describedby="basic-addon1"></textarea>
                </div>
                <div class="col-12 mb-5 text-right">
                    <button type="button" onclick="Comment.addComment()" class=" mt-3 ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">{{ __('managerwork::managerwork.sent') }}</button>
                </div>
            @endif
        </div>
    </div>

    <input type="hidden" id="manage_project_id" value="{{$info['project_id']}}">

    <div class="append-popup"></div>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/manager-project/comment/script.js?v='.time())}}"></script>
    <script>
        function registerSummernote(element, placeholder, max, callbackMax) {
            $('.description').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname', 'fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        for (let i = 0; i < files.length; i++) {
                            uploadImgCk(files[i]);
                        }
                    },
                    onKeydown: function (e) {
                        var t = e.currentTarget.innerText;
                        if (t.length >= max) {
                            //delete key
                            if (e.keyCode != 8)
                                e.preventDefault();
                            // add other keys ...
                        }
                    },
                    onKeyup: function (e) {
                        var t = e.currentTarget.innerText;
                        if (typeof callbackMax == 'function') {
                            callbackMax(max - t.length);
                        }
                    },
                    onPaste: function (e) {
                        var t = e.currentTarget.innerText;
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        // var all = t + bufferText;
                        var all = bufferText;
                        document.execCommand('insertText', false, all.trim().substring(0, max - t.length));
                        // document.execCommand('insertText', false, bufferText);
                        if (typeof callbackMax == 'function') {
                            callbackMax(max - t.length);
                        }
                    }
                },
            });
        }

        $(function(){
            registerSummernote('.description', 'Leave a comment', 1000, function(max) {
                $('.description').text(max)
            });
        });
    </script>
@stop

