@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section('after_style')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
    <style>
        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .fz-10 {
            font-size: 10px;
        }
        #kanban2 .overtime{
            width: 65%;
            padding: 0;
            margin: 0;
            text-align: right;
            margin-left: 35%;
            margin-top: -5px;
            border-top-left-radius: 50px;
            border-bottom-left-radius: 50px;
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

        .timepicker .hh,
        .timepicker .mm {
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

        .modal .modal-content .modal-body {
            padding: 25px;
            /*max-height: 400px;*/
            overflow-y: scroll;
        }

        .max-height-400px {
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

        .flex-wrapper {
            display: flex;
            flex-flow: row nowrap;
        }

        .single-chart {
            width: 40px;
            justify-content: space-around;
        }

        .circular-chart {
            display: block;
            margin: 10px auto;
            max-width: 80%;
            max-height: 250px;
        }

        .comment-button {
            cursor: pointer;
        }

        .circle-bg {
            fill: none;
            stroke: #eee;
            stroke-width: 3.8;
        }

        .circle {
            fill: none;
            stroke-width: 2.8;
            stroke-linecap: round;
            animation: progress 1s ease-out forwards;
        }

        @keyframes progress {
            0% {
                stroke-dasharray: 0 100;
            }
        }

        .circular-chart.orange .circle {
            stroke: #ff9f00;
        }

        .circular-chart.green .circle {
            stroke: #4CC790;
        }

        .circular-chart.blue .circle {
            stroke: #3c9ee5;
        }

        .percentage {
            fill: #666;
            font-size: .6em;
            text-anchor: middle;
        }

        /*kabancusstom*/

        .jqx-kanban-column-header {
            margin-right: 15px !important;;
        }

        .jqx-sortable {
            margin-right: 15px !important;
        }

        .jqx-sortable::-webkit-scrollbar {
            display: none !important;
        }

        .jqx-kanban-column {
            border-width: 0 !important;
        }

        .jqx-icon-dot {
            width: 10px !important;
            height: 10px !important;
            display: block;
            border-radius: 50%;
            background-image: none !important;

        }

        #kanban2 {
            width: auto !important;
            /*overflow-x: scroll;*/
            /*overflow-y: hidden;*/
            display: flex;
            height: 800px !important;
        }

        .overfollow-scroll {
            overflow-x: scroll;
        }

        /*.overfollow-scroll::-webkit-scrollbar {*/
        /*display: none;*/
        /*}*/
        .w-50px {
            width: 50px;
        }

        #kanban2 .jqx-window-collapse-button {
            width: 10px !important;
            height: 10px !important;
            display: block;
            border-radius: 50%;
            background-image: none !important;

        }

        .status_work_priority {
            font-size: 10px;
            margin-left: 0px;
            padding: 5px 30px 5px 10px;
            white-space: nowrap;
        }

        #kanban2 .jqx-icon-arrow-left-light {
            width: 10px !important;
            height: 10px !important;
            display: block;
            border-radius: 50%;
            background-image: none !important;

        }

        #kanban2 .jqx-kanban-column-header-collapsed {
            margin: 0 0 0 5px;

        }

        #kanban2 .jqx-kanban-column-header-collapsed-show {
            border-radius: 10px;
        }

        /*#kanban2 .jqx-kanban-column {*/
        /*margin: 0px 2px 0 2px;*/
        /*}*/
        /*#kanban2 .jqx-kanban-column:last-child {*/
        /*margin: 0px -10px 0 2px;*/
        /*}*/

        #kanban2 .jqx-kanban-column-header {
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
        }

        #kanban2 *:not(.fa):not(.far) {
            font-family: 'Roboto' !important;
        }

        #kanban2 .jqx-kanban-item {
            display: flex;
            flex-wrap: wrap;
        }

        #kanban2 .jqx-kanban-column {
            /*min-width: 25%;*/
        }

        #kanban2 .jqx-kanban-column-header-title {
            font-size: 16px;
            font-weight: 600;
        }

        #kanban2 .jqx-kanban-item {
            margin: 15px !important;
            border-radius: 5px;
            background: #fff;
        }

        #kanban2 .jqx-kanban-column-container {
            background: #e8e8e8;
        }

        #kanban2 .custom-process {
            background-color: transparent !important;
            height: auto !important;
            width: auto !important;
            position: relative;
            width: 30% !important;
            order: 2;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
        }

        #kanban2 .jqx-kanban-item-text {
            order: 0;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            width: 70%;
            padding-left: 15px;
            flex-direction: column;
        }

        #kanban2 .jqx-kanban-item-text .title-item-comment {
            font-size: 17px;
            font-weight: 500;
        }

        #kanban2 .jqx-kanban-item-text .sub-title-icon {
            width: 15px;
            height: 15px;
            margin: 0 5px;
        }

        #kanban2 .jqx-kanban-item-footer {
            width: 100%;
            order: 3;
            display: flex;
            justify-content: space-between;
        }

        #kanban2 .jqx-kanban-item-footer .jqx-kanban-item-keyword:first-child {
            width: 20%;
        }

        #kanban2 .jqx-kanban-item-color-status {
            background-color: #6bbd49;
            height: 40% !important;
            left: 5px !important;
            top: 15% !important;
        }

        #kanban2 .jqx-kanban-item-keyword {
            border: 0px solid transparent !important;
            font-size: 12px;
            background: transparent;
            display: flex;
            align-items: center;
            padding-left: 15px;
        }

        /*endkanban*/
        /*comment*/
        .full-width {
            width: 100%;
            height: 100vh;
            display: flex;
        }

        .full-width .justify-content-center {
            display: flex;
            align-self: center;
            width: 100%;
        }

        .full-width .lead.emoji-picker-container {
            width: 300px;
            display: block;
        }

        .full-width .lead.emoji-picker-container input {
            width: 100%;
            height: 50px;
        }

        #kanban2 .avatars_overview__item:not(:first-child) {
            margin-left: -5px !important;
        }
        #kanban2 .title_overdue {
            background: #FDD9D7;
            padding: 10px;
            padding-left: 15px;
            font-weight: 600;
        }
        .jqx-kanban-column {
            margin-right:10px;
        }
        .m-portlet--head-sm {
            margin-bottom: 5px !important;
        }

        .kt-portlet--mobile {
            margin: 0 !important;
        }
        @foreach($colorStatus as $key => $value)
        .status_work_priority_{{$key}}, .work_priority_{{$key}}, .work_priority_bonus, .work_priority_kpi {
            background: {{$value}}   !important;
            background-color: {{$value}}   !important;
        }
        @endforeach

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

        .chart-name {
            font-size: 20px;
            font-weight: bold;
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
        }
        .card-status-red{
            font-size: 15px;
            color: red;
            border: 1px solid #EEB4B4;
            border-radius: 4px;
            background: #EEB4B4;
            margin: 5px;
        }
        .number-status{
            font-size: 35px
        }
        .column-status {
            float: left;
            width: 20%;
            padding: 10px;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .datepicker{
            width: 310px;
        }

        .select2 {
            width : 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet" >
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
            </div>
        </div>
        @include('manager-project::project-info.block-project-info-master')
    </div>
    <div class="m-portlet" >
        <div class="m-portlet__head  m-portlet__head-update">
            @include('manager-project::layouts.project-info-tab-header')
        </div>
    </div>
    <div class="m-portlet"  style="margin-bottom: 0.15rem;padding: 10px">
        <div class="m-portlet__head" style="height: 6.1rem !important">
            <div class="row" style="width:100%">
                <div class="column-status" style="background-color:#3399FF;">
                    <p  class="mb-0">{{__('CÔNG VIỆC')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['totalWork'] : 0}}</p>
                </div>
{{--                <div class="column-status" style="background-color:#FFCC00;">--}}
{{--                    <p class="mb-0">{{__('CÓ NGUY CƠ TRỄ HẠN')}}</p>--}}
{{--                    <p class="mb-0 number-status">{{ $info['work-duration'] != [] ? $info['work-duration']['workMayBeLate'] : 0}}</p>--}}
{{--                </div>--}}
                <div class="column-status" style="background-color:#66CC33;">
                    <p class="mb-0">{{__('HOÀN THÀNH ĐÚNG HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workCompleteOnTime'] : 0}}</p>
                </div>
                <div class="column-status" style="background-color:#CC33FF;">
                    <p class="mb-0">{{__('HOÀN THÀNH TRỄ HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workCompleteLate'] : 0}}</p>
                </div>
                <div class="column-status" style="background-color:#FF3300;">
                    <p class="mb-0">{{__('ĐÃ TRỄ HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workOutOfDate'] : 0}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
{{--                    <span class="m-portlet__head-icon">--}}
{{--                        <i class="la la-th-list"></i>--}}
{{--                    </span>--}}
                    <h3 class="m-portlet__head-text">
{{--                        {{ __('managerwork::managerwork.list_work') }}--}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="ManagerWork.configSearch()"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-cog"></i>
                        <span> {{ __('managerwork::managerwork.config_list') }}</span>
                    </span>
                </a>
                <a href="{{route('manager-project.work',['manage_project_id' => isset($project) ? $project->manage_project_id : 0])}}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                    <i class="fa fa-cog"></i>
                    <span> {{ __('DANH SÁCH') }}</span>
                    </span>
                </a>
                <a href="javascript:void(0)"
                   onclick="ManagerWork.exportList()"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-cog"></i>
                        <span> {{ __('managerwork::managerwork.export_data') }}</span>
                    </span>
                </a>
                @if(!in_array($project['manage_project_status_group_config_id'],[3,4]) && in_array(\Auth::id(),$listStaffProject))
                    <a href="javascript:void(0)" onclick="WorkChild.showPopup()"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
                            <i class="fa fa-plus-circle"></i>
                            <span> {{ __('managerwork::managerwork.add_work') }}</span>
                        </span>
                    </a>
                @endif
                <a href="javascript:void(0)" onclick="WorkChild.showPopup()"
                   class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                   style="display: none">
                    <i class="fa fa-plus-circle" style="color: #fff"></i>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
        <form id="frm-search" class="frmFilter bg clear-form">
            <input type="hidden" name="manage_project_id" value="{{ $filters['manage_project_id'] ?? '' }}">
                <div class="row padding_row">
                    <input type="hidden" name="manage_project_id" value="{{isset($filters['manage_project_id']) ? $filters['manage_project_id'] : ''}}">
                    @foreach ($searchConfig as $config)
                        @if ($config['active'])
                            @if ($config['type'] == 'select2')
                                @if ($config['name'] == 'customer_id')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="manage_work_customer_type" id="manage_work_customer_type_list"
                                                    onchange="WorkAll.changeCustomerList()"
                                                    class="form-control select2 select2-active">
                                                <option {{isset($filters['manage_work_customer_type']) && $filters['manage_work_customer_type'] == 'customer' ? 'selected' : ''}} value="customer">{{ __('managerwork::managerwork.customer') }}</option>
                                                <option {{isset($filters['manage_work_customer_type']) && $filters['manage_work_customer_type'] == 'lead' ? 'selected' : ''}} value="lead">{{ __('managerwork::managerwork.lead') }}</option>
                                                <option {{isset($filters['manage_work_customer_type']) && $filters['manage_work_customer_type'] == 'deal' ? 'selected' : ''}} value="deal">{{ __('managerwork::managerwork.deal') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="{{ $config['name'] }}" id="select_customer_id"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ $config['placeholder'] }}</option>
                                                {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                @foreach ($config['data'] as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif ($config['name'] == 'processor_id')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="{{ $config['name'] }}"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ $config['placeholder'] }}</option>
                                                {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                @foreach ($config['data'] as $key => $value)
                                                    <option value="{{ $key }}" {{isset($filters['processor_id']) && $filters['processor_id'] == $key ? 'selected' : ''}}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif ($config['name'] == 'department_id')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="{{ $config['name'] }}"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ $config['placeholder'] }}</option>
                                                {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                @foreach ($config['data'] as $key => $value)
                                                    <option value="{{ $key }}"
                                                            {{isset($filters['department_id']) && $filters['department_id'] == $key ? 'selected' : ''}}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif ($config['name'] == 'manage_project_id')
{{--                                    <div class="col-lg-3">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <select name="{{ $config['name'] }}"--}}
{{--                                                    class="form-control select2 select2-active">--}}
{{--                                                <option value="">{{ $config['placeholder'] }}</option>--}}
{{--                                                --}}{{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
{{--                                                @foreach ($config['data'] as $key => $value)--}}
{{--                                                    <option value="{{ $key }}"--}}
{{--                                                            {{isset($filters['manage_project_id']) && $filters['manage_project_id'] == $key ? 'selected' : ''}}>{{ $value }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                @elseif ($config['name'] == 'manage_status_id')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="{{ $config['name'] }}[]"
                                                    class="form-control select2 select2-active" multiple>
                                                <option value="">{{ $config['placeholder'] }}</option>
                                                {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                @foreach ($config['data'] as $key => $value)
                                                    <option value="{{ $key }}" {{isset($param['manage_status_id']) && in_array($key,$param['manage_status_id']) ? 'selected' : ''}}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif ($config['name'] == 'assign_by')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="{{ $config['name'] }}"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ $config['placeholder'] }}</option>
                                                {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                @foreach ($config['data'] as $key => $value)
                                                    <option value="{{ $key }}" {{isset($param['assign_by']) && $param['assign_by'] == $key ? 'selected' : ''}}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="{{ $config['name'] }}"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ $config['placeholder'] }}</option>

                                                @foreach ($config['data'] as $key => $value)
                                                    <option {{isset($param[$config['name']]) && $param[$config['name']] == $key ? 'selected' : ''}} value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            @elseif($config['type'] == 'daterange_picker')

                                @if($config['name'] == 'date_end')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input readonly class="form-control daterange-picker-list"
                                                       style="background-color: #fff" name="{{ $config['name'] }}"
                                                       value="{{isset($filters['date_end']) ? $filters['date_end'] : ''}}"
                                                       autocomplete="off"
                                                       placeholder="{{ $config['placeholder'] }}">
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                <span><i class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($config['name'] == 'created_at')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input readonly class="form-control daterange-picker-list"
                                                       style="background-color: #fff" name="{{ $config['name'] }}"
                                                       value="{{isset($filters['created_at']) ? $filters['created_at'] : ''}}"
                                                       autocomplete="off"
                                                       placeholder="{{ $config['placeholder'] }}">
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input readonly class="form-control daterange-picker-list"
                                                       style="background-color: #fff" name="{{ $config['name'] }}"
                                                       autocomplete="off"
                                                       value="{{ $filters[$config['name']] ?? '' }}"
                                                       placeholder="{{ $config['placeholder'] }}">
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                <span><i class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($config['type'] == 'date_picker')
                            @if($config['name'] == 'date_end')
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control date-picker-list"
                                                   style="background-color: #fff" name="{{ $config['name'] }}"
                                                   value="{{isset($filters['date_end']) ? $filters['date_end'] : \Carbon\Carbon::now()->endOfMonth()->format('d/m/Y')}}"
                                                   autocomplete="off"
                                                   placeholder="{{ $config['placeholder'] }}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($config['name'] == 'date_start')
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control date-picker-list"
                                                   style="background-color: #fff" name="{{ $config['name'] }}"
                                                   value="{{isset($filters['date_start']) ? $filters['date_start'] : \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y')}}"
                                                   autocomplete="off"
                                                   placeholder="{{ $config['placeholder'] }}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($config['name'] == 'created_at')
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control date-picker-list"
                                                   style="background-color: #fff" name="{{ $config['name'] }}"
                                                   value="{{isset($filters['created_at']) ? $filters['created_at'] : ''}}"
                                                   autocomplete="off"
                                                   placeholder="{{ $config['placeholder'] }}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control date-picker-list"
                                                   style="background-color: #fff" name="{{ $config['name'] }}"
                                                   autocomplete="off"
                                                   value="{{ $filters[$config['name']] ?? '' }}"
                                                   placeholder="{{ $config['placeholder'] }}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @elseif($config['type'] == 'text')
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <input value="{{ $param[$config['name']]  ?? '' }}" type="text" class="form-control" name="{{ $config['name'] }}"
                                               placeholder="{{ $config['placeholder'] }}">
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    @if(isset($filters['type-search']))
                        <input type="hidden" name="type-search" value="{{$filters['type-search']}}">
                    @endif
                    @if(isset($filters['type-page']))
                        <input type="hidden" name="type-page" value="{{$filters['type-page']}}">
                    @endif

                    <div class="col-lg-3">
                        <input type="hidden" name="manage_project_id" value="{{ $filters['manage_project_id'] ?? '' }}">
                        <a href="{{route('manager-project.work.kanban-view',['manage_project_id' => $filters['manage_project_id']])}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                            {{ __('managerwork::managerwork.remove_filter') }}
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </a>
                        <button class="btn btn-primary color_button btn-search">
                            {{ __('managerwork::managerwork.search') }} <i
                                    class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="table-content mt-3">
                <div class="d-flex justify-contents-center overfollow-scroll">
                    <div id="kanban2"></div>
                </div>
            </div>
            <!-- end table-content -->
        </div>
    </div>
    {{--    <div class="modal fade" id="modalAdd" role="dialog">--}}
    {{--        <div class="modal-dialog modal-dialog-centered modal-lg">--}}
    {{--            @include('manager-project::work.add')--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="modal" id="comment-popup">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- The modal comment -->
            {{--            @include('manager-project::work.popup.comment-popup')--}}
        </div>
    </div>
    {{--<div class="modal fade" id="modalEdit" role="dialog">--}}
    {{--<div class="modal-dialog modal-dialog-centered modal-lg">--}}
    {{--<!-- Modal content-->--}}
    {{--@include('manager-project::work.edit')--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="modal fade" id="modalView" role="dialog">--}}
    {{--<div class="modal-dialog modal-dialog-centered modal-lg">--}}
    {{--<!-- Modal content-->--}}
    {{--@include('manager-project::work.view')--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="modal fade" id="modalRemind" role="dialog">--}}
    {{--<div class="modal-dialog modal-dialog-centered modal-lg">--}}
    {{--<!-- Modal content-->--}}
    {{--@include('manager-project::work.popup.remind')--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="modal fade" id="modalRepeatNotification" role="dialog">--}}
    {{--<div class="modal-dialog modal-dialog-centered modal-lg">--}}
    {{--<!-- Modal content-->--}}
    {{--@include('manager-project::work.popup.repeat')--}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="d-none" id="date-single">
        <div class="input-group date date-single">
            <input type="text" class="form-control m-input date-timepicker" readonly placeholder="@lang('Ngày hết hạn')"
                   name="date_issue">
            <div class="input-group-append">
                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
            </div>
        </div>
    </div>
    <div class="d-none" id="date-multiple">
        <div class="input-group date date-multiple">
            <input type="text" class="form-control m-input daterange-input" readonly placeholder="@lang('Ngày hết hạn')"
                   name="date_issue">
            <div class="input-group-append">
                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
            </div>
        </div>
    </div>
    @include('manager-project::work.popup.config_search')
    <div class="d-none" id="remind-item">
        <div class="remind-item row m-0">
            <div class="col-11 alert alert-light m-alert--outline alert-dismissible fade show custom-remind-item">
                <div class="row">
                    <div class="col-lg-3">
                        <strong>{date_remind}</strong>
                        <input type="hidden" name="processor_id_remind[]" value="{processor_id_remind}">
                        <input type="hidden" name="date_remind[]" value="{date_remind}">
                        <input type="hidden" name="time_remind[]" value="{time_remind}">
                        <input type="hidden" name="time_type_remind[]" value="{time_type_remind}">
                        <input type="hidden" name="description_remind[]" value="{description_remind}">
                    </div>
                    <div class="col-lg-9 text-left">
                        <h4 class="m-0">{description_remind}</h4>
                        <span>{{ isset(Auth::user()->full_name) ? Auth::user()->full_name : '' }}
                            {{ __('tạo nhắc nhở cho') }} {processor_name}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-1 d-flex align-items-center text-right">
                <button
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-custom-remind-item"
                        title="{{ __('Xóa') }}"><i class="la la-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="d-none" id="percent-template">
        <div class="single-chart percentage-update">
            <svg viewBox="0 0 36 36" class="circular-chart orange">
                <path class="circle-bg"
                      d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <path class="circle"
                      stroke-dasharray="{data}, 100"
                      d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <text x="18" y="20.35" class="percentage">{data}%</text>
            </svg>
        </div>
    </div>
    <input type="hidden" id="hidden_manage_project_id" value="{{$info['project_id']}}">
    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>
    <div class="append-popup"></div>
@stop
@section('after_script')
    <script src="{{ asset('static\backend/js/manager-project/managerWork/table-excel/jquery.table2excel.js') }}"
            type="text/javascript">
    </script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/kanban.js?v=' . time()) }}"
            type="text/javascript">
    </script>
    {{-- js wiget --}}
    <link rel="stylesheet"
          href="{{ asset('static/backend/js/manager-project/managerWork/kanban/jqwidgets/styles/jqx.base.css') }}"
          type="text/css"/>
    <script src="{{ asset('static/backend/js/admin/service/autoNumeric.min.js?v=' . time()) }}"></script>
    <script>
        var decimal_number =
                {{ isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0 }};
    </script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/kanban/jqwidgets/jqxcore.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/kanban/jqwidgets/jqxsortable.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/kanban/jqwidgets/jqxkanban.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/kanban/jqwidgets/jqxsplitter.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/kanban/jqwidgets/jqxdata.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/kanban/jqwidgets/demos.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            WorkAll.changeCustomerList({{isset($filters['customer_id']) ? $filters['customer_id'] : null}});
            var fields = [{
                name: "id",
                type: "string"
            },
                {
                    name: "status",
                    map: "state",
                    type: "string"
                },
                {
                    name: "text",
                    map: "label",
                    type: "string"
                },
                {
                    name: "tags",
                    type: "string"
                },
                {
                    name: "color",
                    map: "hex",
                    type: "string"
                },
                {
                    name: "resourceId",
                    type: "number"
                },
                {
                    name: "content",
                    map: "common",
                    type: "string"
                }
            ];
            var source = {
                localData: [
                        @foreach($list as $key => $value)
                    {
                        @php
                            $n = 0;
                            $avatar= '';
                            $last_avatar= '';
                            if($n == 0 && $value->processor_id != null){
                                $n++;
                                $onerror = ' onerror="https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name='.strtoupper(substr(str_slug($value->processor_full_name),0,1)).'"';
                                $avatar .= '<a href="javascript:void(0)" manage_work_id="'.$value->manage_work_id.'" class="avatars_overview__item"><img class="avatar" src="'.$value->processor_avatar.'" alt="'.$value->processor_full_name.'" '.$onerror.'></a>';
                            }
                            if($value->workSupportListAvatar){
                                foreach($value->workSupportListAvatar as $avt){
                                    $n++;
                                    if($n < 4 && $n > 1){
                                        $onerror = ' onerror="https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name='.strtoupper(substr(str_slug($avt->full_name),0,1)).'"';
                                        $avatar .= '<a href="javascript:void(0)" manage_work_id="'.$value->manage_work_id.'" class="avatars_overview__item"><img class="avatar" src="'.$avt->staff_avatar.'" alt="'.$avt->full_name.'" '.$onerror.'></a>';
                                    }elseif ($n >= 4){
                                        $last_avatar = '<a href="javascript:void(0)" class="avatars_overview__item">+'.$n.'</a>';
                                    }
                                }
                            }
                            $avatar .= $last_avatar;
                            if ((date("Y-m-d h:i:s") > $value->date_end) && ($value->manage_status_id != 6)) {
                                $over_time = "<div class='mb-0 title_overdue overtime'><i class='far fa-clock'></i> ".__('Quá hạn').' '.\Carbon\Carbon::parse($value->date_end)->diffForHumans(\Carbon\Carbon::now())."</div>";
                            }else{
                                $over_time = "<div></div>";
                            }
                            $manage_project_name = $value->manage_project_name?"<span> | {$value->manage_project_name}</span>":'';
                            $icon_type_work = "<image src='{$value->manage_type_work_icon}' class='sub-title-icon'>";
                            $title = "<div class='title-item-comment'>{$value->manage_work_title}</div><div class='sub-title-comment'>{$icon_type_work}<span>{$value->manage_type_work_name}</span>{$manage_project_name}</div>";
                            $cout_comment = '<p class="mb-0 text-nowrap w-50px comment-button" manager-work-id="'.$value->manage_work_id.'"><i class="fa fa-comments"></i>'.(count($value->countComment)).'</p>';
                            $date = \Carbon\Carbon::parse($value->date_end)->format('d/m/Y');
                            $date = '<p class="mb-0 date-end-update d-flex"><i class="fa fa-clock"></i>'.$date.'</p>';
                            $status = '<p class="status_work_priority mb-0" style="background-color:'.$value->manage_color_code.'" >'.$value->manage_status_name.'</p>';
                                $priority = 'green';
                                if($value->priority == 1){
                                   $priority = 'red';
                                }else if($value->priority == 2){
                                    $priority = '#ffb925';
                                }else{
                                    $priority = 'green';
                                }
                        @endphp
                        id: "{{$value->manage_work_id}}",
                        state: "{{$value->manage_status_id}}",
                        label: "{!! $title !!}",
                        tags: '{!! (($avatar != '')?($avatar):'') !!},{!! $date !!},{!! $cout_comment !!},{!! $status !!}',
                        hex: "{{$priority}}",
                        resourceId: "{{$value->progress}}",
                        common: "{!! $over_time !!}"
                    },
                    @endforeach

                ],
                dataType: "array",
                dataFields: fields
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var resourcesAdapterFunc = function () {
                var resourcesSource = {
                    localData: [
                            @foreach($list as $key => $value)
                        {
                            id: "{{$value->manage_work_id}}",
                            name: "{{$value->progress}}",
                            resourceId: "{{$value->manage_work_id}}",
                        },
                        @endforeach
                    ],
                    dataType: "array",
                    dataFields: [{
                        name: "id",
                        type: "number"
                    },
                        {
                            name: "name",
                            type: "string"
                        },
                        {
                            name: "image",
                            type: "string"
                        },
                    ]
                };

                var resourcesDataAdapter = new $.jqx.dataAdapter(resourcesSource);
                return resourcesDataAdapter;
            }

            $('#kanban2').jqxKanban({
                resources: resourcesAdapterFunc(),
                source: dataAdapter,
                width: '100%',
                height: '100%',
                itemRenderer: function (element, item, resource) {
                    var percent = $('#percent-template').html();
                    percent = percent.replace(/{data}/g, parseInt(item.resourceId));
                    $(element).find(".jqx-kanban-item-avatar").addClass('custom-process').html(percent);
                    $(element).find(".jqx-kanban-item-color-status").before(item.content);
                },
                columns: [
                        @foreach ($manageStatusList as $status_id => $status_name)
                    {
                        text: "{{$status_name}}", /* tên trạng thái */
                        dataField: "{{$status_id}}", /* id trạng thái */
                        color: "{{$colorStatus[$status_id]}}", /* id trạng thái */
                        collapseDirection: "right",
                    },
                    @endforeach
                ],
                columnRenderer: function (element, collapsedElement, column) {
                    var columnItems = $("#kanban2").jqxKanban('getColumnItems', column.dataField).length;
                    // update header's status.
                    element.find(".jqx-kanban-column-header-status").html(" (" + columnItems + ")");
                    element.find(".jqx-window-collapse-button").addClass('jqx-icon-dot').css("background-color", column.color);
                    // update collapsed header's status.
                    collapsedElement.find(".jqx-kanban-column-header-status").html(" (" + columnItems + ")");
                }
            });

            $('#kanban2').on('itemMoved', function (event) {
                var args = event.args;
                var itemId = args.itemId;
                var oldParentId = args.oldParentId;
                var newParentId = args.newParentId;
                var itemData = args.itemData;
                var oldColumn = args.oldColumn;
                var newColumn = args.newColumn;
                if (itemId != '' && newColumn.dataField != '') {
                    $.ajax({
                        url: laroute.route('manager-project.work.change-status'),
                        method: "POST",
                        data: {
                            manage_work_id: itemData.id,
                            manage_status_id: newColumn.dataField
                        },
                        success: function (res) {
                            if (res.status == 0) {
                                var priority = '<p class="status_work_priority status_work_priority_' + newColumn.dataField + ' mb-0">' + newColumn.text + '</p>';
                                $('#kanban2_' + itemData.id + ' .jqx-kanban-item-keyword .status_work_priority').parent().html(priority);
                                swal.fire(res.message, '', 'success');
                            } else {
                               swal.fire(res.message, '', 'error').then(function (){
                                   $('.btn-search').trigger('click');
                               });
                            }
                        }
                    });
                }
            });

            $('#kanban2 img.avatar').each(function () {
                if ($(this).attr('src') == '') {
                    $(this).attr('src', $(this).attr('onerror'));
                }
            });

            $('#kanban2 .comment-button').on('click', function (e) {
                var mana_work_id = $(this).attr('manager-work-id');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-project.work.load-comment'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function (res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $('#comment-popup #description_comment').summernote('code');
                                $('#comment-popup').modal('show');
                            } else {
//                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
                if (e.target === $(this).find('.comment-button .fa-comments')[0]) ;

            });

            $('#kanban2 .avatars_overview__item').on('click', function (e) {
                var mana_work_id = $(this).attr('manage_work_id');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-project.work.kanban-view.show-popup-staff'),
                        data: {
                            manage_work_id : mana_work_id
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function(res) {
                            if (res.error == false) {
                                $('.append-popup').empty();
                                $('.append-popup').append(res.view);
                                $('#popup-list-staff').modal('show');
                            } else {
                                swal('',res.message,'error');
                            }
                        },
                        error: function (res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                // if (e.target === $(this).find('.comment-button .fa-comments')[0]) ;

            });

            $(document).on('click', '.title-item-comment', function () {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var id = str.replace('kanban2_', '');
                var manage_project_id = $('#hidden_manage_project_id').val();
                // window.location.href = laroute.route('manager-project.work.detail', {id: id})
                window.open(laroute.route('manager-project.work.detail', {id: id, manage_project_id : manage_project_id}),'_blank')
            });
            /*
            cập nhật tiến độ
             */
            $(document).on('click', '.percentage-update', function () {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var mana_work_id = str.replace('kanban2_', '');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-project.work.load-form-update-process'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function (res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $('#comment-popup').modal('show');
                            } else {
//                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
            /*
            cập nhật ngày hết hạn
             */
            $(document).on('click', '.date-end-update', function () {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var mana_work_id = str.replace('kanban2_', '');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-project.work.load-form-update-date-end'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function (res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $(".time-input").timepicker({
                                    todayHighlight: !0,
                                    autoclose: !0,
                                    pickerPosition: "bottom-left",
                                    // format: "dd/mm/yyyy hh:ii",
                                    format: "HH:ii",
                                    defaultTime: "",
                                    showMeridian: false,
                                    minuteStep: 5,
                                    snapToStep: !0,
                                    // startDate : new Date()
                                    // locale: 'vi'
                                });

                                $(".daterange-input").datepicker({
                                    todayHighlight: !0,
                                    autoclose: !0,
                                    pickerPosition: "bottom-left",
                                    // format: "dd/mm/yyyy hh:ii",
                                    format: "dd/mm/yyyy",
                                    // startDate : new Date()
                                    // locale: 'vi'
                                });
                                $('#comment-popup').modal('show');
                            } else {
//                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
            $(document).on('submit', '#update_date_end', function () {
                console.log($('#update_date_end').serialize())
                var manage_work_id = $('#update_date_end [name="manage_work_id"]').val();
                if(manage_work_id){
                    $.ajax({
                        url: laroute.route('manager-project.work.edit-element-item'),
                        data: $('#update_date_end').serialize(),
                        method: "POST",
                        dataType: "JSON",
                        success: function (res) {
                            if (res.error == false) {
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function (res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $(document).on('submit', '#update_process', function () {
                var progress = $('#update_process [name="progress"]').val();
                var manage_work_id = $('#update_process [name="manage_work_id"]').val();
                if(manage_work_id){
                    $.ajax({
                        url: laroute.route('manager-project.work.edit-element-item'),
                        data: {
                            manage_work_id: manage_work_id,
                            progress: progress
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function (res) {
                            if (res.error == false) {
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function (res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $(document).on('submit', '#send_comment', function () {
                var code = $('#send_comment #description_comment').summernote('code');
                var manage_work_id = $('#send_comment #manage_work_id_comment').val();
                if(manage_work_id){
                    $.ajax({
                        url: laroute.route('manager-project.work.detail.add-comment'),
                        data: {
                            manage_work_id: manage_work_id,
                            description: code
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function (res) {
                            if (res.error == false) {
                                // $('.table-message-main > tbody').prepend(res.view);
                                // $('.description').summernote('code', '');
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function (res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function (a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $('#kanban2 .date-end-update').parent().css({"padding-left": "5px"});
            $('#kanban2 .date-end-update').parent().css({"overflow": "initial"});

            $('.jqx-kanban-column').css('width','400px');

            $('.jqx-kanban-column').click(function (){
                $('.jqx-kanban-column').each(function (i,obj){
                    var width = $(this).width();
                    if (width > 100){
                        $(this).css('width','400px');
                    }
                });

            });
        });
    </script>
@stop
