@php
if (isset($detail['frequency']) && $detail['frequency'] == 'weekly') {
    $isCheckedWeeklyType = count($detail['frequency_value']) == 7 ? 'all' : 'everyday';
}
@endphp
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

        .info_report-survey {
            display: flex;
            flex-direction: column;
            row-gap: 30px;
            color: #000000;
            font-weight: bold;

        }

        .tab_infor-header {
            border: 1px solid #000000;
            padding: 15px 50px;
        }

        .list_answer_question .item-question_answer .index_question_answer {
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .list_answer_question .item-question_answer .body_question_answer {
            padding-top: 15px;
        }

        .list_answer_question {
            border: 1px solid #E5E5E5;
        }

        .list_answer_question .item-question_answer {
            border-bottom: 1px solid #E5E5E5;
        }

        .list_answer_question .index_question_answer {
            border-right: 1px solid #E5E5E5;
        }

        .list_answer_question .list-question-answer_mutiple_choice {
            width: 100%;
        }

        .list_answer_question .list-question-answer_mutiple_choice .item-question_mutiple_choice {
            padding-top: 10px;
        }

        .list_answer_question .list-question-answer_mutiple_choice .item-question_mutiple_choice input[type=checkbox] {
            margin-right: 15px;
            width: 20px;
        }

        .list_answer_question .list-question-answer_mutiple_choice .item-question_mutiple_choice input:checked {
            background: #4FC4CA !important;
            border: 1px solid #4FC4CA !important;
        }

        .list_answer_question .item-question_answer .index_question_answer p {
            color: #000000;
            font-weight: bold;
            font-size: 14px;
        }

        .list_answer_question .question_signle_choice .title-question input {
            width: 100%;
            padding: 10px;
            border: 1px solid #C0C0C0;
            border-radius: 9px;
            outline: none;
        }

        .list_answer_question .question_mutiple_choice .title-question input {
            width: 100%;
            padding: 10px;
            border: 1px solid #C0C0C0;
            border-radius: 9px;
            outline: none;
        }

        .list_answer_question .question_picture .title-question input {
            width: 100%;
            padding: 10px;
            border: 1px solid #C0C0C0;
            border-radius: 9px;
            outline: none;
        }

        .list_answer_question .question_signle_choice .title-question {
            padding-bottom: 30px;
        }

        .list_answer_question .question_mutiple_choice .title-question {
            padding-bottom: 30px;
        }

        .list_answer_question .question_mutiple_choice .content-question_answer {
            display: flex;
            padding: 10px 0 30px 45px;
            flex-direction: column !important;
            gap: 15px;
            margin-left: 60px;
        }

        .list_answer_question .question_mutiple_choice .content-question_answer .item-question_mutiple_choice {
            display: flex;

        }

        .list_answer_question .question_mutiple_choice .content-question_answer .item-question_mutiple_choice .title {
            width: 100%;
            padding: 10px;
            border: 1px solid #C0C0C0;
            border-radius: 9px;
            outline: none;
        }

        .list_answer_question .question_signle_choice .content-question_answer {
            display: flex;
            justify-content: space-around;
            padding: 30px 0;
            flex-direction: column !important;
            gap: 15px;
            margin-left: 60px;

        }

        .prev_answer_question {
            display: flex;
            align-items: center;
        }

        .prev_answer_question i {
            cursor: pointer;
            font-size: 30px;
        }

        .next_answer_question {
            display: flex;
            align-items: center;
        }

        .next_answer_question i {
            cursor: pointer;
            font-size: 30px;
        }

        .title-block_name {
            width: 100%;
            text-align: center;
            padding: 20px;
            margin: 10px 0px;
            font-size: 18px;
            color: black;
            font-weight: bold;
            border: 1px solid #E5E5E5;
            border-radius: 10px;
        }

        .header-question {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 15px
        }

        .header-question .title-question {
            flex-grow: 1;
            padding-bottom: 0px !important;
        }

        .header-question .title-question input.success {
            color:
                #1E8E3E !important;
        }

        .header-question .title-question input.wrong {
            color:
                red !important;
        }

        .header-question__result {
            color: red;
            font-weight: bold;
        }

        .m-radio-wrong>span:after {
            background: #C0C0C0 !important;
            border: 1px solid ##C0C0C0 !important;
        }

        .m-radio-wrong>span {
            border: 1px solid #C0C0C0 !important;
        }


        .kt-checkbox.kt-checkbox--bold span {
            border: 1px solid #4FC4CA;
        }

        .list-answer__success {
            padding-bottom: 20px;
        }

        .list-answer__success h6 {
            color: black;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .list-answer__success label {
            margin-left: 44px;
            margin-bottom: 10px;

        }

        .item__question--answer {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .content-question_answer .content-text__answer {
            width: 100%;
        }
        .total_point--answer {
            color : red;
            font-weight: bold;
        }
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text" style="font-weight: bold;">
                        {{ __('BÁO CÁO CHI TIẾT') }}
                    </h2>
                </div>
            </div>

        </div>
        <div class="kt-portlet kt-portlet--mobile">
            @if (!empty($listAnswerQuestion) || !empty($infoAnswerSurvey))
                <div class="kt-portlet__body">
                    <div class="row form-group tab_infor-header">
                        @if (empty($typeShowPage))
                            <div class="col-lg-1 prev_answer_question">
                                <a title="Next"
                                    @if (isset($totalPage) && $totalPage > 1) onclick="survey.showReportDetailSurvey({{ $page }})" @endif
                                    class="m-datatable__pager-link m-datatable__pager-link--next"><i
                                        class="la la-angle-left"></i></a>
                                </li>
                            </div>
                        @endif
                        <div class="col-lg-2 info_report-survey">
                            <label class="">
                                @lang('Mã số')
                            </label>
                            <label class="">
                                @lang('Số điện thoại')
                            </label>
                            <label class="">
                                @lang('Thời gian thực hiện khảo sát') :
                            </label>
                        </div>
                        <div class="col-lg-3 info_report-survey" style="margin-top:auto">
                            <label class="">
                                {{ $infoAnswerSurvey['code'] }}
                            </label>
                            <label class="">
                                {{ $infoAnswerSurvey['phone'] }}
                            </label>
                            <label class="">
                                {{ $infoAnswerSurvey['created_at'] }}
                            </label>
                        </div>
                        <div class="col-lg-2 info_report-survey">
                            <label class="">
                                @lang('Họ và tên') :
                            </label>
                            <label class="">
                                @lang('Địa chỉ') :
                            </label>
                            <label class="">
                                @lang('Số câu trả lời') :
                            </label>
                        </div>
                        <div class="col-lg-3 info_report-survey" style="margin-top:auto">
                            <label class="">
                                {{ $infoAnswerSurvey['full_name'] }}
                            </label>
                            <label class="">
                                {{ $infoAnswerSurvey['address'] }}
                            </label>
                            <label class="" style="color:blue">
                                {{ $infoAnswerSurvey['total_answer'] . '/' . $infoAnswerSurvey['total_questions'] }}
                            </label>
                        </div>
                        @if (empty($typeShowPage))
                            <div class="col-lg-1 next_answer_question">
                                <a title="Next"
                                    @if (isset($totalPage) && $totalPage > 1) onclick="survey.showReportDetailSurvey({{ $page + 1 }})" @endif
                                    class="m-datatable__pager-link m-datatable__pager-link--next"><i
                                        class="la la-angle-right"></i></a>
                                </li>
                            </div>
                        @endif
                        @if ($infoAnswerSurvey['count_point'])
                            <div class="col-lg-12 info_report-survey"
                                style="flex-direction: inherit; justify-content: space-between; margin-top:30px; @if (empty($typeShowPage)) padding:0px 115px; @endif">
                                <div>
                                    <label class="">
                                        @lang('Tổng số câu trả lời đúng') : <span class="total_point--answer">{{ $infoAnswerSurvey['total_answer_success'] }}</span>
                                    </label>
                                </div>
                                <div style="margin-right: 123px">
                                    <label class="">
                                        @lang('Tổng số câu trả lời sai') : <span class="total_point--answer" >{{ $infoAnswerSurvey['total_answer_wrong'] }}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="" style="margin-right: 200px;">
                                        @lang('Tổng điểm') : <span class="total_point--answer">{{ $infoAnswerSurvey['total_point'] }}</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="form-group row">
                        @if (count($dataListAnswerQuestionBlock) > 0)
                            @foreach ($dataListAnswerQuestionBlock as $key => $listAnswerQuestion)
                                @if ($key != null)
                                    <div class="title-block_name">{{ __('Khối') . ' ' . $key }}</div>
                                @endif
                                <div class="col-lg-12 list_answer_question">
                                    @foreach ($listAnswerQuestion as $key => $answerQuestion)
                                        @if ($answerQuestion['survey_question_type'] == 'single_choice')
                                            @include('survey::survey.report.input_question.single_choice')
                                        @elseif($answerQuestion['survey_question_type'] == 'text')
                                            @include('survey::survey.report.input_question.text')
                                        @elseif($answerQuestion['survey_question_type'] == 'page_picture')
                                            @include('survey::survey.report.input_question.picture')
                                        @elseif($answerQuestion['survey_question_type'] == 'page_text')
                                            @include('survey::survey.report.input_question.description')
                                        @elseif($answerQuestion['survey_question_type'] == 'multi_choice')
                                            @include('survey::survey.report.input_question.mutiple_choice')
                                        @endif
                                    @endForeach
                                </div>
                            @endForeach
                        @endif
                    </div>
                </div>
            @else
                <div class="kt-portlet__body">
                    <div class="row form-group tab_infor-header">
                        <div class="col-lg-3 info_report-survey">
                            <label class="">
                                @lang('Mã số')
                            </label>
                            <label class="">
                                @lang('Số điện thoại')
                            </label>
                            <label class="">
                                @lang('Thời gian thực hiện khảo sát') :
                            </label>
                        </div>
                        <div class="col-lg-3 info_report-survey">
                            <label class="">
                                @lang('Chưa có dữ liệu')
                            </label>
                            <label class="">
                                @lang('Chưa có dữ liệu')
                            </label>
                            <label class="">
                                @lang('Chưa có dữ liệu')
                            </label>
                        </div>
                        <div class="col-lg-3 info_report-survey">
                            <label class="">
                                @lang('Họ và tên') :
                            </label>
                            <label class="">
                                @lang('Địa chỉ') :
                            </label>
                            <label class="">
                                @lang('Số câu trả lời') :
                            </label>
                        </div>
                        <div class="col-lg-3 info_report-survey">
                            <label class="">
                                @lang('Chưa có dữ liệu')
                            </label>
                            <label class="">
                                @lang('Chưa có dữ liệu')
                            </label>
                            <label class="">
                                @lang('Chưa có dữ liệu')
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 list_answer_question">

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('after_script')
    <script>
        var SURVEY_ID = '{{ $survey_id }}';
    </script>
    <script src="{{ asset('static/backend/js/survey/report.js?v=' . time()) }}" type="text/javascript"></script>
    <script></script>

@endsection
