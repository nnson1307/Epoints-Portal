<table class="table table-striped m-table m-table--head-bg-default" id="table-info">
    <thead class="bg">
    <tr>
        <th class="tr_thead_list">@lang('LOẠI THÔNG TIN')</th>
        <th class="tr_thead_list">@lang('THÔNG TIN KÈM THEO')</th>
    </tr>
    </thead>
    <tbody>
    @if(count($data) > 0)
        @foreach($data as $v)
            <tr>
                <td>{{$v['title']}}</td>
                <td>
                    @switch($v['type'])
                        @case('text')
                        <input type="text" id="{{$v['key']}}" name="{{$v['key']}}"
                               class="form-control m-input" value="{{$item[$v['key']]}}"
                               disabled>
                        @break;
                        @case('boolean')
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox" class="manager-btn"
                                                                           id="{{$v['key']}}" name="{{$v['key']}}"
                                                                           value="{{$item[$v['key']]}}"
                                                                           disabled {{$item[$v['key']] == 1 ? 'checked': ''}}>
                                                                    <span></span>
                                                                </label>
                                                </span>
                        @break;
                    @endswitch
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>