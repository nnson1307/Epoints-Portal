@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
            style="height: 20px;">
        {{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section('after_style')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phu-custom.css') }}">--}}
<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
    <style>
        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .ss--text-center {
            vertical-align: middle !important;
        }

        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .ss--text-center {
            vertical-align: middle !important;
        }

        .kt-radio.kt-radio--brand.kt-radio--bold>input:checked~span {
            border: 2px solid #000000 !important;
        }

        .kt-avatar.kt-avatar--circle .kt-avatar__holder {
            border-radius: 0% !important;
        }

        .ss--kt-avatar__upload {
            width: 20px !important;
            height: 20px !important;
        }

        .kt-checkbox.kt-checkbox--bold>input:checked~span {
            background: #4FC4CA;
            border: 2px solid #4FC4CA !important;
            border-radius: 3px !important;
        }

        .kt-checkbox>span:after {
            border: solid #fff;
        }

        .kt-radio.kt-radio--bold>input:checked~span {
            border: 2px solid #4FC4CA;
        }

        .kt-radio>span:after {
            border: solid #027177;
            background: #027177;
            margin-left: -4px;
            margin-top: -4px;
            width: 8px;
            height: 8px;
        }

        .primary-color {
            color: #027177 !important;
            font-weight: 500;
        }

        .form-control-feedback {
            color: red;
        }

        .kt-checkbox-fix span {}

        .m-radio>span:after {
            background: #4FC4CA !important;
            border: 1px solid #4FC4CA !important;
        }

        .m-radio>span {
            border: 1px solid #4FC4CA !important;
        }


        .kt-checkbox.kt-checkbox--bold span {
            border: 1px solid #4FC4CA;
        }

        .fw_title {
            font-weight: bold !important;
            color: #000000;
            font-size: 18px;
        }

        .m-portlet__head-text {
            font-weight: bold !important;
        }

        .form-control-feedback {
            font-weight: 400 !important;
            padding: 5px 0px !important;
        }

        .project-detail__tab {
            color: black;
        }

        .m-portlet--head-sm {
            margin-bottom: 5px !important;
        }

        .kt-portlet--mobile {
            margin: 0 !important;
        }

        #project-member__add label,
        #member-detail label,
        #member-edit label
         {
            color: black !important;
        }

        #project-member__add .text_required {
            color: red;

        }

        .item_project--role {
            display: flex;
            gap: 30px
        }

        .item_project--role .title_role {
            color: black;
        }

        .list_project--role {
            display: flex;
            gap: 20px;
        }

        .info-user {
            width: 40px;
            height: 40px;
            border: 50%;
            border-radius: 50$;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 5px;
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
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array(\Auth::id(),$listStaffProject))
                    <button onclick="member.showModalAdd({{isset($project) ? $project->manage_project_id : 0}})"
                            class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span style="font-weight:400; font-size:12px;">{{ __('Thêm thành viên') }}</span>
                    </span>
                    </button>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="frmFilter ss--background m--margin-bottom-30">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <select id="department" class="form-control select2 select2-active">
                                        <option value="">{{ __('Phòng ban') }}</option>
                                        @foreach ($departments as $item)
                                            <option value="{{ $item->department_id }}" {{isset($param['department_id']) && $param['department_id'] == $item->department_id ? 'selected' : '' }}>{{ $item->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select id="staff" class="form-control select2 select2-active">
                                        <option value="">{{ __('Nhân viên') }}</option>
                                        @foreach ($listStaff as $item)
                                            <option value="{{ $item->staff_id }}">{{ $item->full_name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-lg-3 form-group">
                                    <select id="role" class="form-control select2 select2-active">
                                        <option value="">{{ __('Vai trò') }}</option>
                                        @foreach ($listRole as $item)
                                            <option value="{{ $item->manage_project_role_id }}">
                                                {{ $item->manage_project_role_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="d-flex">
                                        <button onclick="member.reset()"
                                            class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                            {{ __('XÓA BỘ LỌC') }}
                                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                        </button>
                                        <button onclick="member.search()" class="btn ss--button-cms-piospa m-btn--icon">
                                            {{ __('TÌM KIẾM') }}
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="text" hidden id="project_id" value="{{ $project->manage_project_id }}">
            <div class="table-content">

            </div><!-- end table-content -->
        </div>
    </div>
    <!-- Modal add !-->
    <div id="modal">
        @include('manager-work::project.member.add')
    </div>
    <!-- Modal show !-->
    <div id="modal-action__show">

    </div>
    <!-- Modal edit !-->
    <div id="modal-action__edit">

    </div>
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/manager-project/member/main.js?v=' . time()) }}" type="text/javascript">
    </script>
@stop
