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

        .align-conter1 {
            text-align: center;
        }

        .tongtien {
            background-image: url("{{asset('static/backend/images/report/hinh3.jpg')}}");
            background-size: cover;
        }
    </style>
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
                                {{__('BÁO CÁO HOA HỒNG CHO DEAL')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        @if(in_array('report.deal-commission.export-total', session()->get('routeList')))
                            <form action="{{route('report.deal-commission.export-total')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="export_time_total" name="export_time_total">
                                <input type="hidden" id="export_number_deal_total" name="export_number_deal_total">
                                <input type="hidden" id="export_deal_id_total" name="export_deal_id_total">

                                <button type="submit"
                                        class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>{{__('Export Tổng')}}</span>
                                        </span>
                                </button>
                            </form>
                        @endif
                        @if(in_array('report.deal-commission.export-detail', session()->get('routeList')))
                            <form action="{{route('report.deal-commission.export-detail')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="export_time_detail" name="export_time_detail">
                                <input type="hidden" id="export_number_deal_detail" name="export_number_deal_detail">
                                <input type="hidden" id="export_deal_id_detail" name="export_deal_id_detail">
                                <button type="submit"
                                        class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>{{__('Export Chi Tiết')}}</span>
                                        </span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row m--font-bold align-conter1 ss--text-white">
                        <div class="col-xl-3 ss--col-lg-6 ss--col-lg-12 form-group">
                            <div class="tongtien">
                                <div class=" ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('TỔNG TIỀN')}}</h6>
                                    <h3 class="ss--font-size-18" id="totalMoney"></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 ss--col-lg-6 ss--col-lg-12 form-group">
                            <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                <input readonly="" class="form-control m-input daterange-picker"
                                       id="time" name="time" autocomplete="off"
                                       placeholder="{{__('Từ ngày - đến ngày')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-xl-3 ss--col-lg-6 ss--col-lg-12 form-group">
                            <select name="number_deal" style="width: 100%" id="number_deal"
                                    class="form-control m_selectpicker">
                                <option value="">{{__('Giới hạn deal')}}</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-xl-3 ss--col-lg-6 ss--col-lg-12 form-group">
                            <select name="deal_id" style="width: 100%" id="deal_id"
                                    class="form-control m_selectpicker">
                                <option value="">{{__('Chọn deal')}}</option>
                                @foreach($deal as $key => $value)
                                    <option value="{{$value['deal_id']}}">{{$value['deal_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="container" style="min-width: 280px;"></div>
                        <div id="autotable">
                            <form class="frmFilter">
                                <input type="hidden" id="time_detail" name="time_detail">
                                <input type="hidden" id="number_deal_detail" name="number_deal_detail">
                                <input type="hidden" id="deal_id_detail" name="deal_id_detail">
                                <div class="form-group m-form__group" style="display: none;">
                                    <button class="btn btn-primary color_button btn-search">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </form>
                            <div class="table-content div_table_detail">

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
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/deal-commission/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        dealCommission._init();
    </script>
@stop
