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
                    {{ __('BẢNG LƯƠNG') }} {{ \Carbon\Carbon::createFromFormat('Y-m-d', $staffSalary['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $staffSalary['end_date'])->format('d/m/Y') }}
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <form action="{{route('staff-salary.export')}}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" value="{{ $staffSalary['staff_salary_id'] }}" id="staff_salary_id" name="staff_salary_id">
                <button type="submit" class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                    <span>
                        <i class="la la-files-o"></i>
                        <span>
                            @lang('Export tất cả')
                        </span>
                    </span>
                </button>
            </form>
            @if($staffSalary['staff_salary_status'] != 1)
                <button type="submit" onclick="staffSalary.updateReportSalaryDetail({{$staffSalary['staff_salary_id']}})" class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                    <span>
                        <i class="la la-files-o"></i>
                        <span>
                        @lang('Cập nhật bảng lương')
                        </span>
                    </span>
                </button>
                <button type="submit" onclick="staffSalary.closeReportSalaryDetail({{$staffSalary['staff_salary_id']}})" class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                    <span>
                        <i class="la la-files-o"></i>
                        <span>
                            @lang('Chốt bảng lương')
                        </span>
                    </span>
                </button>
            @endif
            
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="m-portlet">
            <div class="m-portlet__body">
                <div id="autotable">
                    <form class="frmFilter">
                        @include('staff-salary::staff-salary.salary-detail')
                    </form>
                </div>
            </div>
        </div>
       
    </div>
  
</div>
<div id="modal-holiday-add"></div>
<div id="modal-holiday-edit"></div>
@stop
@section("after_style")
    
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
<script src="{{asset('static/backend/js/staff-salary/staff-salary/list.js?v='.time())}}"></script>

@stop
