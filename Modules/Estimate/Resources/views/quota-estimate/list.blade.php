<div class="table-responsive" style="">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">{{__('Thời gian')}}</th>
            <th class="tr_thead_list">{{__('Số giờ làm việc tối thiểu (giờ)')}}</th>
            <th class="tr_thead_list">{{__('Ngân sách lương dự kiến')}} ({{ __('VNĐ') }})</th>
            <th></th>
        </tr>

        </thead>
        <tbody>
        @if(!empty($data))
            @foreach ($data as $key => $item)
                <tr>
                    @if ($item['type'] == 'W')
                        @php
                            $week = Modules\Estimate\Libs\help\Help::getStartEndDateOfWeek($item['week']);
                        @endphp
                        <td>{{ __('Tuần') }} {{$item['week'] .' ('. $week[0] .' - '. $week[1] .')'}}</td>
                    @elseif ($item['type'] == 'M')
                        <td>{{ __('Tháng') }} {{ $item['month'] .'/'. $item['year'] }}</td>
                    @endif
                    <td>{{ number_format($item['estimate_time']) }}</td>
                    <td>{{ number_format($item['estimate_money']) }}</td>
                    <td>
                        @if(in_array('admin.branch.edit',session('routeList')))
                            <a href="javascript:void(0)" onclick="estimate.showModalEdit(this);"
                                data-id="{{ $item['estimate_branch_time_id'] }}"
                                data-type="{{ $item['type'] }}"
                                data-time="{{ $item['estimate_time'] }}"
                                data-money="{{ $item['estimate_money'] }}"
                                data-content="{{ $item['type'] == 'W' ? $item['week'] : $item['month'] }}"
                                data-branch="{{ $item['branch_id'] }}"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>