@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@stop
@section('content')

    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list-1"></i>
                </span>
                    <h2 class="m-portlet__head-text">
                        @lang('Xem chi tiết lương nhân viên')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <form action="{{route('staff-salary.export-detail-staff')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="staff_id" value="{{$staffInfoSalary['staff_id']}}">
                    <input type="hidden" name="staff_salary_id" value="{{$staffInfoSalary['staff_salary_id']}}">

                    <button type="submit" class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('EXPORT PHIẾU LƯƠNG')}}
                                            </span>
                                        </span>
                    </button>
                </form>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-8">
                    <div class="m-widget4">
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--logo">
                                @if($staffInfoSalary['staff_avatar']!=null)
                                    <img style="height: 3.5rem; object-fit: cover;"
                                         src="{{ $staffInfoSalary['staff_avatar'] }}" alt="">
                                @else
                                    <img style="height: 3.5rem; object-fit: cover;"
                                         src="/static/backend/images/menu/icon-admin.png" alt="">
                                @endif

                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title" style="font-size:15px;">
                                    {{ $staffInfoSalary['staff_name'] }}
                                </span><br>
                                <span class="m-widget4__sub" style="font-size:13px;">
                                   @lang('Bảng lương') {{ \Carbon\Carbon::createFromFormat('Y-m-d', $staffInfoSalary['start_date'])->format('d/m/Y') }}
                                    - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $staffInfoSalary['end_date'])->format('d/m/Y') }}
                                </span><br>
                                <span class="m-widget4__sub" style="font-size:13px;">
                                
                                    @if($staffInfoSalary['staff_salary_type_code'] == 'shift')
                                        @lang('Loại lương'): @lang('Lương ca')
                                    @elseif($staffInfoSalary['staff_salary_type_code'] == 'monthly')
                                        @lang('Loại lương'): @lang('Lương tháng')
                                    @else
                                        @lang('Loại lương'): @lang('Lương giờ')
                                    @endif
                                </span><br>
                                <span class="m-widget4__sub" style="font-size:13px;">
                                @if($staffInfoSalary['staff_salary_pay_period_code'] == 'pay_week')
                                        {{__('Kỳ hạn trả lương')}}: {{__('Hàng tuần')}}
                                    @else
                                        @lang('Kỳ hạn trả lương'): {{__('Hàng tháng')}}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 text-right">
                    <div class="m-widget4">
                        <div class="m-widget4__item">
                            <span class="m-widget4__ext">

                            </span>
                        </div>
                        <span class="m-widget4__number">
                                <span style="font-weight: bold; font-size: 15px;">
                                    @lang('Tổng tiền thực nhận'): &nbsp;
                                </span>
                                <span style="font-weight: bold; font-size: 20px; color: #ff0000;">
                                    {{number_format($staffInfoSalary['staff_salary_received'] , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}  {{ $staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}} 
                                </span>
                        </span>
                    </div>
                </div>
            </div>

            @if($staffInfoSalary['staff_salary_type_code'] == 'hourly')
                @include('staff-salary::staff-salary.detail-staff-hourly')
            @elseif($staffInfoSalary['staff_salary_type_code'] == 'shift')
                @include('staff-salary::staff-salary.detail-staff-shift')
            @else
                @include('staff-salary::staff-salary.detail-staff-monthly')
            @endif

        </div>
    </div>
    <div id="modal-holiday-add"></div>
    <div id="modal-holiday-edit"></div>
@stop
@section("after_style")

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/staff-salary/staff-salary/list.js?v='.time())}}"></script>
@stop
