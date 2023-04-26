<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold"><i class="la la-edit ss--icon-title m--margin-right-5">
            </i>{{__('CHỈNH SỬA NGUỒN ĐƠN HÀNG')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="oderSourceIdHidden">
        <div class="form-group">
            <label>
                {{__('Tên nguồn đơn hàng')}}:<b class="text-danger">*</b>
            </label>
            <div class="{{ $errors->has('order_source_name') ? ' has-danger' : '' }}">
                <input type="text" id="orderSourceName" class="form-control m-input"
                       placeholder="{{__('Nhập tên nguồn đơn hàng')}}">
                <span class="error-order-source-name"></span>
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
                        <input id="is-actived-edit" type="checkbox" class="manager-btn" name="">
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
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit  ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 ss--btn">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="OrderSource.submitEdit()"
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