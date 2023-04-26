@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@stop
@section('content')
<style>

    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        background-color: #4fc4ca;
    }
    .select2-results__option .wrap:before{
        content: "";
        display: inline-block;
        position: relative;
        height: 20px;
        width: 20px;
        border: 2px solid #e9e9e9;
        border-radius: 4px;
        background-color: #fff;
        margin-right: 20px;
        vertical-align: middle;
        
    }
    .select2-results__option[aria-selected=true] .wrap:before{
        font-family:fontAwesome;
        content: "";
        color: #fff;
        background-color: #f77750;
        border: 0;
        display: inline-block;
        padding-left: 3px;
    }
    .select2-results__option[aria-selected=true]:before {
        background: #f77750;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
	background-color: #fff;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
	background-color: #eaeaeb;
	color: #272727;
}
    .select2-multiple, .select2-multiple2
    {
        width: 100%
    }
</style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list-1"></i>
                </span>
                    <h2 class="m-portlet__head-text">
                        @lang("BÁO CÁO SỐ GIỜ LÀM VIỆC/NGÂN SÁCH DỰ KIẾN")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active show" data-toggle="tab" href="#m_report_chart" onclick="list.getReportBudgetBranchChart();">
                        @lang('Biểu đồ')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#m_report_list" onclick="list.getReportBudgetBranchList();">
                        @lang('Danh sách')
                    </a>
                </li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane active" id="m_report_chart" role="tabpanel">

                </div>
                <div class="tab-pane" id="m_report_list" role="tabpanel">
                  
                </div>
            </div>
          
        </div>
    </div>
@stop
@section("after_style")

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/staff-salary/report-budget-branch/script.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/highcharts.js')}}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> --}}
    <script type="text/template" id="option-week-tpl">
        @for($i = 1; $i <= $week_in_year; $i++)
        <option value="{{$i}}" {{$i == \Carbon\Carbon::now()->isoWeek ? 'selected': ''}}>
            <?php
            $now = \Carbon\Carbon::now();
            $date = $now->setISODate($now->format('Y'), $i);
            ?>
            @lang('Tuần') {{$i. ' ('.$date->startOfWeek()->format('d/m/Y'). ' - '. $date->endOfWeek()->format('d/m/Y'). ')'}}
        </option>
        @endfor
    </script>

    <script type="text/template" id="option-month-tpl">
        @for($i = 1; $i <= 12; $i++)
        <option value="{{$i}}" {{$i == \Carbon\Carbon::now()->format('m') ? 'selected': ''}}>
            {{ __('Tháng ' . $i) }}
        </option>
        @endfor
    </script>
    
@stop
