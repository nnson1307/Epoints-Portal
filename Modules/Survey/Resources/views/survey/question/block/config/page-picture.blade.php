<div class="form-group kt-margin-b-10">
    <p>@lang('Tùy chỉnh số ảnh')</p>
</div>
<div class="form-group kt-margin-b-10">
    <div class="row">
        <div class="col-lg-12">
            <button type="button"
                    class="btn btn-icon btn-default btn-circle font-size-20-important btn-custom-answer kt-margin-l-15 kt-margin-r-10 kt-margin-b-10"
                    {{count($data['image']) >= 8 || $params['action_page'] == 'show' ? 'disabled' : ''}}
                    onclick="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'up', null, 'page_picture')">
                +
            </button>
            <span class="text-black quantity-{{$params['block_number']}}-{{$params['question_number']}}"
                  style="font-size: 20px"
                  data-quantity="{{count($data['image'])}}">
                    {{count($data['image'])}}
                </span>
            <button type="button"
                    class="btn btn-icon btn-success btn-circle font-size-20-important btn-custom-answer kt-margin-l-10 kt-margin-b-10"
                    {{count($data['image']) <= 1 || $params['action_page'] == 'show' ? 'disabled' : ''}}
                    onclick="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'down', null, 'page_picture')">
                -
            </button>
        </div>
    </div>
</div>
<div class="form-group kt-margin-b-10">
    <i>
        @lang('Tổi thiểu 1 ảnh và tối đa 8 ảnh')
    </i>
</div>