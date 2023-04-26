@if(count($data['question']) > 0)
    @foreach($data['question'] as $key => $item)
        @if($item['survey_question_type'] == 'single_choice' || $item['survey_question_type'] == 'multi_choice')
            @include('survey::survey.question.block.question-item.single-choice')
        @elseif($item['survey_question_type'] == 'text')
            @include('survey::survey.question.block.question-item.text')
        @elseif($item['survey_question_type'] == 'page_text')
            @include('survey::survey.question.block.question-item.description')
        @elseif($item['survey_question_type'] == 'page_picture')
            @include('survey::survey.question.block.question-item.page-picture')
        @endif
    @endforeach
@endif