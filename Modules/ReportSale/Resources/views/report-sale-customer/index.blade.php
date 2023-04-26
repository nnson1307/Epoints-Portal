@extends('layout')
@section("after_css")
    <style>
        .m-widget24 .m-widget24__item .m-widget24__title {
            margin-left: 1.8rem;
            margin-top: 1.21rem;
            display: inline-block;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .m-widget24 .m-widget24__item .m-widget24__desc {
            margin-bottom: 1.21rem;
            display: inline-block;
            font-size: 1.5rem;
            font-weight: 600;
            color: #000;
        }
        .m-portlet .m-portlet__body {
            /* padding: 2.2rem 2.2rem; */
            padding-right: 2.2rem;
            
        }
        .m-portlet {
            margin-bottom: 0.2rem;
           
        }
        .row.m-row--col-separator-xl > div {
            border-bottom: 0;
            border-right: none;
        }
        .m-widget24 {
            text-align: center;
        }
        small, .small {
            font-size: 20px;
            font-weight: 400;
        }
        .bg-secondary {
            --bs-bg-opacity: 1;
            background-color: #9c27b1 !important;
        }
        .bg-teal {
            --bs-bg-opacity: 1;
            background-color: #00ba94 !important;
        }
        .text-teal {
            color: #00ba94 !important;
        }
        .bg-cyan{ 
            --bs-bg-opacity: 1;
            background-color: #00cfd5 !important;
        }
        .text-cyan {
            color: #00cfd5 !important;
        }
        .bg-blue{
            --bs-bg-opacity: 1;
            background-color: #0061f2 !important;
        }
        .text-blue {
            color: #0061f2 !important;
        }
        .bg-info1 {
            background-color: #17a2b8!important;
        }
        .text-info1 {
            color: #17a2b8 !important;
        }
        .col{
            padding-bottom: 10px;
        }
        .bg-white-50 {
            background-color: rgba(255, 255, 255, 0.5);
        }
        .icon-circle {
            height: 3rem;
            width: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .text-white {
            color: #fff !important;
            font-size: 14px;
            font-weight: 400;
        }
        .card-text:last-child {
            margin-bottom: 0;
            font-weight: 600;
            font-size: 18px;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header">{{__('BÁO CÁO')}}</span>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                {{__('BÁO CÁO DOANH THU THEO KHÁCH HÀNG')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Chi nhánh'):<b class="text-danger"> *</b>
                                </label>
                                <select name="branch" style="width: 100%" id="branch"
                                        class="form-control m_selectpicker">
                                    @if(Auth::user()->is_admin ==1 )
                                        <option value="">{{__('Tất cả chi nhánh')}}</option>
                                    @endif
                                    @foreach($optionBranch as $key => $value)
                                        @if(Auth::user()->is_admin !=1 )
                                            @if(Auth::user()->branch_id == $value['branch_id'])
                                                <option value="{{$value['branch_id']}}" selected>{{$value['branch_name']}}</option>
                                            @endif
                                        @else
                                            <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                        @endif
                                        
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Nhóm khách hàng'):<b class="text-danger"> *</b>
                                </label>
                                <select name="customer_group" style="width: 100%" id="customer_group"
                                        class="form-control m_selectpicker">
                                    <option value="">{{__('Nhóm khách hàng')}}</option>
                                    @foreach($optionCustomerGroups as $key => $value)
                                        <option value="{{$value['customer_group_id']}}">{{$value['group_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Khách hàng'):<b class="text-danger"> *</b>
                                </label>
                                <select name="customer" style="width: 100%" id="customer"
                                        class="form-control m_selectpicker">
                                    <option value="">{{__('Chọn khách hàng')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Ngày ghi nhận'):<b class="text-danger"> *</b>
                                </label>
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12">
                                <div class="card card-raised bg-success text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('DOANH SỐ')}}</div>
                                                <div class="card-text" id="total"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-success">
                                                <i class="fa fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12">
                                <div class="card card-raised bg-info text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('DOANH THU')}}</div>
                                                <div class="card-text" id="totalReceipt"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-info">
                                                <i class="fa fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12">
                                <div class="card card-raised bg-warning text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('CÔNG NỢ')}}</div>
                                                <div class="card-text" id="totalCustomerDept"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-warning">
                                                <i class="fa fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12">
                                <div class="card card-raised bg-primary text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('ĐƠN HÀNG TRUNG BÌNH')}}</div>
                                                <div class="card-text" id="totalOrderMedium"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-primary">
                                                <i class="fa fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col">
                                <div class="card card-raised bg-blue text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('TỔNG ĐƠN HÀNG')}}</div>
                                                <div class="card-text" id="totalOrder"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-blue">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card card-raised bg-teal text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('ĐÃ THANH TOÁN')}}</div>
                                                <div class="card-text" id="totalOrderPay"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-teal">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card card-raised bg-info1 text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('THANH TOÁN CÒN THIẾU')}}</div>
                                                <div class="card-text" id="totalOrderPayhafl"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-info1">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card card-raised bg-cyan text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('CHƯA THANH TOÁN')}}</div>
                                                <div class="card-text" id="totalOrderNotPay"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-cyan">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card card-raised bg-danger text-white">
                                    <div class="card-body px-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="me-2">
                                                <div class="display-5 text-white">{{__('ĐÃ HỦY')}}</div>
                                                <div class="card-text" id="totalOrderCancel"></div>
                                            </div>
                                            <div class="icon-circle bg-white-50 text-danger">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        {{-- <div class="card-text">
                                            <div class="d-inline-flex align-items-center">
                                                <i class="material-icons icon-xs">arrow_upward</i>
                                                <div class="caption fw-500 me-2">3%</div>
                                                <div class="caption">from last month</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
               
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right">
                        <div class="row m-row--col-separator-xl">
                            <div class="col-md-12 col-lg-6 form-group">
                                <div class="m-portlet ">
                                    <div class="m-portlet__body m-row--no-padding m-portlet__body--no-padding">
                                        <div class="row">
                                            <div class="col-lg-12 form-group" style="padding-top: 10px; padding-left: 30px;">
                                                <h3>{{__('Báo cáo bán hàng theo thời gian')}}</h3>
                                                    <div class="m-radio-inline">
                                                        <label class="m-radio">
                                                            <input type="radio" name="example_3" value="amount" onclick="reportSaleCustomer.changeReportChartType(this);" checked> Theo doanh số
                                                            <span></span>
                                                        </label>
                                                        <label class="m-radio">
                                                            <input type="radio" name="example_3" value="receipt" onclick="reportSaleCustomer.changeReportChartType(this);"> Theo doanh thu
                                                            <span></span>
                                                        </label>
                                                        <label class="m-radio">
                                                            <input type="radio" name="example_3" value="dept" onclick="reportSaleCustomer.changeReportChartType(this);"> Theo công nợ
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                    <input type="hidden" name="type-chart" id="type-chart" value="amount">
                                            </div>
                                            <div class="col-12">
                                                <div id="container-total-amount"></div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 form-group">
                                <div class="m-portlet ">
                                    <div class="m-portlet__body m-row--no-padding m-portlet__body--no-padding">
                                        <div class="row">
                                            <div class="col-lg-12 form-group" style="padding-top: 10px; padding-left: 30px;">
                                                <h3>{{__('Báo cáo số lượng đơn hàng theo thời gian')}}</h3>
                                                   
                                            </div>
                                            <div class="col-12" style="padding-top: 25px;">
                                                <div id="container-total-order"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-row--col-separator-xl">
                            <div class="col-md-12 col-lg-6 form-group">
                                <div class="m-portlet ">
                                    <div class="m-portlet__body m-row--no-padding m-portlet__body--no-padding">
                                        <div class="row">
                                            <div class="col-lg-12 form-group" style="padding-top: 10px; padding-left: 30px;">
                                                <h3>{{__('Báo cáo bán hàng theo nhóm khách hàng')}}</h3>
                                            </div>
                                            <div class="col-12">
                                                <div id="container-total-amount-branch"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 form-group">
                                <div class="m-portlet ">
                                    <div class="m-portlet__body m-row--no-padding m-portlet__body--no-padding">
                                        <div class="row">
                                            <div class="col-lg-12 form-group" style="padding-top: 10px; padding-left: 30px;">
                                                <h3>{{__('Báo cáo số lượng đơn hàng xem theo nhóm khách hàng')}}</h3>
                                            </div>
                                            <div class="col-12" style="padding-top: 25px;">
                                                <div id="container-total-order-branch"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/report-sale/customer/script.js?v='.time())}}"></script>
@stop
