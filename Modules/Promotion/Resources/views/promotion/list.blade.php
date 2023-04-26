<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('Banner')</th>
            <th class="tr_thead_list">@lang('TÊN CHƯƠNG TRÌNH')</th>
            <th class="tr_thead_list">@lang('LOẠI CTKM')</th>
            <th class="tr_thead_list">@lang('HIỂN THỊ NỔI BẬT')</th>
            <th class="tr_thead_list">@lang('HIỂN THỊ TRÊN APP')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN KHUYẾN MÃI')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>
                        <img src="{{$item['image']}}"
                             onerror="this.onerror=null;this.src='{{asset('static/backend/images/default-placeholder.png')}}';"
                             class="m--img-rounded m--marginless" alt="photo" width="50px" height="50px">

                    </td>
                    <td>
                        <a href="{{route('promotion.detail', $item['promotion_id'])}}">
                            {{$item['promotion_name']}}
                        </a>
                    </td>
                    <td>
                        @if ($item['promotion_type'] == 1)
                            @lang('Giảm giá')
                        @elseif($item['promotion_type'] == 2)
                            @lang('Quà tặng')
                        @elseif($item['promotion_type'] == 3)
                            @lang('Tích lũy')
                        @endif
                    </td>
                    <td>
                        @if ($item['is_feature'] == 1)
                            @lang('Có')
                        @else
                            @lang('Không')
                        @endif
                    </td>
                    <td>
                        @if ($item['is_display'] == 1)
                            @lang('Có')
                        @else
                            @lang('Không')
                        @endif
                    </td>
                    <td>
                        @lang('Từ')
                        {{\Carbon\Carbon::parse($item['start_date'])->format('d/m/Y H:i')}} <br>
                        @lang('đến')
                        {{\Carbon\Carbon::parse($item['end_date'])->format('d/m/Y H:i')}}
                    </td>
                    <td>
                        @if ($item['is_actived'] == 1)
                            @lang('Hoạt động')
                        @else
                            @lang('Tạm ngưng')
                        @endif
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if(in_array('promotion.edit', session('routeList')))
                            <a href="{{route('promotion.edit', $item['promotion_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('promotion.destroy', session('routeList')))
                            <a href="javascript:void(0)" onclick="list.remove('{{$item['promotion_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
                        @endif
                        @if(in_array('promotion.change-status-promotion', session('routeList')))
                            @if ($item['is_actived'] == 1)
                                <a href="javascript:void(0)" onclick="list.changeStatus('{{$item['promotion_id']}}', 0)"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="@lang('Thay đổi trạng thái')">
                                    <i class="la la-toggle-off"></i>
                                </a>
                            @else
                                <a href="javascript:void(0)" onclick="list.changeStatus('{{$item['promotion_id']}}', 1)"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="@lang('Thay đổi trạng thái')">
                                    <i class="la la-toggle-on"></i>
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
