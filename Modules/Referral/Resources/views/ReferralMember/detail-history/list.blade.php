<div class="table-responsive" style="padding: 0px;padding-top:12px">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--text-center">#</th>
            <th class="ss--text-center">{{__('Phiếu chi')}}</th>
            <th class="ss--text-center">{{__('Kỳ trả hoa hồng')}}</th>
            <th class="ss--text-center">{{__('Hoa hồng theo kỳ')}}</th>
            <th class="ss--text-center">{{__('Người thanh toán')}}</th>
            <th class="ss--text-center">{{__('Ngày thanh toán')}}</th>
            <th class="ss--text-center">{{__('Hình thức')}}</th>
            <th class="ss--text-center">{{__('Trạng thái')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr class="ss--font-size-13 ss--nowrap">
                    <td>{{($list->currentPage() - 1)*$list->perPage() + $key+1 }}</td>
                    <td class="ss--text-center">{{$item['payment_code']}}</td>
                    <td class="ss--text-center">{{$item['name']}}</td>
                    <td class="ss--text-center">{{number_format($item['total_money'])}}</td>
                    <td class="ss--text-center">{{$item['staff_full_name']}}</td>
                    <td class="ss--text-center">{{isset($item['payment_date']) ? \Carbon\Carbon::parse($item['payment_date'])->format('d/m/Y H:i') : ''}}</td>
                    <td class="ss--text-center">{{$item[getValueByLang('payment_method_name_')]}}</td>
                    <?php
                    $background = '';
                    if($item['status'] == 'new'){
                        $background = '#00BCD4';
                    } else if($item['status'] == 'approve'){
                        $background = '#2F6F76';
                    } else if($item['status'] == 'reject'){
                        $background = '#FF0000';
                    } else if($item['status'] == 'waiting_payment') {
                        $background = '#4fc4cb';
                    } else if($item['status'] == 'payment') {
                        $background = '#1365C6';
                    }
                    ?>
                    <td class="ss--text-center "><p class="status_background" style="background-color : {{$background}}">
                            @if($item['status'] == 'new')
                                {{__('Mới')}}
                            @elseif($item['status'] == 'approve')
                                {{__('Đã ghi nhận')}}
                            @elseif($item['status'] == 'reject')
                                {{__('Từ chối')}}
                            @elseif($item['status'] == 'waiting_payment')
                                {{__('Chờ thanh toán')}}
                            @elseif($item['status'] == 'payment')
                                {{__('Thanh toán')}}
                            @endif
                        </p>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10" class="text-center">{{__('Không có dữ liệu')}}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('referral::helpers.paging-history') }}
