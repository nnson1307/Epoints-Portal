<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<script src="{{asset('static/backend/js/on-call/popup-calling/script.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>


<!-- begin::Head -->

<head>
    @yield('head_data')
    <meta charset="utf-8"/>
    <title>
        {{isset(config()->get('config.text_login')->value) ? config()->get('config.text_login')->value : __('Epoints Platform')}}
    </title>
    <meta name="description" content="Creative portlet examples">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Web font -->
    <script src="{{asset('static/backend/js/')}}/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Roboto:300,400,500,600,700"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->
    <!--begin:: Global Mandatory Vendors -->
    <link href="{{asset('vendors/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css"/>
    <!--end:: Global Mandatory Vendors -->
    <!--begin:: Global Optional Vendors -->
    <link href="{{asset('vendors/tether/dist/css/tether.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('vendors/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('vendors/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('vendors/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('vendors/bootstrap-select/dist/css/bootstrap-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/nouislider/distribute/nouislider.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/owl.carousel/dist/assets/owl.carousel.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/owl.carousel/dist/assets/owl.theme.default.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/ion-rangeslider/css/ion.rangeSlider.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/ion-rangeslider/css/ion.rangeSlider.skinFlat.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('vendors/dropzone/dist/dropzone.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/summernote/dist/summernote.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/animate.css/animate.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/toastr/build/toastr.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/jstree/dist/themes/default/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/chartist/dist/chartist.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/vendors/line-awesome/css/line-awesome.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/vendors/flaticon/css/flaticon.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/vendors/metronic/css/styles.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('vendors/vendors/fontawesome5/css/all.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!--end:: Global Optional Vendors -->
    <!--begin::Global Theme Styles -->
    <link href="{{asset('static/backend/assets/demo/base/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
