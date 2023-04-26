`<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>@lang('chathub::comment.index.ID')</th>
            <th>@lang('chathub::comment.index.CHANNEL')</th>
            <th>@lang('chathub::comment.index.MESSAGE_POST')</th>
            <th>@lang('chathub::comment.index.POST_ID')</th>
            <th>@lang('chathub::comment.index.CUSTOMER_COMMENT')</th>
            <th>@lang('chathub::comment.index.MESSAGE')</th>
            <th>@lang('chathub::comment.index.DATE_COMMENT')</th>
        </tr>
        </thead>
        
        <tbody>
            @if ($LIST->count())
                @foreach($LIST as $item)
                    <tr>
                        <td>{{$item['cmt_id']}}</td>
                        <td>{{$item['cha_name']}}</td>
                        <td><div style="width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{$item['mes']}}</div></td>
                        <td>{{$item['post_id']}}</td>
                        <td>{{$item['cus_name']}}</td>
                        <td><div style="width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{$item['message']}}</div></td>
                        <td>{{$item['date_comment']}}</td>
                        <td>
                            <a href="javascript:void(0)" onclick="window.open('https://www.facebook.com/{{$item['comment_id']}}','_blank');"
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