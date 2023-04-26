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
                    @lang("BÁO CÁO TIẾP NHẬN YÊU CẦU KHÁCH HÀNG")
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            {{-- <a href="javascript:void(0)" onclick="callCenter.showModalSearchCustomer()" class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button">
                <span>
                    <i class="fa fa-plus-circle"></i>
                    <span> @lang("EXPORT")</span>
                </span>
            </a> --}}
        </div>

    </div>
   
</div>
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__body">
        <div class="m-form m-form--label-align-right">
            <div class="row m-row--col-separator-xl">
                <div class="col-md-12 form-group">
                    <div class="m-portlet ">
                        <div class="m-portlet__body m-row--no-padding m-portlet__body--no-padding">
                            <div class="row">
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <label class="black_title">
                                        @lang('Năm'):
                                    </label>
                                    <select class="form-control m_selectpicker" id="years" name="years">
                                                     
                                        <option value="{{\Carbon\Carbon::now()->addYears(-1)->format('Y')}}">
                                            {{\Carbon\Carbon::now()->addYears(-1)->format('Y')}}
                                        </option>
                                        <option value="{{\Carbon\Carbon::now()->format('Y')}}" selected>
                                            {{\Carbon\Carbon::now()->format('Y')}}
                                        </option>
                                        <option value="{{\Carbon\Carbon::now()->addYears(+1)->format('Y')}}">
                                            {{\Carbon\Carbon::now()->addYears(+1)->format('Y')}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <label class="black_title">
                                        @lang('Tháng'):
                                    </label>
                                    <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                       
                                        <select class="form-control m_selectpicker" id="months" name="months">
                                            @for ($i = 1; $i <= 12; $i++)
                                                @if ($i == \Carbon\Carbon::now()->month)
                                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                                @else
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="chart-request-customer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__body">
        <div class="m-form m-form--label-align-right">
            <div class="row m-row--col-separator-xl">
                <div class="col-md-12 form-group">
                    <div class="m-portlet ">
                        <div class="m-portlet__body m-row--no-padding m-portlet__body--no-padding">
                            <div class="row">
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <label class="black_title">
                                        @lang('Năm'):
                                    </label>
                                    <select class="form-control m_selectpicker" id="years_staff" name="years_staff">
                                                     
                                        <option value="{{\Carbon\Carbon::now()->addYears(-1)->format('Y')}}">
                                            {{\Carbon\Carbon::now()->addYears(-1)->format('Y')}}
                                        </option>
                                        <option value="{{\Carbon\Carbon::now()->format('Y')}}" selected>
                                            {{\Carbon\Carbon::now()->format('Y')}}
                                        </option>
                                        <option value="{{\Carbon\Carbon::now()->addYears(+1)->format('Y')}}">
                                            {{\Carbon\Carbon::now()->addYears(+1)->format('Y')}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <label class="black_title">
                                        @lang('Tháng'):
                                    </label>
                                    <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                       
                                        <select class="form-control m_selectpicker" id="months_staff" name="months_staff">
                                            @for ($i = 1; $i <= 12; $i++)
                                                @if ($i == \Carbon\Carbon::now()->month)
                                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                                @else
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="chart-request-customer-by-staff"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
<script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js')}}"></script>
<script src="{{asset('static/backend/js/call-center/report.js?v='.time())}}" type="text/javascript"></script>
@stop

