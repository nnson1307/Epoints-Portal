@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header">{{__('BÁO CÁO')}}</span>
@endsection
@section('content')
    <style>
        .m-demo .m-demo__preview {
            border: 0px solid #f7f7fa;
            padding-top: 10px;
            padding-bottom: 10px;
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
                        {{__('BÁO CÁO NGÀY ĐĂNG KIỂM XE')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form__group m-form--label-align-right">
                <div class="row">
{{--                    <div class="col-lg-3 form-group">--}}
{{--                        <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">--}}
{{--                            <input readonly="" class="form-control m-input daterange-picker ss--search-datetime-hd"--}}
{{--                                   id="time" name="time" autocomplete="off"--}}
{{--                                   placeholder="{{__('Từ ngày - đến ngày')}}">--}}
{{--                            <span class="m-input-icon__icon m-input-icon__icon--right">--}}
{{--                                <span><i class="la la-calendar"></i></span></span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-lg-3 form-group">
                        <select name="expiration_date" style="width: 100%" id="expiration_date"
                                class="form-control m_selectpicker">--}}
                            <option value="" selected>{{__('Hạn đăng kiểm')}}</option>
                            <option value="week">@lang('Trong 7 ngày')</option>
                            <option value="month">@lang('Trong 30 ngày')</option>
                            <option value="expired">@lang('Quá hạn')</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group">
                <div class="m--margin-top-5" id="container">
                    <div id="table-report">

                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/report/vehicle-registration-date/script.js')}}"
            type="text/javascript"></script>
    <script>
        vehicleRegistration._init();
    </script>
@stop
