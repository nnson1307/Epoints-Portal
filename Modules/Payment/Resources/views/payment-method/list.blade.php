<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('STT')</th>
            <th class="tr_thead_list">@lang('MÃ HÌNH THỨC THANH TOÁN')</th>
            <th class="tr_thead_list">@lang('TÊN HÌNH THỨC THANH TOÁN (TIẾNG VIỆT)')</th>
            <th class="tr_thead_list">@lang('TÊN HÌNH THỨC THANH TOÁN (TIẾNG ANH)')</th>
            <th class="tr_thead_list">@lang('LOẠI HÌNH THỨC THANH TOÁN')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('HỆ THỐNG')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @php $stt=1; @endphp
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$stt++}}</td>
                    <td>{{$item['payment_method_code']}}</td>
                    <td>{{$item['payment_method_name_vi']}}</td>
                    <td>{{$item['payment_method_name_en']}}</td>
                    <td>{{$item['payment_method_type'] == "auto" ? __("Tự động") : __("Thủ công")}}</td>
                    <td>
                        @if($item['is_active']=='1')
                            <span class="m-badge m-badge--success" style="width: 60%">@lang('Đang hoạt động')</span>
                        @else
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 60%">@lang('Vô hiệu hoá')</span>
                        @endif
                    </td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" class="manager-btn" {{($item['is_system']==1)?'checked':''}} disabled>
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>
                        <a href="{{route("payment-method.edit", $item["payment_method_id"])}}"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Sửa')}}"
                           id="edit1">
                            <i class="la la-edit"></i>
                        </a>

                        @if($item['is_system'] == 0)
                            <button onclick="paymentMethod.remove(this, {{$item['payment_method_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xoá')}}">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
