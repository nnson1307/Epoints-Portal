<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('MÃ PHIẾU')}}</th>
            <th class="tr_thead_list">{{__('LOẠI')}}</th>
            <th class="tr_thead_list">{{__('TÊN')}}</th>
            <th class="tr_thead_list">{{__('SỐ SERIAL')}}</th>
            <th class="tr_thead_list">{{__('NGÀY HẾT HẠN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($list) > 0)
            @foreach($list as $v)
                <tr>
                    <td>
                        <label class="m-radio m-radio--bold m-radio--state-success">
                            <input type="radio" name="check_warranty"
                                   {{$warrantyCode == $v['warranty_card_code'] ? 'checked' : ''}} onchange="view.chooseWarrantyCard(this)">
                            <span></span>
                        </label>
                        <input type="hidden" class="warranty_code" value="{{$v['warranty_card_code']}}">
                    </td>
                    <td>{{$v['warranty_card_code']}}</td>
                    <td>
                        @if($v['object_type'] == 'product')
                            @lang('Sản phẩm')
                        @elseif($v['object_type'] == 'service')
                            @lang('Dịch vụ')
                        @elseif($v['object_type'] == 'service_card')
                            @lang('Thẻ dịch vụ')
                        @endif
                    </td>
                    <td>{{$v['object_name']}}</td>
                    <td>{{$v['object_serial']}}</td>
                    <td>{{\Carbon\Carbon::parse($v['date_expired'])->format('d/m/Y H:i')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

