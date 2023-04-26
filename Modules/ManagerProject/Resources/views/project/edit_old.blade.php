@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> @lang('survey::survey.create.survey_manager')</span>
@stop
@section('after_style')
    <style>
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

        .kt-checkbox-fix {
            padding: 15px 15px;
        }

        .kt-checkbox-fix span {
            position: absolute;
            top: unset !important;
            bottom: -10px !important;
            left: 30px !important;
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
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{ __('CẬP NHẬT DỰ ÁN') }}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{ route('manager-project.project') }}" class="btn btn-secondary btn-search ml-2"
                    style="color:black; border:1px solid">
                    {{ __('HUỶ') }}
                </a>
                <button onclick="ProjectEdit.save()" type="button"
                    class="btn btn-primary color_button color_button_destroy  btn-search ml-2">
                    <i class="la la-check"></i>
                    {{ __('CẬP NHẬT THÔNG TIN') }}
                </button>
            </div>
        </div>
        <div class="kt-portlet kt-portlet--mobile">
            <form id="form-data" class="mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <!-- Tên dự án -->
                                <div class="col-lg-12">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Tên dự án') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" value="{{ $project->manage_project_name }}"
                                                   class="form-control m-input date-input" placeholder="{{ __('Nhập tên dự án') }}"
                                                   id="project_name" name="project_name">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Phòng ban trực thuộc') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="project_department" id="project_department"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ __('Chọn phòng ban trực thuộc') }}</option>
                                                @foreach ($listDepartment as $item)
                                                    <option
                                                            {{ $project->department_id == $item->department_id ? 'selected' : '' }}
                                                            value="{{ $item->department_id }}">
                                                        {{ $item->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tags -->
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <div class="form-group m-form__group">
                                            <label class="black_title">
                                                {{ __('Tags') }} :
                                            </label>
                                            <div class="input-group">
                                                <select name="manage_tags" multiple id="manage_tags"
                                                        class="form-control select2 select2-active">
                                                    <option value="">{{ __('Chọn tags') }}</option>
                                                    @foreach ($listTag as $item)
                                                        <option
                                                                {{ in_array($item->manage_tag_id, $listTagSelected) ? 'selected' : '' }}
                                                                value="{{ $item->manage_tag_id }}">{{ $item->manage_tag_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Loại khách hàng') }}
                                        </label>
                                        <div class="input-group">
                                            <select onchange="ProjectEdit.getCustomerDynamic(this)"
                                                    name="project_type_customer" id="project_type_customer"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ __('Chọn loại khách hàng') }}</option>
                                                <option {{ $project->customer_type == 'personal' ? 'selected' : '' }}
                                                        value="personal">{{ __('Cá nhân') }}</option>
                                                <option {{ $project->customer_type == 'business' ? 'selected' : '' }}
                                                        value="business">{{ __('Doanh nghiệp') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Khách hàng') }}
                                        </label>
                                        <div class="input-group">
                                            <select name="project_customer" id="project_customer"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ __('Chọn khách hàng') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Màu dự án') }} :
                                        </label>
                                        <div class="input-group">
                                            <input type="color" id="color_code" name="color_code"
                                                   class="bfh-colorpicker" data-name="colorpicker1" id="color_code"
                                                   value="{{ $project->color_code }}" placeholder="{{ __('Placehoder') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Ngày bắt đầu hoạt động') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group date date-multiple">
                                            <input type="text" class="form-control m-input date-input"
                                                   placeholder="{{ __('Chọn ngày') }}" id="date_start" name="date_start">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i
                                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Ngày kết thúc hoạt động') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group date date-multiple">
                                            <input type="text" class="form-control m-input date-input"
                                                   placeholder="{{ __('Chọn ngày') }}" id="date_end" name="date_end">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i
                                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <!-- Tên trạng thái -->
                                <div class="col-lg-12">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Trạng thái dự án') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="project_status" id="project_status"
                                                    class="form-control select2 select2-active">
                                                <option value="">{{ __('Chọn trạng thái') }}</option>
                                                @foreach ($listStatus as $item)
                                                    <option
                                                            {{ $project->manage_project_status_id == $item->manage_project_status_id ? 'selected' : '' }}
                                                            value="{{ $item->manage_project_status_id }}">
                                                        {{ $item->manage_project_status_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mô tả dự án -->
                                <div class="col-lg-12">
                                    <div class="m-form__group">
                                        <label class="black_title">
                                            {{ __('Mô tả dự án') }} :
                                        </label>
                                        <textarea name="description" id="description" class="form-control m-input summernote">{!! $project->manage_project_describe !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Quyền truy cập -->
                            <div class="col-lg-6">
                                <div class="form-group m-form__group mt-4">
                                    <label class="black_title">
                                        {{ __('Quyền truy cập') }} <b class="text-danger">*</b>
                                    </label>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <label class="m-radio cus">
                                                    <input type="radio" name="permission"
                                                           {{ $project->permission == 'private' ? 'checked' : '' }}
                                                           class="period_in_date_type period_in_date_type_unlimited"
                                                           value="private">
                                                    {{ __('Nội bộ') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <label class="m-radio cus">
                                                    <input type="radio" name="permission"
                                                           {{ $project->permission == 'public' ? 'checked' : '' }}
                                                           class="period_in_date_type period_in_date_type_unlimited"
                                                           value="public">
                                                    {{ __('Công khai') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Người quản trị, Phòng ban trực thuộc -->
                        <div class="col-lg-6">
                            <div class="row">
{{--                                <div class="col-lg-6">--}}
{{--                                    <div class="form-group m-form__group">--}}
{{--                                        <label class="black_title">--}}
{{--                                            {{ __('Người quản trị') }} <b class="text-danger">*</b>--}}
{{--                                        </label>--}}
{{--                                        <div class="input-group">--}}
{{--                                            <select name="project_manager" id="project_manager"--}}
{{--                                                class="form-control select2 select2-active">--}}
{{--                                                <option value="">{{ __('Chọn người quản trị') }}</option>--}}
{{--                                                @foreach ($listStaffs as $item)--}}
{{--                                                    <option {{ $project->manager_id == $item->staff_id ? 'selected' : '' }}--}}
{{--                                                        value="{{ $item->staff_id }}">{{ $item->full_name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>
                        </div>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@php
    $date_start = !empty($project->date_start) ? Carbon\Carbon::parse($project->date_start)->format('d/m/Y') : '';
    $date_end = !empty($project->date_end) ? Carbon\Carbon::parse($project->date_end)->format('d/m/Y') : '';
@endphp
@section('after_script')
    <script>
        const DATE_START = '{{ $date_start }}';
        const DATE_END = '{{ $date_end }}';
        const CUSTOMER = '{{ $project->customer_id }}';
        const PROJECT_ID = '{{ $project->manage_project_id }}';
        $('.numeric').mask('00', {
            reverse: true
        });
    </script>
    <script src="{{ asset('static/backend/js/manager-project/project/main.js?v=' . time()) }}"></script>
    <script src="{{ asset('static/backend/js/manager-project/project/edit.js?v=' . time()) }}"></script>

@endsection
