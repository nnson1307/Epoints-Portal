<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{ __('Hành động') }}</th>
            @if (isset($listConfigStaff['show']) > 0)
                @foreach ($listConfigStaff['show'] as $item)
                    <th class=" ss--font-size-th">{{ $item[getValueByLang('column_nameConfig_')] }}</th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr>
                    <td>{{$list->perpage()*($list->currentpage()-1)+($key+1)}}</td>
                    <td>
                        <button onclick="Table.showPopupTable({{$item}})" type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                            title="{{__('Chỉnh sửa')}}" >
                            <i class="la la-edit"></i>
                        </button>
                        <button onclick="Table.deleteTable('{{$item['table_id']}}','{{$item['code']}}')" type="button"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Xóa')}}"><i class="la la-trash"></i>
                        </button>
{{--                        <button onclick="Table.download()" type="button"--}}
{{--                                class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                title="{{__('Tải về')}}"><i class="la 	la-download"></i>--}}
{{--                        </button>--}}

                    </td>
                    @if (isset($listConfigStaff['show']) > 0)
                        @foreach ($listConfigStaff['show'] as $itemValue)
                            @if($itemValue['column_name'] == 'is_active')
                                <td>{{$item[$itemValue['column_name']] == 1 ? __('Đang hoạt động') : __('Ngừng hoạt động')}}</td>
                            @elseif(in_array($itemValue['column_name'],['created_at','updated_at']))
                                <td>{{isset($item[$itemValue['column_name']]) ? \Carbon\Carbon::parse($item[$itemValue['column_name']])->format('H:i:s d/m/Y') : ''}}</td>
                            @else
                                <td>{{$item[$itemValue['column_name']]}}</td>
                            @endif
                        @endforeach
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>
