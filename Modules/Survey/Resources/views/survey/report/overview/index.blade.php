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

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 660px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 1000px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        .highcharts-button-symbol {
            display: none !important;
        }

        .highcharts-button-box {
            display: none !important;

        }

        .highcharts-credits {
            display: none !important;
        }

        .highcharts-title {
            display: none !important;
        }

        .header_answer-question {
            color: #000000;
        }

        .header_answer-question .header_info-question {
            margin: 20px 0;
            display: flex;
            justify-content: space-around;
        }

        .item_content-question h6 {
            color: #000000;
            font-weight: 300;
        }

        .item_aswer_question {
            margin: 50px 0px;
        }

        .item_content-question input {
            height: 40px;
            width: 100%;
            margin-bottom: 10px;
            border: none;
            outline: none;
            color: #000000;
            font-weight: 400;
        }

        .item_content-question .m-scrollable {
            margin: 0 100px;
        }

        .block_question_report--title {
            text-align: center;
            padding: 30px 0px;
            color: #000000;
            font-weight: 500;
            border: 1px solid #555;
            border-radius: 10px;
        }
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="m-portlet  m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text" style="font-weight: bold;">
                        {{ __('BÁO CÁO TỔNG QUAN') }}
                    </h2>
                </div>
            </div>

        </div>
        <div class="kt-portlet kt-portlet--mobile" id="report_answer_question_detail">
            @if (!empty($infoOverview))
                <div class="kt-portlet__body">
                    <div class="row form-group tab_infor-header">
                        <div class="col-lg-4 info_report-survey">
                            <label class="">
                                @lang('Mã số')
                            </label>
                            <label class="">
                                @lang('Tên khảo sát')
                            </label>
                            <label class="">
                                @lang('Tổng số câu hỏi')
                            </label>
                        </div>
                        <div class="col-lg-4 info_report-survey">
                            <label class="">
                                {{ $infoOverview->survey_code }}
                            </label>
                            <label class="">
                                {{ $infoOverview->survey_name }}
                            </label>
                            <label class="" style="color:blue">
                                {{ $infoOverview->questions->count() }}
                            </label>
                        </div>
                        <div class="col-lg-4 info_report-survey">
                            <label class="">
                            </label>
                            <label class="">
                            </label>
                            <div class="total_answer-question d-flex" style="margin-top:auto">
                                <label class="mr-5">
                                    @lang('Tổng số câu trả lời : ')
                                </label>
                                <label class="" style="color:blue">
                                    {{ $totalAnswerQuestion }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="list_answer-question">
                        @if (!empty($dataQuestionAnswer))
                            @foreach ($dataQuestionAnswer as $key => $questionAnswers)
                                <div class="block_question_report">
                                    <div class="block_question_report--title">
                                        <h3>{{ __('Khối') . ' ' . $key }}</h3>
                                    </div>
                                    @foreach ($questionAnswers as $questionAnswer)
                                        @foreach ($questionAnswer as $k => $item)
                                            @if (isset($item['single_choice']))
                                                @include('survey::survey.report.overview.single_choice_question')
                                            @elseif (isset($item['multi_choice']))
                                                @include('survey::survey.report.overview.mutiple_choice_question')
                                            @elseif (isset($item['text']))
                                                @include('survey::survey.report.overview.text_question')
                                            @elseif (isset($item['page_text']))
                                                @include('survey::survey.report.overview.page_text_question')
                                            @elseif (isset($item['page_picture']))
                                                @include('survey::survey.report.overview.page_picture')
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @else
            @endif
        </div>
        @php
        @endphp
    </div>
@endsection

@section('after_script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        const DATA_CHART_SIGNCHOICE = @json($dataChartSingleChoice);
        const DATA_CHART_MUTIPLECHOICE = @json($dataChartMutipleChoice)
    </script>
    <script src="{{ asset('static/backend/js/survey/report.js?v=' . time()) }}" type="text/javascript"></script>


@endsection
