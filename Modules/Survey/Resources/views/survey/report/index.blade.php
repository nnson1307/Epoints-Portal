@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> {{ __('QUẢN LÝ KHẢO SÁT') }}</span>
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

        #modal-survey .modal-body .title {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            color: #000000;
        }

        #modal-survey .modal-body .description {
            font-size: 14px;
            font-weight: 400;
            text-align: center;
            align-content: center;
            color: #000000;
        }

        .color_button_destroy {
            background-color: #FE4C4C !important;
            color: #fff;
            border-color: #FE4C4C !important;
        }

        .kt-padding-l-40 {
            padding-left: 40px !important;
        }

        .kt-padding-r-40 {
            padding-right: 40px !important;
        }

        .color_button {
            font-size: 13px !important;
            font-weight: 400 !important;
        }
    </style>
@endsection
@section('after_css')

    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
    <div class="m-portlet  m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text" style="font-weight: bold;">
                        {{ __('CHI TIẾT KHẢO SÁT') }}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{ route('survey.index') }}" class="btn btn-secondary btn-search ml-2"
                    style="color:black; border:1px solid">
                    @lang('Quay lại trang trước')
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row form-group">
                <div class="col-xl-7 col-lg-7">
                    <div class="btn-group btn-group" role="group" aria-label="...">
                        <a type="button" href="{{ route('survey.show', [$detail['survey_id']]) }}"
                            class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                            @lang('Thông tin chung')
                        </a>
                        <a type="button" class="btn btn-secondary kt-padding-l-40 kt-padding-r-40"
                            href="{{ route('survey.show-question', [$detail['survey_id']]) }}">
                            @lang('Câu hỏi khảo sát')
                        </a>
                        <a href="{{ route('survey.show-branch', [$detail['survey_id']]) }}" type="button"
                            class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                            @lang('Đối tượng áp dụng')
                        </a>
                        <a href="{{ route('survey.report', [$detail['survey_id']]) }}" type="button"
                            class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">
                            @lang('Báo cáo')
                        </a>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5">
                    <a href="{{ route('survey.report.overview', [$detail['survey_id']]) }}"
                        class="btn btn-secondary btn-search mr-5" style="color:black; border:1px solid">
                        @lang('Báo cáo tổng quan')
                    </a>
                    <a href="{{ route('survey.report.show', [$detail['survey_id']]) }}"
                        class="btn btn-secondary btn-search mr-5" style="color:black; border:1px solid">
                        @lang('Báo cáo chi tiết')
                    </a>
                    <a href="javascript:void(0)" onclick="survey.showModalExport()" class="btn btn-secondary btn-search"
                        style="color:black; border:1px solid">
                        @lang('Xuất dữ liệu')
                    </a>
                </div>
            </div>
            <div class="row padding_row frmFilter bg">
                @if($detail['type_user'] == 'customer')
                <div class="col-lg-12 d-flex">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group input-group">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-search glyphicon-th"></i></span>
                            </div>
                            <input name="code_customer_or_staff" type="text" class="form-control"
                                placeholder="@lang('Nhập mã khác hàng hoặc nhân viên')" value="">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group input-group">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-search glyphicon-th"></i></span>
                            </div>
                            <input name="name_customer_or_staff" type="text" class="form-control"
                                placeholder="@lang('Nhập tên khách hàng hoặc nhân viên')" value="">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group input-group date">
                            <input id="created_at_customer" name="created_at" type="text" style="background-color: #fff"
                                class="form-control m-input daterange-picker" placeholder="@lang('Thời gian thực hiện khảo sát')" readonly
                                value="">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 d-flex">
                    <div class="col-3 form-group">
                        <select type="text" name="province_main" id="province_id" onchange='survey.getDistrict()'
                            class="form-control ss--width-100 ss-select2" style="width: 100%">
                            <option></option>
                            @foreach ($optionProvice as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 form-group">
                        <select type="text" name="district_main" id="district_id" onchange="survey.getWard()"
                            class="form-control ss--width-100 ss-select2 district" style="width: 100%">
                        </select>
                    </div>
                    <div class="col-3 form-group">
                        <select type="text" name="ward_main" id="ward_id"
                            class="form-control ss--width-100 ss-select2" style="width: 100%">
                            <option value="">@lang('Phường/xã')</option>
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex justify-content-end handler_button " style="gap:30px">
                        <button onclick="survey.resetSearchReportSurvey()"
                            class="btn btn-primary color_button_danger font_size-button"
                            style="font-weight:400; font-size:13px">
                            @lang('survey::survey.index.delete')
                        </button>
                        <button onclick="survey.loadListReportSurvey()"
                            class="btn btn-primary color_button btn-search font_size-button">
                            @lang('survey::survey.index.search')
                        </button>
                    </div>
                </div>
                @else
                <div class="col-lg-12 d-flex">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group input-group">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-search glyphicon-th"></i></span>
                            </div>
                            <input name="code_customer_or_staff" type="text" class="form-control"
                                placeholder="@lang('Nhập mã khác hàng hoặc nhân viên')" value="">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group m-form__group input-group">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-search glyphicon-th"></i></span>
                            </div>
                            <input name="name_customer_or_staff" type="text" class="form-control"
                                placeholder="@lang('Nhập tên khách hàng hoặc nhân viên')" value="">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group m-form__group input-group date">
                            <input id="created_at_staff" name="created_at_staff" type="text" style="background-color: #fff"
                                class="form-control m-input daterange-picker" placeholder="@lang('Thời gian thực hiện khảo sát')" readonly
                                value="">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 d-flex">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group input-group">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-search glyphicon-th"></i></span>
                            </div>
                            <input name="address_staff" type="text" class="form-control"
                                placeholder="{{__('Nhập địa chỉ tìm kiếm')}}" value="">
                        </div>
                    </div>
                    <div class="col-lg-4 d-flex  handler_button " style="gap:30px">
                        <button onclick="survey.resetSearchReportSurvey()"
                            class="btn btn-primary color_button_danger font_size-button"
                            style="font-weight:400; font-size:13px">
                            @lang('survey::survey.index.delete')
                        </button>
                        <button onclick="survey.loadListReportSurvey()"
                            class="btn btn-primary color_button btn-search font_size-button">
                            @lang('survey::survey.index.search')
                        </button>
                    </div>
                </div>
                @endif
            </div>
            <input type="text" hidden id="id_survey" value="{{ $detail['survey_id'] }}">
            <div class="row table-content mt-5">
            </div>
        </div>
    </div>
    @include('survey::survey.modal.export_report')
@endsection
@section('after_script')
    <script type="text/template" id="image-tpl">
        <div class="kt-avatar__holder" style="background-image: url({link});background-position: center;"></div></script>
    <script>
        const SURVEY_ID = "{{ $detail['survey_id'] }}";
        const DATA_CHART_SIGNCHOICE = "";
        const DATA_CHART_MUTIPLECHOICE = ""
    </script>
    <script src="{{ asset('static/backend/js/survey/report.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        $.getJSON(laroute.route('admin.validation'), function(json) {
            $("#created_at_customer").daterangepicker({
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: [
                        json.content.CN,
                        json.content.T2,
                        json.content.T3,
                        json.content.T4,
                        json.content.T5,
                        json.content.T6,
                        json.content.T7
                    ],
                    "monthNames": [
                        json.content.month_1,
                        json.content.month_2,
                        json.content.month_3,
                        json.content.month_4,
                        json.content.month_5,
                        json.content.month_6,
                        json.content.month_7,
                        json.content.month_8,
                        json.content.month_9,
                        json.content.month_10,
                        json.content.month_11,
                        json.content.month_12
                    ],
                    "firstDay": 1,
                }
            });

            $("#created_at_staff").daterangepicker({
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: [
                        json.content.CN,
                        json.content.T2,
                        json.content.T3,
                        json.content.T4,
                        json.content.T5,
                        json.content.T6,
                        json.content.T7
                    ],
                    "monthNames": [
                        json.content.month_1,
                        json.content.month_2,
                        json.content.month_3,
                        json.content.month_4,
                        json.content.month_5,
                        json.content.month_6,
                        json.content.month_7,
                        json.content.month_8,
                        json.content.month_9,
                        json.content.month_10,
                        json.content.month_11,
                        json.content.month_12
                    ],
                    "firstDay": 1,
                }
            });

            @if (!isset($filters['created_at_customer']))
                $('#created_at_customer').val('');
            @endif

            @if (!isset($filters['created_at_staff']))
                $('#created_at_staff').val('');
            @endif
        });
    </script>
    <script>
        survey.loadListReportSurvey();
    </script>
@endsection
