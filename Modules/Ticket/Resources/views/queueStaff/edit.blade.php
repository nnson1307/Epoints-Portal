<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="la la-edit ss--icon-title m--margin-right-5"></i>
            {{__('CHỈNH SỬA PHÂN CÔNG NHÂN VIÊN')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <form id="form-edit">
            <input type="hidden" id="ticket-staff-queue-id-hiden">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nhân viên xử lý'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            {{-- <select name="staff" class="form-control select2 select2-active" disabled>
                                <option value="">{{ __('Chọn nhân viên') }}...</option>
                                @foreach ($staff as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select> --}}
                            <input type="text" name="staff" class="form-control" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 modal-header mb-3">
                    <h5>@lang('Thông chi tiết')</h5>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('Tên nhân viên') }}:
                        </label>
                        <input type="text" name="name" class="form-control m-input" placeholder=""
                            disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('Email') }}:
                        </label>
                        <input type="text" name="email" class="form-control m-input" placeholder=""
                            disabled>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('Số điện thoại') }}:
                        </label>
                        <input type="text" name="phone" class="form-control m-input" placeholder=""
                            disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('Địa chỉ') }}:
                        </label>
                        <input type="text" name="address" class="form-control m-input" placeholder=""
                            disabled>
                    </div>
                </div>
                <div class="col-lg-12 modal-header mb-3">
                    <h5>@lang('Quyền hạn theo queue')</h5>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Queue'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select name="ticket_queue_id" class="form-control" id="ticket_queue_id_edit" multiple>
                                @foreach ($queue as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Vai trò trên queue'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select name="ticket_role_queue_id" class="form-control select2 select2-active">
                                <option value="">@lang('Chọn vai trò trên queue')</option>
                                @foreach ($roleQueue as $value)
                                    <option value="{{ $value['ticket_role_queue_id'] }}">{{ $value['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="Shift.submitEdit()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>