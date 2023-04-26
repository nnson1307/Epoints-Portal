<div id="add-supplier-quickly" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-user-plus"></i> @lang("THÊM NHÀ CUNG CẤP")
                </h4>
            </div>
            <div class="modal-body">
                <form id="form-add-supplier">
                    <div class="form-group m-form__group">
                        <label class="black-title">
                            @lang("Tên nhà cung cấp")<b class="text-danger">*</b></span>
                        </label>
                        <div class="input-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text" id="pop_supplier_name" name="pop_supplier_name"
                                       class="form-control m-input "
                                       placeholder="{{__("Tên nhà cung cấp")}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                class="la la-user"></i></span></span>
                            </div>
                            <span class="error_name" style="color: #ff0000"></span>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black-title">
                            @lang("Người đại diện")<b class="text-danger">*</b></span>
                        </label>
                        <div class="input-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text" id="pop_contact_name" name="pop_contact_name"
                                       class="form-control m-input "
                                       placeholder="{{__("Người đại diện")}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                class="la la-user"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black-title">{{__('Hotline')}}:<b
                                    class="text-danger">*</b></label>
                        <div class="input-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="number" id="pop_contact_phone" name="pop_contact_phone"
                                       class="form-control m-input "
                                       placeholder="@lang("Nhập hotline")"
                                       onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                class="la la-phone"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black-title">
                            {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="input-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input id="pop_address" name="pop_address"
                                       class="form-control autosizeme"
                                       placeholder="@lang("Nhập địa chỉ khách hàng")"
                                       data-autosize-on="true"
                                       style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-map-marker"></i></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>
                            <button type="button" onclick="addQuickly.createSupplierQuickly()"
                                    class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>@lang("THÊM")</span>
							</span>
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
