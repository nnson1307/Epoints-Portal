<div class="item-question_answer row question_signle_choice">
    <div class="col-lg-3 index_question_answer">
        @php
            $index = $key + 1;
        @endphp
        <p>
            {{ __('Câu hỏi') . ' ' . $index }}
        </p>
    </div>
    <div class="col-lg-9 body_question_answer">
        @if ($answerQuestion['text_value_point'] != true)
            <div class="title-question">
                <input type="text" value="{{ $answerQuestion['survey_question_description'] }}" readonly="true">
            </div>
        @else
            <div class="header-question">
                <div class="header-question__icon">
                    @if (!empty($answerQuestion['answer_value']))
                        <i class="fa fa-check" style="color: 
                #1E8E3E !important; font-size:20px"></i>
                    @else
                        <i class="fa fa-times" aria-hidden="true"
                            style="color: 
                red !important; font-size:20px"></i>
                    @endif
                </div>

                <div class="title-question">
                    <input type="text" class="@if (!empty($answerQuestion['answer_value'])) success @else wrong @endif"
                        value="{{ $answerQuestion['survey_question_description'] }}" readonly="true">
                </div>
                <div class="header-question__result">
                    @if (!empty($answerQuestion['answer_value']))
                        <span>{{ $answerQuestion['value_point'] . '/' . $answerQuestion['value_point'] }}</span>
                    @else
                        <span>{{ '0' . '/' . $answerQuestion['value_point'] }}</span>
                    @endif
                </div>
            </div>
        @endif
        <div class="content-question_answer">
            <textarea class="form-control" @if(!empty($answerQuestion['answer_value']) && $answerQuestion['text_value_point'] == true) style="background: rgba(79, 196, 202, 0.2)"  @endif rows="5" disabled>{{ $answerQuestion['answer_value'] }}</textarea>
        </div>
    </div>
</div>
