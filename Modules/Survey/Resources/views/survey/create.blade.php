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
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang('survey::survey.create.create')
                    </h2>
                </div>
            </div>
        </div>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div class="row form-group">
                    <div class="col-xl-12 col-lg-12">
                        <div class="btn-group btn-group" role="group" aria-label="...">
                            <button type="button"
                                class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">
                                @lang('Thông tin chung')
                            </button>
                            <a type="button" class="btn btn-secondary kt-padding-l-40 kt-padding-r-40"
                                href="javascript:void(0)">
                                @lang('survey::survey.create.question_survey')
                            </a>
                            <a href="javascript:void(0)" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                @lang('survey::survey.create.object_apply')
                            </a>
                            <a href="javascript:void(0)" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                @lang('survey::survey.create.report')
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <form id="form-data" action="" style="margin-bottom:100px" method="POST">
                <div class="m-portlet__body">
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12 mt-2">
                            <div class="kt-portlet__head-label">
                                <h5 class="kt-portlet__head-title fw_title">
                                    @lang('Thông tin chung') </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xl-6 col-lg-6">
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('survey::survey.create.name_survey')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control" id="survey_name" name="survey_name" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('survey::survey.create.code_survey')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control" id="survey_code" name="survey_code" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('survey::survey.create.description')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <textarea name="survey_description" class="form-control" id="survey_description" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('survey::survey.create.status_display')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <select disabled name="status" id="status" class="form-control">
                                        <option value="N">
                                            @lang('survey::survey.create.status_draft')
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('survey::survey.create.date_close_program')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <input readonly class="form-control" id="close_date" name="close_date" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('Banner')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <div class="kt-avatar kt-avatar--outline" id="kt_user_add_avatar">
                                        <div id="image-survey-banner">
                                            <div class="kt-avatar__holder"
                                                style="background-image: url('https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/2d31780a0108715b3fa530aaaaa99bda/2022/08/23/NJYBU5166121736723082022_survey.png');
                                                             background-position: center;">
                                            </div>
                                        </div>
                                        <input type="hidden" id="survey_banner" name="survey_banner"
                                            value="https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/2d31780a0108715b3fa530aaaaa99bda/2022/08/23/NJYBU5166121736723082022_survey.png">
                                        <label class="kt-avatar__upload" data-toggle="kt-tooltip" title=""
                                            data-original-title="">
                                            <i class="fa fa-pen"></i>
                                            <input type="file" id="getFileSurveyBanner" name="getFileSurveyBanner"
                                                accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                                onchange="survey.upload(this);">
                                        </label>
                                        <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title=""
                                            data-original-title="">
                                            <i class="fa fa-times"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__head-label mb-5">
                        <div class="m-form__group form-group row">
                            <div class="col-12 mb-3">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm d-flex"
                                    style="align-items: center; gap:50px; font-weight: 400;">
                                    {{ __('Public link khảo sát') }}
                                    <label style="margin: 0 0 0 10px;">
                                        <input type="checkbox" id="public_link" class="manager-btn receipt_info_check">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-12 mb-3">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm d-flex"
                                    style="align-items: center; gap:35px; font-weight: 400;">
                                    {{ __('Khảo sát có tính điểm') }}
                                    <label style="margin: 0 0 0 10px;">
                                        <input type="checkbox" id="count_point" class="manager-btn receipt_info_check">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="kt-portlet__head-label mb-5">
                        <h5 class="kt-portlet__head-title fw_title">
                            @lang('survey::survey.create.setting_time_survey')
                        </h5>
                    </div>
                    <div class="m-form__group form-group row">
                        <label class="col-xl-3 col-lg-3">
                            @lang('survey::survey.create.effective_time')
                        </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="m-radio cus">
                                        <input type="radio" name="is_exec_time" value="0" checked="checked">
                                        @lang('survey::survey.create.unknown')
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-3">
                                            <label class="m-radio cus">
                                                <input type="radio" name="is_exec_time" value="1">
                                                @lang('survey::survey.create.time_limit')
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-3 d-flex justify-content-center align-items-center">
                                            <div class="input-group date">
                                                <input type="text" disabled readonly class="form-control m-input"
                                                    id="start_date" placeholder="@lang('survey::survey.create.time_start')" name="start_date">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i
                                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 ml-5">
                                            <div class="input-group date">
                                                <input type="text" disabled readonly class="form-control m-input"
                                                    placeholder="@lang('survey::survey.create.time_end')" id="end_date" name="end_date">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i
                                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-form__group form-group row">
                        <label class="col-xl-3 col-lg-3">
                            @lang('survey::survey.create.frequency')
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="m-radio cus">
                                        <input type="radio" name="frequency" class="daily frequency" value="daily"
                                            checked="checked"> @lang('survey::survey.create.daily')
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="m-radio cus">
                                        <input type="radio" name="frequency" class="weekly frequency" value="weekly">
                                        @lang('survey::survey.create.weekly')
                                        <span></span>
                                    </label>
                                    <div class="row">
                                        <div class="col-12 ml-5 frequency_weekly mb-2 mt-3">
                                            <label class="m-radio cus pl-5">
                                                <input type="radio" name="frequency_weekly" class="weekly weekly_type"
                                                    value="all_frequency_weekly">
                                                @lang('survey::survey.create.all_day_on_week')
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-12 frequency_weekly ml-5 mb-4">
                                            <label class="m-radio cus pl-5">
                                                <input type="radio" checked="checked" name="frequency_weekly"
                                                    class="weekly weekly_type" value="frequency_weekly">
                                                @lang('survey::survey.create.repeat')
                                                <span></span>
                                            </label>
                                            <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                <input type="checkbox" value="1" name="frequency_value_weekly[]"
                                                    disabled="" class="frequency_value_weekly"> @lang('survey::survey.create.Mon')
                                                <span></span>
                                            </label>
                                            <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                <input type="checkbox" value="2" name="frequency_value_weekly[]"
                                                    disabled="" class="frequency_value_weekly"> @lang('survey::survey.create.Tue')
                                                <span></span>
                                            </label>
                                            <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                <input type="checkbox" value="3" name="frequency_value_weekly[]"
                                                    disabled="" class="frequency_value_weekly"> @lang('survey::survey.create.Wed')
                                                <span></span>
                                            </label>
                                            <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                <input type="checkbox" value="4" name="frequency_value_weekly[]"
                                                    disabled="" class="frequency_value_weekly"> @lang('survey::survey.create.Thu')
                                                <span></span>
                                            </label>
                                            <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                <input type="checkbox" value="5" name="frequency_value_weekly[]"
                                                    disabled="" class="frequency_value_weekly"> @lang('survey::survey.create.Fri')
                                                <span></span>
                                            </label>
                                            <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                <input type="checkbox" value="6" name="frequency_value_weekly[]"
                                                    disabled="" class="frequency_value_weekly"> @lang('survey::survey.create.Sat')
                                                <span></span>
                                            </label>
                                            <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                <input type="checkbox" value="0" name="frequency_value_weekly[]"
                                                    disabled="" class="frequency_value_weekly"> @lang('survey::survey.create.Sun')
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="m-radio cus">
                                        <input type="radio" name="frequency" class="monthly frequency"
                                            value="monthly">
                                        @lang('survey::survey.create.monthly') <span></span>
                                    </label>
                                    <div class="row">
                                        <div class="col-12 frequency_monthly pl-5">
                                            <div class="row">
                                                <div class="col-3">
                                                    <label class="pl-5">
                                                        @lang('survey::survey.create.repeat_in_month')
                                                    </label>
                                                </div>
                                                <div class="col-9">
                                                    <div class="row">
                                                        <div class="col-4 col-lg-3">
                                                            <div class="row">
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="1"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.january')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="2"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.february')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="3"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.march')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="4"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.april')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-4 col-lg-3">
                                                            <div class="row">
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="5"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.may')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="6"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.june')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="7"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.july')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="8"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.august')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4 col-lg-3">
                                                            <div class="row">
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="9"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.september')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="10"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.october')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="11"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.november')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="12"
                                                                            name="frequency_value_monthly[]"
                                                                            disabled=""
                                                                            class="frequency_value_monthly">
                                                                        @lang('survey::survey.create.december')
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 frequency_monthly pl-5">
                                            <div class="row">
                                                <div class="col-10 offset-1 mb-3">
                                                    <label class="m-radio cus">
                                                        <input type="radio" name="frequency_monthly_type"
                                                            class="frequency_monthly_type" disabled=""
                                                            value="day_in_month"> @lang('survey::survey.create.date_in_month')
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <div class="col-10 offset-1">
                                                    <div class="row">
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="1"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 1
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="2"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 2
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="3"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 3
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="4"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 4
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="5"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 5
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="6"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 6
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="7"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 7
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="8"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 8
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="9"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 9
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="10"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 10
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="11"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 11
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="12"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 12
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="13"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 13
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="14"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 14
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="15"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 15
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="16"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 16
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="17"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 17
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="18"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 18
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="19"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 19
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="20"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 20
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="21"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 21
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="22"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 22
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="23"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 23
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="24"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 24
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="25"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 25
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="26"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 26
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="27"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 27
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="28"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 28
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="29"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 29
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="30"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class="day_in_monthly"> 30
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-4">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="31"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class=" day_in_monthly"> 31
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="-1"
                                                                            name="day_in_monthly[]" disabled=""
                                                                            class=" day_in_monthly"> Last
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 frequency_monthly pl-5 pt-3">
                                            <div class="row">
                                                <div class="col-12 offset-1 mb-3">
                                                    <div class="row" style="gap:20px">
                                                        <div class="col-12">

                                                            <label class="m-radio cus mb-3 mt-3">
                                                                <input type="radio" name="frequency_monthly_type"
                                                                    class="frequency_monthly_type" disabled=""
                                                                    value="day_in_week">@lang('survey::survey.create.date_in_week')
                                                                <span></span>
                                                            </label>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <div class="col-2 ml-4 pl-4">
                                                                            <label>@lang('survey::survey.create.repeat_in_week'): </label>
                                                                        </div>
                                                                        <div class="col-9">
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="0"
                                                                                    name="day_in_week[]" disabled=""
                                                                                    class="day_in_week"> @lang('survey::survey.create.weeken')
                                                                                1
                                                                                <span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="1"
                                                                                    name="day_in_week[]" disabled=""
                                                                                    class="day_in_week"> @lang('survey::survey.create.weeken')
                                                                                2
                                                                                <span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="2"
                                                                                    name="day_in_week[]" disabled=""
                                                                                    class="day_in_week"> @lang('survey::survey.create.weeken')
                                                                                3
                                                                                <span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="3"
                                                                                    name="day_in_week[]" disabled=""
                                                                                    class="day_in_week"> @lang('survey::survey.create.weeken')
                                                                                4
                                                                                <span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="-1"
                                                                                    name="day_in_week[]" disabled=""
                                                                                    class="day_in_week"> @lang('survey::survey.create.last_weeken')
                                                                                <span></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mt-5 mb-5">
                                                            <div class="row">

                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <div class="col-2 ml-4 pl-4">
                                                                            <label>@lang('survey::survey.create.repeat_on_things'):</label>
                                                                        </div>
                                                                        <div class="col-9">
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="0"
                                                                                    name="day_in_week_repeat[]"
                                                                                    disabled=""
                                                                                    class=" day_in_week_repeat">
                                                                                @lang('survey::survey.create.Mon') <span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="1"
                                                                                    name="day_in_week_repeat[]"
                                                                                    disabled=""
                                                                                    class=" day_in_week_repeat">@lang('survey::survey.create.Tue')
                                                                                <span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="2"
                                                                                    name="day_in_week_repeat[]"
                                                                                    disabled=""
                                                                                    class=" day_in_week_repeat">
                                                                                @lang('survey::survey.create.Wed') <span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="3"
                                                                                    name="day_in_week_repeat[]"
                                                                                    disabled=""
                                                                                    class=" day_in_week_repeat">@lang('survey::survey.create.Thu')<span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="4"
                                                                                    name="day_in_week_repeat[]"
                                                                                    disabled=""
                                                                                    class=" day_in_week_repeat">@lang('survey::survey.create.Fri')<span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="5"
                                                                                    name="day_in_week_repeat[]"
                                                                                    disabled=""
                                                                                    class=" day_in_week_repeat">@lang('survey::survey.create.Sat')<span></span>
                                                                            </label>
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="6"
                                                                                    name="day_in_week_repeat[]"
                                                                                    disabled=""
                                                                                    class=" day_in_week_repeat">@lang('survey::survey.create.Sun')<span></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 mb-3 mt-3">
                                                    <label>@lang('survey::survey.create.time_in_day')</label>
                                                </div>
                                                <div class="col-10 pl-5">
                                                    <div class="kt-radio-list">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <label class="m-radio cus">
                                                                            <input type="radio"
                                                                                name="is_limit_exec_time" checked=""
                                                                                class="period_in_date_type period_in_date_type_unlimited"
                                                                                value="0"> @lang('survey::survey.create.no_limit')
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-5" style="margin-top:10px">
                                                                        <label class="m-radio cus">
                                                                            <input type="radio"
                                                                                name="is_limit_exec_time"
                                                                                class="period_in_date_type period_in_date_type_limited"
                                                                                value="1"> @lang('survey::survey.create.limit')
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                    <div
                                                                        class="col-7 d-flex justify-content-center align-items-center">
                                                                        <div class=" form-group input-group date"
                                                                            style="margin-right:30px">
                                                                            <input type="text" disabled readonly
                                                                                class="form-control m-input"
                                                                                id="exec_time_from"
                                                                                placeholder="@lang('survey::survey.create.time_start')"
                                                                                name="exec_time_from">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text"><i
                                                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                                                            </div>
                                                                        </div>

                                                                        <div class=" form-group input-group date">
                                                                            <input type="text" disabled readonly
                                                                                class="form-control m-input"
                                                                                id="exec_time_to"
                                                                                placeholder="@lang('survey::survey.create.time_end')"
                                                                                name="exec_time_to">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text"><i
                                                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__head-label mb-5">
                        <h5 class="kt-portlet__head-title fw_title">
                            @lang('survey::survey.create.setting_limit_survey')
                        </h5>
                    </div>
                    <div class="form-group row div_setting_turns">
                        <label class="col-xl-3 col-lg-3">
                            @lang('survey::survey.create.number_of_survey_on_person')
                        </label>
                        <div class="col-lg-6 col-xl-6">
                            <div class="kt-radio-list">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-5">
                                                <label class="m-radio cus">
                                                    <input checked type="radio" class="config_turn" name="config_turn"
                                                        value="0"> @lang('survey::survey.create.no_limit')
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-5">
                                                <label class="m-radio cus">
                                                    <input type="radio" class="config_turn" name="config_turn"
                                                        value="1"> @lang('survey::survey.create.number_of_survey_on_person')
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div class="col-3">
                                                <input disabled type="text" placeholder="@lang('survey::survey.create.number_survey')"
                                                    class="form-control numeric" name="max_times" id="max_times">
                                            </div>
                                            <div class="col-3 kt-margin-t-10">
                                                <span class="kt-margin-t-10">@lang('survey::survey.create.survey')</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row form-group">
                <div class="col-xl-12 col-lg-12 d-flex justify-content-end pr-5">
                    <button type="button" class="btn btn-secondary  kt-padding-l-40 kt-padding-r-40 mr-5"
                        onclick="survey.back()">
                        <i class="la la-arrow-left"></i>
                        {{ __('HUỶ') }}

                    </button>
                    <button type="button" class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40"
                        onclick="survey.store()">
                        <i class="la la-check"></i>
                        {{ __('LƯU') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_script')
    <script type="text/template" id="image-tpl">
        <div class="kt-avatar__holder" style="background-image: url({link});background-position: center;"></div></script>
    <script src="{{ asset('static/backend/js/jquery.mask.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/survey/create.js?v=' . time()) }}" type="text/javascript"></script>
@endsection
