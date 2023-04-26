@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section('after_style')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
{{--    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
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

        .table-content-font-a a {
            font-size: 15px;
        }
        .m-portlet--head-sm {
            margin-bottom: 5px !important;
        }

        .kt-portlet--mobile {
            margin: 0 !important;
        }

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
        .number-status{
            font-size: 35px
        }
        .column-status {
            float: left;
            width: 25%;
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
{{--    <div class="m-portlet m-portlet--head-sm">--}}
{{--        <div class="m-portlet__head">--}}
{{--            <div class="m-portlet__head-caption">--}}
{{--                <div class="m-portlet__head-title">--}}
{{--                    <span class="m-portlet__head-icon">--}}
{{--                        <i class="la la-eye"></i>--}}
{{--                    </span>--}}
{{--                    <h2 class="m-portlet__head-text">--}}
{{--                        {{ isset($project) ? $project->manage_project_name : '' }}--}}
{{--                    </h2>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="m-portlet__head-tools">--}}
{{--                <a href="{{ route('manager-project.project') }}" class="btn btn-secondary btn-search ml-2"--}}
{{--                   style="color:black; border:1px solid">--}}
{{--                    <span class="m-portlet__head-icon">--}}
{{--                        <i class="la la-arrow-left"></i>--}}
{{--                    </span>--}}
{{--                    {{ __('TRỞ VỀ') }}--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    @include('manager-project::tab-project',['manage_project_id' => isset($project) ? $project->manage_project_id : 0])--}}

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
    <div class="m-portlet" >
        <div class="m-portlet__head m-portlet__head-update">
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
{{--    <div style="padding-top: 10px;float:right">--}}
{{--        <div class="m-portlet__head-tools">--}}
{{--            <a href="javascript:void(0)" onclick="ManagerWork.configSearch()"--}}
{{--               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">--}}
{{--                        <span>--}}
{{--                            <i class="fa fa-plus-circle"></i>--}}
{{--                            <span> {{ __('Tùy chỉnh hiển thị') }}</span>--}}
{{--                        </span>--}}
{{--            </a>--}}
{{--            <a href=""--}}

{{--               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">--}}
{{--                        <span>--}}
{{--                            <i class="fa fa-plus-circle"></i>--}}
{{--                            <span> {{ __('Danh sách') }}</span>--}}
{{--                        </span>--}}
{{--            </a>--}}
{{--            <a href="javascript:void(0)" onclick="ManagerWork.exportList()"--}}

{{--               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">--}}
{{--                        <span>--}}
{{--                            <i class="fa fa-plus-circle"></i>--}}
{{--                            <span> {{ __('Xuất dữ liệu') }}</span>--}}
{{--                        </span>--}}
{{--            </a>--}}
{{--            <a href="javascript:void(0)" onclick="WorkChild.showPopup()"--}}
{{--               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">--}}
{{--                        <span>--}}
{{--                            <i class="fa fa-plus-circle"></i>--}}
{{--                            <span> {{ __('Thêm công việc') }}</span>--}}
{{--                        </span>--}}
{{--            </a>--}}
{{--            <a href="{{route('fnb.areas')}}" data-toggle="modal" data-target="#modalAdd"--}}
{{--               class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill--}}
{{--                                     color_button btn_add_mobile"--}}
{{--               style="display: none">--}}
{{--                <i class="fa fa-plus-circle" style="color: #fff"></i>--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}

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
                <a href="{{route('manager-project.work.kanban-view',['manage_project_id' => isset($project) ? $project->manage_project_id : 0])}}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-cog"></i>
                        <span> {{ __('KANBAN VIEW') }}</span>
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
                {{--                <a href="javascript:void(0)" onclick="ManagerWork.showAdd()"--}}
                @if(!in_array($project['manage_project_status_group_config_id'],[3,4]) && in_array(\Auth::id(),$listStaffProject))
                    <a href="javascript:void(0)" onclick="WorkChild.showPopup()"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
                            <i class="fa fa-plus-circle"></i>
                            <span> {{ __('managerwork::managerwork.add_work') }}</span>
                        </span>
                    </a>
                @endif
                <a href="javascript:void(0)" data-toggle="modal" data-target="#modalAdd" onclick="ManagerWork.clear()"
                   class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                   style="display: none">
                    <i class="fa fa-plus-circle" style="color: #fff"></i>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <form method="get" id="frm_export" action="{{route('manager-project.work.export')}}" style="display: none">

            </form>
            <form id="frm-search" class="frmFilter bg clear-form">
                <div class="row padding_row">
                    <input type="hidden" name="manage_project_id" value="{{isset($filter['manage_project_id']) ? $filter['manage_project_id'] : ''}}">
                    <input type="hidden" name="manage_project_phase_id" id="main_manage_project_phase_id" value="">
                    @foreach ($searchConfig as $config)
                        @if ($config['active'])
                            @if ($config['type'] == 'select2')
                                @if ($config['name'] == 'customer_id')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="manage_work_customer_type" id="manage_work_customer_type_list"
                                                    onchange="WorkAll.changeCustomerList()"
                                                    class="form-control select2 select2-active">
                                                <option value="customer">{{ __('managerwork::managerwork.customer') }}</option>
                                                <option value="lead">{{ __('managerwork::managerwork.lead') }}</option>
                                                <option value="deal">{{ __('managerwork::managerwork.deal') }}</option>
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
                                                    <option value="{{ $key }}" {{isset($filter['processor_id']) && $filter['processor_id'] == $key ? 'selected' : ''}}>{{ $value }}</option>
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
                                                            {{isset($filter['department_id']) && $filter['department_id'] == $key ? 'selected' : ''}}>{{ $value }}</option>
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
{{--                                                            {{isset($filter['manage_project_id']) && $filter['manage_project_id'] == $key ? 'selected' : ''}}>{{ $value }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <input type="hidden" name="manage_project_id" value="{{isset($filter['manage_project_id']) ? $filter['manage_project_id'] : ''}}">
                                @elseif ($config['name'] == 'manage_status_id')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="{{ $config['name'] }}[]"
                                                    class="form-control select2 select2-active" multiple>
                                                <option value="">{{ $config['placeholder'] }}</option>
                                                {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                @foreach ($config['data'] as $key => $value)

                                                    <option value="{{ $key }}" {{isset($filter['manage_status_id']) && in_array($key,$filter['manage_status_id']) ? 'selected' : ''}}>{{ $value }}</option>
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
                                                {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                @foreach ($config['data'] as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
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
                                                       value="{{isset($filter['date_end']) ? $filter['date_end'] : ''}}"
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
                                                       value="{{isset($filter['created_at']) ? $filter['created_at'] : ''}}"
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
                                                   value="{{isset($filter['date_end']) ? $filter['date_end'] : ''}}"
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
                                                   value="{{isset($filter['date_start']) ? $filter['date_start'] : ''}}"
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
                                                   value="{{isset($filter['created_at']) ? $filter['created_at'] : ''}}"
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
                                        <input type="text" class="form-control" name="{{ $config['name'] }}"
                                               placeholder="{{ $config['placeholder'] }}">
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    @if(isset($filter['type-search']))
                        <input type="hidden" name="type-search" value="{{$filter['type-search']}}">
                    @endif
                    @if(isset($filter['type-page']))
                        <input type="hidden" name="type-page" value="{{$filter['type-page']}}">
                    @endif

                    <div class="col-lg-3 mb-3">
                        <input type="hidden" name="manage_project_id" value="{{ $filter['manage_project_id'] ?? '' }}">
                        <a href="{{route('manager-project.work',['manage_project_id' => $filter['manage_project_id']])}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
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
            <div class="table-content table-content-font-a mt-3">
                @include('manager-project::work.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <div class="modal fade" id="my_modal" role="dialog">

    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            {{--            @include('manager-project::work.add')--}}
        </div>
    </div>
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            {{--            @include('manager-project::work.edit')--}}
        </div>
    </div>
    <div class="modal fade" id="modalView" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            {{--            @include('manager-project::work.view')--}}
        </div>
    </div>
    <div class="modal fade" id="modalRemind" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            {{--            @include('manager-project::work.popup.remind')--}}
        </div>
    </div>
    <div class="modal fade" id="modalRepeatNotification" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            {{--            @include('manager-project::work.popup.repeat')--}}
        </div>
    </div>
    <div class="d-none" id="date-single">
        <div class="input-group date date-single">
            <input type="text" class="form-control m-input date-timepicker" readonly
                   placeholder="@lang('Ngày hết hạn')" name="date_issue">
            <div class="input-group-append">
                <span class="input-group-text"><i
                            class="la la-calendar-check-o glyphicon-th"></i></span>
            </div>
        </div>
    </div>
    <div class="d-none" id="date-multiple">
        <div class="input-group date date-multiple">
            <input type="text" class="form-control m-input daterange-input" readonly
                   placeholder="@lang('Ngày hết hạn')" name="date_issue">
            <div class="input-group-append">
                <span class="input-group-text"><i
                            class="la la-calendar-check-o glyphicon-th"></i></span>
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
                        <span>{{ isset(Auth::user()->full_name) ? Auth::user()->full_name : '' }} {{__('tạo nhắc nhở cho')}}
                            {processor_name}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-1 d-flex align-items-center text-right">
                <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-custom-remind-item"
                        title="{{__('Xóa')}}"><i class="la la-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{ asset('static\backend/js/manager-project/managerWork/table-excel/jquery.table2excel.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script>
        $('.btn-search').trigger('click');
    </script>
@stop
