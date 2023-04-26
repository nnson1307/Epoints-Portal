<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-eye ss--icon-title m--margin-right-5"></i>
            {{__('XEM CHI TIẾT LOẠI YÊU CẦU')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="group_request-id-hidden">
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Loại ticket')}}:<b class="text-danger">*</b>
            </label>
            <select name="type" class="form-control" id="type" disabled>
                @foreach (getTypeTicket() as $key => $value)
                <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Tên loại yêu cầu')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="group_request_name" disabled class="form-control m-input"
                   id="group_request_name-view"
                   placeholder="{{__('Nhập tên loại yêu cầu')}}..." disabled>
            <span class="err error-group_request_name-view"></span>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Mô tả')}}:<b class="text-danger">*</b>
            </label>
            <textarea class="form-control m-input" disabled id="description-view" name="description" rows="5"
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