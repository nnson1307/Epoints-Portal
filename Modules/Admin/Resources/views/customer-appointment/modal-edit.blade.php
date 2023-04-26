<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-dialog-centered modal-lg-appointment" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-pencil"></i> {{__('CHỈNH SỬA LỊCH HẸN')}}
                </h5>
            </div>
            <div class="modal-body">
                <div class="m-widget4  m-section__content" id="m_blockui_2_content">
                    <form action="" method="post" id="form-edit" novalidate="novalidate" autocomplete="off">
                        {!! csrf_field() !!}
                        <input type="hidden" id="customer_appointment_id" name="customer_appointment_id"
                               value="{{$item['customer_appointment_id']}}">
                        <input type="hidden" id="customer_appointment_type" name="customer_appointment_type"
                               value="{{$item['customer_appointment_type']}}">
                        <input type="hidden" id="customer_id" name="customer_id" value="{{$item['customer_id']}}">
                        <input type="hidden" id="discount" name="discount" value="{{$item['discount']}}">
                        <input type="hidden" id="is_booking_past" name="is_booking_past" value="{{$is_booking_past}}">

                        <div class="row">
                            <div class="col-lg-7 bdr">
                                <div class="row">
                                    <div class="form-group m-form__group col-lg-4">
                                        <label class="black-title">{{__('Số điện thoại')}}:<b class="text-danger">*</b></label>
                                        <div class="input-group">
                                            <div class="autocomplete" style="width:100%;">
                                                <input type="text" class="form-control" disabled
                                                       onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                                       name="phone1_edit" id="phone1_edit"
                                                       placeholder="{{__('Hãy nhập số điện thoại')}}"
                                                       value="{{$item['phone']}}">
                                            </div>
                                        </div>
                                        <span class="error-phone1" style="color: red;"></span>

                                    </div>
                                    <div class="form-group m-form__group col-lg-4">
                                        <label class="black-title">{{__('Tên khách hàng')}}:<b class="text-danger">*</b></label>
                                        <div class="input-group">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input class="form-control" name="full_name_edit"
                                                       id="full_name_edit"
                                                       placeholder="{{__('Họ và tên')}}" disabled
                                                       value="{{$item['full_name']}}">
                                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                                class="la la-user"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group col-lg-4">
                                        <label class="black-title">{{__('Nhóm khách hàng')}}:</label>
                                        <div class="input-group">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input class="form-control" name="group_name" id="group_name"
                                                       placeholder="Vd: Personal" disabled
                                                       value="{{$item['group_name']}}">
                                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                                class="la la-user"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-separator m-separator--dashed m--margin-top-5"></div>
                                <div class="row">
                                    <div class="form-group col-lg-6"
                                         style="display: {{session()->get('brand_code') == 'giakhang' ? 'block' : 'none'}}">
                                        <label class="black-title">@lang('Đặt lịch theo'):</label>
                                        <div class="input-group">
                                            <select id="time_type" name="time_type" class="form-control"
                                                    style="width:100%;"
                                                    onchange="customer_appointment.changeTimeType(this)"
                                                    {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                                <option value="R" {{$item['time_type'] == 'R' ? 'selected' : ''}}>@lang('Theo ngày')</option>
                                                <option value="W" {{$item['time_type'] == 'W' ? 'selected' : ''}}>@lang('Theo tuần')</option>
                                                <option value="M" {{$item['time_type'] == 'M' ? 'selected' : ''}}>@lang('Theo tháng')</option>
                                                <option value="Y" {{$item['time_type'] == 'Y' ? 'selected' : ''}}>@lang('Theo năm')</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if ($is_change_branch == 1)
                                        <div class="form-group m-form__group col-lg-6">
                                            <label>{{__('Chi nhánh')}}:</label>
                                            <select class="form-control" id="branch_id_modal" name="branch_id"
                                                    style="width:100%">
                                                {{--<option></option>--}}
                                                @foreach($optionBranch as $v)
                                                    <option value="{{$v['branch_id']}}"
                                                            {{$v['branch_id'] == $item['branch_id'] ? 'selected': ''}}>{{$v['branch_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="form-group col-lg-6">
                                            <label class="black-title">{{__('Chi nhánh')}} :</label>
                                            <div class="input-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input type="text" disabled class="form-control"
                                                           id="branch" value="{{$item['branch_name']}}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group m-form__group col-lg-6">
                                        <label class="black-title">{{__('Nguồn lịch hẹn')}}:</label>
                                        <input class="form-control" disabled id="appointment_source_name"
                                               name="appointment_source_name"
                                               value="{{$item['appointment_source_name']}}" {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                    </div>
                                </div>

                                <div class="form-group m-form__group row">
                                    <div class="form-group col-lg-6">
                                        <label class="black-title">{{__('Ngày hẹn')}}:<b
                                                    class="text-danger">*</b></label>
                                        <div class="input-group date_edit">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input class="form-control m-input" name="date" id="date"
                                                       readonly placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                                                       value="{{\Carbon\Carbon::parse($item['date'])->format('d/m/Y')}}"
                                                       {{$item['status'] == 'finish' ? 'disabled' : ''}}
                                                       onchange="customer_appointment.changeNumberTime()">
                                                <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                                class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
                                        <span class="error_time_edit" style="color: #ff0000"></span>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="black-title">{{__('Giờ hẹn')}}:<b
                                                    class="text-danger">*</b></label>
                                        <div class="input-group m-input-group time_edit">
                                            <input class="form-control" id="time" name="time"
                                                   onchange="customer_appointment.changeNumberTime()" readonly
                                                   value="{{\Carbon\Carbon::parse($item['time'])->format('H:i')}}" {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                        </div>
                                    </div>
                                </div>

                                <div class="time_type">
                                    @if ($item['time_type'] != 'R')
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Số tuần/tháng/năm')}}:<b
                                                        class="text-danger">*</b></label>
                                            <input class="form-control" id="type_number" name="type_number"
                                                   value="{{$item['number_start']}}"
                                                   onchange="customer_appointment.changeNumberTime()">
                                        </div>
                                    @endif
                                    @if($configToDate == 1)
                                        <div class="form-group m-form__group row">
                                            <div class="form-group col-lg-6">
                                                <label class="black-title">{{__('Ngày kết thúc')}}:<b
                                                            class="text-danger">*</b></label>
                                                <div class="input-group">
                                                    <div class="m-input-icon m-input-icon--right">
                                                        <input class="form-control m-input" name="end_date"
                                                               id="end_date"
                                                               readonly placeholder="{{__('Chọn ngày hẹn')}}"
                                                               type="text"
                                                               {{$item['status'] == 'finish' ? 'disabled' : ''}}
                                                               value="{{$item['end_date'] != null ? \Carbon\Carbon::parse($item['end_date'])->format('d/m/Y') : null}}">
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
                                                           {{$item['status'] == 'finish' ? 'disabled' : 'readonly'}}
                                                           placeholder="{{__('Chọn giờ hẹn')}}"
                                                           value="{{$item['end_time'] != null ? \Carbon\Carbon::parse($item['end_time'])->format('H:i') : null}}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{__('Ghi chú')}}</label>
                                    <textarea id="description" name="description" class="form-control" rows="3"
                                              {{$item['status'] == 'finish' ? 'disabled' : ''}}
                                              cols="50"
                                              placeholder="{{__('Nhập thông tin ghi chú')}}">{{$item['description']}}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group m-form__group type_edit">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <div class="btn-group m-btn-group type" role="group"
                                             aria-label="...">
                                            @if(in_array($item['customer_appointment_type'], ['appointment', 'booking']))
                                                <button type="button" id="appointment_type"
                                                        class="btn btn-info color_button">{{__('ĐẶT LỊCH TRƯỚC')}}
                                                </button>
                                            @endif
                                            @if($item['customer_appointment_type'] == 'direct')
                                                <button type="button" id="direct_type"
                                                        class="btn btn-default color_button">{{__('ĐẾN TRỰC TIẾP')}}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label>{{__('Trạng thái lịch hẹn')}}:</label>
                                    <div class="append_status_edit">
                                        <div class="form-group m-form__group">
                                            <div class="btn-group btn-group-toggle status_edit" data-toggle="buttons">
                                                @if(in_array($item['customer_appointment_type'], ['appointment', 'booking']))
                                                    @if ($item['status'] == 'new')
                                                        <label class="{{$item['status'] == 'new' ? 'btn btn-info active_edit color_button' : 'btn btn-default'}}"
                                                               id="new_stt"
                                                               onclick="customer_appointment.status_edit(this)">
                                                            <input type="radio" name="status" id="option1" value="new"
                                                                   autocomplete="off" checked=""> {{__('MỚI')}}
                                                        </label>
                                                    @endif
                                                    @if(in_array($item['status'], ['new', 'confirm']))
                                                        <label class="{{$item['status'] == 'confirm' ? 'btn btn-info active_edit color_button' : 'btn btn-default'}}"
                                                               id="confirm_stt"
                                                               onclick="customer_appointment.status_edit(this)">
                                                            <input type="radio" name="status" id="option2"
                                                                   value="confirm"
                                                                   autocomplete="off"> {{__('XÁC NHẬN')}}
                                                        </label>
                                                    @endif
                                                @endif
                                                @if(in_array($item['status'], ['new', 'confirm', 'wait']))
                                                    <label class="{{$item['status'] == 'wait' ? 'btn btn-info active_edit color_button' : 'btn btn-default'}}"
                                                           id="wait_stt"
                                                           onclick="customer_appointment.status_edit(this)">
                                                        <input type="radio" name="status" id="option2" value="wait"
                                                               autocomplete="off"> {{__('CHỜ PHỤC VỤ')}}
                                                    </label>
                                                @endif
                                                @if(in_array($item['status'], ['new', 'confirm', 'wait', 'processing']))
                                                    <label class="{{$item['status'] == 'processing' ? 'btn btn-info active_edit color_button' : 'btn btn-default'}}"
                                                           id="processing_stt"
                                                           onclick="customer_appointment.status_edit(this)">
                                                        <input type="radio" name="status" id="option2"
                                                               value="processing"
                                                               autocomplete="off"> {{__('ĐANG THỰC HIỆN')}}
                                                    </label>
                                                @endif
                                                @if(in_array($item['status'], ['new', 'confirm', 'wait', 'processing', 'cancel']))
                                                    <label class="{{$item['status'] == 'cancel' ? 'btn btn-info active_edit color_button' : 'btn btn-default'}}"
                                                           id="cancel_stt"
                                                           onclick="customer_appointment.status_edit(this)">
                                                        <input type="radio" name="status" id="option2" value="cancel"
                                                               autocomplete="off"> {{__('HỦY')}}
                                                    </label>
                                                @endif
                                                @if ($item['status'] == 'finish')
                                                    <label class="{{$item['status'] == 'finish' ? 'btn btn-info active_edit color_button' : 'btn btn-default'}}"
                                                           id="finish_stt"
                                                           onclick="customer_appointment.status_edit(this)">
                                                        <input type="radio" name="status" id="option1" value="new"
                                                               autocomplete="off" checked=""> {{__('ĐÃ HOÀN THÀNH')}}
                                                    </label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="HistoryAppointmentEdit">
                                    <div class="table-content lstHistoryAppointmentEdit">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="m-section__content">
                            <div class="m-scrollable m-scroller" data-scrollable="true"
                                 style="height: 200px; overflow: auto;">
                                <div class="table-responsive">
                                    <table class="table m-table m-table--head-separator-metal "
                                           id="table_quantity_edit">
                                        <thead>
                                        <tr>
                                            <th class="th_modal_app" style="width: 10%">{{__('HÌNH THỨC')}}</th>
                                            <th class="th_modal_app" style="width: 50%">{{__('DỊCH VỤ')}}</th>
                                            <th class="th_modal_app"
                                                style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('NHÂN VIÊN')}}</th>
                                            <th class="th_modal_app"
                                                style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('PHÒNG')}}</th>
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
                                                        style="width: 100%"
                                                        multiple="multiple" {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                                    <option></option>
                                                    @foreach($optionService as $k => $v)
                                                        <option value="{{$k}}" {{in_array($k, $arrServiceDetail) ? 'selected' : ''}}>{{$v}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                                <select class="form-control staff_id" name="staff_id" id="staff_id_1"
                                                        title="{{__('Chọn nhân viên phục vụ')}}" style="width: 100%"
                                                        {{count($arrServiceDetail) > 0 ? '' : 'disabled'}} {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                                    <option></option>
                                                    {{-- @foreach($optionStaff as $k => $v)
                                                        <option value="{{$k}}" {{in_array($k, $staffService) ? 'selected' : ''}}>{{$v}}</option>
                                                    @endforeach --}}
                                                </select>

                                                <input type="hidden" id="staff_id_old_1" value="{{count($staffService) > 0 ? $staffService[0] : ''}}">
                                            </td>
                                            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                                <select class="form-control room_id" name="room_id" id="room_id_1"
                                                        title="{{__('Chọn phòng')}}"
                                                        style="width: 100%" {{count($arrServiceDetail) > 0 ? '' : 'disabled'}} {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                                    <option></option>
                                                    @foreach($optionRoom as $k => $v)
                                                        <option value="{{$k}}" {{in_array($k, $roomService) ? 'selected' : ''}}>{{$v}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @if (isset($listMemberCard) && count($listMemberCard) > 0)
                                            <tr class="tr_quantity tr_card">
                                                <td>
                                                    {{__('Thẻ liệu trình')}}
                                                    <input type="hidden" name="customer_order" id="customer_order_2"
                                                           value="2">
                                                    <input type="hidden" name="object_type" id="object_type"
                                                           value="member_card">
                                                </td>
                                                <td>
                                                    <select class="form-control service_id" name="service_id"
                                                            id="service_id_2"
                                                            style="width: 100%"
                                                            multiple="multiple" {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                                        <option></option>
                                                        @foreach($listMemberCard as $k => $v)
                                                            <option value="{{$v['customer_service_card_id']}}"
                                                                    {{in_array($v['customer_service_card_id'], $arrMemberCardDetail) ? 'selected' : ''}}>{{$v['card_name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                                    <select class="form-control staff_id" name="staff_id"
                                                            id="staff_id_2"
                                                            title="{{__('Chọn nhân viên phục vụ')}}"
                                                            style="width: 100%" {{count($arrMemberCardDetail) > 0 ? '' : 'disabled'}} {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                                        <option></option>
                                                        @foreach($optionStaff as $k => $v)
                                                            <option value="{{$k}}" {{in_array($k, $staffMemberCard) ? 'selected' : ''}}>{{$v}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" id="staff_id_old_2" value="{{count($staffMemberCard) > 0 ? $staffMemberCard[0] : ''}}">
                                                </td>
                                                <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                                    <select class="form-control room_id" name="room_id" id="room_id_2"
                                                            title="{{__('Chọn phòng')}}"
                                                            style="width: 100%" {{count($arrMemberCardDetail) > 0 ? '' : 'disabled'}} {{$item['status'] == 'finish' ? 'disabled' : ''}}>
                                                        <option></option>
                                                        @foreach($optionRoom as $k => $v)
                                                            <option value="{{$k}}" {{in_array($k, $roomMemberCard) ? 'selected' : ''}}>{{$v}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions m--align-right append_btn">
                                    <button data-dismiss="modal" onclick="customer_appointment.out_modal()"
                                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                        <span>
                                            <i class="la la-arrow-left"></i><span>{{__('HỦY')}}</span>
                                        </span>
                                    </button>
                                    @if ($sameBranch == 1 && $item['status'] != 'finish' && $isEnabledEditMoreThanDay == 1)
                                        <button type="button"
                                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit"
                                                onclick="customer_appointment.submit_edit()">
                                            <span>
                                            <i class="la la-pencil"></i>
                                            <span>{{__('CẬP NHẬT')}}</span>
                                            </span>
                                        </button>

                                        <a href="{{route('admin.customer_appointment.receipt', $item['customer_appointment_id'])}}"
                                           class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit">
                                            <span>
                                            <i class="la la-check"></i>
                                            <span>{{__('THANH TOÁN')}}</span>
                                            </span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
