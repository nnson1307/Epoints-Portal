<div id="add-customer-quickly" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-user-plus"></i> @lang("THÊM KHÁCH HÀNG")
                </h4>
            </div>
            <div class="modal-body">
                <form id="form-add-customer">
                    <div class="form-group m-form__group">
                        <label class="black-title">
                            @lang("Tên khách hàng")<b class="text-danger">*</b></span>
                        </label>
                        <div class="input-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text" id="pop_full_name" name="pop_full_name"
                                       class="form-control m-input "
                                       placeholder="{{__("Tên khách hàng")}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                class="la la-user"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black-title">
                            @lang("Giới tính"):
                        </label>
                        <div class="m-form__group form-group ">

                            <div class="m-radio-inline">
                                <label class="m-radio cus">
                                    <input type="radio" name="pop_gender" checked value="male"> @lang("Nam")
                                    <span class="span"></span>
                                </label>
                                <label class="m-radio cus">
                                    <input type="radio" name="pop_gender" value="female"> @lang("Nữ")
                                    <span class="span"></span>
                                </label>
                                <label class="m-radio cus">
                                    <input type="radio" name="pop_gender" value="other"> @lang("Khác")
                                    <span class="span"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black-title">{{__('Số điện thoại')}}:<b
                                    class="text-danger">*</b></label>
                        <div class="input-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="number" id="pop_phone1" name="pop_phone1"
                                       class="form-control m-input "
                                       placeholder="@lang("Thêm số điện thoại")"
                                       onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                class="la la-phone"></i></span></span>
                            </div>
                            <span class="error_phone1" style="color: #ff0000"></span>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black-title">
                            {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                        </label>
                        <div class="row">
                            <div class="col-lg-6">
                                <select name="pop_province_id" id="pop_province_id" class="form-control select" style="width: 100%">
                                    <option value="">@lang('Chọn tỉnh/thành')</option>
                                    @foreach($optionProvince as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 d">
                                <select name="pop_district_id" id="pop_district_id"
                                        class="form-control district select" style="width: 100%">
                                    <option value="">@lang('Chọn quận/huyện')</option>
                                </select>
                            </div>
                        </div>

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

                    <div class="form-group m-form__group">
                        <label class="black-title">@lang("Loại khách hàng"):<b
                                    class="text-danger">*</b></label>
                        <div class="input-group">
                            <select id="pop_customer_type" name="pop_customer_type" onchange="changeCustomerType(this)"
                                    title="@lang("Chọn loại khách hàng")"
                                    class="form-control m-input select" style="width: 100%">
                                <option value="personal">@lang('Cá nhân')</option>
                                <option value="business">@lang('Doanh nghiệp')</option>
                            </select>
                        </div>
                    </div>
                    <div class="open-business-input form-group m-form__group" hidden>
                        <label class="black-title">@lang("Mã số thuế"):</label>
                        <div class="m-input-icon m-input-icon--right">
                            <input type="text" id="pop_tax_code" name="pop_tax_code" class="form-control m-input" minlength="11" maxlength="13">
                        </div>
                    </div>
                    <div class="open-business-input form-group m-form__group" hidden>
                        <label class="black-title">
                            @lang("Người đại diện"):
                        </label>
                        <div class="input-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text" id="pop_representative" name="pop_representative"
                                       class="form-control m-input " maxlength="191"
                                       placeholder="{{__("Người đại diện")}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                class="la la-user"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="open-business-input form-group m-form__group" hidden>
                        <label class="black-title">{{__('Hotline')}}:</label>
                        <div class="input-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="number" id="pop_hotline" name="pop_hotline"
                                       class="form-control m-input " maxlength="15" minlength="10"
                                       placeholder="@lang("Nhập hotline")"
                                       onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                class="la la-phone"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black-title">@lang("Nhóm khách hàng"):<b
                                    class="text-danger">*</b></label>
                        <div class="input-group">
                            <select id="pop_customer_group_id" name="pop_customer_group_id"
                                    title="@lang("Chọn nhóm khách hàng")"
                                    class="form-control m-input select" style="width: 100%">
                                <option value="">@lang('Chọn nhóm khách hàng')</option>
                                @foreach($optionGroup as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
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
                            <button type="button" onclick="addQuickly.createCustomerQuickly()"
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
