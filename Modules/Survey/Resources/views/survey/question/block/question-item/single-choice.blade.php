{{-- Câu hỏi --}}
{{-- Box câu hỏi trắc nghiệm --}}
<div class="kt-padding-r-0 kt-margin-b-0 div_question_item div_question_item_{{ $params['block_number'] }} div_question_item_{{ $params['block_number'] }}_{{ $key }} pn-pointer"
    onclick="question.selectedQuestion('{{ $params['block_number'] }}', '{{ $key }}', 1)">
    <div class="row border-top-1px border-bottom-1px kt-margin-l-0 kt-margin-r-15">
        <div class="col-lg-1 border-right-1px text-center">
            <div class="vertical-center" style="margin-left: 0px">
                @lang('Câu hỏi') {{ $key + 1 }}
                <br>
                <i class="fa fa-arrows-alt icon-move" data-block-number="{{ $params['block_number'] }}"
                    data-position="{{ $item['position'] }}" style="font-size: 20px;"></i>
            </div>
        </div>
        <div class="col-lg-10 kt-padding-b-20">
            <div class="question-field">
                @if ($item['countPoint'] == 1)
                    <div class="" style="display : flex; width: 100%;">
                        <div class="col-lg-10">
                            <input type="text" name="" id="" class="form-control kt-width-100pt"
                                placeholder="@lang('Nhập câu hỏi')" value="{{ $item['question'] }}"
                                onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'question')"
                                {{ $params['action_page'] == 'show' ? 'disabled' : '' }}>
                        </div>
                        <div class="col-lg-2"
                            style="padding:0 ;display: flex;
                        align-items: center;
                        color: #787878;
                        gap: 10px;">
                            <input type="text" name=""
                                {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                                onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'total_point')"
                                value="{{ $item['totalPoint'] }}" class="form-control numeric" id="">
                            {{ __('Điểm') }}
                        </div>
                    </div>
                @else
                    <input type="text" name="" id="" class="form-control kt-width-100pt"
                        placeholder="@lang('Nhập câu hỏi')" value="{{ $item['question']}}"
                        onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'question')"
                        {{ $params['action_page'] == 'show' || isset($item['question_pl_defaul']) ? 'disabled' : '' }}>
                @endif
                @foreach ($item['answer'] as $ka => $vAnswer)
                    <div class="form-check multiple-radio kt-margin-t-10 kt-width-100pt"
                        style="align-items: center;display: flex;">
                        @if ($item['survey_question_type'] == 'multi_choice')
                            <label class="kt-checkbox kt-checkbox--bold kt-margin-b-25">
                                <input {{ $item['countPoint'] != 1 ? 'disabled' : '' }}
                                    {{ $item['countPoint'] == 1 && isset($item['answer_success']) && is_array($item['answer_success']) && in_array($ka, $item['answer_success']) ? 'checked' : '' }}
                                    {{ $params['action_page'] == 'show' || isset($item['question_pl_defaul']) ? 'disabled' : '' }}
                                    @if ($item['countPoint'] == 1) onclick="question.oncheckedAnswerQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'checked', '{{ $ka }}' , null, 'multi_choice')" @endif
                                    type="checkbox">
                                <span></span>
                            </label>
                        @else
                            <label class="kt-radio kt-radio--bold kt-radio--brand kt-margin-b-25">
                                <input name="input-checked-{{ $params['block_number'] . '-' . $key }}"
                                    {{ $item['countPoint'] != 1 ? 'disabled' : '' }}
                                    {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                                    {{ $item['countPoint'] == 1 && $item['answer_success'] == $ka ? 'checked' : '' }}
                                    @if ($item['countPoint'] == 1) onclick="question.oncheckedAnswerQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'checked', '{{ $ka }}' , null,)" @endif
                                    type="radio">
                                <span></span>
                            </label>
                        @endif
                        @if ($item['countPoint'] == 1)
                            @if ($item['survey_question_type'] == 'multi_choice')
                                <input type="text" name="" id=""
                                    class="form-control question-text__answer 
                                    {{ isset($item['answer_success']) && is_array($item['answer_success']) && in_array($ka, $item['answer_success']) ? 'background-checked__answer--success' : '' }}"
                                    placeholder="@lang('Nhập đáp án')" value="{{ $vAnswer }}"
                                    onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'answer', '{{ $ka }}')"
                                    {{ $params['action_page'] == 'show' ? 'disabled' : '' }}>
                                <i class="fa fa-check icon-check__answer--success {{ isset($item['answer_success']) && is_array($item['answer_success']) && in_array($ka, $item['answer_success']) ? '' : 'icon-check__answer--hiden' }} "
                                    style="color: #4FC4CA !important;"></i>
                            @else
                                <input type="text" name="" id=""
                                    class="form-control question-text__answer {{ $item['answer_success'] == $ka ? 'background-checked__answer--success' : '' }}"
                                    placeholder="@lang('Nhập đáp án')" value="{{ $vAnswer }}"
                                    onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'answer', '{{ $ka }}')"
                                    {{ $params['action_page'] == 'show' ? 'disabled' : '' }}>

                                <i class="fa fa-check icon-check__answer--success {{ $item['answer_success'] == $ka ? '' : 'icon-check__answer--hiden' }} "
                                    style="color: #4FC4CA !important;"></i>
                            @endif
                        @else
                            <input type="text" name="" id=""
                                class="form-control question-text__answer" placeholder="@lang('Nhập đáp án')"
                                value="{{ $vAnswer }}"
                                onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'answer', '{{ $ka }}')"
                                {{ $params['action_page'] == 'show' ? 'disabled' : '' }}>
                            <i class="fa fa-check icon-check__answer--success icon-check__answer--hiden"
                                style="color: #4FC4CA !important;"></i>
                        @endif
                    </div>
                @endforeach
                @if ($item['countPoint'] != 1 && !isset($item['question_pl_defaul']))
                    <div class="handle-question d-flex justify-content-end" style="width:100%">
                        <button type="button" style="padding:0"
                            {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                            class="btn btn-action-list btn-copy-question btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-coppy-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                            @if ($item['survey_question_type'] == 'multi_choice') onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $key }}', 'coppy-multi_choice', 1, '{{ $item['position'] + 1 }}')">
                            @else
                            onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $key }}', 'coppy-single_choice', 1, '{{ $item['position'] + 1 }}')"> @endif
                            <i class="la la-copy"></i>
                        </button>
                        <button type="button" style="padding:0"
                            {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                            class="btn btn-action-list btn-remove-question btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-remove-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                            onclick="question.removeQuestion('{{ $params['block_number'] }}', '{{ $key }}')">
                            <i class="la la-trash"></i>
                        </button>
                    </div>
                @elseif ($item['countPoint'] == 1 && !isset($item['question_pl_defaul']))
                    <div class="handle-question d-flex justify-content-between"
                        style="width:100%; align-items: center; margin-top: 15px;">
                        <div class="count-point" style="color:#0000009c;">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            {{ __('Chọn đáp án chính xác') . ' ' . $data['totalPointDefault'] . ' ' . __('Điểm') }}
                        </div>
                        <div>
                            <button type="button" style="padding:0"
                                {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                                class="btn btn-action-list btn-copy-question btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-coppy-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                                @if ($item['survey_question_type'] == 'multi_choice') onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $key }}', 'coppy-multi_choice', 1, '{{ $item['position'] + 1 }}')">
                            @else
                            onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $key }}', 'coppy-single_choice', 1, '{{ $item['position'] + 1 }}')"> @endif
                                <i class="la la-copy"></i>
                            </button>
                            <button type="button" style="padding:0"
                                {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                                class="btn btn-action-list btn-remove-question btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-remove-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                                onclick="question.removeQuestion('{{ $params['block_number'] }}', '{{ $key }}')">
                                <i class="la la-trash"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-1">
            @if ($params['action_page'] != 'show')
                <div class="action-field">
                    <button type="button"
                        class="btn btn-icon btn-success btn-circle btn-action-list btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-up-list button-up-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $key - 1 }}', null, 1, '{{ $item['position'] }}')">
                        +
                    </button>
                    <button type="button"
                        class="btn btn-icon btn-success btn-circle btn-action-list btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-down-list button-down-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $key + 1 }}', null, 1, '{{ $item['position'] + 1 }}')">
                        +
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
