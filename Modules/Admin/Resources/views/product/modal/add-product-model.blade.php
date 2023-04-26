<div id="modal-add-product-model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('THÊM NHÃN HIỆU SẢN PHẨM')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>
                        {{__('Tên nhãn hiệu sản phẩm')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" id="product-model-name" class="form-control m-input"
                           placeholder="{{__('Nhập tên nhãn hiệu sản phẩm')}}">
                    <span class="error-product-model-name"></span>
                </div>
                <div class="form-group">
                    <label>
                        {{__('Ghi chú')}}:
                    </label>
                    <textarea class="form-control" rows="5" id="product-model-note" placeholder="{{__('Ghi chú')}}"></textarea>
                    <span class="error-product-model-note"></span>
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
                        <button type="button" onclick="product.addModalProductModel()"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