<!--RTL version:<link href="{{asset('static/backend/assets/demo/base/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />-->

    <!-- Menu mobile -->
    <link href="{{asset('vendors/menu-hc/hc-offcanvas-nav.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('static/backend/css/customize-hc-menu.css')}}" rel="stylesheet" type="text/css"/>

    <!--end::Global Theme Styles -->
    <link rel="shortcut icon"
          href="{{isset(config()->get('config.short_logo')->value) ? config()->get('config.short_logo')->value : ''}}"/>
    <link href="{{asset('static/backend/css/customize.css')}}" rel="stylesheet" type="text/css"/>


    {{--    @if (isset(config()->get('config.script_header')->value))--}}
    {{--        {!! config()->get('config.script_header')->value !!}--}}
    {{--    @endif--}}

    <style>
        .notification {
            position: absolute;
            top: 10px;
            right: 5px;
            border-radius: 32px;
            height: 16px;
            width: 16px;
            text-align: center;
        }

        .notification-badge {
            color: red;
            font-weight: 500;
        }

        .notification__items {
            height: 64px;
        }

        .notification__items__unread {
            background-color: #dff7f8;
            height: 64px;
        }

        .list-noti__text {
            display: table-cell;
            text-align: left;
            vertical-align: middle;
            width: 100%;
            padding: 0 5px 0 0;
            font-size: 1rem;
        }

        .list-noti__time {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
            width: 80px;
            padding: 0 0 0 5px;
            font-size: 0.85rem;
        }

        .unread {
            color: cornflowerblue !important;
        }

        .m-timeline-3 .m-timeline-3__item {
            margin-bottom: 0;
            padding: 5px;
        }

        .m-timeline-3 .m-timeline-3__item :hover {
            width: 100%;
            height: 100%;
            background-color: #eaecf2;
        }

        .m-timeline-3 .m-timeline-3__item .m-timeline-3__item-desc {
            padding-left: 0;
            cursor: pointer;
        }

        .time_noti {
            font-size: 0.85rem;
            text-decoration: none;
            color: #c4c5d6
        }

        .blockUI {
            z-index: 1051 !important;
        }
    </style>
    @yield('after_style')
    @yield('after_css')

    {{--    <script src="https://browser.sentry-cdn.com/6.16.1/bundle.tracing.min.js"--}}
    {{--            integrity="sha384-hySah00SvKME+98UjlzyfP852AXjPPTh2vgJu26gFcwTlZ02/zm82SINaKTKwIX2" crossorigin="anonymous">--}}
    {{--    </script>--}}
    {{--    <script>--}}
    {{--        Sentry.init({--}}
    {{--            dsn: "{{env('SENTRY_LARAVEL_DSN')}}",--}}
    {{--            environment: "{{env('APP_ENV')}}",--}}

    {{--            // Set tracesSampleRate to 1.0 to capture 100%--}}
    {{--            // of transactions for performance monitoring.--}}
    {{--            // We recommend adjusting this value in production--}}
    {{--            tracesSampleRate: 1.0,--}}

    {{--        });--}}
    {{--    </script>--}}
    @if(session()->has('brand_code') && in_array(session()->get('brand_code'),['vsetcom']))
        <link href="{{asset('static/backend/css/'.session()->get('brand_code').'.css')}}" rel="stylesheet"
              type="text/css"/>
    @endif
</head>
<!-- end::Head -->
<!-- begin::Body -->

<body
        class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-light m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default m-aside--offcanvas-default m-page--fluid m-brand--minimize m-aside-left--minimize  ">
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <!-- BEGIN: Header -->
<!-- END: Header -->

    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body" style="padding: 0!important;">
    <!-- END: Left Aside -->
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <!-- BEGIN: Subheader -->
        {{--            @include('components.inc.sub-header')--}}
        @yield('sub-header')
        <!-- END: Subheader -->
            <div class="m-content" id="div-loading" style="padding: 0!important;">
            @include('components.flash-message')
            @yield('content')

            {{--  --}}
            <!-- Button to Open the Modal -->
                {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
Open modal
</button>
--}}
                {{--  --}}
            </div>
        </div>
    </div>
    <!-- end:: Body -->
@yield("modal_section")
<!-- begin::Footer -->
    <div id="show-modal-checkin"></div>
@include('components.inc.footer')
<!-- end::Footer -->
</div>
<!-- end:: Page -->
<style>
    .oncall-icon-css {
        background-color: #555;
        border-radius: 50%;
        color: white;
        border: none;
        cursor: pointer;
        opacity: 0.8;
        bottom: 23px;
        right: 28px;
        width: 55px;
        height: 55px;
    }

    .oncall-ul {
        position: fixed;
        bottom: 0;
        right: 0;
    }

    .oncall-li {
        list-style-type: none;
        padding: 4px 30px 10px 0px;
    }

    .icon-css-hover {
        position: relative;
        bottom: 30px;
        left: 0px;
        visibility: hidden;
    }

    .oncall-li:hover .icon-css-hover {
        visibility: visible;
    }

    .oncall-button-window {
        overflow: hidden;
        position: relative;
        border: none;
        padding: 0;
        width: 2em;
        height: 2em;
        background: transparent;
        color: #c4c5d6;
        cursor: pointer;
    }
</style>
<div class="oncall-layout">
    <div class="oncall-append-li">
        <ul class="oncall-ul">
        </ul>
    </div>
</div>
@include('on-call::on-calling.template.icon-popup-messenger')
<!-- begin::Scroll Top -->
<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>

<!-- end::Scroll Top -->

<!--begin:: Global Mandatory Vendors -->
<script src="{{asset('vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/mylib/helper.js')}}" type="text/javascript"></script>
{{-- <script src="{{asset('static/backend/js/mylib/translate.js')}}" type="text/javascript"></script> --}}
<script>
    $(document).ready(function () {

        if(!localStorage.getItem('tranlate')){

            $.getJSON(laroute.route('translate'), function (json) {
                localStorage.setItem('tranlate', JSON.stringify(json));
            });

        }
    });


</script>
<script>
    const brand_code = '{{session()->get('brand_code')}}';
    // $(document).ready(function () {

    //     translate._init();
    // });

</script>
@if(session()->has('brand_code') && in_array(session()->get('brand_code'),['vsetcom']))
    <script>
        // doi mau all
        function colorReplace(findHexColor, replaceWith) {
            // Convert rgb color strings to hex
            // REF: https://stackoverflow.com/a/3627747/1938889
            function rgb2hex(rgb) {
                if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;
                rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

                function hex(x) {
                    return ("0" + parseInt(x).toString(16)).slice(-2);
                }

                return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
            }

            // Select and run a map function on every tag
            $('*').map(function (i, el) {
                // Get the computed styles of each tag
                var styles = window.getComputedStyle(el);

                // Go through each computed style and search for "color"
                Object.keys(styles).reduce(function (acc, k) {
                    var name = styles[k];
                    var value = styles.getPropertyValue(name);
                    if (value !== null && name.indexOf("color") >= 0) {
                        // Convert the rgb color to hex and compare with the target color
                        if (value.indexOf("rgb(") >= 0 && rgb2hex(value) === findHexColor) {
                            // Replace the color on this found color attribute
                            // console.log(name);
                            // $(el).css(name, replaceWith);
                            addStyleAttribute($(el), name + ' : ' + replaceWith);
                        }
                    }
                });
            });
        }

        function addStyleAttribute($element, styleAttribute) {
            $element.attr('style', $element.attr('style') + '; ' + styleAttribute);
        }

        @if(session() -> get('brand_code') == 'matthewsliquor')
        // Call like this for each color attribute you want to replace
        colorReplace("#4fc4cb", "#fcc818!important");
        colorReplace("#c4c5d6", "#fcc818!important");
        colorReplace("#4fc4ca", "#fcc818!important");
        @else
        // Call like this for each color attribute you want to replace
        colorReplace("#4fc4cb", "#125a32!important");
        colorReplace("#c4c5d6", "#125a32!important");
        colorReplace("#4fc4ca", "#125a32!important");
        @endif
    </script>
@endif


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
<script src="{{asset('static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}" type="text/javascript">
</script>
<script src="{{asset('vendors/bootstrap-datepicker/js/locales/bootstrap-datepicker.vi.js')}}"
        type="text/javascript"></script>

<script src="{{asset('vendors/js/framework/components/plugins/forms/bootstrap-datepicker.init.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}" type="text/javascript">
</script>
<script src="{{asset('vendors/js/framework/components/plugins/forms/bootstrap-timepicker.init.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/forms/bootstrap-daterangepicker.init.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js')}}" type="text/javascript">
</script>
<script src="{{asset('vendors/bootstrap-maxlength/src/bootstrap-maxlength.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-switch/dist/js/bootstrap-switch.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/forms/bootstrap-switch.init.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js')}}"
        type="text/javascript"></script>
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
<script src="{{asset('vendors/js/framework/components/plugins/forms/jquery-validation.init.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/base/bootstrap-notify.init.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/toastr/build/toastr.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jstree/dist/jstree.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/raphael/raphael.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/chartist/dist/chartist.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/chart.js/dist/Chart.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/charts/chart.init.js')}}" type="text/javascript">
</script>
<script src="{{asset('vendors/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js')}}"
        type="text/javascript"></script>
<script src="{{asset('vendors/vendors/jquery-idletimer/idle-timer.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/waypoints/lib/jquery.waypoints.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/counterup/jquery.counterup.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/es6-promise-polyfill/promise.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/base/sweetalert2.init.js')}}" type="text/javascript">
</script>
<script src="{{asset('vendors/inputmask/dist/jquery.inputmask.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/inputmask/dist/inputmask/inputmask.date.extensions.js')}}" type="text/javascript">
</script>
<script src="{{asset('vendors/inputmask/dist/inputmask/inputmask.numeric.extensions.js')}}" type="text/javascript">
</script>

<!-- Menu mobile -->
<script src="{{asset('vendors/menu-hc/hc-offcanvas-nav.js')}}" type="text/javascript"></script>

<!--end:: Global Optional Vendors -->
<!--bein::Page Vendors -->
<script src="{{asset('static/backend/assets/demo/base/scripts.bundle.js')}}" type="text/javascript"></script>
<!--end::Page Vendors -->
<!--begin::Global Theme Bundle -->
<script src="{{asset('static/backend/js/mylib/table-manager.js')}}" type="text/javascript"></script>

<!--end::Global Theme Bundle -->

{{--<script src="{{asset('lang/test.js')}}" type="text/javascript"></script>--}}

<script type="text/javascript">

    var tenant_id = '{{session('idTenant ')}}';
    $(window).on('load', function () {
        $('body').removeClass('m-page--loading');
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // var myEl = document.getElementById('m_aside_left_minimize_toggle');
    //
    // myEl.addEventListener('click', function () {
    //
    //     var lib = $('.m-page--fluid').hasClass('m-brand--minimize m-aside-left--minimize');
    //
    //
    //     localStorage.clear();
    //     if (lib) {
    //         localStorage['mtoggle'] = 'true'; // only strings
    //
    //     } else {
    //         localStorage['mtoggle'] = 'false'; // only strings
    //     }
    //
    // }, false);
    //
    // $(function () {
    //     var mtoggle = localStorage['mtoggle'];
    //
    //     if (mtoggle == 'false') {
    //         $('.m-page--fluid').removeClass('m-brand--minimize m-aside-left--minimize');
    //     } else if (mtoggle == 'true') {
    //         $('.m-page--fluid').addClass('m-brand--minimize m-aside-left--minimize');
    //     }
    //
    // });
    $('.ss--select-2').select2();
</script>
<script type="text/javascript">
    $(document).ajaxStart(function () {
        mApp.block("#div-loading", {
            overlayColor: "#000000",
            type: "loader",
            state: "success",
            message: "Loading..."
        });
    });
    $(document).ajaxStop(function () {
        mApp.unblock("#div-loading");
    });
    $(document).ajaxError(function () {
        mApp.unblock("#div-loading");
    });

    $('#left-nav').hcOffcanvasNav({
        insertClose: true,
        insertBack: true,
        labelClose: '',
        labelBack: '',
        levelTitleAsBack: true,
        levelOpen: 'expand',
        customToggle: $('#clickne')
    });

    var $navmain = $('#main-nav').hcOffcanvasNav({
        insertClose: true,
        insertBack: true,
        labelClose: '',
        labelBack: '',
        levelTitleAsBack: true,
        levelOpen: 'expand',
        customToggle: $('#toggle-main-nav'),
        closeOnClick: true
    });

    $('#info-nav').hcOffcanvasNav({
        insertClose: true,
        insertBack: true,
        labelClose: '',
        labelBack: '',
        levelTitleAsBack: true,
        levelOpen: 'expand',
        customToggle: $('#info-user-menu')
    });

    $('#hc-nav-2 li.nav-item.nav-parent > div > a').click(function () {
        var par = $(this).closest('li');
        $('.nav-parent.level-open').not(par).each(function () {
            $(this).removeClass('level-open');
            $(this).find('.hc-chk').first().attr('checked', false);
        });
    });
</script>
<script src="{{asset('static/backend/js/admin/layout/script.js?v='.time())}}" type="text/javascript"></script>
{{--<script type="text-template" id="tpl-notification">--}}
{{--    <div class="m-list-timeline__item" onclick="notification.updateStatus(this, {id_noti})">--}}
{{--        <span class="m-list-timeline__badge m-list-timeline__badge--state1-success"></span>--}}
{{--        <a href="#" class="m-list-timeline__text txt_noti {is_read}">{txt_noti}</a>--}}
{{--        {dot_noti}--}}
{{--        <span class="m-list-timeline__time time_noti">{time_noti}</span>--}}
{{--    </div>--}}
{{--</script>--}}
<script type="text-template" id="tpl-notification">
    <div class="m-timeline-3__item" onclick="notification.updateStatus(this, {id_noti})">
        <div class="m-timeline-3__item-desc">
            <span class="m-timeline-3__item-text txt_noti {is_read}">
                <strong>{txt_noti}</strong>
            </span>
            <br>
            <span class="m-timeline-3__item-text txt_noti1">
                {txt_content_noti}
            </span>
            <br>
            <span class="m-timeline-3__item-user-name time_noti">
                {time_noti}
            </span>
            <hr>
        </div>
    </div>
</script>

<div id="nhandt-my-modal-oncall">

</div>
{{--<script src="{{asset('static/backend/js/shift/Attendances/list.js')}}" type="text/javascript"></script>--}}
{{--<audio src="{{asset('static/backend/mp3/notify.mp3')}}" id="my_audio" loop="loop"
autoplay="autoplay"></audio>--}}
<style>
    .select-unset_arrow::-ms-expand {
        display: none !important;
    }

    .select-unset_arrow {
        -webkit-appearance: none;
        -moz-appearance: none;
        text-indent: 1px;
        text-overflow: '';
    }

    @media (min-width: 992px) {
        .modal-big {
            max-width: 1200px;
        }
    }
</style>
@yield('after_script')
<script>
    // $(document).ready(function () {
    //     $('#m_ver_menu').animate({
    //         scrollTop: $(".active").offset().top - 620
    //     }, 800);
    // });
    notification._init();
</script>
<script>
    $('.select2.select2-active').each(function () {
        let placeholder_value = $(this).find("option:first").text() != undefined ? $(this).find("option:first")
            .text() : "{{__('Vui lòng chọn')}}";
        $(this).select2({
            placeholder: {
                id: '',
                text: placeholder_value
            },
        });
    });
    $('.select2.select2-active-choose-first').each(function () {
        let placeholder_value = $(this).find("option:first").text() != undefined ? $(this).find("option:first")
            .text() : "{{__('Vui lòng chọn')}}";
        $(this).select2({
            placeholder: {
                minimumResultsForSearch: -1,
                text: placeholder_value
            },
        });
    });
    $('.btn-clear-form').click(function () {
        $(this).closest('form')[0].reset();
        $(this).closest('form').find('[name="page"]').val('');
        $(this).closest('form').find('.select2-active').val("").trigger("change");
    });
    $(document).on('click', '.coppy_button', function () {
        let $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(this).text()).select();
        document.execCommand("copy");
        $temp.remove();
        toastr.success("Sao chép thành công", "Thông báo");
    })
</script>
@if(in_array('popup-care-oncall', session()->get('routeList')))
    <script src="{{asset('static/backend/js/')}}/socket.io.min.js"
            integrity="sha384-1fOn6VtTq3PWwfsOrk45LnYcGosJwzMHv+Xh/Jx5303FVOXzEnw0EpLv30mtjmlj" crossorigin="anonymous">
    </script>
    <script>
        var socket = io.connect('{{ENV('SOCKET_URL')}}', {transports: ['websocket']});
        // nhận sự kiện thread từ server
        socket.on('transport', function (data) {
            var decimal_number_layout = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
            let dataArray = JSON.parse(data);
            // lấy nhân viên nhận cuộc gọi
            let staffId = dataArray.dataExtension.staff_id;
            if (staffId != null && staffId != '') {
                // lấy brand của cuộc gọi tới
                let brandCode = dataArray.dataCustomer.brand_code;
                if (staffId == {{Auth()->id()}} && brandCode == '{{session()->get('brand_code')}}') {
                    // getModal(dataArray);
                    setTimeout(layout.getModal(dataArray), 1000);
                } else {
                    console.log('login khác')
                }
            }
        });

        function oncallChangeCustomerType(e) {
            if ($(e).val() == 'personal') {
                $('.oncall-open-business-input').attr('hidden', true);
            } else {
                $('.oncall-open-business-input').removeAttr('hidden');
            }
        }





    </script>
@endif
<!--end::Page Scripts -->
</body>

</html>

