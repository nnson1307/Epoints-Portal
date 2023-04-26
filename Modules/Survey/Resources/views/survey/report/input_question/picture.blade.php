<div class="item-question_answer row question_picture">
    <div class="col-lg-3 index_question_answer">
        @php
            $index = $key + 1;
        @endphp
        <p>
            {{__("Câu hỏi"). ' '. $index}}
        </p>
    </div>
    <div class="col-lg-9 body_question_answer">
        <div class="title-question">
            <input type="text" value="{{ $answerQuestion['survey_question_description'] }}" readonly="true">
        </div>
        <div class="content-question_answer">
            @php
                $listImage = json_decode($answerQuestion['survey_question_config']);
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
