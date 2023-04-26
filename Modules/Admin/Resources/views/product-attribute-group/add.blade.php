<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
            {{__('THÊM NHÓM THUỘC TÍNH SẢN PHẨM')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>
                {{__('Tên nhóm thuộc tính sản phẩm')}}:<b class="text-danger">*</b>
            </label>
            <div class="{{ $errors->has('product_attribute_group_name') ? ' has-danger' : '' }}">
                <input type="text" id="product_attribute_group_name" name="product_attribute_group_name"
                       class="form-control m-input"
                       placeholder="{{__('Nhập tên nhóm thuộc tính sản phẩm')}}">
                <span class="error-product-attribute-group-name"></span>
            </div>
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
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5 ss--btn">
                    <span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="productAttributeGroup.addClose()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                </button>
                <button type="button" onclick="productAttributeGroup.add()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn ss--btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                    <i class="fa fa-plus-circle"></i>
                    <span>{{__('LƯU & TẠO MỚI')}}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>