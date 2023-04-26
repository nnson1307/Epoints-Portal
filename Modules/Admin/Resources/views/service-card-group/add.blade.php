<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5">
            </i>{{__('THÊM NHÓM THẺ DỊCH VỤ')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>
                {{__('Tên nhóm thẻ')}}:<b class="text-danger">*</b>
            </label>
            <div class="{{ $errors->has('order_source_name') ? ' has-danger' : '' }}">
                <input type="text" id="name" name="name" class="form-control m-input"
                       placeholder="{{__('Nhập tên nhóm thẻ')}}">
                <span class="error-name text-danger"></span>
            </div>
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
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit  ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="serviceCardGroup.add(0)"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                </button>
                <button class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-left-10 m--margin-bottom-5"
                        onclick="serviceCardGroup.add(1)">
                    <span class="ss--text-btn-mobi">
                    <i class="fa fa-plus-circle m--margin-right-10">
                    </i>
                        <span>{{__('LƯU & TẠO MỚI')}}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>