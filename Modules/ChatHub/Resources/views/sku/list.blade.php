<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>@lang('chathub::sku.index.ID')</th>
            <th>@lang('chathub::sku.index.NAME')</th>
            <th>@lang('chathub::sku.index.ENTITIES')</th>
            <th>@lang('chathub::sku.index.STATUS')</th>
            <th>@lang('chathub::sku.index.ACTION')</th>
        </tr>
        </thead>
        
        <tbody>
            @if ($LIST->count())
                @foreach($LIST as $item)
                <tr>
                    <td>{{$item['sku_id']}}</td>
                    <td>{{$item['sku_name']}}</td>
                    <td>{{$item['entities']}}</td>
                    <td>
                        @if($item['sku_status'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="" disabled>
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input type="checkbox"
                                        class="manager-btn" name="" disabled>
                                <span></span>
                            </label>
                        </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('chathub.sku.edit',['sku_id'=>$item['sku_id']])}}"
                            class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a>
                        <button onclick="sku.remove(this, {{$item['sku_id']}})"
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