{{--<tr class="block_{{$groupId}}_{{$count}}">--}}
{{--    <input type="hidden" name="group[{{$groupId}}][{{$count}}][manage_status_group_config_id]" value="{{$groupId}}">--}}
{{--    <input type="hidden" name="group[{{$groupId}}][{{$count}}][is_default]" value="0">--}}
{{--    <td width="3%"><input type="color" name="group[{{$groupId}}][{{$count}}][manage_project_color_code]" class="color-select" value="#e66465"></td>--}}
{{--    <td>--}}
{{--        <input type="text" class="form-control" name="group[{{$groupId}}][{{$count}}][manage_status_config_title]" value="">--}}
{{--    </td>--}}
{{--    <td>--}}
{{--        <i class="fas fa-arrow-right d-inline"></i>--}}
{{--    </td>--}}
{{--    <td>--}}
{{--        <select class="form-control select2Full" multiple name="group[{{$groupId}}][{{$count}}][manage_status_config_map][]">--}}
{{--            <option value=""> </option>--}}
{{--            @foreach($listStatusSelect as $item)--}}
{{--                <option value="{{$item['manage_status_id']}}">{{$item['manage_status_name']}}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </td>--}}
{{--    <td class="text-right">--}}
{{--        <label class="kt-checkbox kt-checkbox--bold">--}}
{{--            <input type="checkbox" name="group[{{$groupId}}][{{$count}}][is_edit]">--}}
{{--            <span></span>--}}
{{--        </label>--}}
{{--    </td>--}}
{{--    <td class="text-right">--}}
{{--        <label class="kt-checkbox kt-checkbox--bold">--}}
{{--            <input type="checkbox" name="group[{{$groupId}}][{{$count}}][is_deleted]">--}}
{{--            <span></span>--}}
{{--        </label>--}}
{{--    </td>--}}
{{--    <td class="text-right">--}}
{{--        <a href="javascript:void(0)" onclick="ManageConfig.removeBlock({{$groupId}},{{$count}})"><i class="la la-trash"></i></a>--}}
{{--    </td>--}}
{{--</tr>--}}
@if(isset($value))
    <tr class="block_{{$value['manage_project_status_group_config_id']}}_{{$count}}">
        <input type="text" id="group_status" value="{{$value['manage_project_status_id']}}">
        <input type="hidden" name="group[{{$value['manage_project_status_group_config_id']}}][{{$count}}][manage_project_status_group_config_id]" value="{{$value['manage_project_status_group_config_id']}}">
        <input type="hidden" name="group[{{$value['manage_project_status_group_config_id']}}][{{$count}}][is_default]" value="{{$value['is_default']}}">
        <input type="hidden" name="group[{{$value['manage_project_status_group_config_id']}}][{{$count}}][manage_project_status_id]" value="{{$value['manage_project_status_id']}}">
        <td width="3%"><input type="color" name="group[{{$value['manage_project_status_group_config_id']}}][{{$count}}][manage_project_color_code]" class="color-select" value="{{$value['manage_project_color_code']}}"></td>
        <td>
            <input type="text" class="form-control" name="group[{{$value['manage_project_status_group_config_id']}}][{{$count}}][manage_project_status_config_title]" value="{{$value['manage_project_status_config_title']}}">
        </td>
        <td>
            <i class="fas fa-arrow-right d-inline"></i>
        </td>
        <td>
            <select class="form-control select2Full" multiple name="group[{{$value['manage_project_status_group_config_id']}}][{{$count}}][manage_project_status_config_map][]">
                <option value=""> </option>
                @foreach($listStatusSelect as $itemSelect)
                    @if($detailStatus['manage_project_status_id'] != $itemSelect['manage_project_status_id'])
                        <option value="{{$itemSelect['manage_project_status_id']}}" {{in_array($itemSelect['manage_project_status_id'],collect($value['list_status'])->pluck('manage_project_status_id')->toArray()) ? 'selected' : ''}}>{{$itemSelect['manage_project_status_name']}}</option>
                    @endif
                @endforeach
            </select>
        </td>
        <td class="text-right">
            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label>
                    <input type="checkbox"
                           class="manager-btn" id="active_{{$value['manage_project_status_config_id']}}" value="1" onchange="ManageConfig.changeActive({{$value['manage_project_status_config_id']}})" checked>
                    <span></span>
                </label>
            </span>
        </td>
        <td class="text-right">
            <label class="kt-checkbox kt-checkbox--bold">
                <input type="checkbox" name="group[{{$value['manage_project_status_group_config_id']}}][{{$count}}][is_edit]" {{$value['is_edit'] == 1 ? 'checked' : ''}}>
                <span></span>
            </label>
        </td>
        <td class="text-right">
            <label class="kt-checkbox kt-checkbox--bold">
                <input type="checkbox" name="group[{{$value['manage_project_status_group_config_id']}}][{{$count}}][is_deleted]" {{$value['is_deleted'] == 1 ? 'checked' : ''}}>
                <span></span>
            </label>
        </td>
        <td class="text-right">
            @if($value['is_default'] != 1)
                <a href="javascript:void(0)" onclick="ManageConfig.removeBlock({{$value['manage_project_status_group_config_id']}},{{$count}},{{$value['manage_project_status_id']}})"><i class="la la-trash"></i></a>
            @endif
        </td>
    </tr>
@endif