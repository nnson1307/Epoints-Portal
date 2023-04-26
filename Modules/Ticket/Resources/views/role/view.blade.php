<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-eye ss--icon-title m--margin-right-5"></i>
            {{ __('XEM CHI TIẾT ROLE') }}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" id="role-id-hidden">
            <div class="form-group m-form__group col-lg-6">
                <label class="black_title">
                    {{ __('Tên role') }}:<b class="text-danger">*</b>
                </label>
                {{-- <input type="text" name="role_name" readonly class="form-control m-input" id="role-name-view"
                    placeholder="{{ __('Nhập tên role') }}..." disabled>
                <span class="err error-role_name-view"></span> --}}
                <select name="role_group_id" class="form-control select2 select2-active" id="role_group_id-view" disabled>
                    <option value="">@lang('Chọn tên role')</option>
                    @foreach ($roleGroup as $key => $value )
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group m-form__group  col-lg-6">
                <label class="black_title">
                    {{ __('Mô tả') }}:<b class="text-danger">*</b>
                </label>
                <textarea class="form-control m-input" readonly id="description-view" name="description" rows="1"
                    cols="5" placeholder="{{ __('Nhập mô tả') }}..." disabled></textarea>
                <span class="err error-description-view"></span>
            </div>
            <div class="col-lg-12">
                <label>
                    @lang('Quyền trên trạng thái Ticket')
                </label>
                <div class="input-group m-input-group">
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table" id="ticket_status_role_table-view">
                            <thead>
                                <tr>
                                    @foreach ($ticketStatusList as $status)
                                        <th class="text-center">{{ $status['status_name'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($ticketStatusList as $status)
                                        <td class="text-center">
                                            <input type="checkbox" name="ticket_status_role[]"
                                                value="{{ $status['ticket_status_value'] }}" disabled>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Quyền xem'):<b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <select name="ticket_action_role" class="form-control select2 select2-active" disabled>
                            <option value="">@lang('Chọn quyền xem')</option>
                            @foreach ($ticketAction as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 pt-3">
                <div class="form-group">
                    <div class="input-group">
                        <label class="m-checkbox m-checkbox--air">
                            <input name="is_approve_refund" type="checkbox" disabled>{{ __('Quyền duyệt phiếu hoàn ứng') }}
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                    class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-arrow-left"></i>
                        <span>{{ __('HỦY') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
