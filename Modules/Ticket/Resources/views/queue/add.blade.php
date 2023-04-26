<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
            {{__('THÊM QUEUE')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group m-form__group">
            <label class="black_title">
                @lang('Phòng ban'):<b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="department_id" class="form-control select2 select2-active"
                    id="department_id">
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
                   id="queue_name"
                   placeholder="{{__('Nhập tên queue')}}...">
                   <span class="err error-queue_name"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Địa chỉ email')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="email" class="form-control m-input"
                   id="email"
                   placeholder="{{__('Nhập địa chỉ email')}}...">
                   <span class="err error-email"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Mô tả')}}:
            </label>
            <textarea class="form-control m-input" id="description" name="description" rows="5"
            cols="5" placeholder="{{__('Nhập mô tả')}}..."></textarea>
            <span class="err error-description"></span>
        </div>
        <div class="form-group" style="display: none">
            <label>
                {{__('Trạng thái')}} :
            </label>
            <div class="input-group">
                <label class="m-checkbox m-checkbox--air">
                    <input id="is_actived" checked type="checkbox">{{__('Hoạt động')}}
                    <span></span>
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>

                <button type="button" onclick="Shift.addClose()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                </button>
                <button type="button" onclick="Shift.add()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="fa fa-plus-circle m--margin-right-10"></i>
							<span>{{__('LƯU & TẠO MỚI')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>
