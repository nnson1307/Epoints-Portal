<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>@lang('ID')</th>
            <th>@lang('TITLE')</th>
            <th>@lang('RESPONSE')</th>
            <th>@lang('TARGET')</th>
            <th>@lang('FINISH')</th>
            <th>@lang('ACTION')</th>

        </tr>
        </thead>
        
        <tbody>
            @if ($LIST->count())
                @foreach($LIST as $item)
                <tr>
                    <td>{{$item['response_id']}}</td>
                    <td>{{$item['response_name']}}</td>
                    <td>{{$item['response_content']}}</td>
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
                        <a href="{{route('chathub.response.detail',$item['response_id'])}}"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-eye"></i>
                        </a>
                        <a href="{{route('chathub.response.edit',$item['response_id'])}}"
                            class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
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