<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th ss--text-center">{{__('#')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Mã phiếu')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Loại phiếu')}}</th>
            <th class="ss--font-size-th  ss--text-center">{{__('Người tạo')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Ngày ghi nhận')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Hình thức')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Tổng tiền')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Loại người nhận')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Trạng thái')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $info['listExpenditure'] as $key => $val)
            <tr class="ss--font-size-13 ss--nowrap">
                <td class="ss--text-center">{{isset($param['page']) ? ($param['page']-1)*10 + $key+1 :$key+1}}</td>
                <td class=" ss--text-center">{{ $val['obj_code'] }}</td>
                <td class=" ss--text-center">{{ $val['type'] == 'receipt' ? 'Thu' : 'Chi'}}</td>
                <td class=" ss--text-center">{{ isset($val['expenditure_info']['full_name']) &&  $val['expenditure_info']['full_name'] != null ? $val['expenditure_info']['full_name'] : ''}}</td>
                <td class=" ss--text-center">{{ isset($val['expenditure_info']['date_record']) &&  $val['expenditure_info']['date_record'] != null ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $val['expenditure_info']['date_record'])->format('d/m/Y') : \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $val['expenditure_info']['created_at'])->format('d/m/Y')}}</td>
                <td class=" ss--text-center">{{ isset($val['expenditure_info']['method_name_vi']) &&  $val['expenditure_info']['method_name_vi'] != null ?  $val['expenditure_info']['method_name_vi']: ''}}</td>
                <td class=" ss--text-center">{{ isset($val['expenditure_info']['total_money']) &&  $val['expenditure_info']['total_money'] != null ?  number_format($val['expenditure_info']['total_money']): 0}}</td>
                <td class=" ss--text-center">{{ $val['type'] == 'receipt' ? isset($val['expenditure_info']['full_name']) ? $val['expenditure_info']['full_name'] : '': 'Khách hàng'}}</td>
                @if(isset($val['expenditure_info']['status']) && $val['expenditure_info']['status'] != null)
                    @if($val['type'] == 'receipt')
                        @switch($val['expenditure_info']['status'])
                            @case('unpaid')
                            <td class=" ss--text-center">{{__('Chưa thanh toán')}}</td>
                            @break

                            @case('part-paid')
                            <td class=" ss--text-center">{{__('Đã thanh toán 1 phần')}}</td>
                            @break
                            @case('paid')
                            <td class=" ss--text-center">{{__('Đã thanh toán')}}</td>
                            @break
                            @case('cancel')
                            <td class=" ss--text-center">{{__('Đã hủy')}}</td>
                            @break
                            @case('fail')
                            <td class=" ss--text-center">{{__('Thanh toán không thành công')}}</td>
                            @break
                            @default
                            <td class=" ss--text-center"></td>
                        @endswitch
                    @else
                        @switch($val['expenditure_info']['status'])
                            @case('new')
                            <td class=" ss--text-center">{{__('Mới')}}</td>
                            @break
                            @case('approved')
                            <td class=" ss--text-center">{{__('Đã xác nhận')}}</td>
                            @break
                            @case('paid')
                            <td class=" ss--text-center">{{__('Đã thanh toán')}}</td>
                            @break
                            @case('unpaid')
                            <td class=" ss--text-center">{{__('Chưa thanh toán')}}</td>
                            @break
                            @default
                            <td class=" ss--text-center"></td>
                        @endswitch
                    @endif
                @else
                    <td class=" ss--text-center"></td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>
    @if(count($info['listExpenditure']) < 1)
        <p style="    text-align: center;">{{__('Chưa có thông tin')}}</p>
    @endif
    @if(isset($info['listExpenditure']) && count($info['listExpenditure']) > 0)
        {{ $info['listExpenditure']->links('helpers.paging') }}
    @endif
</div>
