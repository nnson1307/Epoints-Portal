<div class="modal fade" id="member-edit" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title primary-color" id="exampleModalLabel">
                    <i class="la la-edit"></i>
                    {{ __('CHỈNH SỬA THÀNH VIÊN') }}
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{ __('Nhân viên') }} : <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <select name="member_edit" disabled id="member_edit"
                            class="form-control select2 select2-active">
                            <option value="{{ $memberProject->staff_id }}">{{ $memberProject->full_name }}</option>
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
                                            value="{{ $item->manage_project_role_id }}" name="role_edit">
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
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button type="button" onclick="member.update('{{ $memberProject->manage_project_staff_id }}')"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('CẬP NHẬT') }}</span>
                            </span>
                        </button>
                        <button data-dismiss="modal"
                            style="background-color: #c4c5d6;
                        border-color: #c4c5d6;"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HUỶ') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
