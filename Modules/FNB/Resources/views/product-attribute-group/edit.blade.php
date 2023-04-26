
<div class="modal fade" id="modalEditGroup" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="product-attribute-group-edit" class="w-100">
                <div class="modal-header">
                    <h4 class="modal-title ss--title m--font-bold"><i class="la la-edit ss--icon-title m--margin-right-5"></i>
                        {{__('CHỈNH SỬA NHÓM THUỘC TÍNH SẢN PHẨM')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="product_attribute_group_id" name="product_attribute_group_id" value="{{$detail['product_attribute_group_id']}}">
                    <div class="form-group">
                        <label>
                            {{__('Tên nhóm thuộc tính sản phẩm')}} (EN):<b class="text-danger">*</b>
                        </label>
                        <input type="text" id="product_attribute_group_name_en" name="product_attribute_group_name_en" class="form-control m-input"
                               placeholder="{{__('Nhập tên nhóm thuộc tính sản phẩm')}}" value="{{$detail['product_attribute_group_name_en']}}">
                        <span class="error-product-attribute-group-name"></span>
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
                            <button type="button" onclick="productAttributeGroupFNB.submitEdit()"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
                                    </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>