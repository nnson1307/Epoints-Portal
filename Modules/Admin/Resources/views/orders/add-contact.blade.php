<div class="form-group m-form__group address">
    <div class="row col-lg-12"><h5>Address</h5></div>
    <div class="row">
        <div class="form-group m-form__group col-lg-4 state">
            <label style="font-size: 11px">{{__('Bang')}}:</label>
            <select name="province_id" id="province_id"
                    class="form-control" style="width: 100%">
                @if(isset($listProvince) && $listProvince != null)
                    @foreach($listProvince as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group m-form__group col-lg-4">
            <label style="font-size: 11px">{{__('Thị trấn')}}:</label>
            <select name="district_id" id="district_id"
                    class="form-control district" style="width: 100%"
                    title="{{__('Chọn quận/ huyện')}}">
                <option value="">@lang('Chọn quận/ huyện')</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group m-form__group col-lg-4 postcode">
            <label style="font-size: 11px">{{__('Postcode')}}:</label>
            <input type="text" class="form-control btn-sm" name="post_code" id="post_code"
                   placeholder="{{__('Nhập post code')}}">
            <span class="error_postcode" style="color: #ff0000"></span>
        </div>
        <div class="form-group m-form__group col-lg-4 full_address">
            <label style="font-size: 11px">{{__('Full adress')}}:</label>
            <input type="text" class="form-control btn-sm" name="full_address" id="full_address"
                   placeholder="{{__('Nhập địa chỉ')}}">
            <span class="error_postcode" style="color: #ff0000"></span>
        </div>
    </div>
</div>

<div class="form-group m-form__group contact">
    <div class="row col-lg-12"><h5>Contact</h5></div>
    <div class="row">
        <div class="form-group m-form__group col-lg-4 name">
            <label style="font-size: 11px">{{__('Tên khách hàng')}}:</label>
            <input type="text" class="form-control btn-sm" name="contact_name" id="contact_name"
                   placeholder="{{__('Nhập tên khách hàng')}}">
            <span class="error_name" style="color: #ff0000"></span>
        </div>
        <div class="form-group m-form__group col-lg-4 phone">
            <label style="font-size: 11px">{{__('Số điện thoại')}}:</label>
            <input type="number" class="form-control btn-sm" name="contact_phone" id="contact_phone"
                   placeholder="{{__('Nhập số điện thoại')}}">
            <span class="error_phone" style="color: #ff0000"></span>
        </div>
        <div class="form-group m-form__group col-lg-6 name">
            <label style="font-size: 11px">{{__('Email')}}:</label>
            <input type="text" class="form-control btn-sm" name="contact_email" id="contact_email"
                   placeholder="{{__('Nhập email')}}">
            <span class="error_email" style="color: #ff0000"></span>
        </div>
    </div>
</div>

<input type="hidden" id="get_id_contact" value="-1">