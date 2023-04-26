<div class="modal fade show" id="{{$detailWork != null ? 'modal-customer-care-edit' : 'modal-customer-care'}}" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-gratipay"></i> {{__('CHĂM SÓC KHÁCH HÀNG')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="{{$detailWork != null ? 'form-care-edit' : 'form-care'}}" autocomplete="off">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <h5>{{__('Nội dung chăm sóc')}}</h5>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    {{ __('Tiêu đề') }} <b class="text-danger">*</b>
                                </label>
                                <input type="text" name="manage_work_title" class="form-control m-input" value="{{$detailWork != null ? $detailWork['manage_work_title'] : ''}}"
                                       placeholder="{{ __('Nhập tiêu đề') }}...">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Loại công việc'):<b class="text-danger">*</b>
                                </label>
                                <div class="kt-radio-inline">
                                    @foreach ($listTypeWork as $item)
                                        <label class="kt-radio mr-2">
                                            <input type="radio" name="manage_type_work_id" {{($detailWork != null && $item['manage_type_work_id'] == $detailWork['manage_type_work_id']) || ($detailWork == null && $item['manage_type_work_id'] == 1) ? 'checked' : ''}} class="mr-2" value="{{$item['manage_type_work_id']}}"> {{ $item['manage_type_work_name'] }}
                                            <span></span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Nội dung'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <textarea class="form-control summernote" id="content" name="content" rows="5">{{$detailWork != null ? $detailWork['description'] : ''}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group m-form__group" style="display:flex;align-items: initial">
{{--                                <label class="m-checkbox m-checkbox--state-success mt-0">--}}
{{--                                    <input type="checkbox" id="is_booking" onclick="Work.changeBooking()" {{$detailWork != null ? 'disabled' : ''}} value="1" {{($detailWork != null && $detailWork['is_booking'] == 1) || $detailWork == null ? 'checked' : ''}}> <strong>Đặt lịch</strong>--}}
{{--                                    <span></span>--}}
{{--                                </label>--}}
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox" id="is_booking" {{$detailWork != null ? 'disabled' : ''}}
                                               onclick="Work.changeBooking()"
                                               value="1"
                                               {{($detailWork != null && $detailWork['is_booking'] == 1) ? 'checked' : ''}}
                                               class="manager-btn" name="">
                                        <span></span>
                                    </label>
                                </span>
                                <label class="col-form-label pl-2 font-weight-bold" style="padding-top: 5px; font-size:1.25rem">Đặt lịch hẹn trước</label>
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
                                                    <input type="text" class="form-control m-input time-input checkBookingAdd" {{$detailWork != null && $detailWork['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detailWork != null && isset($detailWork['date_start']) && $detailWork['date_start'] != '' && $detailWork['date_start'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detailWork['date_start'])->format('H:i') : ''}}"
                                                           placeholder="@lang('Giờ')" name="time_start">
                                                </div>
                                                <div class="col-8">
                                                    <div class="input-group date date-multiple">
                                                        <input type="text" class="form-control m-input daterange-input checkBookingAdd"  {{$detailWork != null && $detailWork['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detailWork != null && isset($detailWork['date_start']) && $detailWork['date_start'] != '' && $detailWork['date_start'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detailWork['date_start'])->format('d/m/Y') : ''}}"
                                                               placeholder="@lang('Ngày bắt đầu')" name="date_start">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
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
                                                    <input type="text" class="form-control m-input time-input checkBookingAdd" {{$detailWork != null && $detailWork['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detailWork != null && isset($detailWork['date_end']) && $detailWork['date_end'] != '' && $detailWork['date_end'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detailWork['date_end'])->format('H:i') : ''}}"
                                                           placeholder="@lang('Giờ')" name="time_end">
                                                </div>
                                                <div class="col-8">
                                                    <div class="input-group date date-multiple">
                                                        <input type="text" class="form-control m-input daterange-input checkBookingAdd" {{$detailWork != null && $detailWork['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detailWork != null && isset($detailWork['date_end']) && $detailWork['date_end'] != '' && $detailWork['date_end'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detailWork['date_end'])->format('d/m/Y') : ''}}"
                                                               placeholder="@lang('Ngày kết thúc')" name="date_end">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Chọn nhân viên thực hiện') <b class="text-danger">*</b>
                                            </label>
                                            <div class="input-group">
                                                <select name="processor_id" class="form-control select2 select2-active checkBookingAdd" {{$detailWork != null && $detailWork['is_edit'] == 0 ? 'disabled' : '' }}>
                                                    <option value="">@lang('Chọn nhân viên thực hiện')</option>
                                                    @foreach ($listStaff as $item)
                                                        <option value="{{ $item['staff_id'] }}" {{($detailWork != null && $detailWork['processor_id'] == $item['staff_id']) || ($detailWork == null && $item['staff_id'] == \Illuminate\Support\Facades\Auth::id()) ? 'selected' : '' }}>{{ $item['full_name'] }}</option>
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
                                                <select name="manage_status_id" class="form-control select2 select2-active w-100 checkBookingAdd">
{{--                                                    <option value="">@lang('Chọn trạng thái')</option>--}}
                                                    @foreach ($listStatus as $item)
                                                        <option value="{{ $item['manage_status_id'] }}" {{$detailWork != null && $detailWork['manage_status_id'] == $item['manage_status_id'] ? 'selected' : ''}}>{{ $item['manage_status_name'] }}</option>
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
                                                <input type="hidden" name="customer_lead_id" value="{{$customer_lead_id}}">
                                                <input type="hidden" name="obj_id" value="{{$customer_lead_id}}">
                                                <select disabled class="form-control select2 select2-active w-100">
                                                    <option value="">@lang('Chọn khách hàng liên quan')</option>
                                                    @foreach ($listCustomer as $item)
                                                        <option value="{{ $item['customer_lead_id'] }}" {{$customer_lead_id == $item['customer_lead_id'] ? 'selected' : ''}}>{{ $item['customer_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        @if ($detailWork == null)
                                            <div class="block-hide-work mt-3">
                                                <div class="form-group m-form__group">
{{--                                                    <button type="button" class="btn btn-sm m-btn--icon bg-light color" data-toggle="collapse" data-target="#multiCollapseExample1" aria-expanded="false" aria-controls="multiCollapseExample1">--}}
{{--                                                        <span>--}}
{{--                                                            <span class="fa fa-calendar pr-1" aria-hidden="true"></span>--}}
{{--                                                            {{ __('Thêm nhắc nhở') }}--}}
{{--                                                        </span>--}}
{{--                                                    </button>--}}

                                                    <div class="form-group m-form__group" style="display:flex;align-items: initial">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                <input type="checkbox" id="is_remind"
                                                                onclick="Work.changeRemind()"
                                                                   value="1"
                                                                   class="manager-btn" name="">
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                        <label class="col-form-label pl-2 font-weight-bold" style="padding-top: 5px; font-size:1.25rem">Nhắc nhở trước</label>
                                                    </div>

                                                    <input type="hidden" name="staff" value="{{\Illuminate\Support\Facades\Auth::id()}}">
                                                    <div class="row mt-3">
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group m-form__group">
                                                                <label class="black_title">
                                                                    @lang('Thời gian nhắc'):<b class="text-danger">*</b>
                                                                </label>
                                                                <div class="input-group date">
                                                                    <input type="text" class="form-control m-input date-timepicker checkRemindAdd" disabled
                                                                           placeholder="@lang('Thời gian nhắc')" name="date_remind" value="" >
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
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
                                                                        <input type="text" disabled class="form-control input-mask-remind checkRemindAdd" id="time_remind" name="time_remind" value=""
                                                                               placeholder="Nhắc trước">
                                                                        <div class="input-group-append">
                                                                            <select class="input-group-text checkRemindAdd" disabled name="time_type_remind">
                                                                                <option value="m" selected>{{ __('Phút') }}</option>
                                                                                <option value="h" >{{ __('Giờ') }}</option>
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
                                                                <label> {{ __('Nội dung') }}</label>:<b class="text-danger">*</b>
                                                                <textarea name="description_remind" disabled class="form-control m-input checkRemindAdd" rows="3" ></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            @if (count($dataCare) > 0)
                                <h5>{{__('Lịch sử chăm sóc')}}</h5>
                                <div style="width: 100%; height: 450px; overflow-y: scroll;">
                                    <div class="m-scrollable m-scroller ps ps--active-y w-100">
                                        <!--Begin::Timeline 2 -->
                                        @foreach($dataCare as $k => $v)
                                            <div class="m-timeline-2">
                                                <div class="m-timeline-2__items  m--padding-top-25 m--padding-bottom-30">
                                                    <div class="m-timeline-2__item">
                                                <span class="m-timeline-2__item-time">
                                                    {{\Carbon\Carbon::createFromFormat('d/m/Y', $k)->format('d/m')}}
                                                </span>
                                                    </div>
                                                    @if (count($v) > 0)
                                                        @foreach($v as $v1)
                                                            <div class="m-timeline-2__item m--margin-top-30">
                                                                <span class="m-timeline-2__item-time"></span>
                                                                <div class="m-timeline-2__item-cricle">
                                                                    <i class="fa fa-genderless m--font-success"></i>
                                                                </div>
                                                                <div class="m-timeline-2__item-text">
                                                                    <strong>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v1['created_at'])->format('H:i')}}</strong>
                                                                    <br/>
                                                                    @lang('Người chăm sóc'): {{$v1['full_name']}} <br/>
                                                                    @lang('Loại công việc'): {{$v1['manage_type_work_name']}}
                                                                    {{--                                                                    @if ($v1['care_type'] == 'call')--}}
                                                                    {{--                                                                        @lang('Gọi')--}}
                                                                    {{--                                                                    @elseif ($v1['care_type'] == 'chat')--}}
                                                                    {{--                                                                        @lang('Trò chuyện')--}}
                                                                    {{--                                                                    @elseif ($v1['care_type'] == 'email')--}}
                                                                    {{--                                                                        @lang('Email')--}}
                                                                    {{--                                                                    @endif--}}
                                                                    <br/>
                                                                    @lang('Nội dung'): {!! $v1['content'] !!}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <input type="hidden" id="history_id" name="history_id" value="{{isset($historyId) ? $historyId: ''}}">
                    <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detailWork != null ? $detailWork['manage_work_id']: ''}}">
                </form>
                @if ($detailWork == null )
                    <div class="row">
                        <form id="form-search-support">
                            <input type="hidden" name="type_search" value="support">
                            <input type="hidden" name="customer_id" value="{{$customer_lead_id}}">
                            <input type="hidden" name="manage_work_customer_type" value="lead">
                            <input type="hidden" name="page" id="page_support" value="1">
                        </form>
                        <div class="col-12">
                            <h5>{{__('Danh sách lịch hẹn')}}</h5>
                        </div>
                        <div class="col-12 list-table-work">
                            @include('customer-lead::append.append-list-work-child')
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    @if ($detailWork != null)
                        <button onclick="listLead.closeModalCareEdit()"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                            <i class="la la-arrow-left"></i>
                            <span>{{__('HỦY')}}</span>
                            </span>
                        </button>
                    @else
                        <button onclick="listLead.closeModalCare()"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                            <i class="la la-arrow-left"></i>
                            <span>{{__('HỦY')}}</span>
                            </span>
                        </button>
                    @endif

                    @if($detailWork == null)
                        <button type="submit" onclick="listLead.submitCustomerCare('{{$customer_lead_id}}')"
                                class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                    @else
                        <button type="submit" onclick="listLead.submitCustomerCareEdit('{{$customer_lead_id}}')"
                                class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                    @endif
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