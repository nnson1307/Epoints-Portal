@extends('layout')
@section('content')
    <style>
        .m-demo .m-demo__preview {
            border: 0px solid #f7f7fa;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .align-conter1 {
            text-align: center;
        }

        .applyBtn {
            display: none;
        }

        .cancelBtn {
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <div class="m-input-icon m-portlet__head-icon">
                            </div>
                            <h3 class="m-portlet__head-text">
                            </h3>
                            <h2 style=" white-space:nowrap"
                                class="m-portlet__head-label m-portlet__head-label--primary">
                                <span><i class="la 	la-bar-chart m--margin-right-5"></i> {{__('BÁO CÁO DOANH THU')}} </span>
                            </h2>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">

                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-xl-2">
                                <select name="year" id="year" style="width: 100%"
                                        class="form-control m_selectpicker m-btn--pill">
                                    @for($i=0;$i<=5;$i++)
                                        <option value="{{date('Y') - $i}}">{{date('Y') - $i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-xl-3">
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <select name="filter" style="width: 100%" id="filter"
                                        class="form-control m_selectpicker">--}}
                                    <option value="">{{__('Chọn loại doanh thu')}}</option>
                                    <option value="branch">{{__('Chi nhánh')}}</option>
                                    <option value="staff">{{__('Nhân viên')}}</option>
                                    <option value="customer">{{__('Khách hàng')}}</option>
                                </select>
                            </div>
                            <div class="col-xl-3">
                                <select name="filter-child" style="width: 100%" id="filter-child"
                                        class="form-control m_selectpicker">--}}
                                    <option value="">{{__('Tất cả')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="container" style="min-width: 280px; height: 273px;"></div>
                    </div>
                    <div class="form-group m-form__group row m--font-bold align-conter1">
                        <div class="col-lg-3">
                            <div class="m-demo">
                                <div class="m-demo__preview" style="background: #A9A9F5;">
                                    <h6> {{__('Tổng đơn hàng')}}</h6>
                                    <h5 id="totalOrder">  {{number_format($order['totalOrder'],0,"",",") }}</h5>
                                    <hr>
                                    <h6> {{__('Tổng tiền')}}</h6>
                                    <h5 id="totalMoney"> {{number_format($order['totalMoney'],0,"",",") }} {{__('VNĐ')}}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="m-demo">
                                <div class="m-demo__preview" style="background: #A9F5BC;">
                                    <h6> {{__('Đã thanh toán')}}</h6>
                                    <h5 id="totalOrderPaysuccess"> {{number_format($order['totalOrderPaysuccess'],0,"",",")}}</h5>
                                    <hr>
                                    <h6> {{__('Số tiền đã thanh toán')}}</h6>
                                    <h5 id="totalMoneyOrderPaysuccess"> {{number_format($order['totalMoneyOrderPaysuccess'],0,"",",") }}
                                        {{__('VNĐ')}}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="m-demo">
                                <div class="m-demo__preview" style="background: #F5BCA9;">
                                    <h6> {{__('Chưa thanh toán')}}</h6>
                                    <h5 id="totalOrderNew"> {{number_format($order['totalOrderNew'],0,"",",")}}</h5>
                                    <hr>
                                    <h6> {{__('Số tiền chưa thanh toán')}}</h6>
                                    <h5 id="totalMoneyOrderNew"> {{number_format($order['totalMoneyOrderNew'],0,"",",") }}
                                        {{__('VNĐ')}}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="m-demo">
                                <div class="m-demo__preview" style="background: #F5A9A9;">
                                    <h6> {{__('Hủy')}}</h6>
                                    <h5 id="totalOrderPayFail"> {{number_format($order['totalOrderPayFail'],0,"",",")}}</h5>
                                    <hr>
                                    <h6> {{__('Số tiền hủy')}}</h6>
                                    <h5 id="totalMoneyOrderPayFail"> {{number_format($order['totalMoneyOrderPayFail'],0,"",",") }}
                                        {{__('VNĐ')}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <input type="hidden" id="flag" value="0">
    <div id="value-12-month-controller">
        @foreach($timeOrder as $key=>$value)
            <input type="hidden" class="value-12-month" value="{{$value}}">
        @endforeach
    </div>
    <button id="btn-search" style="display: none"></button>
    <input type="hidden" class="totalOrder" value="{{number_format($order['totalOrder'],0,"",",") }}">
    <input type="hidden" class="totalMoney" value="{{number_format($order['totalMoney'],0,"",",") }}">
    <input type="hidden" class="totalOrderPaysuccess"
           value="{{number_format($order['totalOrderPaysuccess'],0,"",",") }}">
    <input type="hidden" class="totalMoneyOrderPaysuccess"
           value="{{number_format($order['totalMoneyOrderPaysuccess'],0,"",",") }}">
    <input type="hidden" class="totalOrderNew" value="{{number_format($order['totalOrderNew'],0,"",",") }}">
    <input type="hidden" class="totalMoneyOrderNew" value="{{number_format($order['totalMoneyOrderNew'],0,"",",") }}">
    <input type="hidden" class="totalOrderPayFail" value="{{number_format($order['totalOrderPayFail'],0,"",",") }}">
    <input type="hidden" class="totalMoneyOrderPayFail"
           value="{{number_format($order['totalMoneyOrderPayFail'],0,"",",") }}">

@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/admin/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/report-revenue/index.js')}}" type="text/javascript"></script>
@stop
