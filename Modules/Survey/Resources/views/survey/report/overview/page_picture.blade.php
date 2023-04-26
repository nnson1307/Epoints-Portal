@php
$titleQuestion = $item['page_picture']['survey_question_description'];
$indexQuestion = $k + 1;
$isRequiredQuestion = $item['page_picture']['is_required'] == 1 ? __("Bắt buộc") : __("Không bắt buộc");
$precentAnswerQuestion = $item['page_picture']['percentageAnswer'];
$dataQuestionPicture = $item['page_picture']['survey_question_config'];

@endphp
<div class="item_aswer_question col-lg-12">
    <div class="header_answer-question">
        <h6 class="title">{{ __('Câu hỏi') . ' ' . $indexQuestion . ' ' . ':' . ' ' . $titleQuestion }} </h6>
    </div>
    <div class="item_content-question">
        <div class="content-question_answer">
            @php
                $listImage = json_decode($dataQuestionPicture);
            @endphp
            @if (isset($listImage->image))
                @foreach ($listImage->image as $item)
                    <div class="kt-avatar kt-avatar--outline kt-margin-t-20 kt-width-height-100px">
                        <div id="page_picture" class="kt-background-color-fff">
                            <div class="kt-avatar__holder kt-width-height-100px"
                                style="background-image: url({{ $item }}); background-position: center; background-size: 100% 100%;">
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>