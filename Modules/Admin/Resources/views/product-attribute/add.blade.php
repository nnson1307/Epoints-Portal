<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
            {{__('THÊM THUỘC TÍNH SẢN PHẨM')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>
                {{__('Nhóm thuộc tính')}}:<b class="text-danger">*</b>
            </label>
            <select id="product_attribute_group_id" style="width: 100%" class="form-control select2-22">
                {{--<option value="">Chọn nhóm thuộc tính</option>--}}
                @foreach($PRODUCTATTRGROUP as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
            <span class="error-product-attribute-group-id"></span>
        </div>
        <div class="form-group">
            <label>
                {{__('Loại nhãn thuộc tính')}}:<b class="text-danger">*</b>
            </label>
            <select id="attribute-type-label" style="width: 100%" class="form-control select2-22">
                <option value="text" selected>@lang('text')</option>
                <option value="int">@lang('int')</option>
                <option value="date">@lang('date')</option>
                <option value="boolean">@lang('boolean')</option>
            </select>
            <span class="error-attribute-type-label"></span>
        </div>
        <div class="form-group">
            <label>
                {{__('Nhãn thuộc tính')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" id="product_attribute_label" class="form-control m-input"
                   placeholder="{{__('Nhập nhãn thuộc tính sản phẩm')}}">
            <span class="error-product-attribute-label"></span>
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
                <button type="button" onclick="productAttribute.addClose()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                </button>
                <button type="button" onclick="productAttribute.add()"
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