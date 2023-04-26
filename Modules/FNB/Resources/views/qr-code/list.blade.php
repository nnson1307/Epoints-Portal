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
                        <a href="{{route('fnb.qr-code.edit',['id' => $item["qr_code_template_id"]])}}" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a>
                        <a href="{{route('fnb.qr-code.detail',['id' => $item["qr_code_template_id"]])}}" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-eye"></i>
                        </a>
                        @if(in_array($item['status'],['new','cancel']))
                            <button type="button" onclick="qrCode.removeQrCode(this,'{{$item["qr_code_template_id"]}}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                    @if (isset($listConfigStaff['show']) > 0)
                        @foreach ($listConfigStaff['show'] as $itemValue)
                            @if($itemValue['column_name'] == 'is_active')
                                <td>{{$item[$itemValue['column_name']] == 1 ? __('Đang hoạt động') : __('Ngừng hoạt động')}}</td>
                            @elseif(in_array($itemValue['column_name'],['created_at','updated_at']))
                                <td>{{isset($item[$itemValue['column_name']]) ? \Carbon\Carbon::parse($item[$itemValue['column_name']])->format('H:i:s d/m/Y') : ''}}</td>
                            @elseif(in_array($itemValue['column_name'],['exprire_date']))
                                <td>
                                    @if($item['expire_type'] == 'limited')
                                        {{isset($item['expire_start']) ? \Carbon\Carbon::parse($item['expire_start'])->format('H:i d/m/Y') : ''}} - {{isset($item['expire_end']) ? \Carbon\Carbon::parse($item['expire_end'])->format('H:i d/m/Y') : ''}}
                                    @endif
                                </td>
                            @elseif(in_array($itemValue['column_name'],['qr_type']))
                                <td>{{$typeQR[$item[$itemValue['column_name']]]}}</td>
                            @elseif(in_array($itemValue['column_name'],['is_request_wifi','is_request_location']))
                                <td>
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label style="margin: 0 0 0 10px; padding-top: 4px">
                                            <input type="checkbox" disabled class="manager-btn" {{$item[$itemValue['column_name']] == 1 ? 'checked' :''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                </td>
                            @elseif(in_array($itemValue['column_name'],['status']))
                                <td><div class="background-status" style="background : {{$status[$item[$itemValue['column_name']]]['color']}}">
                                        {{$status[$item[$itemValue['column_name']]]['name']}}
                                    </div></td>
                            @elseif(in_array($itemValue['column_name'],['apply_for']))
                                <td>{{$item[$itemValue['column_name']] == 'custom' ? __('Tùy chỉnh') : __('Tất cả các bàn')}}</td>
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
