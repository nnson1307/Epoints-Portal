<form class="modal fade" id="add-popup" role="dialog" action="" method="GET">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title color-primary m--font-bold font-primary fz-1_5rem fw-500">
                    <i class="fa fa-address-book ss--icon-title fz-1_5rem m--margin-right-5 fw-500"></i>
                    {{ __('CHỌN NHÂN VIÊN HOÀN ỨNG') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{ __('Nhân viên') }}:<b class="text-danger">*</b>
                    </label>
                    <select name="staff_id" class="form-control select2 select2-active">
                        <option value="">@lang('Nhân viên')</option>
                        @foreach ($staffList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{ __('Queue') }}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="queue_name" class="form-control" readonly>
                </div>
                <h4 class="ss--text-black fw-500 font-primary fz-1_5rem"><i
                        class="fa fa-check-circle ss--text-black m--margin-right-5 fz-1_5rem"></i>{{ __('Người duyệt') }}
                </h4>
                <div class="form-group m-form__group">
                    <select name="approve_id" class="form-control select2 select2-active">
                        <option value="">@lang('Nhân viên')</option>
                        @foreach ($listApproveStaff as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                        </button>

                        <button type="submit"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
