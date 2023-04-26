{{-- Câu hỏi --}}
{{-- Box câu hỏi văn bản mô tả --}}
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
                <div class="kt-margin-t-10  kt-width-100pt" style="align-items: center;display: flex; width:100%">
                    <textarea class="form-control" rows="5" placeholder="@lang('Nhập văn bản')"
                        onchange="question.onChangeQuestion(this, '{{ $params['block_number'] }}', '{{ $key }}', 'question')"
                        {{ $params['action_page'] == 'show' ? 'disabled' : '' }}>{{ $item['question'] }}</textarea>
                </div>
                <div class="handle-question d-flex justify-content-end" style="width:100%">
                    <button type="button" style="padding:0" {{ $params['action_page'] == 'show' ? 'disabled' : '' }}
                        class="btn btn-action-list btn-copy-question btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-coppy-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $key }}', 'coppy-page_text', 1, '{{ $item['position'] + 1 }}')">
                        <i class="la la-copy"></i>
                    </button>
                    <button type="button" style="padding:0"
                        class="btn btn-action-list btn-remove-question btn-action-list-{{ $params['block_number'] }}-{{ $key }} button-remove-list-{{ $params['block_number'] }}-{{ $key }} z-index-100 font-size-20-important div-hidden"
                        onclick="question.removeQuestion('{{ $params['block_number'] }}', '{{ $key }}')">
                        <i class="la la-trash"></i>
                    </button>
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
