<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold"><i class="la la-edit ss--icon-title m--margin-right-5">
            </i>{{__('CHỈNH SỬA THUỘC TÍNH SẢN PHẨM')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="idHide">
        <div class="form-group">
            <label>
                {{__('Nhóm thuộc tính')}}:<b class="text-danger">*</b>
            </label>
            <select id="product_attribute_group_id" style="width: 100%" class="form-control select2-2">

            </select>
        </div>
        <div class="form-group">
            <label>
                {{__('Loại nhãn thuộc tính')}}:<b class="text-danger">*</b>
            </label>
            <select id="attribute-type-label" style="width: 100%" class="form-control select2-22">

            </select>
            <span class="error-attribute-type-label"></span>
        </div>
        <div class="form-group">
            <label>
                {{__('Nhãn thuộc tính')}}:<b class="text-danger">*</b>
            </label>
            <div class="{{ $errors->has('product_attribute_label') ? ' has-danger' : '' }}">
                <input type="text" id="product_attribute_label" class="form-control m-input"
                       placeholder="{{__('Nhập nhãn thuộc tính sản phẩm')}}">
                <span class="error-product-attribute-label"></span>
            </div>
        </div>
        <div class="form-group">
            <label>
                {{__('Trạng thái')}} :
            </label>
            <div class="input-group row">
                <div class="col-lg-1">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_actived" type="checkbox" class="is_actived" name="">
                        <span></span>
                    </label>
                </span>
                </div>
                <div class="col-lg-4 m--margin-top-5">
                    <i>{{__('Select to activate status')}}</i>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 ss--btn">
                    <span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="productAttribute.submitEdit()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>