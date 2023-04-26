<div id="autotable" class="mt-3">
    <div class="table-content m--padding-top-30">
        <div class="table-responsive">
            <table class="table table-striped m-table m-table--head-bg-default" id="table-config">
                <thead class="bg">
                    <tr>
                        <th class="tr_thead_list text-center">#</th>
                        <th class="tr_thead_list text-center">{{__('Tên nhân viên')}}</th>
                        <th class="tr_thead_list text-center">{{__('Queue')}}</th>
                        <th class="tr_thead_list text-center">{{__('Tổng ticket')}}</th>
                        <th class="tr_thead_list text-center">{{__('Tổng ticket quá hạn')}}</th>
                        <th class="tr_thead_list text-center">{{__('Tổng ticket reopen')}}</th>
                        <th class="tr_thead_list text-center">{{__('Tổng thời gian xử lý')}}</th>
                        <th class="tr_thead_list text-center">{{__('Tổng điểm đánh giá')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($list))
                        @foreach ($list as $key => $item)
                            <tr class="text-center">
                                <td>
                                    @if (isset($page))
                                        {{ ($page - 1) * 10 + $key + 1 }}
                                    @else
                                        {{ $key + 1 }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.staff.show', $item->process_by) }}">
                                        {{ $item->full_name }}
                                    </a>
                                </td>
                                <td>
                                    {{ isset($item->queue->queue_name) ? $item->queue->queue_name : '' }}
                                </td>
                                <td>
                                    {{ isset($item->total_ticket) ? $item->total_ticket : 0 }}
                                </td>
                                <td>
                                    {{ $item->total_overtime ? $item->total_overtime : 0 }}
                                </td>
                                <td>
                                    {{ $item->total_reopen ? $item->total_reopen : 0 }}
                                </td>
                                <td>
                                    {{ $item->total_time_handers }} 
                                </td>
                                <td class="text-center">
                                    @php
                                    $star_str = '';
                                    $star = $item->avg_point != null ? $item->avg_point : 0;
                                        for ($i = 1;$i <= 5;$i++){
                                            if ($star >= $i){
                                                $star_str  .= '<i class="fa fa-star text-warning" aria-hidden="true"></i>';
                                            }else{
                                                $star_str  .= '<i class="fa fa-star" aria-hidden="true"></i>';
                                            }
                                        }
                                    @endphp
                                    {!! $star_str !!} 
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        {{ $list->links('helpers.paging') }}
    </div>
</div>
