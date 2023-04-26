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
        @if ($answerQuestion['count_point'] != 1)
            <div class="title-question">
                <input type="text" value="{{ $answerQuestion['survey_question_description'] }}" readonly="true">
            </div>
        @else
            <div class="header-question">
                <div class="header-question__icon">
                    @if ($answerQuestion['resultAnswerQuestion'] == 'success')
                        <i class="fa fa-check"
                            style="color: 
                    #1E8E3E !important; font-size:20px"></i>
                    @elseif ($answerQuestion['resultAnswerQuestion'] == 'wrong')
                        <i class="fa fa-times" aria-hidden="true"
                            style="color: 
                    red !important; font-size:20px"></i>
                    @else
                    @endif
                </div>

                <div class="title-question">
                    <input type="text"
                        class="@if ($answerQuestion['resultAnswerQuestion'] == 'success') success @elseif ($answerQuestion['resultAnswerQuestion'] == 'wrong')
                    wrong
                    @else @endif"
                        value="{{ $answerQuestion['survey_question_description'] }}" readonly="true">
                </div>
                <div class="header-question__result">
                    @if ($answerQuestion['resultAnswerQuestion'] == 'success')
                        <span>{{ $answerQuestion['value_point'] . '/' . $answerQuestion['value_point'] }}</span>
                    @elseif ($answerQuestion['resultAnswerQuestion'] == 'wrong')
                        <span>{{ '0' . '/' . $answerQuestion['value_point'] }}</span>
                    @else
                    @endif
                </div>
            </div>
        @endif
        <div class="content-question_answer">
            @if ($answerQuestion['count_point'] != 1)
                @foreach ($answerQuestion['listQuestionChoice'] as $item)
                    <div class="item__question--answer">
                        <label class="m-radio cus primary-color">
                            <input type="radio" value="0" disabled
                                {{ $item['survey_question_choice_id'] == $answerQuestion['survey_question_choice_id'] ? 'checked' : '' }}>
                            <span></span>
                        </label>
                        <div class="content-text__answer">
                            <input type="text" class="form-control" disabled
                               
                                value="{{ $item['survey_question_choice_title'] }}">
                        </div>
                    </div>
                @endforeach
            @else
                @foreach ($answerQuestion['listQuestionChoice'] as $item)
                    <div class="item__question--answer">
                        <label class="m-radio cus primary-color">
                            <input type="radio" value="0" disabled
                                {{ $item['survey_question_choice_id'] == $answerQuestion['survey_question_choice_id'] ? 'checked' : '' }}>
                            <span></span>
                        </label>
                        <div class="content-text__answer">
                            <input type="text" class="form-control" disabled
                                @if ($item['survey_question_choice_id'] == $answerQuestion['survey_question_choice_id'] &&
                                    $item['survey_question_choice_value'] == 1) style="background: rgba(79, 196, 202, 0.2)"
                        @elseif($item['survey_question_choice_id'] == $answerQuestion['survey_question_choice_id'] &&
                            $item['survey_question_choice_value'] == 0)
                        style="background: rgba(255, 0, 0, 0.2)"
                        @else @endif
                                value="{{ $item['survey_question_choice_title'] }}">
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
        @if ($answerQuestion['resultAnswerQuestion'] == 'wrong')
            <div class="list-answer__success">
                <h6>{{ __('Correct answer') }}</h6>
                @foreach ($answerQuestion['listAnswerSuccess'] as $item)
                    <label class="m-radio cus primary-color">
                        <input type="radio" value="0" disabled checked>
                        {{ $item['survey_question_choice_title'] }}
                        <span></span>
                    </label>
                @endforeach
            </div>
        @endif
    </div>
</div>
