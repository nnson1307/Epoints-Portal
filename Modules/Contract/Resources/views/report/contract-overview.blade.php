@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <style>
        .m-demo .m-demo__preview {
            border: 0px solid #f7f7fa;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .align-conter1 {
            text-align: center;
        }

        .tongtien {
            background-image: url("{{asset('static/backend/images/report/hinh3.jpg')}}");
            background-size: cover;
        }

        .dathanhtoan {
            background-image: url("{{asset('static/backend/images/report/hinh4.jpg')}}");
            background-size: cover;
        }

        .chuathanhtoan {
            background-image: url("{{asset('static/backend/images/report/hinh2.jpg')}}");
            background-size: cover;
        }

        .sotienhuy {
            background-image: url("{{asset('static/backend/images/report/hinh1.jpg')}}");
            background-size: cover;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header">{{__('BÁO CÁO HỢP ĐỒNG')}}</span>
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
                                {{__('BÁO CÁO TỔNG QUAN HỢP ĐỒNG')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row m--font-bold align-conter1 ss--text-white">
                        <div class="col-lg-3 form-group">
                            <div class="tongtien">
                                <div class="">
                                    <div class="ss--padding-13">
                                        <h6 class="ss--font-size-12"> {{__('TỔNG HỢP ĐỒNG')}}</h6>
                                        <h3 class="ss--font-size-18" id="countTotalContract"></h3>
                                        <hr class="ss--hr">
                                        <h6 class="ss--font-size-13">
                                            <span id="amountTotalContract"></span><span> @lang('VNĐ')</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="dathanhtoan">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('CÒN HIỆU LỰC')}}</h6>
                                    <h3 class="ss--font-size-18" id="countValidated"></h3>
                                    <hr class="ss--hr">
                                    <h6 class="ss--font-size-13">
                                        <span id="amountValidated"></span><span> @lang('VNĐ')</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="chuathanhtoan">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('ĐÃ THANH LÝ')}}</h6>
                                    <h3 class="ss--font-size-18" id="countLiquidated"></h3>
                                    <hr class="ss--hr">
                                    <h6 class="ss--font-size-13">
                                        <span id="amountLiquidated"></span><span> @lang('VNĐ')</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="sotienhuy">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('CHỜ THANH LÝ')}}</h6>
                                    <h3 class="ss--font-size-18" id="countWaitingLiquidation"></h3>
                                    <hr class="ss--hr">
                                    <h6 class="ss--font-size-13">
                                        <span id="amountWaitingLiquidation"></span><span> @lang('VNĐ')</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3 form-group">
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <select name="branch_id" style="width: 100%"
                                        {{Auth::user()->is_admin != 1?"disabled":""}} id="branch_id"
                                        class="form-control m_selectpicker">
                                    @if (Auth::user()->is_admin != 1)
                                        @foreach($optionBranches as $key=>$value)
                                            @if(Auth::user()->branch_id == $value['branch_id'])
                                                <option value="{{$value['branch_id']}}" selected>{{$value['branch_name']}}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value="">{{__('Tất cả chi nhánh')}}</option>
                                        @foreach($optionBranches as $key=>$value)
                                            @if(Auth::user()->branch_id == $value['branch_id'])
                                                <option value="{{$value['branch_id']}}" selected>{{$value['branch_name']}}</option>
                                            @else
                                                <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-3 form-group">
                                <select name="department_id" style="width: 100%"
                                        id="department_id"
                                        class="form-control m_selectpicker">
                                    <option value="">{{__('Tất cả phòng ban')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 form-group">
                                <select name="staff_id" style="width: 100%"
                                        id="staff_id"
                                        class="form-control m_selectpicker">
                                    <option value="">{{__('Tất cả nhân viên')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="" style="">
                            <div class="load_ajax" id="container"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/report/export-data.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/contract/report/contract-overview/script.js')}}"
            type="text/javascript"></script>
    <script>
        contractOverviewReport._init();
    </script>
@stop
