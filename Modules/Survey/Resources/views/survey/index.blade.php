@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> {{ __('QUẢN LÝ KHẢO SÁT') }}</span>
@stop
@section('after_style')
    <style>
        .m-datatable>.m-datatable__pager>.m-datatable__pager-nav>li>.m-datatable__pager-link.m-datatable__pager-link--active {
            background: #4fc4ca !important;
            color: #ffffff;
        }

        .m-datatable>.m-datatable__pager>.m-datatable__pager-nav>li>.m-datatable__pager-link:hover {
            background: #4fc4ca !important;
            color: #ffffff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #a9a9a9 !important;
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
        .kt-checkbox.kt-checkbox--bold span {
            border: 1px solid #4FC4CA;
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
        .kt-checkbox.kt-checkbox--bold>input:checked~span {
            background: #4FC4CA;
            border: 2px solid #4FC4CA !important;
            border-radius: 3px !important;
        }

    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text tab">
                        @lang('survey::survey.index.title')
                    </h2>
                </div>
            </div>

            <div class="m-portlet__head-tools">
                <a href="{{ route('survey.create') }}" class="btn btn-info btn-sm m-btn m-btn--icon  color_button">
                    <span>
                        <i class="fa fa-plus-circle mr-3"></i>
                        <span style="font-weight:400; font-size:12px;"> @lang('survey::survey.index.create')</span>
                    </span>
                </a>
            </div>

        </div>
        <div class="m-portlet__body">
            <div class="row padding_row frmFilter bg">
                <div class="col-lg-3">
                    <div class="form-group m-form__group input-group">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-search glyphicon-th"></i></span>
                        </div>
                        <input name="name_or_code" type="text" class="form-control" placeholder="@lang('Nhập tên hoặc mã khảo sát')"
                            value="">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group m-form__group input-group date">
                        <input id="created_at" name="created_at" type="text" style="background-color: #fff"
                            class="form-control m-input daterange-picker" placeholder="@lang('survey::survey.index.created_at')" readonly
                            value="">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group m-form__group">
                        <select class="form-control ss--select-2" id="status" name="status">
                            <option value="">@lang('survey::survey.index.survey_status')</option>
                            <option value="N">
                                @lang('survey::survey.index.status_selected_draft')
                            </option>
                            <option value="R">
                                @lang('survey::survey.index.status_selected_approved')
                            </option>
                            <option value="C">
                                @lang('survey::survey.index.status_selected_end')
                            </option>
                            <option value="D">
                                {{ __('Từ chối') }}
                            </option>

                        </select>
                    </div>
                </div>
                <div class="col-lg-3 d-flex justify-content-end handler_button ">
                    <button onclick="survey.resetSearchSurvey()" class="btn btn-primary color_button_danger "
                        style="font-weight:400; font-size:12px;">
                        @lang('survey::survey.index.delete')
                    </button>
                    <button onclick="survey.loadListSurvey()" class="btn btn-primary color_button btn-search "
                        style="font-weight:400; font-size:12px;">
                        @lang('survey::survey.index.search')
                    </button>
                </div>
            </div>
            <div class="row table-content mt-5">
            </div>
        </div>
    </div>
    <div id="modal">

    </div>
@endsection
@section('after_script')
    <script>
        $.getJSON(laroute.route('admin.validation'), function(json) {
           $("#created_at").daterangepicker({
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
                    "firstDay": 1
                }
            });
            @if (!isset($filters['created_at']))
                $('#created_at').val('');
            @endif
        });
    </script>
    <script src="{{ asset('static/backend/js/survey/script.js?v=' . time()) }}" type="text/javascript"></script>
    <script></script>
@stop
