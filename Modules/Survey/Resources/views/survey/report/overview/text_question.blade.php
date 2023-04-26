@php
$titleQuestion = $item['text']['infoQuestion']['survey_question_description'];
$indexQuestion = $k + 1;
$isRequiredQuestion = $item['text']['infoQuestion']['is_required'] == 1 ? __("Bắt buộc") : __("Không bắt buộc");
$precentAnswerQuestion = $item['text']['percentageAnswer'];
$dataQuestionText = $item['text']['dataQuestionText'];

@endphp
<div class="item_aswer_question col-lg-12">
    <div class="header_answer-question">
        <h6 class="title">{{ __('Câu hỏi') . ' ' . $indexQuestion . ' ' . ':' . ' ' . $titleQuestion }}</h6>
        <div class="header_info-question">
            <p>{{ __('Câu hỏi' . ' ' . $isRequiredQuestion) }}</p>
            <p>@lang('Loại câu hỏi tự luận')</p>
            <p>{{ $precentAnswerQuestion }}</p>
        </div>
    </div>
    <div class="item_content-question">
        @if ($dataQuestionText->count() > 0)
            <div class="m-scrollable m-scroller ps" data-scrollable="true" data-height="250" data-mobile-height="200"
                style="height: 400px; overflow: hidden;" id="scroll-notify">
                @foreach ($dataQuestionText as $item)
                    @if($item->answer_value != '')
                    <input type="text" value="{{ $item->answer_value }}" disabled>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
