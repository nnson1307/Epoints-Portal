<div class="item-question_answer row question_mutiple_choice">
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
            <div class="list-question-answer_mutiple_choice">
                @foreach ($answerQuestion['listQuestionChoice'] as $item)
                    @if ($answerQuestion['count_point'] != 1)
                        <div class="item-question_mutiple_choice col-lg-12">
                            @if (!is_array($answerQuestion['survey_question_choice_id']))
                                @if ($item['survey_question_choice_id'] == $answerQuestion['survey_question_choice_id'])
                                    <input type="checkbox" checked readonly>
                                @else
                                    <input type="checkbox" disabled>
                                @endif
                            @elseif (in_array($item['survey_question_choice_id'], $answerQuestion['survey_question_choice_id']))
                                <input type="checkbox" checked readonly>
                            @else
                                <input type="checkbox" disabled>
                            @endif
                            <input type="text" value="{{ $item['survey_question_choice_title'] }}" class="title"
                                disabled>

                        </div>
                    @else
                        <div class="item-question_mutiple_choice col-lg-12">
                            @if (!is_array($answerQuestion['survey_question_choice_id']))
                                @if ($item['survey_question_choice_id'] == $answerQuestion['survey_question_choice_id'])
                                    @if ($item['survey_question_choice_value'] == 1)
                                        <input type="checkbox" checked readonly>
                                    @elseif ($item['survey_question_choice_value'] == 0)
                                        <input type="checkbox" checked disabled readonly>
                                    @endif
                                @else
                                    <input type="checkbox" disabled>
                                @endif
                            @elseif (in_array($item['survey_question_choice_id'], $answerQuestion['survey_question_choice_id']))
                                @if ($item['survey_question_choice_value'] == 1)
                                    <input type="checkbox" checked readonly>
                                @elseif ($item['survey_question_choice_value'] == 0)
                                    <input type="checkbox" checked disabled readonly>
                                @endif
                            @else
                                <input type="checkbox" disabled>
                            @endif

                            @if (!is_array($answerQuestion['survey_question_choice_id']))
                                @if ($item['survey_question_choice_value'] == 1 &&
                                    $item['survey_question_choice_id'] == $answerQuestion['survey_question_choice_id'])
                                    <input type="text" style="background: rgba(79, 196, 202, 0.2)"
                                        value="{{ $item['survey_question_choice_title'] }}" class="title" disabled>
                                @elseif ($item['survey_question_choice_value'] == 0 &&
                                    $item['survey_question_choice_id'] == $answerQuestion['survey_question_choice_id'])
                                    <input type="text" style="background: rgba(255, 0, 0, 0.2)"
                                        value="{{ $item['survey_question_choice_title'] }}" class="title" disabled>
                                @else
                                    <input type="text" value="{{ $item['survey_question_choice_title'] }}"
                                        class="title" disabled>
                                @endif
                            @elseif($item['survey_question_choice_value'] == 1 &&
                                in_array($item['survey_question_choice_id'], $answerQuestion['survey_question_choice_id']))
                                <input type="text" style="background: rgba(79, 196, 202, 0.2)"
                                    value="{{ $item['survey_question_choice_title'] }}" class="title" disabled>
                            @elseif ($item['survey_question_choice_value'] == 0 &&
                                in_array($item['survey_question_choice_id'], $answerQuestion['survey_question_choice_id']))
                                <input type="text" style="background: rgba(255, 0, 0, 0.2)"
                                    value="{{ $item['survey_question_choice_title'] }}" class="title" disabled>
                            @else
                                <input type="text" value="{{ $item['survey_question_choice_title'] }}"
                                    class="title" disabled>
                            @endif

                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @if ($answerQuestion['resultAnswerQuestion'] == 'wrong')
            <div class="content-question_answer">
                <h6
                    style="color: black;
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 15px;">
                    {{ __('Correct answer') }}</h6>
                <div class="list-question-answer_mutiple_choice">
                    @foreach ($answerQuestion['listAnswerSuccess'] as $item)
                        <div class="item-question_mutiple_choice col-lg-12">
                            <input type="checkbox" checked readonly>
                            <input type="text" value="{{ $item['survey_question_choice_title'] }}" disabled
                                class="title">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
