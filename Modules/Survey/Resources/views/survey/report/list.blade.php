<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
            <tr>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">#</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">@lang('Mã số')</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">@lang('Họ và tên')</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">@lang('Số điện thoại')</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">@lang('Địa chỉ')</p>
                </th>
                <th class="tr_thead_list">
                    <p style="margin-top:10px">@lang('Thời gian thực hiện khảo sát')</p>
                </th>
                <th class="tr_thead_list">
                    <p>@lang('Số câu trả lời')</p>
                </th>
            </tr>
        </thead>
        <tbody>
            @if ($list->count() > 0)
                @foreach ($list as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['full_name'] }}</td>
                        <td>
                            {{ $item['phone'] }}
                        </td>
                        <td>
                            {{ $item['address'] }}
                        </td>
                        <td>
                            {{ $item['create_at_survey'] }}
                        </td>
                        <td>
                            <a href="{{ route('survey.report.item.show', [$item['survey_answer_id']]) }}">
                                {{ $item['total_answer'] . '/' . $item['total_questions'] }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    {{ $list->links('survey::survey.helpers.paging-list-report') }}
</div>
