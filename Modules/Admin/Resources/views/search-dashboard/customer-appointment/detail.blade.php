@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
       {{__('TÌM KIẾM')}}
    </span>
@endsection

@section('content')
    <div class="m-portlet m-portlet--tabs">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text ss--title">
                        <i class="la la la-server ss--icon-title m--margin-right-5"></i>
                        {{__('CHI TIẾT LỊCH HẸN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label>{{__('Mã lịch hẹn')}}: </label>
                        <label for="">
                            {{$customerAppointment['customer_appointment_code']}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>{{__('Tên khách hàng')}}: </label>
                        <label for="">
                            {{$customerAppointment['full_name_cus']}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>{{__('Số điện thoại')}}: </label>
                        <label for="">
                            {{$customerAppointment['full_name_cus']}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>{{__('Thời gian hẹn')}}: </label>
                        <label for="">
                            {{$customerAppointment['dateTime']}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>{{__('Số lượng khách')}}: </label>
                        <label for="">
                            {{$customerAppointment['customer_quantity']}}
                        </label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label>{{__('Dịch vụ')}}: </label>
                        <label for="">
                            {{$customerAppointment['service']}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>{{__('Hình thức')}}: </label>
                        <label for="">
                            @if($customerAppointment['customer_appointment_type']=='appointment')
                                {{__('Đặt lịch trước')}}
                            @else
                                {{__('Đến trực tiếp')}}
                            @endif
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>{{__('Trạng thái')}}: </label>
                        <label for="">
                            @if($customerAppointment['status']=='new')
                                {{__('Mới')}}
                            @elseif($customerAppointment['status']=='confirm')
                                {{__('Xác nhận')}}
                            @elseif($customerAppointment['status']=='cancel')
                                {{__('Hủy')}}
                            @elseif($customerAppointment['status']=='finish')
                                {{__('Hoàn thành')}}
                            @elseif($customerAppointment['status']=='wait')
                                {{__('Chờ phục vụ')}}
                            @endif
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>{{__('Nguồn lịch hẹn')}}: </label>
                        <label class="ss--first-uppercase">
                            {{$customerAppointment['appointment_source_name']}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>{{__('Ghi chú')}}: </label>
                        <label class="ss--first-uppercase">
                            {{$customerAppointment['description']}}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button onclick="window.history.back();"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                           <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>{{__('HỦY')}}</span>
                                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_script')
@endsection