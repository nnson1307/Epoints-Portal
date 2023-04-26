<div id="add_customer_refer" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-user-plus"></i> @lang("THÊM NGƯỜI GIỚI THIỆU")
                </h4>
            </div>
            <div class="modal-body">
                <form id="form_refer">
                    <div class="form-group">
                        <label>
                            @lang("Tên người giới thiệu"):
                        </label>
                        <div>
                            <input type="text" id="full_name_refer" name="full_name"
                                   class="form-control m-input"
                                   placeholder="@lang("Nhập tên người giới thiệu")">
                            <span class="full_name"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Số điện thoại')}}:
                        </label>
                        <div>
                            <input type="text" id="phone1_refer" name="phone1"
                                   class="form-control m-input"
                                   placeholder="@lang("Nhập số điện thoại")">

                        </div>
                        <span class="error_phone" style="color: red"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Địa chỉ')}}:
                        </label>
                        <div>
                            <input type="text" id="address_refer" name="address" class="form-control  m-input"
                                   placeholder="@lang("Nhập địa chỉ")">
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button type="button" onclick="$('#add_customer_refer').modal('hide');"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>
                            <button type="button" onclick="customer.add_customer_refer(1)"
                                    class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>@lang("THÊM")</span>
							</span>
                            </button>

                        </div>
                    </div>
                    <input type="hidden" name="type_add" id="type_add" value="0">
                </form>
            </div>

        </div>

    </div>
</div>
