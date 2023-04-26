@if (isset($isPoint) && $isPoint == 1)
<div class="form-group kt-margin-t-10 kt-margin-b-10">
    <p style="color:#000000; font-weight:bold; font-size:16px;">{{ __('Tổng số điểm hiện tại là') }} : <span style="color:red; font-weight:bold; font-size:16px;">{{ $totalPoint }}</span> </p>
</div>
@endif
<div class="form-group kt-margin-t-10 kt-margin-b-10">
    <p style="color:#000000; font-weight:bold;">@lang('Loại câu hỏi')</p>
</div>
<div class="form-group kt-margin-b-10">
    @if ($params['action_page'] != 'show')
        <button class="btn btn-primary kt-width-100pt btn-259b24" type="button" id="btn-question-type"
            onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', null, 0, null, 1)">
            <i class="fa fa-align-justify float-left kt-padding-10"></i>
        @else
            <button class="btn btn-primary kt-width-100pt btn-259b24" type="button" id="btn-question-type"
                style="cursor: context-menu;">
                <i class="fa fa-align-justify float-left kt-padding-10" style="cursor: context-menu;"></i>
    @endif
    <span class="float-left">
        @if ($data['survey_question_type'] == 'single_choice' || $data['survey_question_type'] == 'multi_choice')
            {{ __('Trắc nghiệm') }}
        @elseif($data['survey_question_type'] == 'text')
            {{ __('Tự luận') }}
        @elseif($data['survey_question_type'] == 'page_text')
            {{ __('Văn bản mô tả') }}
        @elseif($data['survey_question_type'] == 'page_picture')
            {{ __('Hình ảnh minh họa') }}
        @endif
    </span>
    <i class="fa fa-angle-down float-right kt-padding-10"></i>
    </button>
</div>
@if ($data['survey_question_type'] == 'single_choice' || $data['survey_question_type'] == 'multi_choice')
    @include('survey::survey.question.block.config.single-choice')
@elseif($data['survey_question_type'] == 'text')
    @include('survey::survey.question.block.config.text')
@elseif($data['survey_question_type'] == 'page_text')
    @include('survey::survey.question.block.config.description')
@elseif($data['survey_question_type'] == 'page_picture')
    @include('survey::survey.question.block.config.page-picture')
@endif
