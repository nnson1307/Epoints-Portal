@php
$pageNext = $page >= $totalPage ? 1 : $page + 1;
$pagePre = $page == 1 ? $totalPage : $page - 1;
@endphp
@if (!empty($listAnswerQuestion) || !empty($infoAnswerSurvey))
    <div class="kt-portlet__body">
        @if ($infoAnswerSurvey->count() > 0)
            <div class="row form-group tab_infor-header">
                <div class="col-lg-1 prev_answer_question">
                    <a title="Next" onclick="survey.showReportDetailSurvey({{ $pagePre }})"
                        class="m-datatable__pager-link m-datatable__pager-link--next"><i class="la la-angle-left"></i></a>
                    </li>
                </div>
                <div class="col-lg-2 info_report-survey">
                    <label class="">
                        @lang('Mã số')
                    </label>
                    <label class="">
                        @lang('Số điện thoại')
                    </label>
                    <label class="">
                        @lang('Thời gian thực hiện khảo sát:')
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
                <div class="col-lg-1 next_answer_question">
                    <a title="Next" onclick="survey.showReportDetailSurvey({{ $pageNext }})"
                        class="m-datatable__pager-link m-datatable__pager-link--next"><i
                            class="la la-angle-right"></i></a>
                    </li>
                </div>
                @if ($infoAnswerSurvey['count_point'])
                    <div class="col-lg-12 info_report-survey"
                        style="flex-direction: inherit; justify-content: space-between; margin-top:30px; padding: 0px 115px;">
                        <div>
                            <label class="">
                                @lang('Tổng số câu trả lời đúng') : <span
                                    class="total_point--answer">{{ $infoAnswerSurvey['total_answer_success'] }}</span>
                            </label>
                        </div>
                        <div style="margin-right: 123px">
                            <label class="">
                                @lang('Tổng số câu trả lời sai') : <span
                                    class="total_point--answer">{{ $infoAnswerSurvey['total_answer_wrong'] }}</span>
                            </label>
                        </div>
                        <div>
                            <label class="" style="margin-right: 200px;">
                                @lang('Tổng điểm') : <span
                                    class="total_point--answer">{{ $infoAnswerSurvey['total_point'] }}</span>
                            </label>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        <div class="form-group row">
            @if (count($dataListAnswerQuestionBlock) > 0)
                @foreach ($dataListAnswerQuestionBlock as $key => $listAnswerQuestion)
                    <div class="title-block_name"> {{ __('Khối') . ' ' . $key }}</div>
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
