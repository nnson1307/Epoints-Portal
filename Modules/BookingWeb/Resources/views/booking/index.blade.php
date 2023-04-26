@extends('bookingweb::layout')

@section('content')
    <div class="form-group">
        <span class="name-spa">{{$info['name']}}</span>
        <p class="slogan">{{$info['slogan']}}</p>
    </div>
    <div class="form-group">
        <p class="text-content">{{__('Đặt lịch - giữ chổ')}}</p>
    </div>
    <div class="form-group">
        <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="first">
            <div class="kt-grid__item">

                <!--begin: Form Wizard Nav -->
                <div class="kt-wizard-v3__nav none">
                    <div class="kt-wizard-v3__nav-items">
                        <a class="kt-wizard-v3__nav-item" href="javascript:void(0)"  data-ktwizard-type="step" data-ktwizard-state="current" >
                            <div class="kt-wizard-v3__nav-body" >
                                <div class="kt-wizard-v3__nav-label">
                                    {{__('Chi nhánh')}}
                                </div>
                                <div class="kt-wizard-v3__nav-bar"></div>
                            </div>
                        </a>
                        <a class="kt-wizard-v3__nav-item" href="javascript:void(0)"  data-ktwizard-type="step"
                           data-ktwizard-state="pending">
                            <div class="kt-wizard-v3__nav-body">
                                <div class="kt-wizard-v3__nav-label">
                                    {{__('Thời gian')}}
                                </div>
                                <div class="kt-wizard-v3__nav-bar"></div>
                            </div>
                        </a>
                        <a class="kt-wizard-v3__nav-item" href="javascript:void(0)" data-ktwizard-type="step"
                           data-ktwizard-state="pending">
                            <div class="kt-wizard-v3__nav-body">
                                <div class="kt-wizard-v3__nav-label">
                                    {{__('Dịch vụ')}}
                                </div>
                                <div class="kt-wizard-v3__nav-bar"></div>
                            </div>
                        </a>
                        <a class="kt-wizard-v3__nav-item" href="javascript:void(0)" data-ktwizard-type="step"
                           data-ktwizard-state="pending">
                            <div class="kt-wizard-v3__nav-body">
                                <div class="kt-wizard-v3__nav-label">
                                    {{__('Kỹ thuật viên')}}
                                </div>
                                <div class="kt-wizard-v3__nav-bar"></div>
                            </div>
                        </a>
                        <a class="kt-wizard-v3__nav-item" href="javascript:void(0)" data-ktwizard-type="step"
                           data-ktwizard-state="pending">
                            <div class="kt-wizard-v3__nav-body">
                                <div class="kt-wizard-v3__nav-label">
                                    {{__('Thông tin')}}
                                </div>
                                <div class="kt-wizard-v3__nav-bar"></div>
                            </div>
                        </a>
                        <a class="kt-wizard-v3__nav-item" href="javascript:void(0)" data-ktwizard-type="step"
                           data-ktwizard-state="pending">
                            <div class="kt-wizard-v3__nav-body">
                                <div class="kt-wizard-v3__nav-label">
                                    {{__('Xác nhận')}}
                                </div>
                                <div class="kt-wizard-v3__nav-bar"></div>
                            </div>
                        </a>
                    </div>
                </div>


                <div id="nav-link">
                    <ul class="nav nav-wizard">
                        <li class="active" id="tab1">
                            <a href="javascript:void(0)" >
                                {{__('Chi nhánh')}}
                            </a>
                        </li>
                        <li id="tab2">
                            <a href="javascript:void(0)">{{__('Thời gian')}}</a>
                        </li>
                        <li id="tab3">
                            <a href="javascript:void(0)">{{__('Dịch vụ')}}</a>
                        </li>
                        <li id="tab4">
                            <a href="javascript:void(0)">{{__('Kỹ thuật viên')}}</a>
                        </li>
                        <li id="tab5">
                            <a href="javascript:void(0)">{{__('Thông tin')}}</a>
                        </li>
                        <li id="tab6">
                            <a href="javascript:void(0)">{{__('Xác nhận')}}</a>
                        </li>
                    </ul>
                </div>

                <form class="kt-form" id="kt_form" novalidate="novalidate">

                    <!--begin: Form Wizard Step 1-->
                    @include('bookingweb::booking.step1')

                    <!--end: Form Wizard Step 1-->

                    <!--begin: Form Wizard Step 2-->
                    @include('bookingweb::booking.step2')

                    <!--end: Form Wizard Step 2-->

                    <!--begin: Form Wizard Step 3-->
                    @include('bookingweb::booking.step3')

                    <!--end: Form Wizard Step 3-->

                    <!--begin: Form Wizard Step 4-->
                    @include('bookingweb::booking.step4')


                    <!--end: Form Wizard Step 4-->

                    <!--begin: Form Wizard Step 5-->
                    @include('bookingweb::booking.step5')


                    <!--end: Form Wizard Step 5-->

                    <!--begin: Form Wizard Step 6-->
                    @include('bookingweb::booking.step6')


                    <!--end: Form Wizard Step 6-->

                    <!--begin: Form Actions -->
                    <div class="kt-form__actions kt-clearfix">
                        <div class="float-right">
                            <button type="button" class="btn btn-secondary " data-ktwizard-type="action-prev">
                                <i class="la la-angle-left"></i>{{__('QUAY LẠI')}}
                            </button>
                            <div class="btn color-button btn-sm  btn-tall btn-wide kt-font-bold kt-font-transform-u"
                                 data-ktwizard-type="action-submit">
                                Đặt lịch
                            </div>
                            <button type="button" class="btn color-button"  data-ktwizard-type="action-submit">
                                {{__('ĐẶT LỊCH')}} <i class="la la-check"></i>
                            </button>

                            <button type="button" class="btn color-button" data-ktwizard-type="action-next">
                                {{__('TIẾP THEO')}} <i class="la la-angle-right"></i>
                            </button>
                        </div>

                    </div>

                    <!--end: Form Actions -->
                </form>


                <!--end: Form Wizard Nav -->
            </div>
        </div>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/booking-template/css/custom.css')}}">
@endsection
@section('after_script')
    <script src="{{asset('static/booking-template/js/booking/script.js?v='.time())}}" type="text/javascript">
    </script>
@stop