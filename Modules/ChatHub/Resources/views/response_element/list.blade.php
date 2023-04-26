<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>@lang('chathub::response_element.index.ID')</th>
            <th>@lang('chathub::response_element.index.TITLE')</th>
            <th>@lang('chathub::response_element.index.SUB_TITLE')</th>
            <th>@lang('chathub::response_element.index.IMAGE')</th>
            <th>@lang('chathub::response_element.index.CREATED_AT')</th>
            <th></th>

        </tr>
        </thead>
        
        <tbody>
            @if ($LIST->count())
                @foreach($LIST as $item)
                <tr>
                    <td>{{$item['response_element_id']}}</td>
                    <td>{{$item['title']}}</td>
                    <td>{{$item['subtitle']}}</td>
                    <td>
                        @if($item['image_url'])
                        <img src="{{$item['image_url']}}" height="30px">
                        @endif
                    </td>
                    <td>{{$item['created_at']}}</td>
                    <td>
                        <a href="{{route('chathub.response_element.edit',['response_element_id'=>$item['response_element_id']])}}"
                            class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a>
                        <button onclick="response_element.remove(this, {{$item['response_element_id']}})"
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