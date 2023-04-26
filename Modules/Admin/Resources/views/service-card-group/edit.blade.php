<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold"><i class="la la-edit ss--icon-title m--margin-right-5">
            </i>{{__('CHỈNH SỬA NHÓM THẺ DỊCH VỤ')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="groupIdHidden">
        <div class="form-group">
            <label>
                {{__('Tên nhóm thẻ')}}:<b class="text-danger">*</b>
            </label>
            <div class="{{ $errors->has('order_source_name') ? ' has-danger' : '' }}">
                <input type="text" id="edit-name" name="edit-name" class="form-control m-input"
                       placeholder="{{__('Nhập tên nhóm thẻ')}}">
                <span class="error-edit-name text-danger"></span>
            </div>
        </div>
        <div class="form-group">
            <label>
                {{__('Mô tả')}} :
            </label>
            <textarea placeholder="{{__('Nhập mô tả')}}" rows="6" cols="50" name="edit-description" id="edit-description"
                      class="form-control"></textarea>
            <span class="description"></span>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit  ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 ss--btn">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="serviceCardGroup.submitEdit()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 m--margin-left-10">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>