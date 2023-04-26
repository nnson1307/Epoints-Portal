<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold"><i class="la la-edit ss--icon-title m--margin-right-5"></i>
            {{__('CHỈNH SỬA NHÓM THUỘC TÍNH SẢN PHẨM')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="idHidden">
        <div class="form-group">
            <label>
                {{__('Tên nhóm thuộc tính sản phẩm')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" id="productattname" class="form-control m-input"
                   placeholder="{{__('Nhập tên nhóm thuộc tính sản phẩm')}}">
            <span class="error-product-attribute-group-name"></span>
        </div>
        <div class="form-group">
            <label>
                {{__('Trạng thái')}} :
            </label>
            <div class="input-group row">
                <div class="col-lg-1">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_actived11" type="checkbox" class="manager-btn" name="">
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
                <button type="button" onclick="productAttributeGroup.submitEdit()"
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