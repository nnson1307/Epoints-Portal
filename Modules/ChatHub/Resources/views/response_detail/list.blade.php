<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>@lang('chathub::response_detail.index.ID')</th>
            <th>@lang('chathub::response_detail.index.BRAND')</th>
            <th>@lang('chathub::response_detail.index.SUB_BRAND')</th>
            <th>@lang('chathub::response_detail.index.SKU')</th>
            <th>@lang('chathub::response_detail.index.ATTRIBUTE')</th>
            <th>@lang('chathub::response_detail.index.CONTENT')</th>
            <th>@lang('chathub::response_detail.index.TEMPLATE')</th>
            <th>@lang('chathub::response_detail.index.CREATED_AT')</th>
            <th></th>

        </tr>
        </thead>
        
        <tbody>
            @if ($LIST->count())
                @foreach($LIST as $item)
                <tr>
                    <td>{{$item['response_detail_id']}}</td>
                    <td>{{$item['brand']}}</td>
                    <td>{{$item['sub_brand']}}</td>
                    <td>{{$item['sku']}}</td>
                    <td>{{$item['attribute']}}</td>
                    <td>{{$item['response_content']}}</td>
                    <td>{{$item['response_element_id']}}</td>
                    <td>{{$item['created_at']}}</td>
                    <td>
                        {{-- <a href="{{route('chathub.response_detail.edit',['response_detail_id'=>$item['response_detail_id']])}}"
                            class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a> --}}
                        <button onclick="response_detail.remove(this, {{$item['response_detail_id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="Delete">
                            <i class="la la-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="17" align="center">@lang('chathub::validation.NOT_DATA')</td>
                </tr>
            @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}