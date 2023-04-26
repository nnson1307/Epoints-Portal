<div class="modal fade show" id="popup-add-address" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                     {{__('THÊM ĐỊA CHỈ NHẬN HÀNG')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-add-address">
                    <div class="row">
                        <div class="col-6 form-group m-form__group">
                            <label>
                                {{__('Tên người nhận')}}: <b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" name="customer_name" id="customer_name"
                                   placeholder="{{__('Nhập tên người nhận')}}" value="{{$detailAddress != null ? $detailAddress['customer_name'] : '' }}">
                        </div>
                        <div class="col-6 form-group m-form__group">
                            <label>
                                {{__('Số điện thoại người nhận')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" name="customer_phone" id="customer_phone"
                                   placeholder="{{__('Nhập số điện thoại người nhận')}}" value="{{$detailAddress != null ? $detailAddress['customer_phone'] : '' }}">
                        </div>
                        <div class="col-6 form-group m-form__group">
                            <label>
                                {{__('Tỉnh/Thành phố')}}:<b class="text-danger">*</b>
                            </label>
                            <div>
                                <select class="form-control select-fix" name="province_id" id="province_id" onchange="delivery.changeProvince()">
                                    <option value="">{{__('Chọn Tỉnh/Thành phố')}}</option>
                                    @foreach($listProvince as $item)
                                        <option value="{{$item['provinceid']}}" {{$detailAddress != null && $detailAddress['province_id'] == $item['provinceid'] ? 'selected' : ''}}>{{$item['type'].' '.$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 form-group m-form__group">
                            <label>
                                {{__('Quận/Huyện')}}:<b class="text-danger">*</b>
                            </label>
                            <div>
                                <select class="form-control select-fix" name="district_id" id="district_id" onchange="delivery.changeDistrict()">
                                    <option value="">{{__('Chọn Quận/Huyện')}}</option>
                                    @foreach($listDistrict as $item)
                                        <option value="{{$item['districtid']}}" {{$detailAddress != null && $detailAddress['district_id'] == $item['districtid'] ? 'selected' : ''}}>{{$item['type'].' '.$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 form-group m-form__group">
                            <label>
                                {{__('Phường/Xã')}}:<b class="text-danger">*</b>
                            </label>
                            <div>
                                <select class="form-control select-fix" name="ward_id" id="ward_id">
                                    <option value="">{{__('Chọn Phường/Xã')}}</option>
                                    @foreach($listWard as $item)
                                        <option value="{{$item['ward_id']}}" {{$detailAddress != null && $detailAddress['ward_id'] == $item['ward_id'] ? 'selected' : ''}}>{{$item['type'].' '.$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 form-group m-form__group">
                            <label>
                                {{__('Địa chỉ nhận hàng')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" name="address" id="address"
                                   placeholder="{{__('Nhập địa chỉ nhận hàng')}}" value="{{$detailAddress != null ? $detailAddress['address'] : '' }}">
                        </div>
                        <div class="col-12 form-group m-form__group">
                            <label>
                                {{__('Loại địa chỉ')}}:
                            </label>

                            <div class="form-group btn-address-check">
                                <span>
                                    <input type="radio" id="test1" name="type_address" value="home" {{$detailAddress == null ? 'checked' : ($detailAddress['type_address'] == 'home' ? 'checked' : '') }}>
                                    <label for="test1">{{__('Nhà riêng')}}</label>
                                </span>
                                <span>
                                    <input type="radio" id="test2" name="type_address" value="office" {{$detailAddress != null && $detailAddress['type_address'] == 'office' ? 'checked' : ''}}>
                                    <label for="test2">{{__('Văn phòng')}}</label>
                                </span>
                            </div>

                        </div>
                        <div class="col-12 form-group m-form__group">
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                <input type="checkbox" name="is_default" value="1" {{$detailAddress != null && $detailAddress['is_default'] == 1 ? 'checked' : ''}}> {{__('Đặt làm địa chỉ mặc định')}}
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <input type="hidden" name="customer_id" value="{{$customer_id}}">
                    <input type="hidden" name="customer_contact_id" value="{{$detailAddress != null ? $detailAddress['customer_contact_id'] : ''}}">
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        @if ($detailAddress != null)
                            <button data-dismiss="modal" onclick="delivery.showPopup(`{{$detailAddress['customer_contact_id']}}`)"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>
                        @else
                            <button data-dismiss="modal" onclick="delivery.showPopup()"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>
                        @endif

                        <button type="button" onclick="delivery.submitAddress()" class="btn color_button m-btn m-btn--icon m-btn--wide m-btn--md
                            m--margin-left-10" >
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>