<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Loại câu hỏi')}}</th>
            <th class="tr_thead_list">{{__('Câu hỏi')}}</th>
            <th class="tr_thead_list">{{__('Người hỏi')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th class="tr_thead_list">{{__('Ngày tạo')}}</th>
            <th class="tr_thead_list">{{__('Trả lời')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                @php($num = rand(0,7))

                <tr>
                    @if(isset($page))
                        <td class="text_middle">{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td class="text_middle">{{$key+1}}</td>
                    @endif
                    <td>
{{--                        @if(in_array('admin.log.question-customer.detail',session('routeList')))--}}
                            <a href="{{route('admin.log.question-customer.detail',['id' => $item['feedback_question_id']])}}" style="color:#464646" class="m-link">
                                @if($item['feedback_question_type'] =='rating')
                                    {{__('Câu hỏi đánh giá')}}
                                @else
                                    {{__('Câu hỏi dạng phát biểu cảm nghĩ')}}
                                @endif
                            </a>
{{--                        @else--}}
{{--                            @if($item['feedback_question_type'] =='rating')--}}
{{--                                {{__('Câu hỏi đánh giá')}}--}}
{{--                            @else--}}
{{--                                {{__('Câu hỏi dạng phát biểu cảm nghĩ')}}--}}
{{--                            @endif--}}
{{--                        @endif--}}
                    </td>
                    <td class="text_middle" title="{{$item['feedback_question_title']}}">{{subString($item['feedback_question_title'])}}</td>
                    <td class="text_middle">{{$item['full_name']}}</td>
                    <td class="text_middle">{{$item['feedback_question_active'] == 1 ? __('Hiển thị'): __('Ẩn')}}</td>
                    <td class="text_middle">{{date("d/m/Y",strtotime($item['created_at']))}}</td>
                    <td>
                        @if(isset($item['feedback_answer_id']) && $item['feedback_answer_id'] != null)
                            <a onclick="log.popupEditAnswer({{$item['feedback_answer_id']}})"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                               title="Edit answer">
                                <i class="la la-edit"></i>
                            </a>
                            <a onclick="log.removeAnswer({{$item['feedback_answer_id']}})"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                               title="Delete answer">
                                <i class="la la-trash"></i>
                            </a>
                        @else
                            {{--                        @if(in_array('admin.log.question-customer.answer',session('routeList')))--}}
                            <a onclick="log.popupAnswer({{$item['feedback_question_id']}})"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                               title="Answer">
                                <i class="la la-reply"></i>
                            </a>
                            {{--                        @endif--}}
                        @endif
                    </td>
                </tr>

            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}

