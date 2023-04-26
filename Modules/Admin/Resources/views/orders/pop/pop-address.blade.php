<div class="modal fade show" id="popup-address" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                     {{__('CHỌN ĐỊA CHỈ NHẬN HÀNG')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-list-address">
                    <div class="row form-group block-list-address">
                        @foreach ($listAddress as $item)
                            <label class="kt-radio kt-radio--bold kt-radio--success col-12">
                                <div class="row">
                                    <div class="col-1 text-right">
                                        <input type="radio" name="customer_contact_id" {{$item['is_default'] == 1 && $idAddress == '' ? 'checked' : ($idAddress == $item['customer_contact_id'] ? 'checked' : '')}} value="{{$item['customer_contact_id']}}">
                                    </div>
                                    <div class="col-9">
                                        <p class="font-weight-bold">{{$item['customer_name']}} - {{$item['customer_phone']}}</p>
                                        <p style="font-weight:500">{{$item['address']}}, {{$item['ward_name']}}, {{$item['district_name']}}, {{$item['province_name']}} <span class="pl-3" style="font-weight:300">{{$item['is_default'] == 1 ? __('Địa chỉ mặc định') : ''}}</span></p>
                                    </div>
                                    <div class="col-2">
                                        <a href="javascript:void(0)" onclick="delivery.showPopupAddAddress(`{{$item['customer_contact_id']}}`)"
                                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                            <i class="la la-edit"></i>
                                        </a>
                                        @if($item['is_default'] != 1)
                                            <button type="button" onclick="delivery.removeAddress(`{{$item['customer_contact_id']}}`)"
                                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                    title="{{__("Xóa")}}">
                                                <i class="la la-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <span></span>
                            </label>
                        @endforeach
                    </div>
                </form>
                <button type="button" class="button-add-address" onclick="delivery.showPopupAddAddress()"><i class="fa fa-plus pr-2"></i>{{__('Thêm địa chỉ mới')}}</button>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <p><strong>{{__('Thời gian mong muốn nhận hàng:')}}</strong></p>
                        <div class="row">
                            <div class="col-2">
                                <select class="select-fix" name="type_time" id="type_time">
                                    <option value="before" {{ isset($data['type_time']) && $data['type_time'] == 'before' ? 'selected' : '' }}>{{__('Trước')}}</option>
                                    <option value="in" {{ isset($data['type_time']) && $data['type_time'] == 'in' ? 'selected' : '' }}>{{__('Trong')}}</option>
                                    <option value="after" {{ isset($data['type_time']) && $data['type_time'] == 'after' ? 'selected' : '' }}>{{__('Sau')}}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="text" name="time_address" class="form-control" id="time_address" value="{{ isset($data['time_address']) ? $data['time_address'] : '' }}" placeholder="{{__('Chọn ngày mong muốn nhận hàng')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>

                        <button type="button" onclick="delivery.changeInfoAddress()" class="btn color_button m-btn m-btn--icon m-btn--wide m-btn--md
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