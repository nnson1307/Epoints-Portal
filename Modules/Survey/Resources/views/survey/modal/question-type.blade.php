<div class="modal fade" id="modal_question_type" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        @if (!$countPoint)
                            <tr>
                                <td class="type_select_question">@lang('Nội dung tĩnh')</td>
                                <td>
                                    <p class="pn-pointer"
                                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', 'page_text', '{{ $params['add_custom'] }}', '{{ $params['position'] }}', '{{ $params['change_question'] }}')">
                                        @lang('Văn bản mô tả')
                                    </p>
                                    <p class="pn-pointer"
                                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', 'page_picture', '{{ $params['add_custom'] }}', '{{ $params['position'] }}', '{{ $params['change_question'] }}')">
                                        @lang('Hình ảnh minh họa')
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="type_select_question">
                                    @lang('Các loại câu hỏi')</td>
                                <td>
                                    <p class="pn-pointer"
                                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', 'single_choice', '{{ $params['add_custom'] }}', '{{ $params['position'] }}', '{{ $params['change_question'] }}')">
                                        @lang('Trắc nghiệm')
                                    </p>
                                    {{-- <p class="pn-pointer" --}}
                                    {{-- onclick="question.addQuestion('{{$params['block_number']}}', '{{$params['question_number']}}', 'matrix_single', '{{$params['add_custom']}}', '{{$params['position']}}', '{{$params['change_question']}}')"> --}}
                                    {{-- @lang('Bảng ma trận') --}}
                                    {{-- </p> --}}
                                    <p class="pn-pointer"
                                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', 'text', '{{ $params['add_custom'] }}', '{{ $params['position'] }}', '{{ $params['change_question'] }}')">
                                        @lang('Tự luận')
                                    </p>
                                    {{-- <p class="pn-pointer" --}}
                                    {{-- onclick="question.addQuestion('{{$params['block_number']}}', '{{$params['question_number']}}', 'Chưa define', '{{$params['add_custom']}}', '{{$params['position']}}', '{{$params['change_question']}}')"> --}}
                                    {{-- @lang('Chụp ảnh') --}}
                                    {{-- </p> --}}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="type_select_question">@lang('Nội dung tĩnh')</td>
                                <td>
                                    <p class="pn-pointer"
                                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', 'page_text', '{{ $params['add_custom'] }}', '{{ $params['position'] }}', '{{ $params['change_question'] }}')">
                                        @lang('Văn bản mô tả')
                                    </p>
                                    <p class="pn-pointer"
                                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', 'page_picture', '{{ $params['add_custom'] }}', '{{ $params['position'] }}', '{{ $params['change_question'] }}')">
                                        @lang('Hình ảnh minh họa')
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="type_select_question" style="vertical-align: middle;">
                                    @lang('Các loại câu hỏi')</td>
                                <td>
                                    <p class="pn-pointer"
                                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', 'single_choice', '{{ $params['add_custom'] }}', '{{ $params['position'] }}', '{{ $params['change_question'] }}' , 1)">
                                        @lang('Trắc nghiệm')
                                    </p>
                                    {{-- <p class="pn-pointer" --}}
                                    {{-- onclick="question.addQuestion('{{$params['block_number']}}', '{{$params['question_number']}}', 'matrix_single', '{{$params['add_custom']}}', '{{$params['position']}}', '{{$params['change_question']}}')"> --}}
                                    {{-- @lang('Bảng ma trận') --}}
                                    {{-- </p> --}}
                                    <p class="pn-pointer"
                                        onclick="question.addQuestion('{{ $params['block_number'] }}', '{{ $params['question_number'] }}', 'text', '{{ $params['add_custom'] }}', '{{ $params['position'] }}', '{{ $params['change_question'] }}' ,1)">
                                        @lang('Tự luận')
                                    </p>
                                    {{-- <p class="pn-pointer" --}}
                                    {{-- onclick="question.addQuestion('{{$params['block_number']}}', '{{$params['question_number']}}', 'Chưa define', '{{$params['add_custom']}}', '{{$params['position']}}', '{{$params['change_question']}}')"> --}}
                                    {{-- @lang('Chụp ảnh') --}}
                                    {{-- </p> --}}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
