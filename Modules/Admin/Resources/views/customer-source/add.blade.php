<div class="modal-content">
    <div class="modal-header">
        <span class="m-portlet__head-icon">
            <i class="fa fa-plus-circle ss--icon-title-modal"></i>
        </span>
        <h4 class="modal-title ss--title m--font-bold">
            {{__('THÊM NGUỒN KHÁCH HÀNG')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>
                {{__('Tên nguồn khách hàng')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" id="customer_source_name" name="customer_source_name" class="form-control m-input"
                   placeholder="{{__('Nhập tên nguồn khách hàng')}}">
            <span class="error-customer-source-name"></span>
        </div>
        <div class="form-group">
            <label>
                {{__('Tên loại nguồn khách hàng')}}:<b class="text-danger">*</b>
            </label>
            <div class="m-radio-inline">
                <label class="m-radio ss--m-radio--success">
                    <input id="type-in" type="radio" checked name="example_5_1" value="in"> {{__('Nội bộ')}}
                    <span></span>
                </label>
                <label class="m-radio ss--m-radio--success">
                    <input id="type-out" type="radio" name="example_5_1" value="out"> {{__('Ngoại bộ')}}
                    <span></span>
                </label>
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
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                <span class="ss--text-btn-mobi">
                <i class="la la-arrow-left"></i>
                <span>{{__('HỦY')}}</span>
                </span>
                </button>

                <button type="button" onclick="customerSource.addClose()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-left-10 m--margin-bottom-5">
                <span class="ss--text-btn-mobi">
                    <i class="la la-check"></i>
                    <span>{{__('LƯU THÔNG TIN')}}</span>
                    </span>
                </button>

                <button type="button" onclick="customerSource.add()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-left-10 m--margin-bottom-5">
                <span class="ss--text-btn-mobi">
                    <i class="fa fa-plus-circle"></i>
                    <span>{{__('LƯU & TẠO MỚI')}}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

