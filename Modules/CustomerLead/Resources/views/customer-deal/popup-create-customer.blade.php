<div class="modal fade show" id="modal-create-customer" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('TẠO KHÁCH HÀNG')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create-customer">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black-title">
                                    @lang("Tên khách hàng")<b class="text-danger">*</b></span>
                                </label>
                                <div class="input-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text" hidden value="{{$item['customer_lead_code']}}" name="customer_lead_code" id="customer_lead_code">
                                        <input type="text" id="customer_full_name" name="customer_full_name"
                                               class="form-control m-input "
                                               value="{{$item['full_name']}}"
                                               placeholder="{{__("Tên khách hàng")}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                        class="la la-user"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black-title">
                                    {{__("Ngày sinh")}}:
                                </label>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-4">
                                        <select class="form-control op_day" style="width: 100%"
                                                title="{{__('Ngày')}}"
                                                id="customer_day"
                                                name="customer_day">
                                            <option></option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 m">
                                        <select class="form-control width-select" style="width: 100%"
                                                title="@lang("Tháng")"
                                                id="customer_month" name="customer_month">
                                            <option></option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 y">
                                        <select class="form-control width-select" style="width: 100%"
                                                title="@lang("Năm")"
                                                id="customer_year" name="customer_year">
                                            <option></option>
                                            @for($i=1970;$i<= date("Y");$i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <span class="error_birthday" style="color: #ff0000"></span>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black-title">
                                    @lang("Giới tính"):
                                </label>
                                <div class="m-form__group form-group ">

                                    <div class="m-radio-inline">
                                        <label class="m-radio cus">
                                            <input type="radio" {{$item['gender'] == 'male' ? 'checked' : ''}} name="customer_gender" value="male"> @lang("Nam")
                                            <span class="span"></span>
                                        </label>
                                        <label class="m-radio cus">
                                            <input type="radio" {{$item['gender'] == 'female' ? 'checked' : ''}} name="customer_gender" value="female"> @lang("Nữ")
                                            <span class="span"></span>
                                        </label>
                                        <label class="m-radio cus">
                                            <input type="radio" {{$item['gender'] == 'other' ? 'checked' : ''}} name="customer_gender" value="other"> @lang("Khác")
                                            <span class="span"></span>
                                        </label>
                                    </div>
                                    @if ($errors->has('gender'))
                                        <span class="form-control-feedback">
                                     {{ $errors->first('gender') }}
                                         </span>
                                        <br>
                                    @endif

                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black-title">{{__('Số điện thoại')}}:<b
                                            class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="number" id="customer_phone" name="customer_phone"
                                               class="form-control m-input "
                                               value="{{$item['phone']}}"
                                               placeholder="@lang("Thêm số điện thoại")"
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
                                <div class="row">
                                    <div class="col-lg-6">
                                        <select name="customer_province_id" id="customer_province_id" class="form-control" style="width: 100%">
                                            <option></option>
                                            @foreach($optionProvince as $key=>$value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 d">
                                        <select name="customer_district_id" id="customer_district_id"
                                                class="form-control district" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input id="customer_address" name="customer_address"
                                               class="form-control autosizeme"
                                               placeholder="@lang("Nhập địa chỉ khách hàng")"
                                               value="{{$item['address']}}"
                                               data-autosize-on="true"
                                               style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-map-marker"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black-title">
                                    {{__('Email')}}:
                                </label>
                                <div class="m-input-icon m-input-icon--right">
                                    <input type="text" id="customer_email" name="customer_email"
                                           class="form-control m-input"
                                           value="{{$item['email']}}"
                                           placeholder="Vd: piospa@gmail.com">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-at"></i></span></span>
                                </div>
                                <span class="error_email" style="color: #ff0000"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="edit.saveCustomer()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>