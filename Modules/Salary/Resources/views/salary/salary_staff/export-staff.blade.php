
@if ($type == 'kt')
<table>
    <thead>
        <tr>
            <th colspan="13" style="text-align:center">{{$table_name}}</th>
        </tr>
        <tr>
            <th style="text-align:center">{{__('STT')}}</th>
            <th style="">{{__('TÊN NHÂN VIÊN LẮP ĐẶT')}}</th>
            <th style="text-align:center">{{__('MÃ TICKET')}}</th>
            <th style="text-align:center">{{__('MÃ HỢP ĐỒNG')}}</th>
            <th style="text-align:center">{{__('TÊN KHÁCH HÀNG')}}</th>
            <th style="text-align:center">{{__('QUEUE')}}</th>
            <th style="text-align:center">{{__('YÊU CẦU')}}</th>
            <th style="text-align:center">{{__('THỜI GIAN BẮT ĐẦU XỬ LÝ')}}</th>
            <th style="text-align:center">{{__('THỜI GIAN HOÀN TẤT')}}</th>
            <th style="text-align:center">{{__('HOA HỒNG LẮP ĐẶT')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list as $key => $item)
                    @php
                     $commission_percent = isset($item[$item['partner_object_form'] .'_'. $item['contract_form']])?$item[$item['partner_object_form'] .'_'. $item['contract_form']]:0;    
                     $uncollected_debt = $item['total_amount'] - $item['last_total_amount'];
                     $kpis = $item['last_total_amount'] / 1.1;
                    @endphp
            <tr>
                <td style="text-align:center">{{++$key}}</td>
                <td>{{$item['staff_name']}}</td>
                <td style="text-align:center">{{$item['contract_code']}}</td>
                <td style="text-align:center">{{$item['ticket_code']}}</td>
                <td style="text-align:center">{{$item['customer_name']}}</td>
                <td style="text-align:center">{{$item['queue_name']}}</td>
                <td style="text-align:center">{{$item['issue_name']}}</td>
                <td style="text-align:center">{{ date_format(new DateTime($item['date_issue']), 'd/m/Y H:i') }}</td>
                <td style="text-align:center">
                {{ $item['ticket_status_id'] == 6 ? '' : date_format(new DateTime($item['date_finished']), 'd/m/Y H:i') }}</td>
                <td style="text-align:center">{{$kpis*$commission_percent}}</td>

            </tr>
        @endforeach
    </tbody>
</table>
@else
<table>
    <thead>
        <tr>
            <th colspan="13" style="text-align:center">{{$table_name}}</th>
        </tr>
        <tr>
            <th style="text-align:center">{{__('STT')}}</th>
            <th style="text-align:center">{{__('TÊN NHÂN VIÊN BÁN HÀNG')}}</th>
            <th style="text-align:center">{{__('MÃ HỢP ĐỒNG')}}</th>
            <th style="text-align:center">{{__('TÊN KHÁCH HÀNG')}}</th>
            <th style="text-align:center">{{__('TỔNG GIÁ TRỊ HỢP ĐỒNG')}}</th>
            <th style="text-align:center">{{__('SỐ TIỀN THỰC TẾ THU VỀ')}}</th>
            <th style="text-align:center">{{__('DOANH THU TÍNH KPIS')}}</th>
            <th style="text-align:center">{{__('CÔNG NỢ CHƯA THU')}}</th>
            <th style="text-align:center">{{__('HÌNH THỨC ĐỐI TÁC')}}</th>
            <th style="text-align:center">{{__('PHẦN TRĂM HOA HỒNG')}}</th>
            <th style="text-align:center">{{__('SỐ TIỀN HOA HỒNG')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list as $key => $item)
                    @php
                     $commission_percent = isset($item[$item['partner_object_form'] .'_'. $item['contract_form']])?$item[$item['partner_object_form'] .'_'. $item['contract_form']]:0;    
                     $uncollected_debt = $item['total_amount'] - $item['last_total_amount'];
                     $kpis = $item['last_total_amount'] / 1.1;
                    @endphp
            <tr>
                <td style="text-align:center">{{++$key}}</td>
                <td>{{$item['staff_name']}}</td>
                <td style="text-align:center">{{$item['contract_code']}}</td>
                <td style="text-align:center">{{$item['customer_name']}}</td>
                <td style="text-align:center">{{number_format($item['total_amount'])}}</td>
                <td style="text-align:center">{{number_format($item['last_total_amount'])}}</td>
                <td style="text-align:center">{{number_format( $kpis )}}</td>
                <td style="text-align:center">{{number_format( $uncollected_debt )}}</td>
                <td style="text-align:center">
                    @if ($item['partner_object_form'] == "internal")
                    {{__('Nội bộ')}}
                    @elseif($item['partner_object_form'] == "external")
                    {{__('Bên ngoài')}}
                    @elseif($item['partner_object_form'] == "partner")
                    {{__('Đại lý')}}
                    @endif
                </td>
                <td style="text-align:center">{{$commission_percent}}</td>
                <td style="text-align:center">{{$kpis*$commission_percent}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
