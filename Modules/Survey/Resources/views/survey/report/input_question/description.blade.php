<div class="item-question_answer row question_signle_choice">
    <div class="col-lg-3 index_question_answer">
        @php
            $index = $key + 1;
        @endphp
        <p>
            {{__("Câu hỏi"). ' '. $index}}
        </p>
    </div>
    <div class="col-lg-9 body_question_answer">
        <div class="content-question_answer">
            <textarea class="form-control" rows="5" disabled>{{ $answerQuestion['survey_question_description'] }}</textarea>
        </div>
    </div>
</div>