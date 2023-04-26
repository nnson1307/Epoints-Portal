@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" style="height: 20px;">
        {{__('SMS')}}
    </span>
@endsection
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

        /*.modal-lg {*/
        /*max-width: 65% !important;*/
        /*}*/
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-institution"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CẤU HÌNH GỬI SMS')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.sms.get-config',session('routeList')))
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modal-config"
                       onclick="ConfigSms.getConfig(1)"
                       class="btn btn-primary color_button m-btn m-btn--icon m-btn--pill btn-sm">
                        <span>
						    <i class="la la-cog"></i>
							<span> {{__('CẤU HÌNH')}}</span>
                        </span>
                    </a> &nbsp;
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modal-config"
                       onclick="ConfigSms.getConfig(2)"
                       class="btn btn-primary color_button m-btn m-btn--icon m-btn--pill btn-sm">
                        <span>
						    <i class="la la-cog"></i>
							<span> {{__('CẤU HÌNH MARKETING')}}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: body -->
            <div class="m-widget4">
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'birthday')" type="checkbox"
                                        {{$allType['birthday']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['birthday']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                        {{__('Chúc mừng sinh nhật khách hàng')}}.
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                {{__('Được gửi đến vào ngày sinh nhật khách hàng')}}
                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="3"
                                                  name="message-birthday"
                                                  id="message-birthday"
                                                  class="form-control m-input ss--background-color">{{$allType['birthday']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('birthday')" href="javascript:void(0)"
                                               title="Chỉnh sửa"
                                               style="color: #a1a1a1;float: right"><i class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="time-send-birthday" value="{{$allType['birthday']['time_sent']}}">
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'new_appointment')" type="checkbox"
                                        {{$allType['new_appointment']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['new_appointment']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Lịch hẹn mới')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Được gửi đến những khách có lịch hẹn mới')}}
                                </span>
                                <br>
                                {{--<a href="#"--}}
                                {{--onclick="ConfigSms.config('new_appointment')"--}}
                                {{--class="btn btn-sm btn-secondary active m-btn m-btn--custom m-btn--icon">--}}
                                {{--<span><i class="la la-edit"></i><span>--}}
                                {{--Cài đặt--}}
                                {{--</span></span>--}}
                                {{--</a>--}}
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>

                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="3"
                                                  name="message-new-calendar"
                                                  id="message-new-calendar"
                                                  class="form-control m-input ss--background-color">{{$allType['new_appointment']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('new_appointment')" href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="{{__('Chỉnh sửa')}}"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="m-widget4__ext">

                    </div>
                </div>
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'cancel_appointment')" type="checkbox"
                                        {{$allType['cancel_appointment']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['cancel_appointment']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                        {{__('Huỷ lịch hẹn')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Khách hàng huỷ lich hẹn khi nhân viên gọi điện thoại xác nhân')}}
                                </span>
                                <br>
                                {{--<a href="#"--}}
                                {{--onclick="ConfigSms.config('cancel_appointment')"--}}
                                {{--class="btn btn-sm btn-secondary active m-btn m-btn--custom m-btn--icon">--}}
                                {{--<span><i class="la la-edit"></i><span>--}}
                                {{--Cài đặt--}}
                                {{--</span></span>--}}
                                {{--</a>--}}
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>

                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="3"
                                                  name="message-cancel-calendar"
                                                  id="message-cancel-calendar"
                                                  class="form-control m-input ss--background-color">{{$allType['cancel_appointment']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('cancel_appointment')"
                                               href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="Chỉnh sửa"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'remind_appointment')" type="checkbox"
                                        {{$allType['remind_appointment']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['remind_appointment']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                        {{__('Nhắc lịch')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                {{__('Được gửi đến những khách có lịch hẹn trong ngày')}}
                                </span>
                                <br>
                                {{--<a href="#"--}}
                                {{--onclick="ConfigSms.config('remind_appointment')"--}}
                                {{--class="btn btn-sm btn-secondary active m-btn m-btn--custom m-btn--icon">--}}
                                {{--<span><i class="la la-edit"></i><span>--}}
                                {{--Cài đặt--}}
                                {{--</span></span>--}}
                                {{--</a>--}}
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>

                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="3"
                                                  name="message-remind-calendar"
                                                  id="message-remind-calendar"
                                                  class="form-control m-input ss--background-color">{{$allType['remind_appointment']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('remind_appointment')"
                                               href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="Chỉnh sửa"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" id="value-remind-appointment"
                                   value="{{$allType['remind_appointment']['value']}}">
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'paysuccess')" type="checkbox"
                                        {{$allType['paysuccess']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['paysuccess']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                        {{__('Đơn hàng')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                {{__('Được gửi đến những khách thanh toán thành công đơn hàng')}}
                                </span>
                                <br>
                                {{--<a href="#"--}}
                                {{--onclick="ConfigSms.config('paysuccess')"--}}
                                {{--class="btn btn-sm btn-secondary active m-btn m-btn--custom m-btn--icon">--}}
                                {{--<span><i class="la la-edit"></i><span>--}}
                                {{--Cài đặt--}}
                                {{--</span></span>--}}
                                {{--</a>--}}
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  name="message-order"
                                                  id="message-paysuccess"
                                                  class="form-control m-input ss--background-color">{{$allType['paysuccess']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('paysuccess')" href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="{{__('Chỉnh sửa')}}"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'new_customer')" type="checkbox"
                                        {{$allType['new_customer']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['new_customer']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                       {{__('Khách hàng mới')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                {{__('Được gửi đến những khách đang kí làm hội viên, thành viên')}}
                                </span>
                                <br>
                                {{--<a href="#"--}}
                                {{--onclick="ConfigSms.config('new_customer')"--}}
                                {{--class="btn btn-sm btn-secondary active m-btn m-btn--custom m-btn--icon">--}}
                                {{--<span><i class="la la-edit"></i><span>--}}
                                {{--Cài đặt--}}
                                {{--</span></span>--}}
                                {{--</a>--}}
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>

                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  name="message-new-customer"
                                                  id="message-new-customer"
                                                  class="form-control m-input ss--background-color">{{$allType['new_customer']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('new_customer')" href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="Chỉnh sửa"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'service_card_nearly_expired')"
                                       type="checkbox"
                                        {{$allType['service_card_nearly_expired']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['service_card_nearly_expired']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Thẻ dịch vụ sắp hết hạn')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Được gửi đến những khách có thẻ dịch vụ sắp hết hạn')}}
                                </span>
                                <br>
                                {{--<a href="#"--}}
                                {{--onclick="ConfigSms.config('service_card_nearly_expired')"--}}
                                {{--class="btn btn-sm btn-secondary active m-btn m-btn--custom m-btn--icon">--}}
                                {{--<span><i class="la la-edit"></i><span>--}}
                                {{--Cài đặt--}}
                                {{--</span></span>--}}
                                {{--</a>--}}
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>

                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-service-card-nearly-expired"
                                                  class="form-control m-input ss--background-color">{{$allType['service_card_nearly_expired']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('service_card_nearly_expired')"
                                               href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="Chỉnh sửa"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" id="value-service-card-nearly-expired"
                                   value="{{$allType['service_card_nearly_expired']['value']}}">
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'service_card_over_number_used')"
                                       type="checkbox"
                                        {{$allType['service_card_over_number_used']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['service_card_over_number_used']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Thẻ dịch vụ hết số lần sử dụng')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Được gửi đến những khách  có thẻ dịch vụ đã sử dụng')}}
                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-service-card-over-number-used"
                                                  class="form-control m-input ss--background-color">{{$allType['service_card_over_number_used']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('service_card_over_number_used')"
                                               href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="Chỉnh sửa"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'service_card_expires')" type="checkbox"
                                        {{$allType['service_card_expires']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['service_card_expires']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Thẻ dịch vụ hết hạn')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Được gửi đến những khách có thẻ dich vụ đã hết hạn')}}
                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-service-card-expired"
                                                  class="form-control m-input ss--background-color">{{$allType['service_card_expires']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('service_card_expires')"
                                               href="javascript:void(0)"
                                               title="Chỉnh sửa"
                                               style="color: #a1a1a1;float: right"><i class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'delivery_note')"
                                       type="checkbox"
                                        {{$allType['delivery_note']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['delivery_note']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Tạo phiếu giao hàng thành công')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Được gửi đến khách hàng đã đặt hàng')}}
                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-delivery-note"
                                                  class="form-control m-input ss--background-color">{{$allType['delivery_note']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('delivery_note')"
                                               href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="Chỉnh sửa"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'confirm_deliveried')" type="checkbox"
                                        {{$allType['confirm_deliveried']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['confirm_deliveried']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Xác nhận giao hàng hoàn tất')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Được gửi đến khách hàng đã đặt hàng')}}
                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-confirm-deliveried"
                                                  class="form-control m-input ss--background-color">{{$allType['confirm_deliveried']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('confirm_deliveried')"
                                               href="javascript:void(0)"
                                               title="Chỉnh sửa"
                                               style="color: #a1a1a1;float: right"><i class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'order_success')"
                                       type="checkbox"
                                        {{$allType['order_success']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['order_success']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Đặt hàng thành công')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Được gửi đến khách hàng đã đặt hàng thành công')}}
                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-order-success"
                                                  class="form-control m-input ss--background-color">{{$allType['order_success']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('order_success')"
                                               href="javascript:void(0)"
                                               style="color: #a1a1a1;float: right" title="{{__('Chỉnh sửa')}}"><i
                                                        class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'active_warranty_card')" type="checkbox"
                                        {{$allType['active_warranty_card']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['active_warranty_card']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Kích hoạt thẻ bảo hành thành công')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">
                                    {{__('Được gửi đến khách hàng có đối tượng được kích hoạt bảo hành')}}
                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-active-warranty-card"
                                                  class="form-control m-input ss--background-color">{{$allType['active_warranty_card']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('active_warranty_card')"
                                               href="javascript:void(0)"
                                               title="Chỉnh sửa"
                                               style="color: #a1a1a1;float: right"><i class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'otp')" type="checkbox"
                                        {{$allType['otp']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['otp']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('OTP')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">

                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-otp"
                                                  class="form-control m-input ss--background-color">{{$allType['otp']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('otp')"
                                               href="javascript:void(0)"
                                               title="Chỉnh sửa"
                                               style="color: #a1a1a1;float: right"><i class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-widget4__item ss--background-config-sms">
                    <div class="m-widget4__checkbox  m--margin-left-15">
                        @if(in_array('admin.sms.active-sms-config',session('routeList')))
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input onclick="ConfigSms.activedSmsConfig(this,'is_remind_use')" type="checkbox"
                                        {{$allType['is_remind_use']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @else
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                <input type="checkbox"
                                        {{$allType['is_remind_use']['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        @endif
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                    {{__('Gửi nhắc sử dụng lại dịch vụ/ sản phẩm/ thẻ dịch vụ')}}
                                </span>
                                <br>
                                <span class="m-widget4__sub sz_dt">

                                </span>
                                <br>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="sz_sms">{{__('Nội dung tin nhắn')}}</label>
                                        <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="4"
                                                  id="message-remind-use"
                                                  class="form-control m-input ss--background-color">{{$allType['is_remind_use']['content']}}</textarea>
                                    </div>
                                    <div class="col-lg-1">
                                        @if(in_array('admin.sms.get-config',session('routeList')))
                                            <a onclick="ConfigSms.config('is_remind_use')"
                                               href="javascript:void(0)"
                                               title="Chỉnh sửa"
                                               style="color: #a1a1a1;float: right"><i class="la la-edit"></i></a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-config" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ss--title" id="exampleModalLabel">
                        <i class="la la-edit ss--icon-title m--margin-right-5"></i>
                        {{__('CẤU HÌNH GỬI SMS')}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <input type="hidden" id="brand_name_id" name="brand_name_id">
                            <label class="col-form-label col-lg-4 col-sm-12">{{__('Bật/tắt cấu hình')}}:</label>
                            <div class="col-lg-8 col-md-9 col-sm-12">
                                <input data-switch="true" type="checkbox"
                                       {{$smsProvider->is_actived==1?'checked="checked"':''}}  id="is_actived">
                            </div>
                        </div>
                        <div class="m---content" {{$smsProvider->is_actived==0?'style=display:none;':''}}>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{__('Nhà cung cấp')}}:<b class="text-danger">*</b>
                                        </label>
                                        <select class="form-control select-picker" name="provider" id="provider">
                                            <option value="viettel" {{$smsProvider->provider=='viettel'?'selected':''}}>
                                                {{__('Viettel')}}
                                            </option>
                                            <option value="vht" {{$smsProvider->provider=='vht'?'selected':''}}>
                                                VHT
                                            </option>
                                            <option value="vietguys" {{$smsProvider->provider=='vietguys'?'selected':''}}>
                                                {{__('Vietguys')}}
                                            </option>
                                            <option value="fpt" {{$smsProvider->provider=='fpt'?'selected':''}}>
                                                FPT
                                            </option>
                                            <option value="st" {{$smsProvider->provider=='st'?'selected':''}}>
                                                ST
                                            </option>
                                            <option value="clicksend" {{$smsProvider->provider=='clicksend'?'selected':''}}>
                                                Click Send
                                            </option>
                                        </select>

                                    </div>
                                    <div class="form-group m-form__group">

                                        <label>
                                            {{__('Số/Brandname')}}:<b class="text-danger">*</b>
                                        </label>


                                        <input type="text" id="value" class="form-control m-input"
                                               value="{{$smsProvider->value}}"
                                               placeholder="{{__('Số/Tên brandname')}}">
                                        <span class="text-danger error-value"></span>

                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{__('Cách gửi')}}:<b class="text-danger">*</b>
                                        </label>
                                        <select class="form-control select-picker" name="type" id="type">
                                            <option value="brandname" {{$smsProvider->type=='brandname'?'selected':''}}>
                                                {{__('Brandname')}}
                                            </option>
                                            <option value="09xxxxxx" {{$smsProvider->type=='09xxxxxx'?'selected':''}}>
                                                {{__('Số ngẫu nhiên(09xxxxxxxx)')}}
                                            </option>
                                            <option value="1900xxxx" {{$smsProvider->type=='1900xxxx'?'selected':''}}>
                                                {{__('Số ngẫu nhiên(1900xxxx)')}}
                                            </option>
                                            <option value="8xxx" {{$smsProvider->type=='8xxx'?'selected':''}}>
                                                {{__('Số ngẫu nhiên(8xxxx)')}}
                                            </option>
                                        </select>

                                    </div>
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{__('Account')}}:<b class="text-danger">*</b>
                                        </label>


                                        <input type="text" id="account" class="form-control m-input"
                                               placeholder="{{__('API key  hoặc Username')}}"
                                               value="{{$smsProvider->account}}">

                                    </div>
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{__('Password')}}:<b class="text-danger">*</b>
                                        </label>


                                        <input type="password" id="password" class="form-control m-input"
                                               value="{{$smsProvider->password}}"
                                               placeholder="{{__('API key  hoặc Password')}}">
                                        <span class="text-danger error-password"></span>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>
                            <button onclick="ConfigSms.saveChange()" type="button"
                                    class="ss--btn-mobiles btn btn-primary ss--btn color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
							</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin::marketing.sms.setting-sms.include')
    <input type="hidden" value="" id="sms-type">
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/marketing/sms/config-sms/config-sms.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="parameter-customer-name">
        <a href="javascript:void(0)" class="btn btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('customer-name')">{{__('Tên khách hàng')}}</a>
    </script>
    <script type="text/template" id="parameter-customer-full-name">
        <a href="javascript:void(0)" class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('full-name')">{{__('Họ & Tên')}}</a>
    </script>
    <script type="text/template" id="parameter-customer-gender">
        <a href="javascript:void(0)"
           class="btn m--margin-left-10 btn-sm gioitinh ss--btn-parameter gioitinh ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('customer-gender')">{{__('Giới tính')}}</a>
    </script>
    <script type="text/template" id="parameter-day-time-appointment">
        <a href="javascript:void(0)" class="btn m--margin-left-10 btn-sm ss--btn-parameter time-hen ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('day-appointment')">
            {{__('Thời gian hẹn')}}</a>
    </script>
    {{--<script type="text/template" id="parameter-time-appointment">--}}
    {{--<a id="time-appointment" href="javascript:void(0)" class="btn btn-metal m--margin-left-10 time-appointment"--}}
    {{--style="color: black;" onclick="ConfigSms.valueParameter('time-appointment')">Giờ hẹn</a>--}}
    {{--</script>--}}
    <script type="text/template" id="parameter-code-appointment">
        <a id="code-lich" href="javascript:void(0)"
           class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('code-appointment')">{{__('Mã lịch hẹn')}}</a>
    </script>
    <script type="text/template" id="parameter-name-spa">
        <a href="javascript:void(0)" class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('name-spa')">{{__('Tên Spa')}}</a>
    </script>
    <script type="text/template" id="parameter-datetime">
        <a href="javascript:void(0)" class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('datetime')">{{__('Thời gian')}}</a>
    </script>
    <script type="text/template" id="parameter-code-card">
        <a href="javascript:void(0)" class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('code-card')">{{__('Mã thẻ')}}</a>
    </script>
    <script type="text/template" id="parameter-otp">
        <a href="javascript:void(0)" class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('otp')">{{__('OTP')}}</a>
    </script>
    <script type="text/template" id="parameter-object-type">
        <a href="javascript:void(0)" class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('object_type')">{{__('Loại đối tượng')}}</a>
    </script>
    <script type="text/template" id="parameter-object-name">
        <a href="javascript:void(0)" class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
           style="color: black;" onclick="ConfigSms.valueParameter('object_name')">{{__('Tên đối tượng')}}</a>
    </script>
@stop