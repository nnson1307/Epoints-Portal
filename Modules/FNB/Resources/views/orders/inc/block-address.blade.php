
<p>{{__('Tên người nhận:')}} : <strong> {{$detailAddress!= null ? $detailAddress['customer_name'] : ''}}</strong></p>
<p>{{__('SĐT người nhận:')}} : <strong>{{$detailAddress!= null ? $detailAddress['customer_phone'] : ''}}</strong></p>
<p>{{__('Địa chỉ nhận hàng:')}} :<strong>{{$detailAddress!= null ? $detailAddress['address'].' , '.$detailAddress['ward_name'].' , '.$detailAddress['district_name'].' , '.$detailAddress['province_name'] : ''}}</p>
<p>{{__('Thời gian mong muốn nhận hàng:')}} <strong>{{isset($data['time_address']) ? ($data['type_time'] == 'before' ? __('Trước') : ($data['type_time'] == 'in' ? __('Trong') : __('Sau'))).' '.$data['time_address']  : ''}}</strong></p>

{{--<div class="m-list-timeline__items pb-2 ">--}}
{{--    <div class="m-list-timeline__item sz_bill">--}}
{{--        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Tên người nhận:')}}</span>--}}
{{--        <span class="m-list-timeline__time m--font-boldest  w-50">{{$detailAddress!= null ? $detailAddress['customer_name'] : ''}}</span>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="m-list-timeline__items pb-2 ">--}}
{{--    <div class="m-list-timeline__item sz_bill">--}}
{{--        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('SĐT người nhận:')}}</span>--}}
{{--        <span class="m-list-timeline__time m--font-boldest  w-50">{{$detailAddress!= null ? $detailAddress['customer_phone'] : ''}}</span>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="m-list-timeline__items pb-2 ">--}}
{{--    <div class="m-list-timeline__item sz_bill">--}}
{{--        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Địa chỉ nhận hàng:')}}</span>--}}
{{--        <span class="m-list-timeline__time m--font-boldest  w-50">{{$detailAddress!= null ? $detailAddress['address'].' , '.$detailAddress['ward_name'].' , '.$detailAddress['district_name'].' , '.$detailAddress['province_name'] : ''}}</span>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="m-list-timeline__items pb-2 ">--}}
{{--    <div class="m-list-timeline__item sz_bill">--}}
{{--        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Thời gian mong muốn nhận hàng:')}}</span>--}}
{{--        <span class="m-list-timeline__time m--font-boldest  w-50">{{isset($data['time_address']) ? ($data['type_time'] == 'before' ? __('Trước') : ($data['type_time'] == 'in' ? __('Trong') : __('Sau'))).' '.$data['time_address']  : ''}}</span>--}}
{{--    </div>--}}
{{--</div>--}}
<hr class="w-100">
<div class="row">
    @if ($itemFee != null)
        <div class="col-6">
            <div class="form-group btn-fee-check">
                <span>
                    <input type="radio" id="delivery_type_0" name="type_shipping" value="1" >
                    <label style="padding: 0px;" for="delivery_type_0" class="text-center w-100 delivery_type" data-delivery-cost-id="{{$itemFee['delivery_cost_id']}}" data-type-shipping="0" data-fee="{{(int)$itemFee['delivery_cost']}}" onclick="delivery.changeDeliveryStyle(this)">
                        <div>
                            <label style="margin-bottom: 0px;"><img class="img-fluid color-blue-check" src="{{asset('static/backend/images/car.png')}}"> <strong>{{__('Tiết kiệm')}}</strong></label><br>
                        <label class="description-check" style="margin-bottom: 0px;">{{__('Giao hàng thường')}}</label><br>
                        <label class="color-blue-check" style="margin-bottom: 0px;"><strong>{{number_format($itemFee['delivery_cost'])}}đ</strong></label><br>
                        </div>
                    </label>
                </span>
            </div>
        </div>
        @if (isset($itemFee['is_delivery_fast']) && $itemFee['is_delivery_fast'] == 1)
            <div class="col-6">
                <div class="form-group btn-fee-check">
                    <span>
                        <input type="radio" id="delivery_type_1" name="type_shipping" value="1" >
                        <label style="padding: 0px;" for="delivery_type_1" class="text-center w-100 delivery_type" data-delivery-cost-id="{{$itemFee['delivery_cost_id']}}" data-type-shipping="1" data-fee="{{(int)$itemFee['delivery_fast_cost']}}" onclick="delivery.changeDeliveryStyle(this)">
                            <div>
                                <label style="margin-bottom: 0px;"><img class="img-fluid color-blue-check" src="{{asset('static/backend/images/clock.png')}}"> <strong>{{__('Hoả tốc')}}</strong></label><br>
                                <label class="description-check" style="margin-bottom: 0px;">{{__('Giao hàng nhanh chóng')}}</label><br>
                                <label class="color-blue-check" style="margin-bottom: 0px;"><strong>{{number_format($itemFee['delivery_fast_cost'])}}đ</strong></label><br>
                            </div>
                        </label>
                    </span>
                </div>
            </div>
        @endif
    @endif
</div>