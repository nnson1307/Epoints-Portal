<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-eye ss--icon-title m--margin-right-5"></i>
            {{__('XEM CHI TIẾT QUEUE')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        {{-- <input type="hidden" id="queue-id-hidden"> --}}
        <div class="form-group m-form__group">
            <label class="black_title">
                @lang('Phòng ban'):<b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="department_id" class="form-control select2 select2-active"
                    id="department_id-view" disabled>
                    <option value="">@lang('Chọn phòng ban')</option>
                    @foreach ($department as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Tên queue')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="queue_name" readonly class="form-control m-input"
                   id="queue_name-view"
                   placeholder="{{__('Nhập tên queue')}}..." disabled>
            <span class="err error-queue_name-view"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Địa chỉ email')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="email" readonly class="form-control m-input"
                   id="email-view"
                   placeholder="{{__('Nhập địa chỉ email')}}..." disabled>
            <span class="err error-email-view"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Mô tả')}}:
            </label>
            <textarea class="form-control m-input" readonly id="description-view" name="description" rows="5"
            cols="5" placeholder="{{__('Nhập mô tả')}}..." disabled></textarea>
             <span class="err error-description-view"></span>
        </div>
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
            </div>
        </div>
    </div>
</div>