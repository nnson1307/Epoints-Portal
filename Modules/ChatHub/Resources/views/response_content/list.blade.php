<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>@lang('ID')</th>
            <th>@lang('TITLE')</th>
            <th>@lang('CONTENT RESPONSE')</th>
            <th>@lang('TYPE')</th>
            <th>@lang('TARGET')</th>
            <th>@lang('FINISH')</th>
            <th>@lang('ACTION')</th>

        </tr>
        </thead>
        
        <tbody>
            @if ($LIST->count())
                @foreach($LIST as $item)
                <tr>
                    <td>{{$item['response_content_id']}}</td>
                    <td>{{$item['title']}}</td>
                    <td>{{$item['response_content']}}</td>
                    <td>{{$item['type_message']}}</td>
                    @if($item['response_target'] == 1)
                        <td class="text-center">
                            <span class="publish"><i class="fa fa-check" aria-hidden="true"></i></span>
                        </td>
                    @else
                        <td class="text-center">
                            <span class="publish"><i class="fa fa-ban" aria-hidden="true"></i></span>
                        </td>
                    @endif
                    @if($item['response_end'] == 1)
                        <td class="text-center">
                            <span class="publish"><i class="fa fa-check" aria-hidden="true"></i></span>
                        </td>
                    @else
                        <td class="text-center">
                            <span class="publish"><i class="fa fa-ban" aria-hidden="true"></i></span>
                        </td>
                    @endif
                    <td>
                        <a href="{{route('chathub.response-content.edit',$item['response_content_id'])}}"
                            class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a>
                        <button onclick="response_content.remove(this, {{$item['response_content_id']}})"
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