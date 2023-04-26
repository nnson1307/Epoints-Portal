<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>@lang('Piospa | Cung cấp giải pháp công nghê quản lý dành cho spa')</title>
    <meta name="description" content="Creative portlet examples" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!--begin::Web font -->
    <script src="{{asset('static/backend/js/')}}/webfont.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Roboto:300,400,500,600,700"] },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <!--end::Web font -->
    <!--begin:: Global Mandatory Vendors -->
    <link href="{{asset('vendors/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
    <!--end:: Global Mandatory Vendors -->
    <!--begin:: Global Optional Vendors -->
    <link href="{{asset('vendors/tether/dist/css/tether.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/bootstrap-select/dist/css/bootstrap-select.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/nouislider/distribute/nouislider.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/owl.carousel/dist/assets/owl.carousel.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/owl.carousel/dist/assets/owl.theme.default.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/ion-rangeslider/css/ion.rangeSlider.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/ion-rangeslider/css/ion.rangeSlider.skinFlat.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/dropzone/dist/dropzone.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/summernote/dist/summernote.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/animate.css/animate.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/jstree/dist/themes/default/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/chartist/dist/chartist.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/vendors/line-awesome/css/line-awesome.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/vendors/flaticon/css/flaticon.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/vendors/metronic/css/styles.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/vendors/fontawesome5/css/all.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.min.css')}}" rel="stylesheet" type="text/css" />
    <!--end:: Global Optional Vendors -->
    <!--begin::Global Theme Styles -->
    <link href="{{asset('static/backend/assets/demo/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
<!--RTL version:<link href="{{asset('static/backend/assets/demo/base/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />-->

    <!--end::Global Theme Styles -->
    <link rel="shortcut icon" href="{{isset(config()->get('config.short_logo')->value) ? config()->get('config.short_logo')->value : ''}}" />
    <link href="{{asset('static/backend/css/customize.css')}}" rel="stylesheet" type="text/css" />
    @yield('after_style') @yield('after_css')
</head>
<!-- end::Head -->
<!-- begin::Body -->
<body
        class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-light m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default m-aside--offcanvas-default m-page--fluid m-brand--minimize m-aside-left--minimize"
>
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <!-- BEGIN: Header -->
@include('components.inc.header')
<!-- END: Header -->

    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        <!-- BEGIN: Left Aside -->
    @include('components.inc.left-menu')
    <!-- END: Left Aside -->
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <!-- BEGIN: Subheader -->
        {{-- @include('components.inc.sub-header')--}} @yield('sub-header')
        <!-- END: Subheader -->
            <div class="m-content">
            @include('components.flash-message')
            <!-- menu  -->
                <div class="m-content">
                    <div id="m-dashbroad">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="m-portlet m-portlet--head-sm">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <h3 class="m-portlet__head-text pt-3">
                                                    <strong>Schedule for 7 next days</strong>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="row mx-auto text-center">
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-4 col-6 pt-3 pb-3 mt-3 mb-3">
                                                <a href="#" class="nt-icon-menu">
                                                    <div class="icon-logo">
                                                        <object type="image/svg+xml" data="{{asset('static/backend/images/menu/svg/hand.svg')}}" class="icon-arrow">
                                                        </object>
                                                        <h5 class="name-menu p-2">Thẻ đã bán</h5>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- menu  -->
            </div>
        </div>
    </div>
    <!-- end:: Body -->
@yield("modal_section")
<!-- begin::Footer -->
@include('components.inc.footer')
<!-- end::Footer -->
</div>
<!-- end:: Page -->

<!-- begin::Scroll Top -->
<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>

<!-- end::Scroll Top -->

<!--begin:: Global Mandatory Vendors -->
<script src="{{asset('vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/moment/min/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/perfect-scrollbar/dist/perfect-scrollbar.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/wnumb/wNumb.js')}}" type="text/javascript"></script>
<script src="{{asset('js/laroute.js?v='.time())}}" type="text/javascript"></script>

<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
<script src="{{asset('vendors/jquery.repeater/src/lib.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery.repeater/src/jquery.input.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery.repeater/src/repeater.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/block-ui/jquery.blockUI.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-datepicker/js/locales/bootstrap-datepicker.vi.js')}}" type="text/javascript"></script>

<script src="{{asset('vendors/js/framework/components/plugins/forms/bootstrap-datepicker.init.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/forms/bootstrap-timepicker.init.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/forms/bootstrap-daterangepicker.init.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-maxlength/src/bootstrap-maxlength.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-switch/dist/js/bootstrap-switch.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/forms/bootstrap-switch.init.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-select/dist/js/bootstrap-select.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/select2/dist/js/select2.full.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/typeahead.js/dist/typeahead.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/handlebars/dist/handlebars.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/nouislider/distribute/nouislider.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/owl.carousel/dist/owl.carousel.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/autosize/dist/autosize.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/clipboard/dist/clipboard.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/ion-rangeslider/js/ion.rangeSlider.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/dropzone/dist/dropzone.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/summernote/dist/summernote.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery-validation/dist/jquery.validate.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery-validation/dist/additional-methods.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/forms/jquery-validation.init.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/base/bootstrap-notify.init.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jstree/dist/jstree.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/raphael/raphael.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/chartist/dist/chartist.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/chart.js/dist/Chart.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/charts/chart.init.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/vendors/jquery-idletimer/idle-timer.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/waypoints/lib/jquery.waypoints.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/counterup/jquery.counterup.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/es6-promise-polyfill/promise.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/base/sweetalert2.init.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/inputmask/dist/jquery.inputmask.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/inputmask/dist/inputmask/inputmask.date.extensions.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/inputmask/dist/inputmask/inputmask.numeric.extensions.js')}}" type="text/javascript"></script>
<!--end:: Global Optional Vendors -->
<!--bein::Page Vendors -->
<script src="{{asset('static/backend/assets/demo/base/scripts.bundle.js')}}" type="text/javascript"></script>
<!--end::Page Vendors -->
<!--begin::Global Theme Bundle -->
<script src="{{asset('static/backend/js/mylib/table-manager.js')}}" type="text/javascript"></script>
<!--end::Global Theme Bundle -->

{{--<script src="{{asset('lang/test.js')}}" type="text/javascript"></script>--}}

<script type="text/javascript">
    $(window).on("load", function () {
        $("body").removeClass("m-page--loading");
    });
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var myEl = document.getElementById("m_aside_left_minimize_toggle");

    myEl.addEventListener(
        "click",
        function () {
            var lib = $(".m-page--fluid").hasClass("m-brand--minimize m-aside-left--minimize");

            localStorage.clear();
            if (lib) {
                localStorage["mtoggle"] = "true"; // only strings
            } else {
                localStorage["mtoggle"] = "false"; // only strings
            }
        },
        false
    );

    $(function () {
        var mtoggle = localStorage["mtoggle"];

        if (mtoggle == "false") {
            $(".m-page--fluid").removeClass("m-brand--minimize m-aside-left--minimize");
        } else if (mtoggle == "true") {
            $(".m-page--fluid").addClass("m-brand--minimize m-aside-left--minimize");
        }
    });
    $(".ss--select-2").select2();
</script>
<script src="{{asset('static/backend/js/admin/layout/script.js')}}" type="text/javascript"></script>
@yield('after_script')
<script>
    $(document).ready(function () {
        $("#m_ver_menu").animate(
            {
                scrollTop: $(".active").offset().top - 620,
            },
            800
        );
    });
</script>
<!--end::Page Scripts -->
</body>
</html>
