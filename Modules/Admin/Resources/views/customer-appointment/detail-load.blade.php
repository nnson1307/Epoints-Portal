@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ LỊCH HẸN')}}</span>
@stop
@section('content')
    <style>
        .m-image {
            padding: 5px;
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"></i> {{__('THÔNG TIN CHI TIẾT LỊCH HẸN')}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="black-title">{{__('Số điện thoại')}}:</label>
                        <input class="form-control" disabled value="{{$item['phone']}}">
                    </div>
                    <div class="form-group">
                        <label class="black-title">{{__('Tên khách hàng')}}:</label>
                        <input class="form-control" disabled value="{{$item['full_name']}}">
                    </div>
                    <div class="form-group"
                         style="display: {{session()->get('brand_code') == 'giakhang' ? 'block' : 'none'}}">
                        <label class="black-title">{{__('Đặt lịch theo')}}:</label>
                        @switch($item['time_type'])
                            @case('R')
                            <input class="form-control" disabled value="@lang('Theo ngày')">
                            @break;
                            @case('W')
                            <input class="form-control" disabled value="@lang('Theo tuần')">
                            @break;
                            @case('M')
                            <input class="form-control" disabled value="@lang('Theo tháng')">
                            @break;
                            @case('Y')
                            <input class="form-control" disabled @lang('Theo năm')>
                            @break;
                        @endswitch
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="black-title">{{__('Ngày hẹn')}}:</label>
                            <input class="form-control" disabled
                                   value="{{\Carbon\Carbon::parse($item['date'])->format('d/m/Y')}}">
                        </div>
                        <div class="col-lg-6">
                            <label class="black-title">{{__('Giờ hẹn')}}:</label>
                            <input class="form-control" disabled
                                   value="{{\Carbon\Carbon::parse($item['time'])->format('H:i')}}">
                        </div>
                    </div>
                    @if ($item['time_type'] != 'R')
                        <div class="form-group m-form__group">
                            <label class="black-title">{{__('Số tuần/tháng/năm')}}:</label>
                            <input class="form-control" disabled value="{{$item['number_start']}}">
                        </div>
                    @endif
                    @if($configToDate == 1)
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label class="black-title">{{__('Ngày kết thúc')}}:</label>
                                <input class="form-control" disabled value="{{\Carbon\Carbon::parse($item['end_date'])->format('d/m/Y')}}">
                            </div>
                            <div class="col-lg-6">
                                <label class="black-title">{{__('Giờ kết thúc')}}:</label>
                                <input class="form-control" disabled value="{{\Carbon\Carbon::parse($item['end_time'])->format('H:i')}}">
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="black-title">{{__('Chi nhánh')}}:</label>
                        <input class="form-control" disabled value="{{$item['branch_name']}}">
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="black-title">{{__('Hình thức đặt lịch')}}:</label>
                            @if(in_array($item['customer_appointment_type'], ['appointment', 'booking']))
                                <input class="form-control" disabled value="{{__('ĐẶT LỊCH TRƯỚC')}}">
                            @endif
                            @if($item['customer_appointment_type'] == 'direct')
                                <input class="form-control" disabled value="{{__('ĐẾN TRỰC TIẾP')}}">
                            @endif

                        </div>
                        <div class="col-lg-6">
                            <label class="black-title">{{__('Nguồn lịch hẹn')}}:</label>
                            <input class="form-control" disabled value="{{$item['appointment_source_name']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="black-title">{{__('Trạng thái')}}:</label>
                        @switch($item['status'])
                            @case('new')
                            <input class="form-control" disabled value="@lang('MỚI')">
                            @break;
                            @case('confirm')
                            <input class="form-control" disabled value="@lang('XÁC NHẬN')">
                            @break;
                            @case('wait')
                            <input class="form-control" disabled value="@lang('CHỜ PHỤC VỤ')">
                            @break;
                            @case('cancel')
                            <input class="form-control" disabled value="@lang('HUỶ')">
                            @break;
                            @case('finish')
                            <input class="form-control" disabled value="@lang('HOÀN THÀNH')">
                            @break;
                        @endswitch
                    </div>
                    <div class="form-group">
                        <label class="black-title">{{__('Ghi chú')}}:</label>
                        <textarea class="form-control" disabled>{{$item['description']}}</textarea>
                    </div>
                </div>
            </div>
            <div class="m-section m--margin-top-10">
                <div class="m-section__content">
                    <div class="table-responsive">
                        <table class="table table-striped m-table">
                            <thead style="white-space: nowrap;">
                            <tr>
                                <th class="tr_thead_od_detail">{{__('HÌNH THỨC')}}</th>
                                <th class="tr_thead_od_detail">{{__('DỊCH VỤ')}}</th>
                                <th class="tr_thead_od_detail" style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('NHÂN VIÊN')}}</th>
                                <th class="tr_thead_od_detail" style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('PHÒNG')}}</th>
                            </tr>
                            </thead>
                            <tbody style="font-size: 12px">
                            @if(count($detail) > 0)
                                @foreach($detail as $v)
                                    <tr>
                                        <td>
                                            @if($v['object_type'] == 'service')
                                                @lang('Dịch vụ')
                                            @elseif($v['object_type'] == 'member_card')
                                                @lang('Thẻ liệu trình')
                                            @endif
                                        </td>
                                        <td>{{$v['object_name']}}</td>
                                        <td style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{$v['staff_name']}}</td>
                                        <td style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{$v['room_name']}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-form__actions m--align-right">
                <a href="{{route('admin.customer_appointment.list-day')}}"
                   class="btn btn-metal bold-huy  m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('QUAY LẠI')}}</span>
						</span>
                </a>
            </div>
        </div>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
