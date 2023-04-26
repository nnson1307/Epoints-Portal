<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="la la-edit ss--icon-title m--margin-right-5"></i>
            {{__('CHỈNH SỬA NHÀ CUNG CẤP')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <form id="form-edit">
        <div class="modal-body">
            <input type="hidden" id="supplier-id">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>
                            {{__('Tên nhà cung cấp')}}:<b class="text-danger">*</b>
                        </label>
                        <input id="supplierName-edit" type="text" name="supplierName" class="form-control m-input"
                               placeholder="{{__('Nhập tên nhà cung cấp')}}">
                        <span class="error error-supplier-name"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Tên người đại diện')}}:<b class="text-danger">*</b>
                        </label>
                        <input type="text" name="contactName" id="contact_name-edit" class="form-control m-input"
                               placeholder="{{__('Nhập tên người đại diện')}}">
                        <span class="error error-contact-name"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Địa chỉ nhà chung cấp')}}:<b class="text-danger">*</b>
                        </label>
                        <textarea rows="4" type="text" name="address" id="address-edit" class="form-control m-input"
                                  placeholder="{{__('Nhập địa chỉ nhà chung cấp')}}"></textarea>
                        <span class="error error-address"></span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>
                            {{__('Chức vụ người đại diện')}}:
                        </label>
                        <input type="text" name="contactTitle" id="contact_title-edit" class="form-control m-input"
                               placeholder="{{__('Nhập chức vụ người đại diện')}}">
                        <span class="error error-contact-title"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('SĐT người đại diện')}}:<b class="text-danger">*</b>
                        </label>
                        <input onkeydown="onKeyDownInput(this)" type="text" name="contactPhone" id="contact_phone-edit"
                               class="form-control m-input"
                               placeholder="{{__('Nhập số điện thoại người đại diện')}}">
                        <span class="error error-contact-phone"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Mô tả')}} :
                        </label>
                        <textarea placeholder="{{__('Nhập mô tả')}}" rows="6" cols="50" id="description-edit" name="description"
                                  class="form-control"></textarea>
                        <span class="description-edit"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="ss--btn-mobiles m--margin-bottom-5 btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="Supplier.submitEdit()"
                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
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