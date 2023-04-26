<div class="form-group kt-margin-b-10">
    <p>@lang('Bắt buộc trả lời')</p>
</div>
<div class="form-group kt-margin-b-10">
    <div class="kt-radio-list kt-margin-l-15">
        <label class="kt-radio m-radio  kt-radio--brand">
          
            <input type="radio" name="is_required" value="1"
                   {{$data['is_required'] == 1 ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'is_required')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Có')
            <span></span>
        </label>
        <label class="kt-radio m-radio  kt-radio--brand">
            <input type="radio" name="is_required" value="0"
                   {{$data['is_required'] == 0 ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'is_required')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Không')
            <span></span>
        </label>
    </div>
</div>
<div class="form-group kt-margin-b-10">
    <p>@lang('Loại xác nhận')</p>
</div>
<div class="form-group kt-margin-b-10">
    <div class="kt-radio-list kt-margin-l-15">
        <label class="kt-radio m-radio pb-2 kt-radio--brand">
            <input type="radio" name="confirm_type" value="none"
                   {{$data['confirm_type'] == 'none' ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Không')
            <span></span>
        </label>
        <label class="kt-radio m-radio pb-2 kt-radio--brand">
            <input type="radio"
                   name="confirm_type"
                   value="min"
                   {{$data['confirm_type'] == 'min' ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Số kí tự tối thiểu')
            <span></span>
        </label>
    </div>
</div>
<div class="form-group kt-margin-b-10 kt-margin-l-45 div_min div_config_hidden {{$data['confirm_type'] == 'min' ? '' : 'div-hidden'}}">
    <input type="text" class="form-control numeric"
           name="min_value"
           value="{{$data['min_value']}}"
           placeholder="@lang('Nhập số kí tự tối thiểu')"
           onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'min_value')"
            {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
</div>
<div class="form-group kt-margin-b-10">
    <div class="kt-radio-list kt-margin-l-15">
        <label class="kt-radio m-radio pb-2 kt-radio--brand">
            <input type="radio"
                   name="confirm_type"
                   value="max"
                   {{$data['confirm_type'] == 'max' ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Số kí tự tối đa')
            <span></span>
        </label>
    </div>
</div>
<div class="form-group kt-margin-b-10 kt-margin-l-45 div_max div_config_hidden {{$data['confirm_type'] == 'max' ? '' : 'div-hidden'}}">
    <input type="text" class="form-control numeric"
           name="max_value"
           value="{{$data['max_value']}}"
           placeholder="@lang('Nhập số kí tự tối đa')"
           onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'max_value')"
            {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
</div>
<div class="form-group kt-margin-b-10">
    <div class="kt-radio-list kt-margin-l-15">
        <label class="kt-radio m-radio  kt-radio--brand">
            <input type="radio"
                   name="confirm_type"
                   value="digits_between"
                   {{$data['confirm_type'] == 'digits_between' ? 'checked' : ''}}
                   onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Chọn số kí tự')
            <span></span>
        </label>
    </div>
</div>
<div class="form-group kt-margin-b-10 div_digits_between div_config_hidden {{$data['confirm_type'] == 'digits_between' ? '' : 'div-hidden'}}">
    <p class="kt-margin-l-50" style="color: #acadae;">@lang('Số kí tự tối thiểu')</p>
</div>
<div class="form-group kt-margin-b-10 kt-margin-l-45 div_digits_between div_config_hidden {{$data['confirm_type'] == 'digits_between' ? '' : 'div-hidden'}}">
    <input type="text" class="form-control numeric"
           name="min_value"
           value="{{$data['min_value']}}"
           placeholder="@lang('Nhập số kí tự tối thiểu')"
           onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'min_value')"
            {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
</div>
<div class="form-group kt-margin-b-10 div_digits_between div_config_hidden {{$data['confirm_type'] == 'digits_between' ? '' : 'div-hidden'}}">
    <p class="kt-margin-l-50" style="color: #acadae;">@lang('Số kí tự tối đa')</p>
</div>
<div class="form-group kt-margin-b-10 kt-margin-l-45 div_digits_between div_config_hidden {{$data['confirm_type'] == 'digits_between' ? '' : 'div-hidden'}}">
    <input type="text" class="form-control numeric"
           name="max_value"
           value="{{$data['max_value']}}"
           placeholder="@lang('Nhập số kí tự tối đa')"
           onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'max_value')"
            {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
</div>
<div class="form-group kt-margin-b-10">
    <div class="kt-radio-list kt-margin-l-15">
        <label class="kt-radio m-radio  kt-radio--brand">
            <input type="radio"
                   name="is_confirm_content"
                   value="is_confirm_content"
                   {{in_array($data['confirm_type'], ['email', 'phone', 'date_format', 'numeric']) ? 'checked' : ''}}
                   onclick="question.onChangeIsConfirmContent(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')"
                    {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
            @lang('Xác nhận nội dung')
            <span></span>
        </label>
    </div>
</div>
<div class="form-group kt-margin-b-10">
    <div class="kt-radio-list kt-margin-l-45">
        <label class="kt-radio m-radio  kt-radio--brand">
            @if($params['action_page'] != 'show' && !isset($data['question_pl_defaul']))
                <input {{in_array($data['confirm_type'], ['email', 'phone', 'date_format', 'numeric']) ? '' : 'disabled'}}
                       type="radio"
                       name="confirm_type"
                       value="email"
                       {{$data['confirm_type'] == 'email' ? 'checked' : ''}}
                       onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')">
            @else
                <input disabled
                       type="radio"
                       name="confirm_type"
                       value="email"
                        {{$data['confirm_type'] == 'email' ? 'checked' : ''}}>
            @endif
            @lang('Địa chỉ email')
            <span></span>
        </label>
        <label class="kt-radio m-radio  kt-radio--brand">
            @if($params['action_page'] != 'show' && !isset($data['question_pl_defaul']))
                <input {{in_array($data['confirm_type'], ['email', 'phone', 'date_format', 'numeric']) ? '' : 'disabled'}}
                       type="radio"
                       name="confirm_type"
                       value="phone"
                       {{$data['confirm_type'] == 'phone' ? 'checked' : ''}}
                       onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')">
            @else
                <input disabled
                       type="radio"
                       name="confirm_type"
                       value="phone"
                        {{$data['confirm_type'] == 'phone' ? 'checked' : ''}}>
            @endif
            @lang('Số điện thoại')
            <span></span>
        </label>
        <label class="kt-radio m-radio  kt-radio--brand">
            @if($params['action_page'] != 'show' && !isset($data['question_pl_defaul']))
                <input {{in_array($data['confirm_type'], ['email', 'phone', 'date_format', 'numeric']) ? '' : 'disabled'}}
                       type="radio"
                       name="confirm_type"
                       value="date_format"
                       {{$data['confirm_type'] == 'date_format' ? 'checked' : ''}}
                       onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')">
            @else
                <input disabled
                       type="radio"
                       name="confirm_type"
                       value="date_format"
                        {{$data['confirm_type'] == 'date_format' ? 'checked' : ''}}>
            @endif
            @lang('Định dạng ngày')
            <span></span>
        </label>
        <label class="kt-radio m-radio  kt-radio--brand">
            @if($params['action_page'] != 'show' && !isset($data['question_pl_defaul']))
                <input {{in_array($data['confirm_type'], ['email', 'phone', 'date_format', 'numeric']) ? '' : 'disabled'}}
                       type="radio"
                       name="confirm_type"
                       value="numeric"
                       {{$data['confirm_type'] == 'numeric' ? 'checked' : ''}}
                       onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'confirm_type')">
            @else
                <input disabled
                       type="radio"
                       name="confirm_type"
                       value="numeric"
                        {{$data['confirm_type'] == 'numeric' ? 'checked' : ''}}>
            @endif
            @lang('Number')
            <span></span>
        </label>
    </div>
</div>
<div class="form-group kt-margin-b-10 div_numeric div_config_hidden {{$data['confirm_type'] == 'numeric' ? '' : 'div-hidden'}}">
    <p class="kt-margin-l-50" style="color: #acadae;">@lang('Giá trị nhỏ nhất')</p>
</div>
<div class="form-group kt-margin-b-10 kt-margin-l-45 div_numeric div_config_hidden {{$data['confirm_type'] == 'numeric' ? '' : 'div-hidden'}}">
    <input type="text" class="form-control numeric"
           name="min_value"
           value="{{$data['min_value']}}"
           placeholder="@lang('')"
           onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'min_value')"
            {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
</div>
<div class="form-group kt-margin-b-10 div_numeric div_config_hidden {{$data['confirm_type'] == 'numeric' ? '' : 'div-hidden'}}">
    <p class="kt-margin-l-50" style="color: #acadae;">@lang('Giá trị lớn nhất')</p>
</div>
<div class="form-group kt-margin-b-10 kt-margin-l-45 div_numeric div_config_hidden {{$data['confirm_type'] == 'numeric' ? '' : 'div-hidden'}}">
    <input type="text" class="form-control numeric"
           name="max_value"
           value="{{$data['max_value']}}"
           placeholder="@lang('')"
           onchange="question.onChangeQuestion(this, '{{$params['block_number']}}', '{{$params['question_number']}}', 'max_value')"
            {{$params['action_page'] == 'show' || isset($data['question_pl_defaul']) ? 'disabled' : ''}}>
</div>

