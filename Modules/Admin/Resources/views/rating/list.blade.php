<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">Loại đánh giá</th>
            <th class="tr_thead_list">Đối tượng bị đánh giá</th>
            <th class="tr_thead_list">Người đánh giá</th>
            <th class="tr_thead_list text-center">Chấm điểm</th>
            <th class="tr_thead_list">Bình luận</th>
            <th class="tr_thead_list">@lang('Hiển thị')</th>
            <th class="tr_thead_list">{{__('Ngày tạo')}}</th>
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
                        @switch($item['object'])
                            @case('order')
                                Đơn hàng
                            @break
                            @case('appointment')
                                Lịch hẹn
                            @break
                            @case('product')
                                Sản phẩm
                            @break
                            @case('airtist')

                            @break
                            @case('voucher')
                                Khuyến mãi
                            @break
                            @case('article')
                                Bài viết
                            @break
                            @case('service')
                                Dịch vụ
                            @break
                        @endswitch
                    </td>
                    <td>
                        @switch($item['object'])
                            @case('order')
                                {{$item['order_code']}}
                            @break
                            @case('appointment')
                                {{$item['customer_appointment_code']}}
                            @break
                            @case('product')
                                {{$item['product_name']}}
                            @break
                            @case('airtist')

                            @break
                            @case('voucher')
                                {{$item['voucher_code']}}
                            @break
                            @case('article')
                                {{$item['title_vi']}}
                            @break
                            @case('service')
                                {{$item['service_name']}}
                            @break
                        @endswitch
                    </td>
                    <td>{{$item['full_name']}}</td>
                    <td class="text-center">{{$item['rating_value']}}</td>
                    <td>{{$item['comment']}}</td>
                    <td>
                        @if ($item['is_show'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn" checked
                                           onclick="listRating.changeShow('{{$item['id']}}', 0)">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn"
                                           onclick="listRating.changeShow('{{$item['id']}}', 1)">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td>
                        {{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s')}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
