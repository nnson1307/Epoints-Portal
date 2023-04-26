<div class="modal-dialog modal-dialog-centered modal-big">
    <form class="modal-content" id="edit_customer_care">
        <div class="modal-header">
            <h4 class="modal-title ss--title m--font-bold">
                <i class="fa fa-eye ss--icon-title m--margin-right-5"></i>
                {{__('CHỈNH SỬA THÔNG TIN NGƯỜI QUAN TÂM')}}
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="zalo_customer_care_id" value="{{$item->zalo_customer_care_id}}">
            <div class="form-group m-form__group row">
                <div class="col-md-3">
                    <label class="black_title">
                        {{__('Họ tên')}}:<b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="full_name" class="form-control m-input"
                           placeholder="{{__('Nhập họ tên')}}..." value="{{$item->full_name}}">
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-3">
                    <label class="black_title">
                        {{__('Số điện thoại')}}:<b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="phone_number" class="form-control m-input"
                           placeholder="{{__('Nhập số điện thoại')}}..." value="{{$item->phone_number}}">
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-3">
                    <label class="black_title">
                        {{__('Tỉnh/thành phố')}}:<b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-md-9">
                    <div class="input-group">
                        <select name="province_id" class="form-control select2 select2-active">
                            <option value="">@lang('Chọn tỉnh/thành phố')</option>
                            @foreach ($optionProvince as $key => $value)
                                <option value="{{ $key }}"
                                        @php
                                            if ($item->province_id) {
                                                echo ($item->province_id == $key ? ' selected': '');
                                            } else {
                                                echo ((79 == $key) ? ' selected': '');
                                            }
                                        @endphp
                                >{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-3">
                    <label class="black_title">
                        {{__('Quận/Huyện')}}:<b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-md-9">
                    <div class="input-group">
                        <select id="district_id" name="district_id" class="form-control select2 select2-active"
                                data-district-id="{{$item->district_id}}">
                            <option value="">@lang('Chọn Quận/Huyện')</option>
                            @foreach ($optionDistrict as $key => $value)
                                <option value="{{ $key }}" {{($item->district_id == $key ? ' selected': '')}}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-3">
                    <label class="black_title">
                        {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="address" class="form-control m-input"
                           placeholder="{{__('Nhập địa chỉ')}}..." value="{{$item->address}}">
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-3">
                    <label class="black_title">
                        {{__('Thông tin nhãn')}}:
                    </label>
                </div>
                <div class="col-md-9">
                    <div class="input-group">
                        <select name="zalo_customer_tag_id[]" class="form-control select2 select2-active"
                                multiple data-placeholder="Chọn thẻ">
                            @foreach ($listTag as $key => $value)
                                <option value="{{ $key }}"
                                @if(($item->tagList()))
                                    @foreach ($item->tagList() as $item_tag)
                                        {{ $item_tag->zalo_customer_tag_id == $key ? ' selected' : '' }}
                                            @endforeach
                                        @endif
                                >
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button type="submit"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>@lang('LƯU THÔNG TIN')</span>
                                </span>
                    </button>
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>