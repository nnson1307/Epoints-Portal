<div class="modal fade" id="modal-add" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg-appointment" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM LỊCH HẸN')}}
                </h5>

            </div>
            <div class="modal-body">
                <div class="m-widget4  m-section__content" id="m_blockui_1_content">
                    <form action="" method="post" id="form-add" novalidate="novalidate" autocomplete="off">
                        {!! csrf_field() !!}
                        <input type="hidden" id="customer_hidden" name="customer_hidden">
                        <input type="hidden" id="day_click" name="day_click" value="{{$date_now}}">
                        <input type="hidden" id="day_now" name="day_now" value="{{$day_now}}">
                        <input type="hidden" id="time_now" name="time_now" value="{{$time_now}}">
                        <input type="hidden" id="is_booking_past" name="is_booking_past" value="{{$is_booking_past}}">
                        <div class="row">
                            <div class="col-lg-7 bdr">
                                <div class="row">
                                    <div class="form-group m-form__group col-lg-4">
                                        <label class="black-title">{{__('Số điện thoại')}}:<b class="text-danger">*</b></label>
                                        <div class="input-group">
                                            <div class="autocomplete" style="width:100%;">
                                                <input type="text" class="form-control"
                                                       onkeydown="customer_appointment.chooseCustomer(this)"
                                                       name="phone1" id="phone1" placeholder="Vd: 0791234567">
                                            </div>
                                        </div>
                                        <span class="error-phone1" style="color: red;"></span>

                                    </div>
                                    <div class="form-group m-form__group col-lg-4">
                                        <label class="black-title">{{__('Họ tên khách hàng')}}:<b
                                                    class="text-danger">*</b></label>
                                        <div class="input-group">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input class="form-control" name="full_name" id="full_name"
                                                       placeholder="Vd: Nguyễn Văn A">
                                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                                class="la la-user"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group col-lg-4">
                                        <label class="black-title">{{__('Nhóm khách hàng')}}:</label>
                                        <div class="input-group">
                                            <select id="customer_group_id" name="customer_group_id" class="form-control" style="width:100%;">
                                                <option></option>
                                                @foreach($optionGroup as $key=>$value)
                                                    <option value="{{$value['customer_group_id']}}">{{$value['group_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m--margin-top-5"></div>
                                <div class="form-group m-form__group row">
                                    <div class="form-group col-lg-6" style="display: {{session()->get('brand_code') == 'giakhang' ? 'block' : 'none'}}">
                                        <label class="black-title">@lang('Đặt lịch theo'):</label>
                                        <div class="input-group">
                                            <select id="time_type" name="time_type" class="form-control"
                                                    style="width:100%;"
                                                    onchange="customer_appointment.changeTimeType(this)">
                                                <option value="R" selected>@lang('Theo ngày')</option>
                                                <option value="W">@lang('Theo tuần')</option>
                                                <option value="M">@lang('Theo tháng')</option>
                                                <option value="Y">@lang('Theo năm')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group col-lg-6">
                                        <label>{{__('Nguồn lịch hẹn')}}:</label>
                                        <select class="form-control m_selectpicker" id="appointment_source_id"
                                                name="appointment_source_id" title="{{__('Chọn nguồn lịch hẹn')}}"
                                                style="width:100%">
                                            {{--<option></option>--}}
                                            @foreach($optionSource as $key=>$value)
                                                @if($value['appointment_source_name'] == 'gọi điện')
                                                    <option value="{{$value['appointment_source_id']}}"
                                                            selected>{{$value['appointment_source_name']}}</option>
                                                @else
                                                    <option value="{{$value['appointment_source_id']}}">{{$value['appointment_source_name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group m-form__group row">
                                    <div class="form-group col-lg-6">
                                        <label class="black-title">{{__('Ngày hẹn')}}:<b
                                                    class="text-danger">*</b></label>
                                        <div class="input-group date_app">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input class="form-control m-input" name="date" id="date" readonly
                                                       placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                                                       value="{{$day_now}}" onchange="customer_appointment.changeNumberTime()">
                                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                                class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
                                        <span class="error_time" style="color: #ff0000"></span>
                                    </div>
                                    <div class="form-group m-form__group col-lg-6">
                                        <label class="black-title">{{__('Giờ hẹn')}}:<b
                                                    class="text-danger">*</b></label>
                                        <div class="input-group m-input-group time_app">
                                            <input id="time" name="time" class="form-control"
                                                   placeholder="{{__('Chọn giờ hẹn')}}" readonly
                                                   value="{{Carbon\Carbon::now()->format('H:i')}}" onchange="customer_appointment.changeNumberTime()">
                                        </div>
                                    </div>
                                </div>
                                <div class="time_type">
                                    @if($configToDate == 1)
                                        <div class="form-group m-form__group row">
                                            <div class="form-group col-lg-6">
                                                <label class="black-title">{{__('Ngày kết thúc')}}:<b
                                                            class="text-danger">*</b></label>
                                                <div class="input-group">
                                                    <div class="m-input-icon m-input-icon--right">
                                                        <input class="form-control m-input" name="end_date"
                                                               id="end_date"
                                                               readonly
                                                               placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                                                               value="">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                                        class="la la-calendar"></i></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group col-lg-6">
                                                <label class="black-title">{{__('Giờ kết thúc')}}:<b
                                                            class="text-danger">*</b></label>
                                                <div class="input-group m-input-group">
                                                    <input id="end_time" name="end_time" class="form-control"
                                                           placeholder="{{__('Chọn giờ hẹn')}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group m-form__group">
                                    <label>{{__('Ghi chú')}}</label>
                                    <textarea id="description" name="description" class="form-control" rows="3"
                                              cols="50" placeholder="{{__('Nhập thông tin ghi chú')}}"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group m-form__group">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <div class="btn-group btn-group-toggle source" data-toggle="buttons">
                                            <label class="btn btn-info  color_button active" id="appointment"
                                                   onclick="customer_appointment.appointment(this)">
                                                <input type="radio" name="customer_appointment_type" id="option1"
                                                       value="appointment"
                                                       autocomplete="off" checked=""> {{__('ĐẶT LỊCH TRƯỚC')}}
                                            </label>
                                            <label class="btn btn-default " id="direct"
                                                   onclick="customer_appointment.direct(this)">
                                                <input type="radio" name="customer_appointment_type" id="option2"
                                                       value="direct"
                                                       autocomplete="off"> {{__('ĐẾN TRỰC TIẾP')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label>{{__('Trạng thái lịch hẹn')}}:</label>
                                    <div class="input-group m-input-group m-input-group--solid append_status">

                                    </div>
                                </div>
                                <div id="HistoryAppointment">
                                    <div class="table-content lstHistoryAppointment">

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="m-section__content">
                            <div class="m-scrollable m-scroller" data-scrollable="true"
                                 style="height: 200px; overflow: auto;">
                                <div class="table-responsive">
                                    <table class="table m-table m-table--head-separator-metal" id="table_quantity">
                                        <thead>
                                        <tr>
                                            <th class="th_modal_app" style="width: 10%">{{__('HÌNH THỨC')}}</th>
                                            <th class="th_modal_app" style="width: 50%">{{__('DỊCH VỤ')}}</th>
                                            <th class="th_modal_app" style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('NHÂN VIÊN')}}</th>
                                            <th class="th_modal_app" style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('PHÒNG')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="tr_quantity tr_service">
                                            <td>
                                                @lang('Dịch vụ')
                                                <input type="hidden" name="customer_order" id="customer_order_1"
                                                       value="1">
                                                <input type="hidden" name="object_type" id="object_type"
                                                       value="service">
                                            </td>
                                            <td>
                                                <select class="form-control service_id" name="service_id"
                                                        id="service_id_1"
                                                        style="width: 100%" multiple="multiple">
                                                    <option></option>
                                                    @foreach($optionService as $k => $v)
                                                        <option value="{{$v['service_id']}}"
                                                                {{$service_id == $v['service_id'] ? 'selected': ''}}>{{$v['service_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                                <select class="form-control staff_id" name="staff_id" id="staff_id_1"
                                                        title="{{__('Chọn nhân viên phục vụ')}}" style="width: 100%"
                                                        disabled>
                                                    <option></option>
                                                    @foreach($optionStaff as $k => $v)
                                                        <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                                <select class="form-control room_id" name="room_id" id="room_id_1"
                                                        title="{{__('Chọn phòng')}}" style="width: 100%" disabled>
                                                    <option></option>
                                                    @foreach($optionRoom as $k => $v)
                                                        <option value="{{$v['room_id']}}">{{$v['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions m-form__actions--solid m--align-right w-100">
                                    <a href="javascript:void(0)" onclick="customer_appointment.hideAddNewModal()"
                                       class="btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md bold-huy">
                                        <span>
                                            <i class="la la-arrow-left"></i>
                                            <span> {{__('HỦY')}}</span>
                                            </span>
                                    </a>
                                    <button type="button"
                                            class="btn btn-info m-btn m-btn--icon m-btn--wide m-btn--md btn_add color_button son-mb m--margin-left-10"
                                            onclick="customer_appointment.addNew()">
                                            <span>
                                            <i class="la la-check"></i>
                                            <span>{{__('LƯU THÔNG TIN')}}</span>
                                            </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>