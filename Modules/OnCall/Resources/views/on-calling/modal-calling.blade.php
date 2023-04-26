<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">


<!-- The Modal -->
<div class="modal fade" id="nhandt-modal-oncall" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-big">
        <div class="modal-content clear-form">
            <div class="">
                <button onclick="layout.closePopupOncall(this)" style="font-size: 20px;"
                        class="oncall-button-window float-right">
                     <span>
                         <i class="la la-close" style="font-size: 30px; margin: 0px 0px 0px -10px;"></i>
                     </span>
                </button>
                <button onclick="layout.minimizePopupOncall('{{Auth()->id()}}', '{{$history_id}}', '{{$id}}', '{{$type}}', '{{$phone}}', '{{session()->get('brand_code')}}', '{{$item['avatar']}}')"
                        class="oncall-button-window float-right">
                    <span><i class="far fa-window-minimize"></i></span>
                </button>
            </div>
            <div class="modal-header" style="height: 4rem;padding: 20px!important;align-items: center;">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <h4 class="m-portlet__head-text">
                        <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                        {{ __('CHĂM SÓC KHÁCH HÀNG') }}
                    </h4>
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('customer-lead.customer-deal') . '?object_type=' .$type . '&object_id=' . $id}}"
                           target="_blank"
                           class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                            <span class="ss--text-btn-mobi">
                                <i class="fa fa-plus-circle m--margin-right-10"></i>
                                <span>{{ __('THÊM DEAL') }}</span>
                            </span>
                        </a>
                        @if($type == 'customer')
                            <a href="{{route('ticket.add') . '?customer_id=' .$item['customer_id']}}" target="_blank"
                               class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                            <span class="ss--text-btn-mobi">
                                <i class="fa fa-plus-circle m--margin-right-10"></i>
                                <span>{{ __('THÊM TICKET') }}</span>
                            </span>
                            </a>
                            <a href="{{route('admin.order.add', ['customer_id' => $item['customer_id']])}}" target="_blank"
                               class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                            <span class="ss--text-btn-mobi">
                                <i class="fa fa-plus-circle m--margin-right-10"></i>
                                <span>{{ __('THÊM ĐƠN HÀNG') }}</span>
                            </span>
                            </a>
                        @endif
                        <button type="button" onclick="layout.saveCareAndInfo('{{$type}}', '{{$id}}')"
                                class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                             <span class="ss--text-btn-mobi">
                                 <i class="la la-check"></i>
                                 <span>{{ __('LƯU THÔNG TIN') }}</span>
                             </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <form id="oncall_form_care_info">
                            <input type="text" hidden class="hidden" id="parent_oncall_type" value="{{$type}}">
                            <input type="text" hidden class="hidden" id="parent_oncall_id" value="{{$id}}">
                            <input type="hidden" id="history_id" name="history_id" value="{{$history_id}}">
                            <h5>{{__('Thông tin khách hàng')}}</h5>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Tên khách hàng') }} <b class="text-danger">*</b>
                                        </label>
                                        <input type="text" name="oncall_full_name" id="oncall_full_name"
                                               class="form-control m-input"
                                               value="{{isset($item['full_name']) ? $item['full_name'] : ''}}"
                                               placeholder="{{ __('Nhập tên khách hàng') }}...">
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Số điện thoại') <b class="text-danger">*</b>
                                        </label>
                                        <div>
                                            <div class="input-group mb-3">
                                                @if($type == 'customer')
                                                    <input type="text" class="form-control" name="oncall_phone1"
                                                           id="oncall_phone1"
                                                           value="{{isset($item['phone1']) ? $item['phone1'] : ''}}"
                                                           placeholder="{{__('Nhập số điện thoại')}}">
                                                @else
                                                    <input type="text" class="form-control" name="oncall_phone"
                                                           id="oncall_phone"
                                                           value="{{isset($item['phone']) ? $item['phone'] : ''}}"
                                                           placeholder="{{__('Nhập số điện thoại')}}">
                                                @endif
                                                <div class="input-group-append">
                                             <span class="input-group-text" id="basic-addon2"><i
                                                         class="fa fa-phone" aria-hidden="true"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Giới tính') }}
                                        </label>
                                        <div class="input-group m-radio-inline">
                                            <label class="m-radio cus">
                                                <input type="radio" name="oncall_gender" value="male"
                                                        {{isset($item['gender']) && $item['gender'] == 'male' ? 'checked' : ''}}
                                                >{{ __('Nam') }}
                                                <span></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="oncall_gender"
                                                       {{isset($item['gender']) && $item['gender'] == 'female' ? 'checked' : ''}}
                                                       value="female">{{ __('Nữ') }}
                                                <span></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="oncall_gender"
                                                       {{isset($item['gender']) && $item['gender'] == 'other' ? 'checked' : ''}}
                                                       value="other">{{ __('Khác') }}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? '' : 'hidden'}}>
                                        <label class="black_title">
                                            @lang('Ngày sinh')
                                        </label>
                                        <div class="d-flex">
                                            <select name="oncall_day" id=oncall_"day"
                                                    style="width: 100% !important;"
                                                    class="form-control select-unset_arrow text-center mr-3 modal-select2">
                                                <option value="">@lang('Ngày')</option>
                                                @for ($i = 1; $i < 31 + 1; $i++)
                                                    @if(isset($item['birthday'])
                                                            && $item['birthday'] != null
                                                            && Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['birthday'])->format('d') == sprintf('%02d',$i))
                                                        <option value="{{ sprintf('%02d',$i) }}"
                                                                selected>{{ sprintf('%02d',$i) }}</option>
                                                    @else
                                                        <option value="{{ sprintf('%02d',$i) }}">{{ sprintf('%02d',$i) }}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                            <select name="oncall_month" id="oncall_month"
                                                    style="width: 100% !important;"
                                                    class="form-control select-unset_arrow text-center mr-3 modal-select2">
                                                <option value="">@lang('Tháng')</option>
                                                @for ($i = 1; $i < 12 + 1; $i++)
                                                    @if(isset($item['birthday'])
                                                            && $item['birthday'] != null
                                                            && Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['birthday'])->format('m') == sprintf('%02d',$i))
                                                        <option value="{{ sprintf('%02d',$i) }}"
                                                                selected>{{ sprintf('%02d',$i) }}</option>
                                                    @else
                                                        <option value="{{ sprintf('%02d',$i) }}">{{ sprintf('%02d',$i) }}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                            <select name="oncall_year" id="oncall_year"
                                                    style="width: 100% !important;"
                                                    class="form-control select-unset_arrow text-center modal-select2">
                                                <option value="">@lang('Năm')</option>
                                                @for ($i = 1970; $i < date('Y') + 1; $i++)

                                                    @if(isset($item['birthday'])
                                                            && $item['birthday'] != null
                                                            && Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['birthday'])->format('Y') == $i)
                                                        )
                                                        <option value="{{ $i }}" selected>{{ $i }}</option>
                                                    @else
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="input-group">
                                            <div class="col-lg-6">
                                                <label class="black_title">
                                                    @lang('Tỉnh/thành')
                                                </label>
                                                <select name="oncall_province_id" id="oncall_province_id"
                                                        style="width: 100% !important;"
                                                        class="form-control modal-select2">
                                                    @foreach($optionProvinces as $key => $value)
                                                        @if(isset($item['province_id']) && $value['provinceid'] == $item['province_id'])
                                                            <option value="{{$value['provinceid']}}" selected>
                                                                {{ $value['name'] }}
                                                            </option>
                                                        @else
                                                            <option value="{{$value['provinceid']}}">
                                                                {{ $value['name'] }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="black_title">
                                                    @lang('Quận/huyện')
                                                </label>
                                                <select name="oncall_district_id" id="oncall_district_id"
                                                        style="width: 100% !important;"
                                                        class="form-control modal-select2 oncall_district">
                                                    @foreach($optionDistricts as $key => $value)
                                                        @if(isset($item['district_id']) && $value['districtid'] == $item['district_id'])
                                                            <option value="{{$value['districtid']}}" selected>
                                                                {{ $value['name'] }}
                                                            </option>
                                                        @else
                                                            <option value="{{$value['districtid']}}">
                                                                {{ $value['name'] }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Địa chỉ')
                                        </label>
                                        <input type="text" name="oncall_address" id="oncall_address"
                                               class="form-control m-input"
                                               value="{{isset($item['address']) ? $item['address'] : ''}}"
                                               placeholder="{{ __('Nhập địa chỉ khách hàng') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h5>{{__('Nội dung chăm sóc')}}</h5>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{ __('Tiêu đề') }} <b class="text-danger">*</b>
                                                </label>
                                                <input type="text" name="manage_work_title" class="form-control m-input"
                                                       placeholder="{{ __('Nhập tiêu đề') }}...">
                                            </div>
                                            <div class="form-group m-form__group" style="display: none;">
                                                <label class="black_title">
                                                    @lang('Loại công việc'):<b class="text-danger">*</b>
                                                </label>
                                                <div class="kt-radio-inline">
                                                    @foreach ($listTypeWork as $key => $value)
                                                        @if($value['manage_type_work_key'] == 'call')
                                                            <label class="kt-radio mr-2">
                                                                <input type="radio" name="manage_type_work_id" checked
                                                                       class="mr-2"
                                                                       value="{{$value['manage_type_work_id']}}"> {{ $value['manage_type_work_name'] }}
                                                                <span></span>
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Nội dung'):<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <textarea class="form-control oncall_summernote" id="oncall_content"
                                                              name="content" rows="8"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                                <div class="col-lg-12" style="display: none;">
                                    <div class="form-group m-form__group"
                                         style="display:flex;align-items: initial">
                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                    <input type="checkbox" id="oncall_is_booking"
                                                        onclick="layoutWork.changeBooking()"
                                                        value="1"
                                                        class="manager-btn" name="">
                                                    <span></span>
                                                </label>
                                            </span>
                                        <label class="col-form-label pl-2 font-weight-bold"
                                               style="padding-top: 5px; font-size:1.25rem">Đặt lịch hẹn
                                            trước</label>
                                    </div>

                                    <div class="block-hide-work">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group m-form__group">
                                                    <label class="black_title">
                                                        @lang('Ngày bắt đầu')
                                                    </label>
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <input type="text"
                                                                   class="form-control m-input oncall_time-input oncall_checkBookingAdd"
                                                                   disabled
                                                                   placeholder="@lang('Giờ')" name="time_start">
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="input-group date date-multiple">
                                                                <input type="text"
                                                                       class="form-control m-input oncall_daterange-input oncall_checkBookingAdd"
                                                                       disabled
                                                                       placeholder="@lang('Ngày bắt đầu')"
                                                                       name="date_start">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i
                                                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group m-form__group">
                                                    <label class="black_title">
                                                        @lang('Ngày kết thúc') <b class="text-danger">*</b>
                                                    </label>
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <input type="text"
                                                                   class="form-control m-input oncall_time-input oncall_checkBookingAdd"
                                                                   disabled
                                                                   placeholder="@lang('Giờ')" name="time_end">
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="input-group date date-multiple">
                                                                <input type="text"
                                                                       class="form-control m-input oncall_daterange-input oncall_checkBookingAdd"
                                                                       disabled
                                                                       placeholder="@lang('Ngày kết thúc')"
                                                                       name="date_end">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i
                                                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group m-form__group">
                                                    <label class="black_title">
                                                        @lang('Chọn nhân viên thực hiện') <b
                                                                class="text-danger">*</b>
                                                    </label>
                                                    <div class="input-group">
                                                        <select name="processor_id"
                                                                class="form-control select2 oncall_select2-active oncall_checkBookingAdd"
                                                                disabled>
                                                            <option value="">@lang('Chọn nhân viên thực hiện')</option>
                                                            @foreach ($listStaff as $key => $value)
                                                                @if($value['staff_id'] == Auth()->id())
                                                                    <option value="{{ $value['staff_id'] }}"
                                                                            selected>{{ $value['full_name'] }}</option>
                                                                @else
                                                                    <option value="{{ $value['staff_id'] }}">{{ $value['full_name'] }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group m-form__group">
                                                    <label class="black_title">
                                                        @lang('Trạng thái')
                                                    </label>
                                                    <div class="input-group">
                                                        <select name="manage_status_id"
                                                                class="form-control select2 oncall_select2-active w-100 oncall_checkBookingAdd"
                                                                disabled>
                                                            @foreach ($listStatus as $key => $value)
                                                                <option value="{{ $value['manage_status_id'] }}">{{ $value['manage_status_name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group m-form__group">
                                                    <label class="black_title">
                                                        @lang('Khách hàng')
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="hidden" name="history_id"
                                                               value="{{$history_id}}">
                                                        <input type="hidden" name="obj_id" value="{{$id}}">
                                                        <input type="hidden" name="manage_work_customer_type"
                                                               value="{{$type}}">
                                                        <select disabled
                                                                class="form-control select2 oncall_select2-active w-100">
                                                            <option value="">@lang('Chọn khách hàng liên quan')</option>
                                                            @foreach ($listCustomer as $key => $value)
                                                                @if($value['id'] == $id)
                                                                    <option value="{{ $value['id'] }}"
                                                                            selected>{{ $value['customer_name'] }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="block-hide-work mt-3">
                                                    <div class="form-group m-form__group">
                                                        <div class="form-group m-form__group"
                                                             style="display:flex;align-items: initial">
                                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                        <input type="checkbox" id="oncall_is_remind"
                                                               onclick="layoutWork.changeRemind()"
                                                               value="1"
                                                               class="manager-btn" name="is_remind">
                                                        <span></span>
                                                    </label>
                                                </span>
                                                            <label class="col-form-label pl-2 font-weight-bold"
                                                                   style="padding-top: 5px; font-size:1.25rem">Nhắc
                                                                nhở trước</label>
                                                        </div>
                                                        <input type="hidden" name="staff"
                                                               value="{{\Illuminate\Support\Facades\Auth::id()}}">
                                                        <div class="row mt-3">
                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-group m-form__group">
                                                                    <label class="black_title">
                                                                        @lang('Thời gian nhắc'):<b
                                                                                class="text-danger">*</b>
                                                                    </label>
                                                                    <div class="input-group date">
                                                                        <input type="text"
                                                                               class="form-control m-input oncall_date-timepicker oncall_checkRemindAdd"
                                                                               disabled
                                                                               placeholder="@lang('Thời gian nhắc')"
                                                                               name="date_remind" value="">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i
                                                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-12">
                                                                <div class="form-group m-form__group">
                                                                    <label class="black_title">
                                                                        @lang('Thời gian trước nhắc nhở')
                                                                    </label>
                                                                    <div>
                                                                        <div class="input-group mb-3">
                                                                            <input type="text" disabled
                                                                                   class="form-control oncall_input-mask-remind oncall_checkRemindAdd"
                                                                                   id="oncall_time_remind"
                                                                                   name="time_remind" value=""
                                                                                   placeholder="Nhắc trước">
                                                                            <div class="input-group-append">
                                                                                <select class="input-group-text oncall_checkRemindAdd"
                                                                                        disabled
                                                                                        name="time_type_remind">
                                                                                    <option value="m"
                                                                                            selected>{{ __('Phút') }}</option>
                                                                                    <option value="h">{{ __('Giờ') }}</option>
                                                                                    <option value="d">{{ __('Ngày') }}</option>
                                                                                    <option value="w">{{ __('Tuần') }}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="form-group m-form__group">
                                                                    <label> {{ __('Nội dung') }}</label>:<b
                                                                            class="text-danger">*</b>
                                                                    <textarea name="description_remind" disabled
                                                                              class="form-control m-input oncall_checkRemindAdd"
                                                                              rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-12" id="oncall-list-care-timeline">
                                    @include('on-call::on-calling.template.list-care-template')
                                </div>
                                {{--                                             <div class="form-group m-form__group">--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     {{ __('Email') }}--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <input type="text" name="oncall_email" id="oncall_email" class="form-control m-input"--}}
                                {{--                                                        value="{{isset($item['email']) ? $item['email'] : ''}}"--}}
                                {{--                                                        placeholder="{{ __('Nhập email') }}">--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? '' : 'hidden'}}>--}}
                                {{--                                                 <span class="mr-3">{{ __('Trạng thái') }}</span>--}}
                                {{--                                                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">--}}
                                {{--                                                 <label>--}}
                                {{--                                                     <input type="checkbox" class="manager-btn"--}}
                                {{--                                                            {{isset($item['is_actived']) && $item['is_actived'] == 1 ? 'checked' : '' }}--}}
                                {{--                                                            name="oncall_is_actived" id="oncall_is_actived">--}}
                                {{--                                                     <span></span>--}}
                                {{--                                                 </label>--}}
                                {{--                                             </span>--}}
                                {{--                                                 <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>--}}
                                {{--                                             </div>--}}

                                {{--                                         <div class="col-lg-6">--}}
                                {{--                                             <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? '' : 'hidden'}}>--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     @lang('Nhóm khách hàng') <b class="text-danger">*</b>--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <div class="input-group">--}}
                                {{--                                                     <select name="oncall_customer_group" id="oncall_customer_group"--}}
                                {{--                                                             style="width: 100% !important;"--}}
                                {{--                                                             class="form-control modal-select2">--}}
                                {{--                                                         <option value="">@lang('Chọn nhóm khách hàng')</option>--}}
                                {{--                                                         @foreach($optionCustomerGroups as $key => $value)--}}
                                {{--                                                             @if(isset($item['customer_group_id']) && $value['customer_group_id'] == $item['customer_group_id'])--}}
                                {{--                                                                 <option value="{{$value['customer_group_id']}}" selected>--}}
                                {{--                                                                     {{ $value['group_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @else--}}
                                {{--                                                                 <option value="{{$value['customer_group_id']}}">--}}
                                {{--                                                                     {{ $value['group_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @endif--}}
                                {{--                                                         @endforeach--}}
                                {{--                                                     </select>--}}
                                {{--                                                 </div>--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="form-group m-form__group">--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     @lang('Nguồn khách hàng')--}}
                                {{--                                                     @if(isset($type) && $type != 'customer')--}}
                                {{--                                                         <b class="text-danger">*</b>--}}
                                {{--                                                     @endif--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <div class="input-group">--}}
                                {{--                                                     <select name="oncall_customer_source" id="oncall_customer_source"--}}
                                {{--                                                             style="width: 100% !important;"--}}
                                {{--                                                             class="form-control modal-select2">--}}
                                {{--                                                         <option value="">@lang('Chọn nguồn khách hàng')</option>--}}
                                {{--                                                         @foreach($optionCustomerSources as $key => $value)--}}
                                {{--                                                             @if((isset($item['customer_source']) && $value['customer_source_id'] == $item['customer_source'])--}}
                                {{--                                                             || (isset($item['customer_source_id']) && $value['customer_source_id'] == $item['customer_source_id']))--}}
                                {{--                                                                 <option value="{{$value['customer_source_id']}}" selected>--}}
                                {{--                                                                     {{ $value['customer_source_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @else--}}
                                {{--                                                                 <option value="{{$value['customer_source_id']}}">--}}
                                {{--                                                                     {{ $value['customer_source_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @endif--}}
                                {{--                                                         @endforeach--}}
                                {{--                                                     </select>--}}
                                {{--                                                 </div>--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="form-group m-form__group">--}}
                                {{--                                                 <label class="black-title">--}}
                                {{--                                                     {{__('Loại khách hàng')}}:--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <div class="m-input-icon m-input-icon--right">--}}
                                {{--                                                     <select id="oncall_customer_type" name="oncall_customer_type" onchange="oncallChangeCustomerType(this)"--}}
                                {{--                                                             title="@lang("Chọn loại khách hàng")"--}}
                                {{--                                                             class="form-control m-input modal-select2" style="width: 100%">--}}
                                {{--                                                         <option value="personal" {{isset($item['customer_type']) && $item['customer_type'] == 'personal' ? 'selected ' : ''}}>--}}
                                {{--                                                             @lang('Cá nhân')--}}
                                {{--                                                         </option>--}}
                                {{--                                                         <option value="business" {{isset($item['customer_type']) && $item['customer_type'] == 'business' ? 'selected ' : ''}}>--}}
                                {{--                                                             @lang('Doanh nghiệp')--}}
                                {{--                                                         </option>--}}
                                {{--                                                     </select>--}}
                                {{--                                                 </div>--}}
                                {{--                                                 <span class="error_type_customer" style="color: #ff0000"></span>--}}
                                {{--                                             </div>--}}

                                {{--                                             <div class="oncall-open-business-input form-group m-form__group" {{(isset($item['customer_type']) && $item['customer_type'] == 'personal') ? 'hidden ' : ''}}>--}}
                                {{--                                                 <label class="black-title">@lang("Mã số thuế"):</label>--}}
                                {{--                                                 <div class="m-input-icon m-input-icon--right">--}}
                                {{--                                                     <input type="text" id="oncall_tax_code" value="{{isset($item['tax_code']) && $item['tax_code'] != '' ? $item['tax_code'] : ''}}" name="oncall_tax_code" class="form-control m-input" minlength="11" maxlength="13">--}}
                                {{--                                                 </div>--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="oncall-open-business-input form-group m-form__group" {{(isset($item['customer_type']) && $item['customer_type'] == 'personal') ? 'hidden ' : ''}}>--}}
                                {{--                                                 <label class="black-title">--}}
                                {{--                                                     @lang("Người đại diện"):--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <div class="input-group">--}}
                                {{--                                                     <div class="m-input-icon m-input-icon--right">--}}
                                {{--                                                         <input type="text" id="oncall_representative" name="oncall_representative"--}}
                                {{--                                                                class="form-control m-input " maxlength="191"  value="{{isset($item['representative']) && $item['representative'] != '' ? $item['representative'] : ''}}"--}}
                                {{--                                                                placeholder="{{__("Người đại diện")}}">--}}
                                {{--                                                         <span class="m-input-icon__icon m-input-icon__icon--right"><span><i--}}
                                {{--                                                                         class="la la-user"></i></span></span>--}}
                                {{--                                                     </div>--}}
                                {{--                                                 </div>--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="oncall-open-business-input form-group m-form__group" {{(isset($item['customer_type']) && $item['customer_type'] == 'personal') ? 'hidden ' : ''}}>--}}
                                {{--                                                 <label class="black-title">{{__('Hotline')}}:</label>--}}
                                {{--                                                 <div class="input-group">--}}
                                {{--                                                     <div class="m-input-icon m-input-icon--right">--}}
                                {{--                                                         <input type="number" id="oncall_hotline" name="oncall_hotline"  value="{{isset($item['hotline']) && $item['hotline'] != '' ? $item['hotline'] : ''}}"--}}
                                {{--                                                                class="form-control m-input " maxlength="15" minlength="10"--}}
                                {{--                                                                placeholder="@lang("Nhập hotline")"--}}
                                {{--                                                                onkeydown="javascript: return event.keyCode == 69 ? false : true">--}}
                                {{--                                                         <span class="m-input-icon__icon m-input-icon__icon--right"><span><i--}}
                                {{--                                                                         class="la la-phone"></i></span></span>--}}
                                {{--                                                     </div>--}}
                                {{--                                                 </div>--}}
                                {{--                                             </div>--}}

                                {{--                                             <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? '' : 'hidden'}}>--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     @lang('Người giới thiệu')--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <div class="input-group">--}}
                                {{--                                                     <select name="oncall_customer_refer_id" id="oncall_customer_refer_id"--}}
                                {{--                                                             style="width: 100% !important;"--}}
                                {{--                                                             class="form-control modal-select2">--}}
                                {{--                                                         <option value="">@lang('Chọn người giới thiệu')</option>--}}
                                {{--                                                         @foreach($optionStaffs as $key => $value)--}}
                                {{--                                                             @if(isset($item['customer_refer_id']) && $value['staff_id'] == $item['customer_refer_id'])--}}
                                {{--                                                                 <option value="{{$value['staff_id']}}" selected>--}}
                                {{--                                                                     {{ $value['full_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @else--}}
                                {{--                                                                 <option value="{{$value['staff_id']}}">--}}
                                {{--                                                                     {{ $value['full_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @endif--}}
                                {{--                                                         @endforeach--}}
                                {{--                                                     </select>--}}
                                {{--                                                 </div>--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? 'hidden' : ''}}>--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     @lang('Pipeline')--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <div class="input-group">--}}
                                {{--                                                     <select name="oncall_pipeline_code" id="oncall_pipeline_code"--}}
                                {{--                                                             style="width: 100% !important;"--}}
                                {{--                                                             class="form-control modal-select2">--}}
                                {{--                                                         @foreach($optionPipelines as $key => $value)--}}
                                {{--                                                             @if(isset($item['pipeline_code']) && $value['pipeline_code'] == $item['pipeline_code'])--}}
                                {{--                                                                 <option value="{{$value['pipeline_code']}}" selected>--}}
                                {{--                                                                     {{ $value['pipeline_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @else--}}
                                {{--                                                                 <option value="{{$value['pipeline_code']}}">--}}
                                {{--                                                                     {{ $value['pipeline_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @endif--}}
                                {{--                                                         @endforeach--}}
                                {{--                                                     </select>--}}
                                {{--                                                 </div>--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? 'hidden' : ''}}>--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     @lang('Hành trình')--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <div class="input-group">--}}
                                {{--                                                     <select name="oncall_journey_code" id="oncall_journey_code"--}}
                                {{--                                                             style="width: 100% !important;"--}}
                                {{--                                                             class="form-control modal-select2 oncall_journey">--}}
                                {{--                                                         @foreach($optionJourney as $key => $value)--}}
                                {{--                                                             @if(isset($item['journey_code']) && $value['journey_code'] == $item['journey_code'])--}}
                                {{--                                                                 <option value="{{$value['journey_code']}}" selected>--}}
                                {{--                                                                     {{ $value['journey_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @else--}}
                                {{--                                                                 <option value="{{$value['journey_code']}}">--}}
                                {{--                                                                     {{ $value['journey_name'] }}--}}
                                {{--                                                                 </option>--}}
                                {{--                                                             @endif--}}
                                {{--                                                         @endforeach--}}
                                {{--                                                     </select>--}}
                                {{--                                                 </div>--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? '' : 'hidden'}}>--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     {{ __('Facebook') }}--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <input type="text" name="oncall_facebook" id="oncall_facebook" class="form-control m-input"--}}
                                {{--                                                        value="{{isset($item['facebook']) ? $item['facebook'] : ''}}"--}}
                                {{--                                                        placeholder="{{ __('Nhập link facebook') }}">--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? 'hidden' : ''}}>--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     {{ __('Zalo') }}--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <input type="text" name="oncall_zalo" id="oncall_zalo" value="{{$item['zalo']}}" class="form-control m-input"--}}
                                {{--                                                        placeholder="">--}}
                                {{--                                             </div>--}}
                                {{--                                             <div class="form-group m-form__group" {{isset($type) && $type == 'customer' ? '' : 'hidden'}}>--}}
                                {{--                                                 <label class="black_title">--}}
                                {{--                                                     @lang('Ghi chú'):--}}
                                {{--                                                 </label>--}}
                                {{--                                                 <textarea class="form-control m-input" name="oncall_note" id="oncall_note" rows="6" cols="5"--}}
                                {{--                                                           placeholder="@lang('Nhập thông tin ghi chú')...">{{isset($item['note']) ? $item['note'] : ''}}</textarea>--}}
                                {{--                                             </div>--}}
                                {{--                                         </div>--}}
                                {{--                                         <div class="col-lg-12">--}}
                                {{--                                             <button type="button" onclick="layout.saveInfo('{{$type}}', '{{$id}}')"--}}
                                {{--                                                     class="float-right ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">--}}
                                {{--                                                 <span class="ss--text-btn-mobi">--}}
                                {{--                                                     <i class="la la-check"></i>--}}
                                {{--                                                     <span>{{ __('LƯU THÔNG TIN') }}</span>--}}
                                {{--                                                 </span>--}}
                                {{--                                             </button>--}}
                                {{--                                         </div>--}}
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-12">
                        <hr>
                    </div>
                    <div class="col-lg-12">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-list-deal-tab" data-toggle="pill"
                                   href="#pills-list-deal" role="tab" aria-controls="pills-list-deal-tab"
                                   aria-selected="false">@lang('Danh sách deal')</a>
                            </li>
                            @if($type == 'customer')
                                {{--                                     <li class="nav-item">--}}
                                {{--                                         <a class="nav-link active" id="pills-history-buy-tab" data-toggle="pill"--}}
                                {{--                                            href="#pills-history-work" role="tab" aria-controls="pills-history-work"--}}
                                {{--                                            aria-selected="false">@lang('Danh sách lịch hẹn')</a>--}}
                                {{--                                     </li>--}}
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-history-buy-tab" data-toggle="pill"
                                       href="#pills-history-buy" role="tab" aria-controls="pills-history-buy"
                                       aria-selected="false">@lang('Lịch sử mua hàng')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-list-contract-tab" data-toggle="pill"
                                       href="#pills-list-contract" role="tab" aria-controls="pills-list-contract"
                                       aria-selected="false">@lang('Danh sách hợp đồng')</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-history-ticket-tab" data-toggle="pill"
                                       href="#pills-history-ticket" role="tab" aria-controls="pills-history-ticket"
                                       aria-selected="false">@lang('Lịch sử ticket')</a>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            {{--                                     <div class="tab-pane fade active show" id="pills-history-work" role="tabpanel"--}}
                            {{--                                          aria-labelledby="pills-history-buy-tab">--}}
                            {{--                                         <div class="row">--}}
                            {{--                                             <form id="oncall_form-search-support">--}}
                            {{--                                                 <input type="hidden" name="type_search" value="support">--}}
                            {{--                                                 <input type="hidden" name="customer_id" value="{{$id}}">--}}
                            {{--                                                 <input type="hidden" name="manage_work_customer_type" value="{{$type}}">--}}
                            {{--                                                 <input type="hidden" name="page" id="oncall_page_support" value="1">--}}
                            {{--                                             </form>--}}
                            {{--                                             <div class="col-12">--}}
                            {{--                                                 <h5>{{__('Danh sách lịch hẹn')}}</h5>--}}
                            {{--                                             </div>--}}
                            {{--                                             <div class="col-12 oncall_list-table-work">--}}
                            {{--                                                 @include('on-call::on-calling.append.append-list-work-child')--}}
                            {{--                                             </div>--}}
                            {{--                                         </div>--}}
                            {{--                                     </div>--}}
                            <div class="tab-pane fade active show" id="pills-list-deal" role="tabpanel"
                                 aria-labelledby="pills-list-deal">
                                <div id="oncall-deal-autotable">
                                    <form class="frmFilter bg">
                                        <input type="text" hidden class="form-control m-input" id="oncall_type"
                                               name="oncall_type" value="{{$type}}">
                                        <input type="text" hidden class="form-control m-input" id="oncall_code"
                                               name="oncall_code" value="{{$code}}">
                                    </form>
                                    <div class="table-content m--padding-top-30">
                                        @include('on-call::on-calling.list-deal')
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-history-buy" role="tabpanel"
                                 aria-labelledby="pills-history-buy-tab">
                                @include('on-call::on-calling.list-order')
                            </div>
                            <div class="tab-pane fade" id="pills-list-contract" role="tabpanel"
                                 aria-labelledby="pills-list-contract-tab">
                                @include('on-call::on-calling.list-contract')
                            </div>
                            <div class="tab-pane fade" id="pills-history-ticket" role="tabpanel"
                                 aria-labelledby="pills-history-ticket-tab">
                                @include('on-call::on-calling.list-ticket')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="oncall-modal">
        <div class="oncall-append-li">
            <ul class="oncall-ul">
            </ul>
        </div>
    </div>
</div>
