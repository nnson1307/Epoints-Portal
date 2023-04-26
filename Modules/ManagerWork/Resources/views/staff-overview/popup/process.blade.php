<div class="modal fade" id="popup-staff-overview-process" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{ __('Sửa tiến độ') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-change-process">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('tiến độ') <b class="text-danger">*</b>
                        </label>
                        <select id="progress" name="progress" class="form-control select2 select2-active"
                                data-placeholder="Chọn nhân viên hỗ trợ">
                            @for($i = 0 ; $i <= 100 ; $i = $i+10)
                                <option value="{{ $i }}" {{ $detail != null && $detail['progress'] == $i ? 'selected' : '' }}>{{ $i .' %' }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
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
                        <button type="button" onclick="StaffOverview.changeProcess('{{$detail['manage_work_id']}}')"
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
</div>
