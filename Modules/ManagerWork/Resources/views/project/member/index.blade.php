@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
            style="height: 20px;">
        {{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section('after_style')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phu-custom.css') }}">

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

        .m-portlet__body {
            color
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
    </style>
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-eye"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{ $project->manage_project_name }}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{ route('manager-work.project') }}" class="btn btn-secondary btn-search ml-2"
                    style="color:black; border:1px solid">
                    <span class="m-portlet__head-icon">
                        <i class="la la-arrow-left"></i>
                    </span>
                    {{ __('TRỞ VỀ') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Danh sách tab !-->
{{--    <div class="m-portlet m-portlet--head-sm">--}}
{{--        <div class="kt-portlet kt-portlet--mobile">--}}
{{--            <div class="kt-portlet__body">--}}
{{--                <div class="row form-group" style="margin:0px">--}}
{{--                    <div class="col-xl-12 col-lg-12">--}}
{{--                        <div class="btn-group btn-group project-detail__tab" role="group" aria-label="...">--}}
{{--                            <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Thông tin dự án') }}--}}
{{--                            </a>--}}

{{--                            <a type="button" class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Danh sách công việc') }}--}}
{{--                            </a>--}}
{{--                            <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Biểu đồ Gantt') }}--}}
{{--                            </a>--}}
{{--                            <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Tài liệu') }}--}}
{{--                            </a>--}}
{{--                            <button type="button"--}}
{{--                                class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Thành viên') }}--}}
{{--                            </button>--}}
{{--                            <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Cấu hình vai trò') }}--}}
{{--                            </a>--}}
{{--                            <a class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">--}}
{{--                                {{ __('Lịch sử hoạt động') }}--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    @include('manager-project::tab-project',['manage_project_id' => isset($project) ? $project->manage_project_id : 0])
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
    <script src="{{ asset('static/backend/js/manager-work/project/member/main.js?v=' . time()) }}" type="text/javascript">
    </script>
@stop
