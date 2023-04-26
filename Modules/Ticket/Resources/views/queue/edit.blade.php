<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="la la-edit ss--icon-title m--margin-right-5"></i>
            {{__('CHỈNH SỬA QUEUE')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <input type="hidden" class="queue-id-hidden" id="queue-id-hidden">
        <div class="form-group m-form__group">
            <label class="black_title">
                @lang('Phòng ban'):<b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="department_id" class="form-control select2 select2-active"
                    id="department_id-edit">
                    <option value="">@lang('Chọn phòng ban')</option>
                    @foreach ($department as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <span class="err error-department_id"></span>
            </div>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Tên queue')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="queue_name" class="form-control m-input"
                   id="queue_name-edit"
                   placeholder="{{__('Nhập tên queue')}}...">
            <span class="err error-queue_name-edit"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Địa chỉ email')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="email" class="form-control m-input"
                   id="email-edit"
                   placeholder="{{__('Nhập địa chỉ email')}}...">
            <span class="err error-email-edit"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Mô tả')}}:
            </label>
            <textarea class="form-control m-input" id="description-edit" name="description" rows="5"
            cols="5" placeholder="{{__('Nhập mô tả')}}..."></textarea>
             <span class="err error-description-edit"></span>
        </div>
        {{-- <div class="form-group">
            <label>
                {{__('Trạng thái')}} :
            </label>
            <div class="input-group row">
                <div class="col-lg-1">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is-actived-edit" type="checkbox" class="manager-btn" name="">
                        <span></span>
                    </label>
                </span>
                </div>
                <div class="col-lg-4 m--margin-top-5">
                    <i>{{__('Chọn để kích hoạt trạng thái')}}</i>
                </div>
            </div>
        </div> --}}
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