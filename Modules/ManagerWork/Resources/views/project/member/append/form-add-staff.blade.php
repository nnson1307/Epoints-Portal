<div class="form-group m-form__group">
    <label class="black_title">
        {{ __('Nhân viên') }} : <b class="text-danger">*</b>
    </label>
    <div class="input-group">
        <select name="list_member" multiple id="list_member"
                class="form-control select2 select2-active">
            <option value="">{{ __('Chọn thành viên') }}</option>
            @foreach ($listStaffPopup as $item)
                <option value="{{ $item->staff_id }}">{{ $item->full_name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group m-form__group">
    <label class="black_title">
        {{ __('Vai trò') }} : <b class="text-danger">*</b>
    </label>
    <div class="input-group list_project--role">
        @if ($listRole->count() > 0)
            @foreach ($listRole as $item)
                <div class="item_project--role">
                    <label class="m-radio cus">
                        <input type="radio" value="{{ $item->manage_project_role_id }}"
                               name="role">
                        {{ $item->manage_project_role_name }}
                        <span></span>
                    </label>
                </div>
            @endforeach
        @endif
    </div>
</div>