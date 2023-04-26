@extends('layout')
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
        <div class="m-portlet__body">
            <div class="m-form m-form__group m-form--label-align-right">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                            <input readonly="" class="form-control m-input daterange-picker"
                                   id="time" name="time" autocomplete="off"
                                   placeholder="{{__('Từ ngày - đến ngày')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <select name="staff" style="width: 100%" id="staff"
                                class="form-control m_selectpicker">--}}
                            <option value="">{{__('Nhân viên')}}</option>
                            @foreach($staff as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group m--margin-top-10">
                <div id="chart-growth-branch" style="min-width: 280px; height: 273px;"></div>
            </div>
            <div class="form-group m-form__group row m--font-bold align-conter1">
                <div class="col-lg-4">
                    <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                        <div id="pie-chart-customer"
                             style="min-width: 310px; height: 273px; max-width: 500px; margin: 0 auto">

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                        <div id="pie-chart-service-category"
                             style="min-width: 310px; height: 300px; max-width: 600px; margin: 0 auto"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
                        <div id="pie-chart-product-category"
                             style="min-width: 310px; height: 300px; max-width: 600px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/statistical/staff.js')}}"
            type="text/javascript"></script>
@stop