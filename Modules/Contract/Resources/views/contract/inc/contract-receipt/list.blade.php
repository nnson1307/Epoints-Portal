<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('NỘI DUNG')}}</th>
            <th class="tr_thead_list">{{__('NGÀY THU')}}</th>
            <th class="tr_thead_list">{{__('NGƯỜI THU')}}</th>
            <th class="tr_thead_list">{{__('GIÁ TRỊ THANH TOÁN')}}</th>
            <th class="tr_thead_list">{{__('SỐ HOÁ ĐƠN')}}</th>
            <th class="tr_thead_list">{{__('NGƯỜI CẬP NHẬT')}}</th>
            <th class="tr_thead_list">{{__('NGÀY XUẤT HOÁ ĐƠN')}}</th>
            <th class="tr_thead_list">{{__('NGÀY CẬP NHẬT')}}</th>
            <th class="tr_thead_list">{{__('GHI CHÚ')}}</th>
            <th class="tr_thead_list">{{__('XEM HOÁ ĐƠN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(session()->get('is_detail') == 0)
                            <a href="javascript:void(0)"
                               onclick="contractReceipt.showModalEdit('{{$item['contract_receipt_id']}}')"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Sửa')}}">
                                <i class="la la-edit"></i>
                            </a>
                            <button onclick="contractReceipt.showModalRemove('{{$item['contract_receipt_id']}}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xoá')}}">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                    <td>{{$item['content']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['collection_date'])->format('d/m/Y')}}</td>
                    <td>{{$item['collection_by_name']}}</td>
                    <td>
                        {{number_format($item['total_amount_receipt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>{{$item['invoice_no']}}</td>
                    <td>{{$item['update_by_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['invoice_date'])->format('d/m/Y')}}</td>
                    <td>{{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y')}}</td>
                    <td>{{$item['note']}}</td>
                    <td>
                        @if (count($item['file']) > 0)
                            @foreach($item['file'] as $v)
                                <a href="{{$v['link']}}" class="ss--text-black"
                                   download="{{$v['file_name']}}">{{$v['file_name']}}</a><br>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
