<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
            {{ __('THÊM YÊU CẦU') }}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group m-form__group">
            <label class="black_title d-block">
                {{ __('Loại yêu cầu') }}:<b class="text-danger">*</b>
            </label>
            <select name="ticket_issue_group_id" class="form-control select2 select2-activ">
                <option value="">@lang('Chọn loại yêu cầu')</option>
                @foreach ($groupRequest as $name => $item)
                    <option value="{{ $item['ticket_issue_group_id'] }}">{{ $item['name'] }}</option>
                @endforeach
            </select>
            <span class="err error-ticket_issue_group_id"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{ __('Tên yêu cầu') }}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="request_name" class="form-control m-input"
                placeholder="{{ __('Nhập tên yêu cầu') }}...">
            <span class="err error-request_name"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{ __('Cấp độ yêu cầu') }}:<b class="text-danger">*</b>
            </label>
            <select name="level" class="form-control select2 select2-active">
                <option value="">@lang('Chọn cấp độ yêu cầu')</option>
                @foreach (levelIssue() as $key => $value )
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <span class="err error-level"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{ __('Thời gian xử lý') }}:<b class="text-danger">*</b>
            </label>
            <div class="d-flex">
                <div class="day d-flex align-items-end mr-3">
                    <input type="number" name="day" class="form-control m-input mr-3 check-number-int"
                    placeholder="{{ __('00') }}" max="1000000"> <h5 class="fw-200">@lang('Ngày')</h5>
                </div>
                <div class="hour d-flex align-items-end">
                    <input type="number" name="hour" max="24" class="form-control m-input mr-3 check-number-int"
                    placeholder="{{ __('00') }}"> <h5 class="fw-200">@lang('Giờ')</h5>
                </div>
            </div>
            <span class="err error-process_time"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{ __('Mô tả') }}:
            </label>
            <textarea class="form-control m-input" id="description" name="description" rows="5" cols="5"
                placeholder="{{ __('Nhập mô tả') }}..."></textarea>
            <span class="err error-description"></span>
        </div>
        <div class="form-group" style="display: none">
            <label>
                {{ __('Trạng thái') }} :
            </label>
            <div class="input-group">
                <label class="m-checkbox m-checkbox--air">
                    <input id="is_actived" checked type="checkbox">{{ __('Hoạt động') }}
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
                        <span>{{ __('HỦY') }}</span>
                    </span>
                </button>

                <button type="button" onclick="Shift.addClose()"
                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-check"></i>
                        <span>{{ __('LƯU THÔNG TIN') }}</span>
                    </span>
                </button>
                <button type="button" onclick="Shift.add()"
                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="fa fa-plus-circle m--margin-right-10"></i>
                        <span>{{ __('LƯU & TẠO MỚI') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
