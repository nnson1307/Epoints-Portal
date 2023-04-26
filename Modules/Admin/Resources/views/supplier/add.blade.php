<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
           {{__('THÊM NHÀ CUNG CẤP')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <form id="form-add">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>
                            {{__('Tên nhà cung cấp')}}:<b class="text-danger">*</b>
                        </label>
                        <input type="text" name="supplierName" id="supplierName" class="form-control m-input"
                               placeholder="{{__('Nhập tên nhà cung cấp')}}">
                        <span class="error-supplier-name"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Tên người đại diện')}}:<b class="text-danger">*</b>
                        </label>
                        <input type="text" name="contact_name" id="contact_name" class="form-control m-input"
                               placeholder="{{__('Nhập tên người đại diện')}}">
                        <span class="error-contact-name"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Địa chỉ nhà cung cấp')}}:<b class="text-danger">*</b>
                        </label>
                        <textarea rows="4" type="text" name="address" id="address" class="form-control m-input"
                                  placeholder="{{__('Nhập địa chỉ nhà chung cấp')}}"></textarea>
                        <span class="error-address"></span>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>
                            {{__('Chức vụ người đại diện')}}:
                        </label>
                        <input type="text" name="contact_title" id="contact_title" class="form-control m-input"
                               placeholder="{{__('Nhập chức vụ người đại diện')}}">
                        <span class="error-contact-title"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('SĐT người đại diện')}}:<b class="text-danger">*</b>
                        </label>
                        <input onkeydown="onKeyDownInput(this)" type="number" min="0" name="contact_phone"
                               id="contact_phone"
                               class="form-control m-input"
                               placeholder="{{__('Nhập số điện thoại người đại diện')}}">
                        <span class="error-contact-phone"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Mô tả')}} :
                        </label>
                        <textarea placeholder="{{__('Nhập mô tả')}}" rows="6" cols="50" name="description" id="description"
                                  class="form-control"></textarea>
                        <span class="description"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="ss--btn-mobiles m--margin-bottom-5  btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>

                    <button type="button" onclick="Supplier.add(1)"
                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                    </button>
                    <button type="button" onclick="Supplier.add(0)"
                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span class="ss--text-btn-mobi">
							<i class="fa fa-plus-circle m--margin-right-10"></i>
							<span>{{__('LƯU & TẠO MỚI')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </form>
</div>
<input type="hidden" id="parameters" value="0">