<div class="modal fade" role="dialog" id="modal-customer">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-user-plus"></i> {{__('CHỌN KHÁCH HÀNG')}}
                </h4>
                <a href="javascript:void(0)" onclick="order.customer_haunt('1',this)" style="margin-top:-10px"
                   class="m-btn m-btn--pill ss--button-cms-piospa btn btn-sm choose_1 son-mb btn-choose-customer btn-choose-customer-temporary">
                    <img src="{{asset('static/backend/images/fnb/select-customer.png')}}">{{__('Khách hàng vãng lai')}}</a>
            </div>
            <form id="form-customer">
                <div class="modal-body">
                    <div class="form-group m-form__group search_customer">
                        <div>
                            <label style="font-weight: bold;font-size:13px ">@lang('Tìm kiếm khách hàng'):</label>
                        </div>
                        <div>
                            <select class="form-control" id="customer-search" style="width: 100%">
                                @if(isset($customerLoad) && $customerLoad != null && $customerLoad['customer_id'] != null)
                                    <option value="{{$customerLoad['customer_id']}}" selected>{{$customerLoad['full_name'] .' - '. $customerLoad['phone1']}}</option>
                                @endif
                            </select>
                        </div>

                        <input type="hidden" name="customer_id_modal" id="customer_id_modal" value="{{isset($customerLoad) && $customerLoad != null ? $customerLoad['customer_id'] : ''}}">
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="form-group m-form__group col-lg-6">
                            <label style="font-size: 11px">{{__('Nhóm khách hàng')}}:</label>
                            <select class="form-control group" style="width: 100%"
                                    name="customer_group" id="customer_group" {{isset($customerLoad) && $customerLoad != null  ? 'disabled': ''}}>
                                <option value="">{{__('Chọn nhóm khách hàng')}}</option>
                                @if(isset($customer_group) && count($customer_group) > 0)
                                    @foreach($customer_group as $key=>$value)
                                        <option value="{{$value['customer_group_id']}}"
                                                {{isset($customerLoad) && $customerLoad != null && $customerLoad['customer_group_id'] == $value['customer_group_id'] ? 'selected': ''}}>{{$value['group_name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="error_group" style="color: #ff0000"></span>
                        </div>
                        <div class="form-group m-form__group col-lg-6 name">
                            <label style="font-size: 11px">{{__('Tên khách hàng')}}:</label>
                            <input type="text" class="form-control btn-sm" name="full_name" id="full_name"
                                   placeholder="{{__('Nhập tên khách hàng')}}" value="{{isset($customerLoad) && $customerLoad != null  ? $customerLoad['full_name']: ''}}" {{isset($customerLoad) && $customerLoad != null  ? 'disabled': ''}}>
                            <span class="error_name" style="color: #ff0000"></span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="form-group m-form__group col-lg-6 postcode">
                            <label style="font-size: 11px">{{__('Postcode')}}:</label>
                            <input type="text" class="form-control btn-sm" name="postcode" id="postcode"
                                   placeholder="{{__('Nhập postcode')}}" value="{{isset($customerLoad) && $customerLoad != null  ? $customerLoad['postcode']: ''}}" {{isset($customerLoad) && $customerLoad != null  ? 'disabled': ''}}>
                            <span class="error_postcode" style="color: #ff0000"></span>
                        </div>
                        <div class="form-group m-form__group col-lg-6 phone">
                            <label style="font-size: 11px">{{__('Số điện thoại')}}:</label>
                            <input type="number" class="form-control btn-sm" name="phone" id="phone" value="{{isset($customerLoad) && $customerLoad != null  ? $customerLoad['phone1']: ''}}" {{isset($customerLoad) && $customerLoad != null  ? 'disabled': ''}}
                                   placeholder="{{__('Nhập số điện thoại')}}" onkeydown="javascript: return event.keyCode == 69 ? false : true">
                            <input type="hidden" name="customer_avatar" id="customer_avatar" value="{{isset($customerLoad) && $customerLoad != null  ? $customerLoad['customer_avatar']: ''}}">
                            <input type="hidden" name="member_money" id="member_money" value="{{isset($customerLoad['money']) ? $customerLoad['money']['balance'] : 0}}">
                            <span class="error_phone" style="color: #ff0000"></span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="form-group m-form__group col-lg-6">
                            <label style="font-size: 11px">{{__('Tỉnh/ Thành phố')}}:</label>
                            <select class="form-control state" style="width: 100%"
                                    name="state" id="state" {{isset($customerLoad) && $customerLoad != null  ? 'disabled': ''}}>
{{--                                <option value="">{{__('Chọn tỉnh/thành')}}</option>--}}
                                <option></option>
                                @if(isset($province) && count($province) > 0)
                                    @foreach($province as $key=>$value)
                                        <option value="{{(int)$value['provinceid']}}"
                                                {{isset($customerLoad) && $customerLoad != null && intval($customerLoad['province_id']) == intval($value['provinceid']) ? 'selected': ''}}>{{$value['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="error_state" style="color: #ff0000"></span>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label style="font-size: 11px">{{__('Quận/ Huyện')}}:</label>
                            <select class="form-control suburb" style="width: 100%" name="suburb" id="suburb" {{isset($customerLoad) && $customerLoad != null  ? 'disabled': ''}}>
                                <option>{{__('Chọn quận/huyện')}}</option>
                                @if(isset($customerLoad) && $customerLoad != null && $customerLoad['district_id'] != null)
                                    <option value="{{$customerLoad['district_id']}}" selected>{{$customerLoad['district_name']}}</option>
                                @endif
                            </select>
                            <span class="error_suburb" style="color: #ff0000"></span>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label style="font-size: 11px">{{__('Phường/xã')}}:</label>
                            <select class="form-control suburbward" style="width: 100%" name="suburbward" id="suburbward">
                                <option>{{__('Chọn phường/xã')}}</option>

                            </select>
                            <span class="error_suburb" style="color: #ff0000"></span>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label style="font-size: 11px">{{__('Địa chỉ')}}:</label>
                            <input type="text" class="form-control btn-sm" name="address" id="address"
                                   placeholder="{{__('Nhập địa chỉ khách hàng')}}" value="{{isset($customerLoad) && $customerLoad != null  ? $customerLoad['address']: ''}}" {{isset($customerLoad) && $customerLoad != null  ? 'disabled': ''}}>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-form__actions m--align-right w-100">
                        <button type="button" onclick="order.cancelModalCus()"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn ">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                        </button>
                        <button type="button" onclick="order.modal_customer_click()"
                                class="btn btn-primary color_button  m-btn m-btn--icon m-btn--wide m-btn--md btn-print m--margin-left-10 son-mb">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
