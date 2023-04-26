<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', isset(config()->get('config.text_login')->value) ? config()->get('config.text_login')->value : __('Epoints Platform'))</title>

    <!--begin::Web font -->
    <script src="{{asset('static/backend/js/')}}/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Quicksand:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;

               
            }
        });
    </script>
    <!--end::Web font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!--begin:: Global Mandatory Vendors -->
    <link href="{{asset('vendors/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css"/>
    <!--end:: Global Mandatory Vendors -->
    <!--begin:: Global Optional Vendors -->
    <link href="{{asset('vendors/tether/dist/css/tether.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/owl.carousel/dist/assets/owl.theme.default.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/dropzone/dist/dropzone.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/animate.css/animate.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/vendors/line-awesome/css/line-awesome.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/vendors/flaticon/css/flaticon.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/vendors/metronic/css/styles.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/vendors/fontawesome5/css/all.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css"/>
    <!--end:: Global Optional Vendors -->
    <!--begin::Global Theme Styles -->
    <link href="{{asset('static/backend/assets/demo/base/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
<!--RTL version:<link href="{{asset('static/backend/assets/demo/base/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />-->
    <!--Base Styles -->
    <link href="{{asset('static/backend/assets/demo/base/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/backend/css/login.css?key='. time())}}" rel="stylesheet" type="text/css"/>
    @if(session()->has('brand_code') && session()->get('brand_code') == 'vsetcom')
        <link href="{{asset('static/backend/css/vsetcom.css')}}" rel="stylesheet" type="text/css"/>
    @endif
    <link rel="shortcut icon"
          href="{{isset(config()->get('config.short_logo')->value) ? config()->get('config.short_logo')->value : ''}}"/>
    @yield('after_style')

    {{--        <script src="https://browser.sentry-cdn.com/6.16.1/bundle.tracing.min.js"--}}
    {{--                integrity="sha384-hySah00SvKME+98UjlzyfP852AXjPPTh2vgJu26gFcwTlZ02/zm82SINaKTKwIX2"--}}
    {{--                crossorigin="anonymous"></script>--}}
    {{--        <script>--}}
    {{--            Sentry.init({--}}
    {{--                dsn: "{{env('SENTRY_LARAVEL_DSN')}}",--}}
    {{--				environment: "{{env('APP_ENV')}}",--}}

    {{--                // Set tracesSampleRate to 1.0 to capture 100%--}}
    {{--                // of transactions for performance monitoring.--}}
    {{--                // We recommend adjusting this value in production--}}
    {{--                tracesSampleRate: 1.0,--}}

    {{--            });--}}
    {{--        </script>--}}
</head>
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

<div class="m-grid m-grid--hor m-grid--root m-page">
    @if(session()->has('brand_code') && session()->get('brand_code') == 'vsetcom')
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2"
             id="m_login"
             style="background-image: url({{asset('static/backend/images/'.session()->get('brand_code').'-login.png')}});">
            <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
                @yield('content')
            </div>
        </div>
    @else
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2"
             id="m_login" style="background-image: url({{asset('static/backend/images/bg-login-while.jpg')}});">
            <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
                @yield('content')
            </div>
        </div>
    @endif
</div>


<!--begin:: Global Mandatory Vendors -->
<script src="{{asset('vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/moment/min/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/perfect-scrollbar/dist/perfect-scrollbar.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/wnumb/wNumb.js')}}" type="text/javascript"></script>
<script src="{{asset('js/laroute.js')}}" type="text/javascript"></script>

<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
<script src="{{asset('vendors/jquery.repeater/src/lib.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery.repeater/src/jquery.input.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery.repeater/src/repeater.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>

<script src="{{asset('vendors/jquery-validation/dist/jquery.validate.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery-validation/dist/additional-methods.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/base/sweetalert2.init.js')}}"
        type="text/javascript"></script>

<script src="{{asset('static/backend/assets/demo/base/scripts.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/login/login.js')}}" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $.getJSON(laroute.route('translate'), function (json) {
                    localStorage.setItem('tranlate', JSON.stringify(json));
                });
    });
</script>
@yield('after_script')
</body>
</html>
