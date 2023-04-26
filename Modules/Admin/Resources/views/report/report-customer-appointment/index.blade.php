@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" style="height: 20px;">
        THỐNG KÊ
    </span>
@endsection
@section('content')

    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THỐNG KÊ LỊCH HẸN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form__group m-form--label-align-right">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 ss--col-xl-4"></div>
                            <div class="col-lg-3  ss--col-xl-4 ss--col-lg-12 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-3 filter-child  ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="branch_id" id="branch_id"
                                        {{Auth::user()->is_admin != 1?"disabled":""}} class="form-control"
                                        style="width: 100%">
                                    @if (Auth::user()->is_admin != 1)
                                        @foreach($optionBranch as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    @else
                                        <option value="" selected>{{__('Tất cả chi nhánh')}}</option>
                                        @foreach($optionBranch as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="container" class="m_blockui_1_content m-section__content"
                 style="min-width: 250px; height: 300px; margin: 0 auto;">

            </div>
        </div>
    </div>
    <div class="form-group m-form__group row m--font-bold align-conter1">
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                <div class="form-group m-form__group ss--margin-bottom-0 ss--text-center ">
                    <label class="m--margin-top-20 ss--font-weight-400">
                         {{__('LỊCH HẸN')}}
                    </label>
                </div>
                <div id="source-appointment"
                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto">
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                <div class="form-group m-form__group ss--margin-bottom-0 ss--text-center ">
                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                        {{__('GIỚI TÍNH')}}
                    </label>
                </div>
                <div id="gender"
                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto">
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                <div class="form-group m-form__group ss--margin-bottom-0 ss--text-center ">
                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400 ">
                        {{__('NGUỒN KHÁCH HÀNG')}}
                    </label>
                </div>
                <div id="customer-source"
                     style="min-width: 290px; height: 290px; max-width: 290px; margin: 0 auto">
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/general/loader.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/report/report-customer-appointment/script.js')}}"
            type="text/javascript"></script>
@endsection
