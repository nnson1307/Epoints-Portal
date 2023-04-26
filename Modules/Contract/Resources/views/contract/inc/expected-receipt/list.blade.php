<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('TIÊU ĐỀ')}}</th>
            <th class="tr_thead_list">{{__('NỘI DUNG NHẮC NHỞ')}}</th>
            <th class="tr_thead_list">{{__('GIÁ TRỊ THANH TOÁN')}}</th>
            <th class="tr_thead_list">{{__('GHI CHÚ')}}</th>
            <th class="tr_thead_list">{{__('NGƯỜI TẠO')}}</th>
            <th class="tr_thead_list">{{__('NGÀY CẬP NHẬT')}}</th>
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
                               onclick="expectedRevenue.showModalEdit('{{$item['type']}}', '{{$item['contract_expected_revenue_id']}}')"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Sửa')}}">
                                <i class="la la-edit"></i>
                            </a>
                            <button onclick="expectedRevenue.remove('{{$item['type']}}', '{{$item['contract_expected_revenue_id']}}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xoá')}}">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                    <td>{{$item['title']}}</td>
                    <td>{{$item['title_remind']}}</td>
                    <td>
                        {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>{{$item['note']}}</td>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y')}}</td>
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
