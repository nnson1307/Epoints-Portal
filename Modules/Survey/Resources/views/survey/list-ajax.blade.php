<div class="kt-section__content">
    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th><p>@lang('Mã khảo sát')</p></th>
            <th><p>@lang('Tên khảo sát')</p></th>
            <th><p>@lang('Thời gian tạo')</p></th>
            <th><p>@lang('Trạng thái hiển thị')</p></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($list as $item)
            <tr>
                <td>
                    <label class="kt-radio">
                        <input type="radio"
                               name="survey_radio"
                               {{isset($filter['end_point_value'])
                                && $filter['end_point_value'] == $item['survey_id'] ? 'checked' : ''}}
                               value="{{$item['survey_id']}}"
                               data-name="{{ $item['survey_name'] }}"
                        >
                        <span></span>
                    </label>
                </td>
                <td>{{$item['survey_code']}}</td>
                <td>{{$item['survey_name']}}</td>
                <td>
                    @if(!empty($item['created_at']))
                        {{(new DateTime($item['created_at']))->format('H:i:s d/m/Y')}}
                    @endif
                </td>
                <td>
                    @if($item['status'] == 'N')
                        @lang('Bản nháp')
                    @elseif($item['status'] == 'R')
                        @lang('Đã duyệt')
                    @elseif($item['status'] == 'C')
                        @lang('Kết thúc')
                    @elseif($item['status'] == 'D')
                        @lang('Từ chối')
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{$list->appends($filter)->links('survey::survey.helpers.paging-list-ajax')}}
</div>
