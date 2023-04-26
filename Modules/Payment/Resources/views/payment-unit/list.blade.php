<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('STT')</th>
            <th class="tr_thead_list">@lang('TÊN ĐƠN VỊ THANH TOÁN')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @php $stt=1; @endphp
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$stt++}}</td>
                    <td>{{$item['name']}}</td>
                    <td>
                        @if($item['is_actived']=='1')
                            <span class="m-badge m-badge--success" style="width: 60%">@lang('Đang hoạt động')</span>
                        @else
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 60%">@lang('Vô hiệu hoá')</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route("payment-unit.edit", $item["payment_unit_id"])}}"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Sửa')}}"
                           id="edit1">
                            <i class="la la-edit"></i>
                        </a>
                        <button onclick="paymentUnit.remove(this, {{$item['payment_unit_id']}})"
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
