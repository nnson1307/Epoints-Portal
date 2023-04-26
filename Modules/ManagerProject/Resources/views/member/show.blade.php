<div class="modal fade" id="member-detail" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title primary-color" id="exampleModalLabel">
                    <i class="la la-eye"></i>
                    {{ __('CHI TIẾT THÀNH VIÊN') }}
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{ __('Nhân viên') }} : <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <select name="list_member" disabled id="list_member"
                            class="form-control select2 select2-active">
                            <option>{{ $memberProject->full_name }}</option>
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
                                        <input type="radio"
                                            {{ $item->manage_project_role_id == $memberProject->manage_project_role_id ? 'checked' : '' }}
                                            disabled value="{{ $item->manage_project_role_id }}" name="role">
                                        {{ $item->manage_project_role_name }}
                                        <span></span>
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
