
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th width="5%">@lang('ID')</th>
            <th width="15%">@lang('Loại')</th>
            <th width="15%">@lang('Nhãn hiệu')</th>
            <th width="15%">@lang('Nhãn hiệu con')</th>
            <th width="15%">@lang('Sản phẩm')</th>
            <th width="15%">@lang('Thuộc tính')</th>
            <th width="20%">@lang('Phản hồi')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if ($object->count())
            @foreach ($object as $index => $item)
                <tr>
                    <td>{{$item['response_detail_id']}}</td>
                    <td>{{$item['type']}}</td>
                    <td>{{$item['brand_name']}}</td>
                    <td>{{$item['sub_brand_name']}}</td>
                    <td>{{$item['sku_name']}}</td>
                    <td>{{$item['attribute_name']}}</td>
                    <td>{{$item['response_content']}}</td>
                    <td>
                    @if($response_id == 'all')
                        <a href="{{route('chathub.response.edit',$item['response_id'])}}"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a>
                    @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="17" align="center">@lang('Tạm thời chưa có dữ liệu').</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
{{ $object->links('helpers.paging') }}