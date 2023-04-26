<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('ID')</th>
            <th class="tr_thead_list">@lang('ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN NHÂN VIÊN')</th>
            <th class="tr_thead_list">@lang('TIỀN HOA HỒNG')</th>
            <th class="tr_thead_list">@lang('GHI CHÚ')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>
                        @if (isset($item['order_id']) && $item['order_id'] != null)
                            <a href="{{route("admin.order.detail", $item['order_id'])}}"
                               class="line-name font-name">{{$item['order_code']}}</a>
                        @endif
                    </td>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{number_format($item['staff_money'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{$item['note']}}</td>
                    <td>
                        @if ($item['status'] == 'approve')
                            @lang('Đã duyệt')
                        @elseif($item['status'] == 'cancel')
                            @lang('Huỷ')
                        @endif
                    </td>
                    <td>
                        <button onclick="staffCommission.edit('{{$item['id']}}')"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Sửa')}}">
                            <i class="la la-edit"></i>
                        </button>
                        <button onclick="staffCommission.remove( {{$item['id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Xoá')}}">
                            <i class="la la-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
