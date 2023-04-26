<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--font-size-th ss--nowrap">
            <th>#</th>
            <th>{{__('MÃ VOUCHER')}}</th>
            <th class="ss--text-center">{{__('HÌNH THỨC')}}</th>
            <th class="ss--text-center">{{__('LOẠI GIẢM GIÁ')}}</th>
            <th class="ss--text-center">{{__('TÌNH TRẠNG VOUCHER')}}</th>
            <th class="ss--text-center">{{__('TRẠNG THÁI')}}</th>
            {{--<th>Trạng thái</th>--}}
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr class="ss--font-size-13">
                    <td>{{$key+1}}</td>
                    <td>
                        <a class="test ss--text-black" href="javascript:void(0)"
                           onclick="Voucher.detail( {{$item['voucher_id']}})">
                            {{$item["code"]}}
                        </a>
                    </td>
                    <td class="ss--text-center ss--nowrap">
                        @switch($item["object_type"])
                            @case("all")
                            <span class="e">{{__('Tất cả')}}</span>
                            @break
                            @case("service_card")
                            <span class="e">{{__('Theo thẻ dịch vụ')}}</span>
                            @break
                            @case("product")
                            <span class="e">{{__('Theo sản phẩm')}}</span>
                            @break
                            @case("service")
                            <span class="e">{{__('Theo dịch vụ')}}</span>
                            @break
                            @default
                            <span></span>
                        @endswitch
                    </td>
                    <td class="ss--text-center ss--nowrap">
                        @switch($item["type"])
                            @case("sale_percent")
                            <span class="e">{{__('Theo phần trăm')}}</span>
                            @break
                            @case("sale_cash")
                            <span class="e">{{__('Theo tiền')}}</span>
                            @break
                            @default
                            <span></span>
                        @endswitch
                    </td>
                    <td class="ss--text-center ss--nowrap">
                        @if(\Carbon\Carbon::parse($item['expire_date'])->gt(\Carbon\Carbon::now()))
                            <span class="">{{__('Còn hạn')}}</span>
                        @else
                            <span class="">{{__('Đã hết hạn')}}</span>
                        @endif
                    </td>
                    <td class="ss--text-center">
                        @if(in_array('admin.voucher.changeStatus',session('routeList')))
                            @if ($item['is_actived'])
                                {{--<button class="m-badge  m-badge--success m-badge--wide">{{__('Hoạt động')}}</button>--}}
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label>
                                    <input type="checkbox"
                                           onclick="Voucher.changeStatus(this, '{!! $item['voucher_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                {{--<button class="m-badge  m-badge--danger m-badge--wide">{{__('Tạm ngưng')}}</button>--}}
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label>
                                    <input type="checkbox"
                                           onclick="Voucher.changeStatus(this, '{!! $item['voucher_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" checked class="manager-btn" name="">
                                        <span></span>
                                    </label>
                                </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" class="manager-btn" name="">
                                        <span></span>
                                    </label>
                                </span>
                            @endif
                        @endif
                    </td>

                    <td class="pull-right">
                        @if($item['total_use']>0)
                        @else
                            @if(in_array('admin.voucher.edit',session('routeList')))
                                <a href="{{route("admin.voucher.edit",$item['voucher_id'])}}"
                                   title="{{__('Cập nhật')}}"
                                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                    <i class="la la-edit"></i>
                                </a>
                            @endif
                        @endif
                        @if(in_array('admin.voucher.delete',session('routeList')))
                            <button onclick="Voucher.remove(this, {{$item['voucher_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}">
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
