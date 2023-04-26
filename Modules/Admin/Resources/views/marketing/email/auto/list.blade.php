<div class="m-widget4">
    @if(isset($LIST))
        @foreach($LIST as $key=>$item)
            <div class="m-widget4__item">
                <div class="m-widget4__checkbox">
                    @if(in_array('admin.email-auto.change-status',session('routeList')))
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success">
                            @if($item['is_actived'])
                                <input type="checkbox" id="actived" checked
                                       onclick="auto.changeStatus(this, '{!! $item['id'] !!}')"
                                       value="{{$item['is_actived']}}">
                            @else
                                <input type="checkbox" id="actived"
                                       onclick="auto.changeStatus(this, '{!! $item['id'] !!}')"
                                       value="{{$item['is_actived']}}">
                            @endif
                            <span></span>
                        </label>
                    @else
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success">
                            @if($item['is_actived'])
                                <input type="checkbox" id="actived" checked
                                       value="{{$item['is_actived']}}">
                            @else
                                <input type="checkbox" id="actived"
                                       value="{{$item['is_actived']}}">
                            @endif
                            <span></span>
                    @endif
                </div>
                <div class="m-widget4__info">
							<span class="m-widget4__title sz_dt left-wg-em">
{{--                                @if($item['key']=='birthday')--}}
{{--                                    {{__('Chúc mừng sinh nhật')}}--}}
{{--                                @elseif($item['key']=='remind_appointment')--}}
{{--                                    {{__('Nhắc lịch hẹn')}}--}}
{{--                                @elseif($item['key']=='new_appointment')--}}
{{--                                    {{__('Lịch hẹn mới')}}--}}
{{--                                @elseif($item['key']=='cancel_appointment')--}}
{{--                                    {{__('Hủy lịch hen')}}--}}
{{--                                @elseif($item['key']=='paysuccess')--}}
{{--                                    {{__('Mua hàng thành công')}}--}}
{{--                                @elseif($item['key']=='new_customer')--}}
{{--                                    {{__('Đăng kí khách hàng mới')}}--}}
{{--                                @elseif($item['key']=='service_card_nearly_expired')--}}
{{--                                    {{__('Thông báo thẻ dịch vụ sắp hết hạn')}}--}}
{{--                                @elseif($item['key']=='service_card_over_number_used')--}}
{{--                                    {{__('Thông báo thẻ dịch vụ hết số lần sử dụng')}}--}}
{{--                                @elseif($item['key']=='service_card_expires')--}}
{{--                                    {{__('Thông báo thẻ dịch vụ hết hạn')}}--}}
{{--                                @endif--}}
                                {{__($item['title'])}}
							</span><br>
                    <span class="m-widget4__sub sz_dt left-wg-em">
                        @if($item['key']=='birthday')
                            {{__('Gửi email chúc mừng sinh nhật khách hàng')}}
                        @elseif($item['key']=='remind_appointment')
                            {{__('Gửi email nhắc khách hàng có đặt lịch vào ngày hôm nay')}}
                        @elseif($item['key']=='new_appointment')
                            {{__('Được gửi đến sau khi khách hàng thêm lịch hẹn')}}
                        @elseif($item['key']=='cancel_appointment')
                            {{__('Được gửi đến sau khi khách hàng hủy lịch hẹn')}}
                        @elseif($item['key']=='paysuccess')
                            {{__('Được gửi đến sau khi khách hàng thanh toán đơn hàng thành công')}}
                        @elseif($item['key']=='new_customer')
                            {{__('Được gửi đến sau khi khách hàng đăng kí thành viên mới')}}
                        @elseif($item['key']=='service_card_nearly_expired')
                            {{__('Gửi email thông báo thẻ dịch vụ sắp hết hạn')}}
                        @elseif($item['key']=='service_card_over_number_used')
                            {{__('Gửi email thông báo thẻ dịch vụ hết số lần sử dụng')}}
                        @elseif($item['key']=='service_card_expires')
                            {{__('Gửi email thông báo thẻ dịch vụ hết hạn')}}
                        @elseif($item['key']=='order_success')
                            {{__('Gửi email thông báo đặt hàng thành công')}}
                        @elseif($item['key']=='active_warranty_card')
                            {{__('Gửi email thông báo kích hoạt thẻ bảo hành thành công')}}
                        @elseif($item['key']=='is_remind_use')
                            {{__('Gửi nhắc sử dụng lại dịch vụ/ sản phẩm/ thẻ dịch vụ')}}
                        @endif

							</span>
                </div>
                <div class="m-widget4__ext">
                    @if(in_array('admin.email-auto.submit-setting-content',session('routeList')))
                        <a href="javascript:void(0)" onclick="auto.modal_content({{$item['id']}})"
                           class="m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary btn-sm-list_email">
                            <i class="la la-edit icon-sz"></i>
                            <span>{{__('Chỉnh sửa')}}</span>
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>