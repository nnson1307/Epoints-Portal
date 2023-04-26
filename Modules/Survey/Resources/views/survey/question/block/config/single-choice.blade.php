<div class="form-group kt-margin-b-10">
    <p>@lang('Tùy chỉnh đáp án')</p>
</div>
<div class="form-group kt-margin-b-10">
    <div class="row">
        <div class="col-lg-12">
            <button type="button"
                    class="btn btn-icon btn-default btn-circle font-size-20-important btn-custom-answer kt-margin-l-15 kt-margin-r-10 kt-margin-b-10"
                    onclick="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'up')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
                +
            </button>
            <span class="text-black quantity-{{$params['block_number']}}-{{$params['question_number']}}"
                  style="font-size: 20px"
                  data-quantity="{{count($data['answer'])}}">
                    {{count($data['answer'])}}
                </span>
            <button type="button"
                    class="btn btn-icon btn-success btn-circle font-size-20-important btn-custom-answer kt-margin-l-10 kt-margin-b-10"
                    {{count($data['answer']) <= 2 ? 'disabled' : ''}}
                    onclick="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'down')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
                -
            </button>
        </div>
    </div>
</div>
<div class="form-group kt-margin-b-10">
    <p>@lang('Hình thức trả lời')</p>
</div>
<div class="form-group">
    <div class="kt-radio-list kt-margin-l-15">
        <label class="kt-radio m-radio kt-radio--brand">
            <input type="radio" name="survey_question_type" value="single_choice"
                   {{$data['survey_question_type'] == 'single_choice' ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'survey_question_type')"
                    {{$params['action_page'] ==  'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Chỉ chọn được 1 đáp án')
            <span></span>
        </label>
        <label class="kt-radio m-radio kt-radio--brand">
            <input type="radio" name="survey_question_type" value="multi_choice"
                   {{$data['survey_question_type'] == 'multi_choice' ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'survey_question_type')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Có thể chọn nhiều đáp án')
            <span></span>
        </label>
    </div>
</div>
<div class="form-group kt-margin-b-10">
    <p>@lang('Bắt buộc trả lời')</p>
</div>
<div class="form-group kt-margin-b-10">
    <div class="kt-radio-list kt-margin-l-15">
        <label class="kt-radio m-radio kt-radio--brand">
            <input type="radio" name="is_required" value="1"
                   {{$data['is_required'] == '1' ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'is_required')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Có')
            <span></span>
        </label>
        <label class="kt-radio m-radio kt-radio--brand">
            <input type="radio" name="is_required" value="0"
                   {{$data['is_required'] == '0' ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'is_required')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Không')
            <span></span>
        </label>
    </div>
</div>
