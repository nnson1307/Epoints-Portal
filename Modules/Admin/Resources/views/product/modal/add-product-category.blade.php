<div id="modal-add-product-category" class="modal fade" role="dialog">
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
                    {{__('THÊM DANH MỤC SẢN PHẨM')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>
                        {{__('Tên danh mục')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" id="category-name" class="form-control m-input"
                           placeholder="{{__('Nhập tên danh mục')}}">
                    <span class="error-category-name"></span>
                </div>
                <div class="form-group" style="display: none">
                    <label>
                        {{__('Trạng thái')}} :
                    </label>
                    <div class="input-group">
                        <label class="m-checkbox m-checkbox--air">
                            <input class="is_actived" checked type="checkbox">{{__('Hoạt động')}}
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        {{__('Mô tả')}}:
                    </label>
                    <textarea class="form-control" rows="5" id="description" placeholder="{{__('Mô tả')}}"></textarea>
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
                        <button type="button" onclick="product.addProductCategory()"
                                class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-left-10 m--margin-bottom-5">
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