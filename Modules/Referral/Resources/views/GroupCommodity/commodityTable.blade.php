<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
    <thead>
    <tr class="ss--nowrap">
        <th class="ss--font-size-th ss--text-center">#</th>
        <th class="ss--font-size-th ss--text-center">{{__('Hành động')}}</th>
        <th class="ss--font-size-th ss--text-center">{{__('Tên loại hàng hóa')}}</th>
        <th class="ss--font-size-th ss--text-center">{{__('Tên nhóm hàng hóa')}}</th>
        <th class="ss--font-size-th ss--text-center">{{__('Tên hàng hóa')}}</th>
    </tr>
    </thead>
    <tbody >
    @if($dataCommodity != [])
        @foreach($dataCommodity as $k => $v)
            <tr class="ss--font-size-13 ss--nowrap">
                <td class="ss--text-center">{{($dataCommodity->currentPage() - 1)*$dataCommodity->perPage() + $k+1 }}</td>
                <td class="ss--text-center">
                    <a href="javascript:void(0)" onclick="commodity.delete(this, {{$v}})"
                       class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                       title="Xóa">
                        <i class="la la-trash"></i>
                    </a>
                </td>
                @if($v['object_type'] == 'products')
                    <td class="ss--text-center">Sản phẩm</td>
                @elseif($v['object_type'] == 'services')
                    <td class="ss--text-center">Dịch vụ</td>
                @else
                    <td class="ss--text-center">Thẻ dịch vụ</td>
                @endif
                <td class="ss--text-center">{{$v['category_name']}}</td>
                <td class="ss--text-center">{{$v['name']}}</td>
            </tr>
        @endforeach
    @else
        <tr class="ss--font-size-13 ss--nowrap">
            <td colspan="5" style="text-align:center">Đã chọn tất cả hàng hóa</td>
        </tr>
    @endif
    </tbody>
    </table>
</div>
{{ $dataCommodity->links('referral::ChooseProduct.helpers.paging') }}