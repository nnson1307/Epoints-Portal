<!DOCTYPE html>

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 7
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

<!-- begin::Head -->
<head>

    <!--begin::Base Path (base relative path for assets of this page) -->
    <base href="../">

    <!--end::Base Path -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- COMMON TAGS -->
    <meta charset="utf-8">
    <title>@lang('Epoints Platform')</title>
    <!-- Search Engine -->
    <meta name="description" content="">
    <meta name="image" content="https://spa.piotech.biz/static/booking-template/image/Layer6.jpg">
    <!-- Schema.org for Google -->
    <meta itemprop="name" content="@lang('Epoints Platform')">
    <meta itemprop="description" content="">
    <meta itemprop="image" content="https://spa.piotech.biz/static/booking-template/image/Layer6.jpg">
    <!-- Open Graph general (Facebook, Pinterest & Google+) -->
    <meta name="og:title" content="@lang('Epoints Platform')">
    <meta name="og:description" content="">
    <meta name="og:image" content="https://spa.piotech.biz/static/booking-template/image/home.png">
    <meta name="og:url" content="https://spa.piotech.biz">
    <meta name="og:site_name" content="@lang('Epoints Platform')">
    <meta name="og:locale" content="vi_Vi">
    <meta name="og:type" content="website">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!--end::Fonts -->


    <link href="{{asset('static/booking-template/assets/css/demo1/pages/general/wizard/wizard-3.css')}}" rel="stylesheet"
          type="text/css"/>
    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{asset('static/booking-template')}}/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css"
          rel="stylesheet" type="text/css"/>

    <!--end::Page Vendors Styles -->

    <!--begin:: Global Mandatory Vendors -->
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css"
          rel="stylesheet" type="text/css"/>

    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/tether/dist/css/tether.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/select2/dist/css/select2.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/nouislider/distribute/nouislider.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/summernote/dist/summernote.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/animate.css/animate.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/toastr/build/toastr.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/morris.js/morris.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/sweetalert2/dist/sweetalert2.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/socicon/css/socicon.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/custom/vendors/line-awesome/css/line-awesome.css"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css"
          rel="stylesheet" type="text/css"/>

    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{asset('static/booking-template')}}//assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css"/>

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{asset('static/booking-template')}}//assets/css/demo1/skins/header/base/light.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/css/demo1/skins/header/menu/light.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/css/demo1/skins/brand/dark.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('static/booking-template')}}//assets/css/demo1/skins/aside/dark.css" rel="stylesheet"
          type="text/css"/>

    <!--end::Layout Skins -->
    <link rel="shortcut icon"
          href="{{isset(config()->get('config.short_logo')->value) ? config()->get('config.short_logo')->value : ''}}"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    @yield('after_style')
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body>

<!-- begin:: Page -->
<div class="container">
    <!-- Navigation -->
    <header>

    </header>
    <!-- Hero Section -->

{{--   @include('bookingweb::inc.header')--}}
    <div id="div-banner-slider"></div>
    <!-- About Section -->
    <div class="row content">
        @include('bookingweb::inc.menu')
        <div class="col-lg-6">
            <div class="form-group info-spa" id="name-spa">
            </div>
            @yield('content')
        </div>
        <div class="form-group col-lg-3" id="div-info" >

        </div>

    </div>
    <img src="{{asset('/static/booking-template/image/scroll_top.png')}}" id="scroll-top-btn" >
    <!-- Stats Gallery Section -->


</div>
@include('bookingweb::inc.footer')


<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
</script>

<!-- end::Global Config -->

@include('bookingweb::inc.script')

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#scroll-top-btn").click(function() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });
</script>

<script src="{{asset('js/laroute.js')}}" type="text/javascript"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6U984HVhOs-y7ntfHOBTezNii-iVe-14"></script>
<script src="{{asset('static/booking-template/js/booking/banner-slider.js?v='.time())}}" type="text/javascript"></script>
<script src="{{asset('static/booking-template/js/booking/info.js?v='.time())}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
@yield('after_script')
<!--end::Page Scripts -->
</body>

<!-- end::Body -->
</html>