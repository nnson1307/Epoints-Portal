@php
$titleQuestion = $item['multi_choice']['infoQuestion']['survey_question_description'];
$indexQuestion = $k + 1;
$isRequiredQuestion = $item['multi_choice']['infoQuestion']['is_required'] == 1 ? __("Bắt buộc") : __("Không bắt buộc");
$precentAnswerQuestion = $item['multi_choice']['percentageAnswer'];
$dataQuestionSingleChoice = $item['multi_choice']['dataQuestionSingleChoice'] ?? [];
$nameChart = 'chartExportMutiple' . $key . $indexQuestion;
$converNamechart = str_replace(' ', '', $nameChart);
@endphp
<div class="item_aswer_question col-lg-12">
    <div class="header_answer-question">
        <h6 class="title">{{ __('Câu hỏi') . ' ' . $indexQuestion . ' ' . ':' . ' ' . $titleQuestion }}</h6>
        <div class="header_info-question">
            <p>
                {{ __('Câu hỏi' . ' ' . $isRequiredQuestion) }}
            </p>
            <p>@lang('Loại câu hỏi trắc nghiệm')</p>
            <p>{{ $precentAnswerQuestion }}</p>
        </div>
    </div>
    <div class="item_content-question">
        <h6>@lang('Hình thức trả lời: Chọn nhiều đáp án')</h6>
        @if (count($dataQuestionSingleChoice) > 0)
            <figure class="highcharts-figure">
                <div id="{{ $converNamechart }}"></div>
            </figure>
        @endif
    </div>
</div>
