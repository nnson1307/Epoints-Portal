{{--@extends('backpack::layout')--}}
@extends('layout')
@section('title_header')
    <span class="title_header">{{__('BÁO CÁO')}}</span>
@endsection
<link rel="stylesheet" type="text/css" href="{{asset('vendors/adminlte/bootstrap/css/bootstrap.css')}}">
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/custom-admin.css')}}">
{{--    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/bootstrap.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/daterangepicker.css')}}">
@endsection
@section('content')
    <div class="box box-default">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row padding-top-bottom">
                        <div class="col-md-4 nt_bg_date nt_bg_date_padding">
                            <div class="input-group margin nt_center nt_customer_date">
                                <label class="nt_text_style m-auto">Date Range</label>
                                <input class="form-control range-picker nt_custome_input w-100" id="date-range" type="text" placeholder="Chọn ngày">
                            </div>
                            <div class="input-group margin nt_center">
                                <a class="nt_btn_text m-auto" href="{{route('dashboard')}}">Cancel</a>
                            </div>
                        </div>
                       <div class="col-md-8">
                           <div class="row">
                               <div class="col-md-6 padding-none">
                                   <div class="text-center text_total">
                                       <label class="nt_text_style">Total unique users</label>
                                   </div>
                                   <div class="text-center padding8">
                                       <span class="total_user"></span>
                                   </div>
                                   <div class="text-center btn_nt_bottom_ex padding8">
                                       <form method="POST" action="{{route('dashboard.export-user')}}">
                                           {{ csrf_field() }}
                                           <input type="hidden" name="date_range" id="date_user">
                                           <button type="submit" class="btn btn-success btn-sm nt_btn_suss">Export</button>
                                       </form>
                                   </div>
                               </div>
                               <div class="col-md-6 padding-none">
                                   <div class="text-center text_total_mess">
                                       <label class="nt_text_style">Total messages</label>
                                   </div>
                                   <div class="text-center padding8">
                                       <span class="total_message"></span>
                                   </div>
                                   <div class="text-center btn_nt_bottom_ex padding8">
                                       <form method="POST" action="{{route('dashboard.export-total-message')}}">
                                           {{ csrf_field() }}
                                           <input type="hidden" name="date_range" id="date_message">
                                           <button class="btn btn-success btn-sm nt_btn_suss">Export</button>
                                       </form>
                                   </div>
                               </div>
                           </div>
                       </div>
                    </div>
                    <div class="row nt_bg2 nt_bgx padding-top-bottom">
                        <div class="form-group bg_blue w-100">
                            <label class="text_brand">VIEW BY BRAND:</label>
                            <select id="brand" name="brand" class="form-control nt_custome_input" onchange="over_view.change_brand()">
                                <option value="all">All</option>
                                @foreach($optionBrand as $item)
                                    <option value="{{$item['entities']}}">{{$item['brand_name']}}</option>
                                @endforeach
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        <div class="text-center w-100">
                            <label class="text_by_month ">Total message by month</label>
                            <div class="form-group" id="total_message_month"></div>
                        </div>

                    </div>
                    <div class="row nt_bg2 nt_bgx padding-top-bottom">
                        <div class="col-lg-6 padding-none-left-right">
                            <div class="text-center bg_blue">
                                <label class="nt_stye_lable color-ff">Completion vs. <br>Confusion rate</label>
                            </div>
                            <div class="" id="total_message_scale" style="width: 100%; height: 100%"></div>
                        </div>
                        <div class="col-lg-6 padding-none-left-right">
                            <div class="text-center bg_blue_mess">
                                <label class="nt_stye_lable color-ff">Average message <br>per conversation</label>
                            </div>
                            <div class="text-center nt_nnumber">
                                <span class="average"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                   <div class="row nt_bg nt_bgx">
                       <div class="form-group text-center bg_blue mangin_botton_0 w-100">
                           <label class="nt_text_brand color-ff">TOTAL MESSAGES BY BRAND</label>
                       </div>
                       <div class="form-group" id="total_message_brand"></div>
                   </div>
                    <div style="height: 50px"></div>

                    <div class="row nt_bg row-custome-none-padding">
                        <div class="col-lg-4">
                            <div class="form-group text-center bg_blue margin_bottom_0">
                                <label class="color-ff">Completion Message</label>
                            </div>
                            <div class="form-group total_message_attribute" id="total_message_attribute"></div>
                            <div class="text-center">
                                <a class="btn btn-primary btn-sm nt_btn_suss btn_red" href="{{route('message-completion')}}">View All</a>
                            </div>

                        </div>
                        <div class="col-lg-4 nt_bg_4">
                            <div class="form-group text-center bg_blue_mess">
                                <label class="color-ff">Confusion Message</label>
                            </div>
                            <div class="form-group" id="total_message_not_response"></div>
                            <div class="text-center">
                                <a class="btn btn-primary btn-sm nt_btn_suss btn_red" href="{{route('message-attribute')}}">View All</a>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group text-center nt_padding15">
                                <label>Monitoring Message</label>
                            </div>
                            <div id="cloud"></div>
                            <div class="text-center">
                                <a class="btn btn-primary btn-sm nt_btn_suss btn_red" href="{{route('word-cloud')}}">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('after_styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection
@section('after_script')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    {{--    <script src="{{ asset('vendor/backpack/select2/select2.js') }}"></script>--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
{{--    <script src="{{asset('static/admin/js/node_modules/d3/d3.min.js')}}"></script>--}}
{{--    <script src="{{asset('static/admin/js/node_modules/d3-cloud/build/d3.layout.cloud.js')}}"></script>--}}
{{--    <script src="{{asset('static/admin/js/node_modules/lodash/lodash.min.js')}}"></script>--}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="{{ asset('static/backend/js/report/dashboard/over-view.js?v='.time())}}"></script>
    <script type="text/javascript" src="{{ asset('static/backend/js/report/dashboard/word-cloud.js?v='.time())}}"></script>

    <script>
        over_view._init();
    </script>
    <script>

    </script>
@endsection
