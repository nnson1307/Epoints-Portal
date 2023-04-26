<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>@lang('chathub::post.index.ID')</th>
            <th>@lang('chathub::post.index.MESSAGE')</th>
            <th>@lang('chathub::post.index.CHANNEL')</th>
            {{-- <th>@lang('chathub::post.index.BRAND')</th>
            <th>@lang('chathub::post.index.SUB_BRAND')</th>
            <th>@lang('chathub::post.index.SKU')</th>
            <th>@lang('chathub::post.index.ATTRIBUTE')</th> --}}
            <th>@lang('chathub::post.index.DATE_COMMENT')</th>
            <th>@lang('chathub::post.index.KEY')</th>
            <th></th>
            <th>@lang('Hành động')</th>
        </tr>
        </thead>
        
        <tbody>
            @if ($LIST->count())
                @foreach($LIST as $item)
                    <tr>
                        <td>{{$item['id']}}</td>
                        <td>{{$item['message']}}</td>
                        <td>{{$item['name']}}</td>
                        {{-- <td>{{$item['brand']}}</td>
                        <td>{{$item['sub_brand']}}</td>
                        <td>{{$item['sku']}}</td>
                        <td>{{$item['attribute']}}</td> --}}
                        <td>{{$item['date_comment']}}</td>
                        <td>
                            <a href="javascript:void(0);" onclick="post.addKey({{$item['id']}})" class="btn btn-success">@lang('chathub::post.index.KEY')</a>
                        </td>
                        <td>
                            @if($item['active'] == 0)
                                <a href="javascript:void(0);" id="active-{{$item['id']}}" onclick="post.subcribe({{$item['id']}})" class="btn btn-success">@lang('chathub::post.index.SUBCRIBE')</a>
                            @else
                                <a href="javascript:void(0);" id="active-{{$item['id']}}" onclick="post.unsubcribe({{$item['id']}})" class="btn btn-warning">@lang('chathub::post.index.UNSUBCRIBE')</a>
                            @endif
                        </td>
                        <td>
                            <a href="javascript:void(0)" onclick="window.open('https://www.facebook.com/{{$item['post_id']}}','_blank');"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-eye"></i>
                            </a>
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