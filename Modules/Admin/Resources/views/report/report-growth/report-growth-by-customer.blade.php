@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" style="height: 20px;">
        {{__('THỐNG KÊ')}}
    </span>
@endsection
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
    </style>
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                   <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THỐNG KÊ KHÁCH HÀNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form__group m-form--label-align-right">
                <div class="row">
                    <div class="col-lg-6 ss--col-xl-4"></div>
                    <div class="col-lg-3  ss--col-xl-4 ss--col-lg-12 form-group">
                        <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                            <input readonly="" class="form-control m-input daterange-picker"
                                   id="time" name="time" autocomplete="off"
                                   placeholder="{{__('Từ ngày - đến ngày')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-3  ss--col-xl-4 ss--col-lg-12 form-group">
                        <select name="branch" {{Auth::user()->is_admin != 1?"disabled":""}} style="width: 100%"
                                id="branch" class="form-control m_selectpicker">--}}
                            @if (Auth::user()->is_admin != 1)
                                @foreach($branch as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            @else
                                <option value="">{{__('Tất cả chi nhánh')}}</option>
                                @foreach($branch as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                </div>
            </div>
            <div class="form-group m-form__group row m--margin-top-10">
                <div class="col-lg-12" id="div-chart-growth-customer">
                    <div id="chart-growth-customer" style="width: 100%; height: 450px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group m-form__group row m--font-bold align-conter1">
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                <div class="form-group m-form__group ss--margin-bottom-0">
                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                        {{__('NHÓM KHÁCH HÀNG')}}
                    </label>
                </div>
                <div id="pie-chart-customer"
                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto">
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                <div class="form-group m-form__group ss--margin-bottom-0">
                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                        {{__('GIỚI TÍNH')}}
                    </label>
                </div>

                <div id="pie-chart-customer-gender"
                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
                <div class="form-group m-form__group ss--margin-bottom-0">
                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                        {{__('NGUỒN KHÁCH HÀNG')}}
                    </label>
                </div>
                <div id="pie-chart-customer-source"
                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto"></div>
            </div>
        </div>
    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/general/loader.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/report/report-growth/by-customer.js')}}"
            type="text/javascript"></script>
@endsection
{{--a--}}