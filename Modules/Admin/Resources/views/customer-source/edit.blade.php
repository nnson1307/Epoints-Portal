<div class="modal-content edit-class">
    <div class="modal-header">
        <span class="m-portlet__head-icon">
            <i class="la la-edit ss--icon-title-modal"></i>
        </span>
        <h4 class="modal-title ss--title m--font-bold">
            {{__('CHỈNH SỬA NGUỒN KHÁCH HÀNG')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="customer_source_id_edit">
        <div class="form-group">
            <label>
                {{__('Tên nguồn khách hàng')}}:<b class="text-danger">*</b>
            </label>
            <div class="{{ $errors->has('group_name') ? ' has-danger' : '' }}">
                <input type="text" name="customer_source_name_edit" class="form-control m-input"
                       placeholder="{{__('Nhập tên nguồn khách hàng')}}">
                <span class="error-group-name"></span>
            </div>
        </div>
        <div class="form-group">
            <label>
                {{__('Tên loại nguồn khách hàng')}}:<b class="text-danger">*</b>
            </label>
            <div class="m-radio-inline">
                <label class="m-radio ss--m-radio--success">
                    <input type="radio" name="example_5_1" class="type-in" value="in"> {{__('Nội bộ')}}
                    <span></span>
                </label>
                <label class="m-radio ss--m-radio--success">
                    <input type="radio" name="example_5_1" class="type-out" value="out"> {{__('Ngoại bộ')}}
                    <span></span>
                </label>
            </div>
            <div class="form-group">
                <label>
                    {{__('Trạng thái')}} :
                </label>
                <div class="input-group row">
                    <div class="col-lg-1">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_actived_edit" type="checkbox" class="manager-btn" name="">
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
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles m--margin-bottom-5 btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
						 <span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="customerSource.submitEdit()"
                        class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							 <span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>