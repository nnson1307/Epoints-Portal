@if(in_array('fnb.orders',session('routeList')))
    <td class="text-center">
        <label class="m-radio m-radio--air m-radio--solid">
            <input style="text-align: center" name="is_master[]" class="is_master" data-name="{{isset($name) ? $name : '{name}'}}" type="radio" {{isset($is_master) && $is_master == 1 ? 'checked' : '' }}>
            <span></span>
        </label>
    </td>
@endif
