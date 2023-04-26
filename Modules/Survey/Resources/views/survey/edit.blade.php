@php
if (isset($detail['frequency']) && $detail['frequency'] == 'weekly') {
    $isCheckedWeeklyType = count($detail['frequency_value']) == 7 ? 'all' : 'everyday';
}
@endphp
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
            border: 2px solid #027177;
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
            padding: 5px 15px;
        }

        .kt-checkbox-fix span {
            position: absolute;
            top: unset !important;
            bottom: -20px !important;
            left: 30px !important;
        }

        .m-portlet__body {
            color
        }

        .primary-color {
            color: #575962 !important;
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
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="m-portlet  m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text" style="font-weight: bold;">
                        {{ __('CẬP NHẬT KHẢO SÁT') }}
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
                                href="{{ route('survey.show-question', [$detail['survey_id']]) }}">
                                @lang('survey::survey.create.question_survey')
                            </a>
                            <a href="{{ route('survey.show-branch', [$detail['survey_id']]) }}" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                @lang('survey::survey.create.object_apply')
                            </a>
                            <a href="{{ route('survey.report', [$detail['survey_id']]) }}" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                @lang('survey::survey.create.report')
                            </a>
                        </div>
                    </div>
                </div>
                <form id="form-data" action="" method="POST">
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12 mt-2">
                            <div class="kt-portlet__head-label">
                                <h5 class="kt-portlet__head-title fw_title">
                                    @lang('Thông tin chung') </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group primary-color">
                        <div class="col-xl-6 col-lg-6">
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('survey::survey.create.name_survey')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control" id="survey_name" name="survey_name" type="text"
                                        value="{{ $detail['survey_name'] }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('survey::survey.create.code_survey')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control" id="survey_code" name="survey_code" type="text"
                                        value="{{ $detail['survey_code'] }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    @lang('survey::survey.create.description')
                                </label>
                                <div class="col-lg-9 col-xl-9">
                                    <textarea name="survey_description" class="form-control" id="survey_description" cols="30" rows="5">{{ $detail['survey_description'] }}</textarea>
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
                                                style="background-image: url('{{ $detail['survey_banner'] }}');
                                                         background-position: center;">
                                            </div>
                                        </div>
                                        <input type="hidden" id="survey_banner" name="survey_banner"
                                            value="{{ $detail['survey_banner'] }}">
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
                                    style="align-items: center; gap:50px; font-weight: 400; color:#000000ad;">
                                    {{ __('Public link khảo sát') }}
                                    <label style="margin: 0 0 0 10px;">
                                        <input type="checkbox" id="public_link"
                                            {{ $detail['is_short_link'] ? 'checked' : '' }}
                                            class="manager-btn receipt_info_check">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-12 mb-3">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm d-flex"
                                    style="align-items: center; gap:35px; font-weight: 400; color:#000000ad;">
                                    {{ __('Khảo sát có tính điểm') }}
                                    <label style="margin: 0 0 0 10px;">
                                        <input type="checkbox" id="count_point"
                                            {{ $detail['count_point'] ? 'checked' : '' }}
                                            class="manager-btn receipt_info_check">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="kt-portlet__head-label primary-color mb-5">
                        <h5 class="kt-portlet__head-title fw_title">
                            @lang('survey::survey.create.setting_time_survey')
                        </h5>
                    </div>
                    <div class="form-group row primary-color">
                        <label class="col-xl-3 col-lg-3">
                            @lang('survey::survey.create.effective_time')
                        </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="m-radio cus primary-color">
                                        <input type="radio" name="is_exec_time" value="0"
                                            {{ $detail['start_date'] ? '' : 'checked' }}>
                                        @lang('survey::survey.create.unknown')
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-3">
                                            <label class="m-radio cus primary-color">
                                                <input type="radio" name="is_exec_time" value="1"
                                                    {{ $detail['start_date'] ? 'checked' : '' }}>
                                                @lang('survey::survey.create.time_limit')
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <div class="input-group date">
                                                <input type="text" {{ $detail['start_date'] ? '' : 'disabled' }}
                                                    readonly class="form-control m-input" id="start_date"
                                                    placeholder="@lang('survey::survey.create.time_start')" value="" name="start_date">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i
                                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 ml-5">
                                            <div class="input-group date">
                                                <input type="text" {{ $detail['start_date'] ? '' : 'disabled' }}
                                                    readonly class="form-control m-input" id="end_date"
                                                    placeholder="@lang('survey::survey.create.time_end')" value="" name="end_date">
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
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 primary-color">
                            @lang('survey::survey.create.frequency')
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="m-radio cus primary-color">
                                        <input type="radio" name="frequency" class="daily frequency" value="daily"
                                            {{ $detail['frequency'] == 'daily' ? 'checked' : '' }}> @lang('survey::survey.create.daily')
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-12">
                                    <label class="m-radio cus primary-color">
                                        <input type="radio" name="frequency" class="weekly frequency" value="weekly"
                                            {{ $detail['frequency'] == 'weekly' ? 'checked' : '' }}>
                                        @lang('survey::survey.create.weekly')
                                        <span></span>
                                    </label>
                                    <div class="row mb-2 mt-3">
                                        <div class="row">
                                            <div class="col-12 ml-5 frequency_weekly">
                                                <label class="m-radio cus pl-5">
                                                    <input type="radio" name="frequency_weekly"
                                                        {{ isset($isCheckedWeeklyType) && $isCheckedWeeklyType == 'all' ? 'checked' : '' }}
                                                        class="weekly  weekly_type" value="all_frequency_weekly">
                                                    @lang('survey::survey.create.all_day_on_week')
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div class="col-12 frequency_weekly ml-5 mb-4 primary-color">
                                                <label class="m-radio cus pl-5">
                                                    <input type="radio" name="frequency_weekly"
                                                        {{ isset($isCheckedWeeklyType) && $isCheckedWeeklyType == 'everyday' ? 'checked' : '' }}
                                                        class="weekly  weekly_type" value="frequency_weekly">
                                                    @lang('survey::survey.create.repeat')
                                                    <span></span>
                                                </label>
                                                @for ($i = 1; $i <= 6; $i++)
                                                    <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                        <input type="checkbox" value="{{ $i }}"
                                                            name="frequency_value_weekly[]" class="frequency_value_weekly"
                                                            {{ $detail['frequency'] == 'weekly' ? '' : 'disabled' }}
                                                            @if (isset($isCheckedWeeklyType) && $isCheckedWeeklyType == 'everyday') {{ $detail['frequency'] == 'weekly' && in_array($i, $detail['frequency_value']) ? 'checked' : '' }} @endif>
                                                        {{ __('Thứ ' . ($i + 1)) }}
                                                        <span></span>
                                                    </label>
                                                @endfor
                                                <label class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                    <input type="checkbox" value="0" name="frequency_value_weekly[]"
                                                        class="frequency_value_weekly"
                                                        {{ $detail['frequency'] == 'weekly' ? '' : 'disabled' }}
                                                        @if (isset($isCheckedWeeklyType) && $isCheckedWeeklyType == 'everyday') {{ $detail['frequency'] == 'weekly' && in_array(0, $detail['frequency_value']) ? 'checked' : '' }} @endif>
                                                    @lang('survey::survey.create.Sun')
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="m-radio cus primary-color">
                                        <input type="radio" name="frequency" class="monthly frequency" value="monthly"
                                            {{ $detail['frequency'] == 'monthly' ? 'checked' : '' }}>
                                        @lang('survey::survey.create.monthly')
                                        <span></span>
                                    </label>
                                    <div class="row">
                                        <div class="col-12 pl-5 primary-color frequency_monthly pl-5">
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
                                                                @for ($i = 1; $i <= 4; $i++)
                                                                    <div class="col-12 mb-2">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                class="frequency_value_monthly"
                                                                                name="frequency_value_monthly[]"
                                                                                {{ $detail['frequency'] == 'monthly' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency'] == 'monthly' && in_array($i, $detail['frequency_value']) ? 'checked' : '' }}>
                                                                            {{ __('Tháng ' . $i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-4 col-lg-3">
                                                            <div class="row">
                                                                @for ($i = 5; $i <= 8; $i++)
                                                                    <div class="col-12 mb-2">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                class="frequency_value_monthly"
                                                                                name="frequency_value_monthly[]"
                                                                                {{ $detail['frequency'] == 'monthly' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency'] == 'monthly' && in_array($i, $detail['frequency_value']) ? 'checked' : '' }}>
                                                                            {{ __('Tháng ' . $i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-4 col-lg-3">
                                                            <div class="row">
                                                                @for ($i = 9; $i <= 12; $i++)
                                                                    <div class="col-12 mb-2">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                class="frequency_value_monthly"
                                                                                name="frequency_value_monthly[]"
                                                                                {{ $detail['frequency'] == 'monthly' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency'] == 'monthly' && in_array($i, $detail['frequency_value']) ? 'checked' : '' }}>
                                                                            {{ __('Tháng ' . $i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pl-5 primary-color frequency_monthly pl-5">
                                            <div class="row">
                                                <div class="col-10 offset-1 mb-3">
                                                    <label class="m-radio cus primary-color">
                                                        <input type="radio" name="frequency_monthly_type"
                                                            class="frequency_monthly_type" value="day_in_month"
                                                            {{ $detail['frequency'] == 'monthly' ? '' : 'disabled' }}
                                                            {{ $detail['frequency_monthly_type'] == 'day_in_month' ? 'checked' : '' }}>
                                                        @lang('survey::survey.create.date_in_month')
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <div class="col-10 offset-1">
                                                    <div class="row">
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <div class="col-12">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                name="day_in_monthly[]"
                                                                                class="day_in_monthly"
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' && in_array($i, $detail['day_in_monthly']) ? 'checked' : '' }}>
                                                                            {{ __($i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                @for ($i = 6; $i <= 10; $i++)
                                                                    <div class="col-12">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                name="day_in_monthly[]"
                                                                                class="day_in_monthly"
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' && in_array($i, $detail['day_in_monthly']) ? 'checked' : '' }}>
                                                                            {{ __($i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                @for ($i = 11; $i <= 15; $i++)
                                                                    <div class="col-12">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                name="day_in_monthly[]"
                                                                                class="day_in_monthly"
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' && in_array($i, $detail['day_in_monthly']) ? 'checked' : '' }}>
                                                                            {{ __($i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                @for ($i = 16; $i <= 20; $i++)
                                                                    <div class="col-12">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                name="day_in_monthly[]"
                                                                                class="day_in_monthly"
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' && in_array($i, $detail['day_in_monthly']) ? 'checked' : '' }}>
                                                                            {{ __($i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                @for ($i = 21; $i <= 25; $i++)
                                                                    <div class="col-12">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                name="day_in_monthly[]"
                                                                                class="day_in_monthly"
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' && in_array($i, $detail['day_in_monthly']) ? 'checked' : '' }}>
                                                                            {{ __($i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-5">
                                                            <div class="row" style="gap:10px">
                                                                @for ($i = 26; $i <= 30; $i++)
                                                                    <div class="col-12">
                                                                        <label class="kt-checkbox kt-checkbox--bold">
                                                                            <input type="checkbox"
                                                                                value="{{ $i }}"
                                                                                name="day_in_monthly[]"
                                                                                class="day_in_monthly"
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' ? '' : 'disabled' }}
                                                                                {{ $detail['frequency_monthly_type'] == 'day_in_month' && in_array($i, $detail['day_in_monthly']) ? 'checked' : '' }}>
                                                                            {{ __($i) }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-1 ml-4">
                                                            <div class="row" style="gap:10px">
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="31"
                                                                            name="day_in_monthly[]"
                                                                            class=" day_in_monthly"
                                                                            {{ $detail['frequency_monthly_type'] == 'day_in_month' ? '' : 'disabled' }}
                                                                            {{ $detail['frequency_monthly_type'] == 'day_in_month' && in_array(31, $detail['day_in_monthly']) ? 'checked' : '' }}>
                                                                        {{ __(31) }}
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="kt-checkbox kt-checkbox--bold">
                                                                        <input type="checkbox" value="-1"
                                                                            name="day_in_monthly[]"
                                                                            class=" day_in_monthly"
                                                                            {{ $detail['frequency_monthly_type'] == 'day_in_month' ? '' : 'disabled' }}
                                                                            {{ $detail['frequency_monthly_type'] == 'day_in_month' && in_array('-1', $detail['day_in_monthly']) ? 'checked' : '' }}>
                                                                        {{ __('Last') }}
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pl-5 frequency_monthly pl-5 pt-3">
                                            <div class="row">
                                                <div class="col-12 offset-1 mb-3">
                                                    <div class="row" style="gap:20px">
                                                        <div class="col-12">
                                                            <label class="m-radio cus primary-color">
                                                                <input type="radio" name="frequency_monthly_type"
                                                                    class="frequency_monthly_type" value="day_in_week"
                                                                    {{ $detail['frequency'] == 'monthly' ? '' : 'disabled' }}
                                                                    {{ $detail['frequency_monthly_type'] == 'day_in_week' ? 'checked' : '' }}>
                                                                @lang('survey::survey.create.date_in_week')
                                                                <span></span>
                                                            </label>
                                                            <div class="row primary-color">
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <div class="col-2 ml-4 pl-4">
                                                                            <label>@lang('survey::survey.create.repeat_in_week'): </label>
                                                                        </div>
                                                                        <div class="col-9">
                                                                            @for ($i = 1; $i <= 4; $i++)
                                                                                <label
                                                                                    class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                    <input type="checkbox"
                                                                                        value="{{ $i - 1 }}"
                                                                                        name="day_in_week[]"
                                                                                        class="day_in_week"
                                                                                        {{ $detail['frequency_monthly_type'] == 'day_in_week' ? '' : 'disabled' }}
                                                                                        {{ $detail['frequency_monthly_type'] == 'day_in_week' && in_array($i - 1, $detail['day_in_week']) ? 'checked' : '' }}>
                                                                                    {{ __('Tuần ' . $i) }}
                                                                                    <span></span>
                                                                                </label>
                                                                            @endfor
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="-1"
                                                                                    name="day_in_week[]"
                                                                                    class="day_in_week"
                                                                                    {{ $detail['frequency_monthly_type'] == 'day_in_week' ? '' : 'disabled' }}
                                                                                    {{ $detail['frequency_monthly_type'] == 'day_in_week' && in_array('-1', $detail['day_in_week']) ? 'checked' : '' }}>
                                                                                @lang('survey::survey.create.Mon')
                                                                                <span></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mt-3 mb-3">
                                                            <div class="row">

                                                                <div class="col-12 primary-color ">
                                                                    <div class="row">
                                                                        <div class="col-2 ml-4 pl-4">
                                                                            <label>@lang('survey::survey.create.repeat_on_things'): </label>
                                                                        </div>
                                                                        <div class="col-9">
                                                                            @for ($i = 1; $i <= 6; $i++)
                                                                                <label
                                                                                    class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                    <input type="checkbox"
                                                                                        value="{{ $i - 1 }}"
                                                                                        name="day_in_week_repeat[]"
                                                                                        class=" day_in_week_repeat"
                                                                                        {{ $detail['frequency_monthly_type'] == 'day_in_week' ? '' : 'disabled' }}
                                                                                        {{ $detail['frequency_monthly_type'] == 'day_in_week' && in_array($i - 1, $detail['day_in_week_repeat']) ? 'checked' : '' }}>
                                                                                    {{ __('Thứ ' . ($i + 1)) }}
                                                                                    <span></span>
                                                                                </label>
                                                                            @endfor
                                                                            <label
                                                                                class="kt-checkbox kt-checkbox-fix kt-checkbox--bold">
                                                                                <input type="checkbox" value="6"
                                                                                    name="day_in_week_repeat[]"
                                                                                    class=" day_in_week_repeat"
                                                                                    {{ $detail['frequency_monthly_type'] == 'day_in_week' ? '' : 'disabled' }}
                                                                                    {{ $detail['frequency_monthly_type'] == 'day_in_week' && in_array(6, $detail['day_in_week_repeat']) ? 'checked' : '' }}>
                                                                                @lang('survey::survey.create.Sun')
                                                                                <span></span>
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
                                        <div class="col-12 primary-color">
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
                                                                        <label class="m-radio cus primary-color">
                                                                            <input type="radio"
                                                                                name="is_limit_exec_time"
                                                                                class="period_in_date_type period_in_date_type_unlimited"
                                                                                value="0"
                                                                                {{ $detail['is_limit_exec_time'] ? '' : 'checked' }}>
                                                                            @lang('survey::survey.create.no_limit')
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-5" style="margin-top:10px">
                                                                        <label class="m-radio cus">
                                                                            <input type="radio"
                                                                                name="is_limit_exec_time"
                                                                                {{ $detail['is_limit_exec_time'] ? 'checked' : '' }}
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
                                                                                placeholder="{{ $detail['is_limit_exec_time'] ? '' : __('Thời gian bắt đầu') }}"
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
                                                                                placeholder="{{ $detail['is_limit_exec_time'] ? '' : __('Thời gian kết thúc') }}"
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
                    <div class="kt-portlet__head-label primary-color mb-5">
                        <h5 class="kt-portlet__head-title fw_title">
                            @lang('survey::survey.create.setting_limit_survey')
                        </h5>
                    </div>
                    <div class="form-group row div_setting_turns primary-color">
                        <label class="col-xl-3 col-lg-3">
                            @lang('survey::survey.create.number_of_survey_on_person')
                        </label>
                        <div class="col-lg-6 col-xl-6">
                            <div class="kt-radio-list">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-5">
                                                <label class="m-radio cus primary-color">
                                                    <input type="radio" class="config_turn" name="config_turn"
                                                        value="0" {{ $detail['max_times'] == 0 ? 'checked' : '' }}>
                                                    @lang('survey::survey.create.no_limit')
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-5">
                                                <label class="m-radio cus primary-color">
                                                    <input type="radio" class="config_turn" name="config_turn"
                                                        value="1" {{ $detail['max_times'] == 0 ? '' : 'checked' }}>
                                                    @lang('survey::survey.create.number_of_survey_on_person')
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div class="col-3">
                                                <input {{ $detail['max_times'] == 0 ? 'disabled' : '' }} type="text"
                                                    class="form-control numeric" name="max_times" id="max_times"
                                                    value="{{ $detail['max_times'] == 0 ? '' : $detail['max_times'] }}">
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
                </form>
                <div class="row form-group">
                    <div class="col-xl-12 col-lg-12 d-flex justify-content-end pr-5">
                        <button type="button" class="btn btn-secondary  kt-padding-l-40 kt-padding-r-40 mr-5"
                            onclick="survey.back()">
                            <i class="la la-arrow-left"></i>
                            @lang('HUỶ')

                        </button>
                        <button type="button"
                            class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40"
                            onclick="survey.update('{{ $detail['survey_id'] }}')">
                            <i class="la la-check"></i>
                            @lang('CẬP NHẬT')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_script')
    <script type="text/template" id="image-tpl">
        <div class="kt-avatar__holder" style="background-image: url({link});background-position: center;"></div></script>
    <script src="{{ asset('static/backend/js/jquery.mask.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/survey/edit.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                @if ($detail['start_date'])
                    $('#start_date').val(
                        '{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail['start_date'])->format('H:i:s d/m/Y') }}'
                    );
                    $('#end_date').val(
                        '{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail['end_date'])->format('H:i:s d/m/Y') }}'
                    );
                    $('#end_date').trigger('change');
                @endif
                @if ($detail['is_limit_exec_time'])
                    $('#exec_time_from').val('{{ $detail['exec_time_from'] }}');
                    $('#exec_time_to').val('{{ $detail['exec_time_to'] }}');
                @endif
            }, 2000);
        });
    </script>
@endsection
