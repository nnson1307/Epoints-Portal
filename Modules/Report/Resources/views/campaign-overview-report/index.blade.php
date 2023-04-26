@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <style>
        .col-xs-5ths,
        .col-sm-5ths,
        .col-md-5ths,
        .col-lg-5ths {
            position: relative;
            min-height: 1px;
            padding-right: 10px;
            padding-left: 10px;
        }

        .col-xs-5ths {
            width: 20%;
            float: left;
        }

        @media (min-width: 768px) {
            .col-sm-5ths {
                width: 20%;
                float: left;
            }
        }

        @media (min-width: 992px) {
            .col-md-5ths {
                width: 20%;
                float: left;
            }
        }

        @media (min-width: 1200px) {
            .col-lg-5ths {
                width: 20%;
                float: left;
            }
        }
        .m-demo .m-demo__preview {
            border: 0px solid #f7f7fa;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .align-conter1 {
            text-align: center;
        }
        .solid-region {
            border: solid 1px;
            border-color: #027177;
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
                                {{__('BÁO CÁO TỔNG QUAN CHIẾN DỊCH')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="form-group">
                            <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                <input readonly="" class="form-control m-input daterange-picker"
                                       id="time_overview" name="time_overview" autocomplete="off"
                                       placeholder="{{__('Từ ngày - đến ngày')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row m--font-bold align-conter1">
                        <div class="col-lg-6">
                            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                <div class="form-group m-form__group ss--margin-bottom-0">
                                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                                        {{__('TỔNG CHI PHÍ CHIẾN DỊCH')}}
                                    </label>
                                    <h3 class="ss--font-size-18" style="color:green;" id="totalCost"></h3>
                                </div>
                                <div id="column-chart-total-cost"
                                     style="min-width: 290px; height: 580px; max-width: 580px; margin: 0 auto">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                <div class="form-group m-form__group ss--margin-bottom-0">
                                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                                        {{__('TỔNG DOANH SỐ BÁN HÀNG')}}
                                    </label>
                                    <h3 class="ss--font-size-18" style="color:green;" id="totalRevenue"></h3>
                                </div>
                                <div id="column-chart-total-revenue"
                                     style="min-width: 290px; height: 580px; max-width: 580px; margin: 0 auto">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row m--font-bold align-conter1">
                        <div class="col-lg-4">
                            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                <div class="form-group m-form__group ss--margin-bottom-0">
                                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                                        {{__('TỔNG KH TIẾP CẬN')}}
                                    </label>
                                    <h3 class="ss--font-size-18" style="color:green;" id="totalCustomerApproach"></h3>
                                </div>
                                <div id="pie-chart-total-approach"
                                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                                <div class="form-group m-form__group ss--margin-bottom-0">
                                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                                        {{__('CHỐT ĐƠN THÀNH CÔNG')}}
                                    </label>
                                    <h3 class="ss--font-size-18" style="color:green;" id="totalDealSuccess"></h3>
                                </div>

                                <div id="pie-chart-deal-successful"
                                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto"></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
                                <div class="form-group m-form__group ss--margin-bottom-0">
                                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                                        {{__('TỈ LỆ CHUYỂN ĐỔI ROI')}}
                                    </label>
                                    <h3 class="ss--font-size-18" style="color:green;" id="totalRateRoi"></h3>
                                </div>
                                <div id="pie-chart-roi-convert-rate"
                                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row m--font-bold align-conter1">
                        <div class="tab-content m--margin-top-40">
                            <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 0;" id="id_ne">
                                <li class="nav-item">
                                    <a class="nav-link active son" data-toggle="tab" show style=" color: #4fc4cb; "
                                       onclick="changeTab('sms')" value="@lang("SMS")">@lang("SMS")</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link son" data-toggle="tab"
                                       onclick="changeTab('email')" value="@lang("EMAIL")">@lang("EMAIL")</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link son" data-toggle="tab"
                                       onclick="changeTab('notification')" value="@lang("NOTIFICATION")">@lang("NOTIFICATION")</a>
                                </li>
                            </ul>
                            <div class="bd-ct row">
                                <div class="form-group row col-12">
                                    <div class="m-input-icon m-input-icon--right col-3 float-right" id="m_daterangepicker_6">
                                        <input readonly="" class="form-control m-input daterange-picker"
                                               id="time_campaign_detail" name="time_campaign_detail" autocomplete="off"
                                               placeholder="{{__('Từ ngày - đến ngày')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                    <div class="form-group m-form__group col-3 float-right">
                                        <div class="input-group">
                                            <select class="form-control option_sms" id="option_sms" name="option_sms"
                                                    style="width:100%;display: block;">
                                                <option></option>
                                            </select>
                                            <select class="form-control option_email" id="option_email" name="option_email"
                                                    style="width:100%;display: none;">
                                                <option></option>
                                            </select>
                                            <select class="form-control option_notify" id="option_notify" name="option_notify"
                                                    style="width:100%;display: none;">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div id="div-sms" style="display: block;">
                                        <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                            <div class="form-group m-form__group ss--margin-bottom-0">
                                                <div class="m-demo__preview" style="min-width: 100%; margin: 0 auto">
                                                    <div class="row" id="m--star-dashbroad">
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG CHI PHÍ')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="sms_total_cost" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG DOANH SỐ BÁN HÀNG')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="sms_total_revenue" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG KHÁCH HÀNG TIẾP CẬN')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <span class="sms_customer_approach" style="color:green;font-size:18px!important;">0</span>
                                                                    <div class="m-widget25">
                                                                        <div class="progress" id="progress-sms">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('CHỐT ĐƠN THÀNH CÔNG')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="sms_deal_success" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỈ LỆ CHUYỂN ĐỔI')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="sms_roi_rate" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                                            <div id="combi_sms_detail"
                                                 style="width:100%"></div>
                                        </div>
                                    </div>
                                    <div id="div-email" style="display: none">
                                        <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                            <div class="form-group m-form__group ss--margin-bottom-0">
                                                <div class="m-demo__preview" style="min-width: 100%; margin: 0 auto">
                                                    <div class="row" id="m--star-dashbroad">
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG CHI PHÍ')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="email_total_cost" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG DOANH SỐ BÁN HÀNG')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="email_total_revenue" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG KHÁCH HÀNG TIẾP CẬN')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <span class="email_customer_approach" style="color:green;font-size:18px!important;">0</span>
                                                                    <div class="m-widget25">
                                                                        <div class="progress" id="progress-email">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('CHỐT ĐƠN THÀNH CÔNG')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="email_deal_success" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỈ LỆ CHUYỂN ĐỔI')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="email_roi_rate" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                                            <div id="combi_email_detail"
                                                 style="width:100%"></div>
                                        </div>
                                    </div>
                                    <div id="div-notify" style="display: none">
                                        <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                            <div class="form-group m-form__group ss--margin-bottom-0">
                                                <div class="m-demo__preview" style="min-width: 100%; margin: 0 auto">
                                                    <div class="row" id="m--star-dashbroad">
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm tab">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG CHI PHÍ')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="notify_total_cost" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG DOANH SỐ BÁN HÀNG')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="notify_total_revenue" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỔNG KHÁCH HÀNG TIẾP CẬN')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <span class="notify_customer_approach" style="color:green;font-size:18px!important;">0</span>
                                                                    <div class="m-widget25">
                                                                        <div class="progress" id="progress-notify">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('CHỐT ĐƠN THÀNH CÔNG')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="notify_deal_success" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5ths">
                                                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                                                @lang('TỈ LỆ CHUYỂN ĐỔI')
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget25">
                                                                        <span class="notify_roi_rate" style="color:green;font-size:18px!important;">0</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                                            <div id="combi_notify_detail"
                                                 style="width:100%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section("after_style")
{{--    <link rel="stylesheet" href="{{asset('static/backend/css/sinh.css')}}">--}}
{{--    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">--}}
{{--    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">--}}
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/report/loader.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/report/campaign-overview-report/script.js')}}"
            type="text/javascript"></script>
    <script>
        campaignOverviewReport._init();
    </script>
@stop
