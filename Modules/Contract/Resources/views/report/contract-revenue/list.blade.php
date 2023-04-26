<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('MÃ HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('TÊN HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('LOẠI HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
            <th class="tr_thead_list">{{__('ĐỐI TÁC')}}</th>
            <th class="tr_thead_list">{{__('NHÂN VIÊN PHỤ TRÁCH')}}</th>
            <th class="tr_thead_list">{{__('NGÀY HIỆU LỰC')}}</th>
            <th class="tr_thead_list">{{__('NGÀY HẾT HẠN')}}</th>
            <th class="tr_thead_list">{{__('GIÁ TRỊ HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('GIÁ TRỊ ĐÃ THANH TOÁN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td>
                        @if(in_array('contract.contract.show', session()->get('routeList')))
                            <a href="{{route("contract.contract.show",[ 'id' => $item['contract_id']])}}" target="_blank">
                                {{$item['contract_code']}}
                            </a>
                        @else
                            {{$item['contract_code']}}
                        @endif
                    </td>
                    <td>{{$item['contract_name']}}</td>
                    <td>{{$item['contract_category_name']}}</td>
                    <td>{{$item['status_name']}}</td>
                    <td>{{$item['partner_name']}}</td>
                    <td>{{$item['staff_performer_name']}}</td>
                    <td>{{$item['effective_date'] != '' ? \Carbon\Carbon::parse($item['effective_date'])->format('d/m/Y') : ''}}</td>
                    <td>{{$item['expired_date'] != '' ? \Carbon\Carbon::parse($item['expired_date'])->format('d/m/Y') : ''}}</td>
                    <td>{{number_format($item['last_total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{number_format($item['total_receipt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>

@if(isset($LIST))
{{ $LIST->links('helpers.paging') }}
@endif
