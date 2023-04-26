
<table>
    <thead>
        <tr>
{{--            <th colspan="13" style="text-align:center">BẢNG LƯƠNG THÁNG {{\Carbon\Carbon::now()->format('m/Y')}}</th>--}}
            <th colspan="13" style="text-align:center">{{$list['detail']['name']}}</th>
        </tr>
        <tr>
            <th style="text-align:center">{{__('STT')}}</th>
            <th style="text-align:center">{{__('MÃ NV')}}</th>
            <th style="text-align:center">{{__('HỌ VÀ TÊN')}}</th>
            <th style="text-align:center">{{__('PHÒNG BAN')}}</th>
            <th style="text-align:center">{{__('CHỨC VỤ')}}</th>
            <th style="text-align:center">{{__('LƯƠNG CƠ BẢN')}}</th>
            <th style="text-align:center">{{__('DOANH THU')}}</th>
            <th style="text-align:center">{{__('THƯỞNG HOA HỒNG')}}</th>
            <th style="text-align:center">{{__('THƯỞNG KPIs')}}</th>
            <th style="text-align:center">{{__('PHỤ CẤP')}}</th>
            <th style="text-align:center">{{__('TĂNG')}}</th>
            <th style="text-align:center">{{__('GIẢM')}}</th>
            <th style="text-align:center">{{__('TỔNG TIỀN THỰC LÃNH')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($list['list'] as $key => $item)
            <tr>
                <td style="text-align:center">{{++$key}}</td>
                <td style="text-align:center">{{$item['staff_code']}}</td>
                <td>{{$item['staff_name']}}</td>
                <td style="text-align:center">{{$item['department_name']}}</td>
                <td style="text-align:center">{{$item['staff_title_name']}}</td>
                <td style="text-align:center">{{number_format($item['salary'])}}</td>
                <td style="text-align:center">{{number_format($item['total_revenue'] )}}</td>
                <td style="text-align:center">{{number_format($item['total_commission'] )}}</td>
                <td style="text-align:center">{{number_format($item['total_kpi'] )}}</td>
                <td style="text-align:center">{{number_format($item['total_allowance'] )}}</td>
                <td style="text-align:center">{{number_format($item['plus'] )}}</td>
                <td style="text-align:center">{{number_format($item['minus'] )}}</td>
                <td style="text-align:center">{{number_format($item['total'] )}}</td>
            </tr>
        @endforeach
            <tr>
                <td colspan="5" style="text-align:center">TỔNG CỘNG</td>
                <td style="text-align:center">{{number_format($list['total']['sum_salary'] )}}</td>
                <td style="text-align:center">{{number_format($list['total']['sum_total_revenue'] )}}</td>
                <td style="text-align:center">{{number_format($list['total']['sum_total_commission'] )}}</td>
                <td style="text-align:center">{{number_format($list['total']['sum_total_kpi'] )}}</td>
                <td style="text-align:center">{{number_format($list['total']['sum_total_allowance'] )}}</td>
                <td style="text-align:center">{{number_format($list['total']['sum_plus'] )}}</td>
                <td style="text-align:center">{{number_format($list['total']['sum_minus'] )}}</td>
                <td style="text-align:center">{{number_format($list['total']['sum_total'] )}}</td>
            </tr>
    </tbody>
</table>