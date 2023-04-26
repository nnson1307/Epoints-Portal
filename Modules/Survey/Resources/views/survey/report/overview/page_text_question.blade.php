@php
$titleQuestion = $item['page_text']['infoQuestion']['survey_question_description'];
$indexQuestion = $k + 1;
$isRequiredQuestion = $item['page_text']['infoQuestion']['is_required'] == 1 ? __("Bắt buộc") : __("Không bắt buộc");
$precentAnswerQuestion = $item['page_text']['percentageAnswer'];
$dataQuestionText = $item['page_text']['dataQuestionText'];

@endphp
<div class="item_aswer_question col-lg-12">
    <div class="header_answer-question">
        <h6 class="title">{{ __('Câu hỏi') . ' ' . $indexQuestion . ' ' . ':' . ' ' . $titleQuestion }} </h6>
    </div>
</div>
