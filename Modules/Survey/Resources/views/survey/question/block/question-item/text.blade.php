{{-- Câu hỏi --}}
{{-- Box câu hỏi tự luận --}}
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
                    <div class="" style="display : flex; width: 100%; gap: 15px;">
                        <div class="col-lg-10" style="padding: 0px">
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
                                onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'total_point')"
                                {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                                value="{{ $item['totalPoint'] }}" class="form-control numeric" id="">
                            {{ __('Điểm') }}
                        </div>
                    </div>
                @else
                    <input type="text" name="" id="" class="form-control kt-width-100pt"
                        placeholder="@lang('Nhập câu hỏi')" value="{{ $item['question'] }}"
                        onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'question')"
                        {{ $params['action_page'] == 'show' || isset($item['question_pl_defaul']) ? 'disabled' : '' }}>
                @endif
                <div class="kt-margin-t-10 kt-width-100pt" style="align-items: center;display: flex;">
                    @if ($item['countPoint'] == 1)
                        <textarea class="form-control" @if (!empty($item['value_text'])) style = 'background: #4fc4ca54 !important;' @endif
                            {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                            onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'value_text')"
                            rows="5">{{ $item['value_text'] }}</textarea>
                    @else
                        <textarea class="form-control" disabled></textarea>
                    @endif
                </div>
                <div class="handle-question d-flex justify-content-end" style="width:100%">
                    @if (!isset($item['question_pl_defaul']))
                        <button type="button" style="padding:0"
                            {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                            class="btn btn-action-list btn-copy-question btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-coppy-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                            onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $key }}', 'coppy-text', 1, '{{ $item['position'] + 1 }}')">
                            <i class="la la-copy"></i>
                        </button>
                        <button type="button" style="padding:0"
                            {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                            class="btn btn-action-list btn-remove-question btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-remove-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                            onclick="question.removeQuestion('{{ $params['block_number'] }}', '{{ $key }}')">
                            <i class="la la-trash"></i>
                        </button>
                    @endif
                </div>
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
