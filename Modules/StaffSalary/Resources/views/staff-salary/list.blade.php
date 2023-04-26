
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('Tên bảng lương')</th>
            <th class="tr_thead_list">@lang('Kỳ hạn trả lương')</th>
            <th class="tr_thead_list">@lang('Trạng thái')</th>
        </tr>
        </thead>
        <tbody>
            @if(isset($LIST))
                @foreach ($LIST as $key => $item)
                    <tr>
                        <td>
                            @if(isset($page))
                                {{ ($page-1)*10 + $key+1}}
                            @else
                                {{$key+1}}
                            @endif
                        </td>
                        <td>
                            <a href="{{route('staff-salary.detail',array ('id'=>$item['staff_salary_id']))}}">
                                @lang('Bảng lương') {{ \Carbon\Carbon::createFromFormat('Y-m-d', $item['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $item['end_date'])->format('d/m/Y') }}
                            </a>
                        </td>
                        <td>@lang($item['staff_salary_pay_period_name'])</td>
                        <td>
                            @if($item['staff_salary_status'] == 1)
                                {{__('Đã Chốt Lương')}}
                            @else
                                {{__('Chưa Chốt Lương')}}
                              
                            @endif
                        </td>
                        
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    
</div>
{{ $LIST->links('helpers.paging') }}