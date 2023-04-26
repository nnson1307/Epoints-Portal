@foreach ($listAddress as $item)
    <label class="kt-radio kt-radio--bold kt-radio--success col-12">
        <div class="row">
            <div class="col-1 text-right">
                <input type="radio" name="delivery_customer_address_id" {{$item['is_default'] == 1 ? 'checked' : ''}} value="{{$item['delivery_customer_address_id']}}">
            </div>
            <div class="col-9">
                <p class="font-weight-bold">{{$item['customer_name']}} - {{$item['customer_phone']}}</p>
                <p style="font-weight:500">{{$item['address']}}, {{$item['district_name']}}, {{$item['province_name']}} <span class="pl-3" style="font-weight:300">{{$item['is_default'] == 1 ? __('Địa chỉ mặc định') : ''}}</span></p>
            </div>
            <div class="col-2">
                <a href=""
                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                    <i class="la la-edit"></i>
                </a>
                @if($item['is_default'] != 1)
                    <button type="button" onclick="delivery.removeAddress(`{{$item['delivery_customer_address_id']}}`)"
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