<!DOCTYPE html>
<html lang="vi">

<script src="http://piospa.com.dev.com/static/backend/js/on-call/popup-calling/script.js?v=1665044292"
    type="text/javascript"></script>
<script src="http://piospa.com.dev.com/static/backend/js/admin/service/autoNumeric.min.js"></script>


<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>
        Hệ thống quản trị Epoints
    </title>
    <meta name="description" content="Creative portlet examples">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="fgsd4BN2HtBiQPPAyb6kq1rfqXIevBlID0iNOEwp">
    <!--begin::Web font -->
    <script src="http://piospa.com.dev.com/static/backend/js/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;

            }
        });
    </script>
    <!--end::Web font -->
    <!--begin:: Global Mandatory Vendors -->
    <link href="http://piospa.com.dev.com/vendors/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"
        type="text/css" />
    <!--end:: Global Mandatory Vendors -->
    <!--begin:: Global Optional Vendors -->
    <link href="http://piospa.com.dev.com/vendors/tether/dist/css/tether.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css"
        rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/bootstrap-timepicker/css/bootstrap-timepicker.min.css"
        rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css"
        rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css"
        rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/nouislider/distribute/nouislider.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/ion-rangeslider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/animate.css/animate.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/jstree/dist/themes/default/style.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/chartist/dist/chartist.min.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/vendors/line-awesome/css/line-awesome.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/vendors/flaticon/css/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/vendors/metronic/css/styles.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/vendors/vendors/fontawesome5/css/all.min.css" rel="stylesheet"
        type="text/css" />
    <link href="http://piospa.com.dev.com/static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.min.css"
        rel="stylesheet" type="text/css" />
    <!--end:: Global Optional Vendors -->
    <!--begin::Global Theme Styles -->
    <link href="http://piospa.com.dev.com/static/backend/assets/demo/base/style.bundle.css" rel="stylesheet"
        type="text/css" />
    <!--RTL version:<link href="http://piospa.com.dev.com/static/backend/assets/demo/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

    <!-- Menu mobile -->
    <link href="http://piospa.com.dev.com/vendors/menu-hc/hc-offcanvas-nav.css" rel="stylesheet" type="text/css" />
    <link href="http://piospa.com.dev.com/static/backend/css/customize-hc-menu.css" rel="stylesheet"
        type="text/css" />

    <!--end::Global Theme Styles -->
    <link rel="shortcut icon"
        href="https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/c9bbb5f0772a6c289681448715898108/2021/03/08/Ul9Fm3161516729208032021_config-general.png" />
    <link href="http://piospa.com.dev.com/static/backend/css/customize.css" rel="stylesheet" type="text/css" />






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
    <link rel="stylesheet" type="text/css" href="http://piospa.com.dev.com/static/backend/css/customize.css">
    <link rel="stylesheet" type="text/css" href="http://piospa.com.dev.com/static/backend/css/sinh-custom.css">
    <link rel="stylesheet" type="text/css" href="http://piospa.com.dev.com/static/backend/css/phu-custom.css">
    <style>
        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .fz-10 {
            font-size: 10px;
        }

        #kanban2 .overtime {
            width: 65%;
            padding: 0;
            margin: 0;
            text-align: right;
            margin-left: 35%;
            margin-top: -5px;
            border-top-left-radius: 50px;
            border-bottom-left-radius: 50px;
        }

        .timepicker {
            border: 1px solid rgb(163, 175, 251);
            text-align: center;
            /* display: inline; */
            border-radius: 4px;
            padding: 2px;
            height: 38px;
            line-height: 30px;
            width: 130px;
        }

        .timepicker .hh,
        .timepicker .mm {
            width: 50px;
            outline: none;
            border: none;
            text-align: center;
        }

        .timepicker.valid {
            border: solid 1px springgreen;
        }

        .timepicker.invalid {
            border: solid 1px red;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .custom-remind-item {
            color: #575962 !important;
            border: 1px solid #4bb072 !important;
            position: relative;
        }

        .custom-remind-item strong {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .custom-remind-item button {
            color: #575962 !important;
        }

        .custom-remind-item::before {
            content: '';
            position: absolute;
            left: -1px;
            background: #79cca8;
            width: 9px;
            height: calc(100% + 2px);
            top: -1px;
            /* border-radius: 0px 5px 5px 0px; */
            border-radius: 5px;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        .modal .modal-content .modal-body {
            padding: 25px;
            /*max-height: 400px;*/
            overflow-y: scroll;
        }

        .max-height-400px {
            max-height: 400px;
            overflow-y: scroll;
        }

        .weekDays-selector input {
            display: none !important;
        }

        .weekDays-selector input[type=checkbox]+label {
            display: inline-block;
            border-radius: 6px;
            background: #dddddd;
            height: 40px;
            width: 30px;
            margin-right: 3px;
            line-height: 40px;
            text-align: center;
            cursor: pointer;
        }

        .weekDays-selector input[type=checkbox]:checked+label {
            background: #2AD705;
            color: #ffffff;
        }

        .flex-wrapper {
            display: flex;
            flex-flow: row nowrap;
        }

        .single-chart {
            width: 40px;
            justify-content: space-around;
        }

        .circular-chart {
            display: block;
            margin: 10px auto;
            max-width: 80%;
            max-height: 250px;
        }

        .comment-button {
            cursor: pointer;
        }

        .circle-bg {
            fill: none;
            stroke: #eee;
            stroke-width: 3.8;
        }

        .circle {
            fill: none;
            stroke-width: 2.8;
            stroke-linecap: round;
            animation: progress 1s ease-out forwards;
        }

        @keyframes progress {
            0% {
                stroke-dasharray: 0 100;
            }
        }

        .circular-chart.orange .circle {
            stroke: #ff9f00;
        }

        .circular-chart.green .circle {
            stroke: #4CC790;
        }

        .circular-chart.blue .circle {
            stroke: #3c9ee5;
        }

        .percentage {
            fill: #666;
            font-size: .6em;
            text-anchor: middle;
        }

        /*kabancusstom*/

        .jqx-kanban-column-header {
            margin-right: 15px !important;
            ;
        }

        .jqx-sortable {
            margin-right: 15px !important;
        }

        .jqx-sortable::-webkit-scrollbar {
            display: none !important;
        }

        .jqx-kanban-column {
            border-width: 0 !important;
        }

        .jqx-icon-dot {
            width: 10px !important;
            height: 10px !important;
            display: block;
            border-radius: 50%;
            background-image: none !important;

        }

        #kanban2 {
            width: auto !important;
            /*overflow-x: scroll;*/
            /*overflow-y: hidden;*/
            display: flex;
            height: 800px !important;
        }

        .overfollow-scroll {
            overflow-x: scroll;
        }

        /*.overfollow-scroll::-webkit-scrollbar {*/
        /*display: none;*/
        /*}*/
        .w-50px {
            width: 50px;
        }

        #kanban2 .jqx-window-collapse-button {
            width: 10px !important;
            height: 10px !important;
            display: block;
            border-radius: 50%;
            background-image: none !important;

        }

        .status_work_priority {
            font-size: 10px;
            margin-left: 0px;
            padding: 5px 30px 5px 10px;
            white-space: nowrap;
        }

        #kanban2 .jqx-icon-arrow-left-light {
            width: 10px !important;
            height: 10px !important;
            display: block;
            border-radius: 50%;
            background-image: none !important;

        }

        #kanban2 .jqx-kanban-column-header-collapsed {
            margin: 0 0 0 5px;

        }

        #kanban2 .jqx-kanban-column-header-collapsed-show {
            border-radius: 10px;
        }

        /*#kanban2 .jqx-kanban-column {*/
        /*margin: 0px 2px 0 2px;*/
        /*}*/
        /*#kanban2 .jqx-kanban-column:last-child {*/
        /*margin: 0px -10px 0 2px;*/
        /*}*/

        #kanban2 .jqx-kanban-column-header {
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
        }

        #kanban2 *:not(.fa):not(.far) {
            font-family: 'Roboto' !important;
        }

        #kanban2 .jqx-kanban-item {
            display: flex;
            flex-wrap: wrap;
        }

        #kanban2 .jqx-kanban-column {
            /*min-width: 25%;*/
        }

        #kanban2 .jqx-kanban-column-header-title {
            font-size: 16px;
            font-weight: 600;
        }

        #kanban2 .jqx-kanban-item {
            margin: 15px !important;
            border-radius: 5px;
            background: #fff;
        }

        #kanban2 .jqx-kanban-column-container {
            background: #e8e8e8;
        }

        #kanban2 .custom-process {
            background-color: transparent !important;
            height: auto !important;
            width: auto !important;
            position: relative;
            width: 30% !important;
            order: 2;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
        }

        #kanban2 .jqx-kanban-item-text {
            order: 0;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            width: 70%;
            padding-left: 15px;
            flex-direction: column;
        }

        #kanban2 .jqx-kanban-item-text .title-item-comment {
            font-size: 17px;
            font-weight: 500;
        }

        #kanban2 .jqx-kanban-item-text .sub-title-icon {
            width: 15px;
            height: 15px;
            margin: 0 5px;
        }

        #kanban2 .jqx-kanban-item-footer {
            width: 100%;
            order: 3;
            display: flex;
            justify-content: space-between;
        }

        #kanban2 .jqx-kanban-item-footer .jqx-kanban-item-keyword:first-child {
            width: 20%;
        }

        #kanban2 .jqx-kanban-item-color-status {
            background-color: #6bbd49;
            height: 40% !important;
            left: 5px !important;
            top: 15% !important;
        }

        #kanban2 .jqx-kanban-item-keyword {
            border: 0px solid transparent !important;
            font-size: 12px;
            background: transparent;
            display: flex;
            align-items: center;
            padding-left: 15px;
        }

        /*endkanban*/
        /*comment*/
        .full-width {
            width: 100%;
            height: 100vh;
            display: flex;
        }

        .full-width .justify-content-center {
            display: flex;
            align-self: center;
            width: 100%;
        }

        .full-width .lead.emoji-picker-container {
            width: 300px;
            display: block;
        }

        .full-width .lead.emoji-picker-container input {
            width: 100%;
            height: 50px;
        }

        #kanban2 .avatars_overview__item:not(:first-child) {
            margin-left: -5px !important;
        }

        #kanban2 .title_overdue {
            background: #FDD9D7;
            padding: 10px;
            padding-left: 15px;
            font-weight: 600;
        }

        .jqx-kanban-column {
            margin-right: 10px;
        }

        .status_work_priority_1,
        .work_priority_1,
        .work_priority_bonus,
        .work_priority_kpi {
            background: #f23607 !important;
            background-color: #f23607 !important;
        }

        .status_work_priority_2,
        .work_priority_2,
        .work_priority_bonus,
        .work_priority_kpi {
            background: #1b0ced !important;
            background-color: #1b0ced !important;
        }

        .status_work_priority_3,
        .work_priority_3,
        .work_priority_bonus,
        .work_priority_kpi {
            background: #bbeb0f !important;
            background-color: #bbeb0f !important;
        }

        .status_work_priority_4,
        .work_priority_4,
        .work_priority_bonus,
        .work_priority_kpi {
            background: #000000 !important;
            background-color: #000000 !important;
        }

        .status_work_priority_5,
        .work_priority_5,
        .work_priority_bonus,
        .work_priority_kpi {
            background: #f50bf9 !important;
            background-color: #f50bf9 !important;
        }

        .status_work_priority_6,
        .work_priority_6,
        .work_priority_bonus,
        .work_priority_kpi {
            background: #06d4ef !important;
            background-color: #06d4ef !important;
        }

        .status_work_priority_7,
        .work_priority_7,
        .work_priority_bonus,
        .work_priority_kpi {
            background: #f91aa7 !important;
            background-color: #f91aa7 !important;
        }
    </style>

</head>
<!-- end::Head -->
<!-- begin::Body -->

<body
    class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-light m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default m-aside--offcanvas-default m-page--fluid m-brand--minimize m-aside-left--minimize  ">
    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <!-- BEGIN: Header -->
        <header id="m_header" class="m-grid__item m-header" m-minimize-offset="200" m-minimize-mobile-offset="200">
            <div class="m-container m-container--fluid m-container--full-height">
                <div class="m-stack m-stack--ver m-stack--desktop m_header_nav_nt">

                    <!-- BEGIN: Brand -->
                    <div class="m-stack__item m-brand  m-brand--skin-light ">
                        <div class="m-stack m-stack--ver m-stack--general">
                            <div class="m-stack__item m-stack__item--middle m-brand__logo icon-header-left">



                            </div>
                            <div class="m-stack__item m-stack__item--middle m-brand__tools">
                                <a href="javascript:void(0);" id="clickne" class="icon mobile-menu-handle">
                                    <i class="fa fa-align-right"></i>
                                </a>

                                <a href="javascript:void(0);" id="toggle-main-nav" class="icon mobile-menu-handle">
                                    <i class="fa fa-bars"></i>
                                </a>
                                <!-- BEGIN: Left Aside Minimize Toggle -->
                                <a href="javascript:void(0);" id="info-user-menu"
                                    class="icon mobile-menu-handle icon-menu-info">



                                    <img src="http://piospa.com.dev.com/static/backend/images/menu/icon-admin-mobile.png"
                                        class="m--marginless " alt="" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- END: Brand -->
                    <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

                        <!-- BEGIN: Horizontal Menu -->
                        <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark "
                            id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                        <div id="m_header_menu"
                            class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                            <ul class="m-menu__nav  m-menu__nav--submenu-arrow list-inline">
                                <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel list-inline-item"
                                    m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true">
                                    <div class="m-title-header">
                                        <a class="m-menu__link m-menu__toggle" title="">
                                            <h3 class="m-menu__link-text"> <span class="title_header"><img
                                                        src="http://piospa.com.dev.com/static/backend/images/icon/icon-staff.png"
                                                        alt="" style="height: 20px;">
                                                    QUẢN LÝ CÔNG VIỆC</span>
                                                |</h3>
                                        </a>
                                    </div>
                                </li>
                            </ul>

                        </div>
                        <!-- END: Horizontal Menu -->

                        <!-- BEGIN: Topbar -->
                        <div id="m_header_topbar"
                            class="m-topbar m-stack m-stack--ver m-stack--general m-stack--fluid">
                            <div class="m-stack__item m-topbar__nav-wrapper">
                                <ul class="m-topbar__nav m-nav m-nav--inline">
                                    <li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width"
                                        m-dropdown-toggle="click" m-dropdown-persistent="1" aria-expanded="true">
                                        <div class="notification" style="display: none">
                                            <span class="notification-badge" id="number-noti-new">1</span>
                                            <input type="hidden" id="number-noti-new_hidden" value="">
                                        </div>

                                        <a href="javascript:void(0)" class="m-nav__link m-dropdown__toggle"
                                            id="m_topbar_notification_icon" onclick="notification.loadNotification()">

                                            <span class="m-nav__link-icon"><i class="flaticon-alarm"></i></span>
                                        </a>
                                        <div class="m-dropdown__wrapper" style="z-index: 101;">
                                            <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">
                                                        <div class="m-scrollable m-scroller ps" data-scrollable="true"
                                                            data-height="250" data-mobile-height="200"
                                                            style="height: 250px; overflow: hidden;"
                                                            id="scroll-notify">
                                                            <!--Begin::Timeline 3 -->
                                                            <div class="m-timeline-3">
                                                                <div class="m-timeline-3__items" id="list-notify">

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!--End::Timeline 3 -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                        m-dropdown-toggle="click">
                                        <a href="#" class="m-nav__link m-dropdown__toggle">
                                            <span class="m-topbar__userpic">
                                                <img src="http://piospa.com.dev.com/static/backend/images/menu/icon-admin.png"
                                                    class="m--marginless img-fluid-40" alt="" />
                                            </span>
                                            <span class="m-topbar__username m--padding-right-5 m--padding-left-5">
                                                Admin_dùng thử
                                                <i
                                                    class="m--padding-left-5 m-menu__hor-arrow la la-angle-down"></i></span>
                                        </a>
                                        <div class="m-dropdown__wrapper">
                                            <span
                                                class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">
                                                        <ul class="m-nav m-nav--skin-light">
                                                            <li class="m-nav__section m--hide">
                                                                <span class="m-nav__section-text">Section</span>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="http://piospa.com.dev.com/admin/staff/profile/220"
                                                                    class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                                    <span class="m-nav__link-title">
                                                                        <span class="m-nav__link-wrap">
                                                                            <span class="m-nav__link-text">Thông tin cá
                                                                                nhân</span>
                                                                        </span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__separator m-nav__separator--fit"></li>
                                                            <li class="m-nav__separator m-nav__separator--fit"></li>
                                                            <li class="m-nav__item">
                                                                <a href="http://piospa.com.dev.com/logout"
                                                                    class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                                    Đăng xuất </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- END: Topbar -->

                    </div>
                </div>
            </div>
        </header>

        <!-- END: Header -->

        <!-- begin::Body -->
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
            <!-- BEGIN: Left Aside -->
            <!-- BEGIN: Left Aside -->
            <button class="m-aside-left-close m-aside-left-close--skin-dark" id="m_aside_left_close_btn">
                <i class="la la-close"></i>
            </button>
            <div id="m_aside_left" class="m-grid__item m-aside-left m-aside-left--skin-light">
                <!-- BEGIN: Aside Menu -->
                <div id="m_ver_menu" class="m-aside-menu m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
                    style="position: relative;">
                    <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow m-scroller">
                    </ul>
                    <div class="mx-auto text-center nt-nav">
                        <a href="http://piospa.com.dev.com/admin/menu-all" class="m-menu__link1">
                            <div class="menu-icon m-menu__link-icon">
                                <object type="image/svg+xml" style="pointer-events: none;"
                                    data="http://piospa.com.dev.com/static/backend/images/menu/svg/nav.svg"
                                    class="icon icon-arrow">
                                </object>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- END: Aside Menu -->

                <nav id="left-nav" style="display:none">
                    <ul>
                        <li class="ddt-all-mn">
                            <a href="http://piospa.com.dev.com/admin/menu-all" class="all-menu">&nbsp;</a>
                        </li>
                    </ul>
                </nav>

                <nav id="main-nav" style="display:none">
                    <ul>
                    </ul>
                </nav>

                <nav id="info-nav" style="display:none">
                    <ul>
                        <li>
                            <a href="http://piospa.com.dev.com/admin/staff/profile/220">
                                <object type="image/svg+xml" style="pointer-events: none;"
                                    data="http://piospa.com.dev.com/static/backend/images/menu/user-icon.png"
                                    class="icon icon-arrow">
                                </object>
                                <span class="hc-text-mnt">Thông tin cá nhân</span>
                            </a>
                        </li>
                        <li>
                            <a href="http://piospa.com.dev.com/logout">
                                <object type="image/svg+xml" style="pointer-events: none;"
                                    data="http://piospa.com.dev.com/static/backend/images/menu/logout-icon.png"
                                    class="icon icon-arrow">
                                </object>
                                <span class="hc-text-mnt">Đăng xuất</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <style>
                .chathub_chat .noti-chat {
                    color: #fff;
                    font-weight: bold;
                    font-size: 10px;
                    position: absolute;
                    background-color: red;
                    height: 20px;
                    width: 20px;
                    border-radius: 20px;
                    line-height: 19px;
                    top: 0px;
                    right: -5px;
                }

                .chathub_inbox .noti-chathub {
                    color: #fff;
                    font-weight: bold;
                    font-size: 10px;
                    position: absolute;
                    background-color: red;
                    height: 20px;
                    width: 20px;
                    border-radius: 20px;
                    line-height: 19px;
                    top: 0px;
                    right: -5px;
                }
            </style>
            <script type="text/javascript">
                var appBanners = document.getElementsByClassName("hidecate");
                for (var i = 0; i < appBanners.length; i++) {
                    // appBanners[i].style.display = 'none';
                    const elem = document.getElementsByClassName(appBanners[i].value);
                    while (elem.length > 0) elem[0].remove();
                }
            </script>
            <!-- END: Left Aside aa-->
            <!-- END: Left Aside -->
            <div class="m-grid__item m-grid__item--fluid m-wrapper">
                <!-- BEGIN: Subheader -->

                <!-- END: Subheader -->
                <div class="m-content" id="div-loading">
                    <div class="m-portlet" id="autotable">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon">
                                        <i class="la la-th-list"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        DANH SÁCH DỰ ÁN
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                                <a href="http://piospa.com.dev.com/manager-work/project/edit"
                                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                                    <span>
                                        <i class="fa fa-cog"></i>
                                        <span> TÙY CHỈNH HIỂN THỊ</span>
                                    </span>
                                </a>
                                <a href="http://piospa.com.dev.com/manager-project/project/add"
                                    onclick="WorkChild.showPopup()"
                                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> THÊM CÔNG VIỆC</span>
                                    </span>
                                </a>

                                <a href="javascript:void(0)" onclick="WorkChild.showPopup()"
                                    class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                                    style="display: none">
                                    <i class="fa fa-plus-circle" style="color: #fff"></i>
                                </a>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <form class="frmFilter bg clear-form">
                                <div class="row padding_row">

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="search"
                                                placeholder="Tên dữ án" value="">
                                        </div>
                                    </div>

                                    <div class="col-lg-3">

                                        <div class="form-group">
                                            <select name="assign_by" class="form-control select2 select2-active">
                                                <option value="">Chọn trạng thái</option>
                                                @foreach ($listStatus as $item)
                                                    <option value="{{ $item->manage_status_id }}">
                                                        {{ $item->manage_status_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="processor_id" class="form-control select2 select2-active">
                                                <option value="">{{__('Người quản trị')}}</option>
                                                @foreach ($listStaff as $item)
                                                    <option value="{{ $item->staff_id }}">{{ $item->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-3">

                                        <a  onclick="WorkChild.showPopup()"
                                            class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                            {{__('XOÁ BỘ LỌC')}}
                                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                        </a>

                                        <button class="btn btn-primary color_button btn-search">
                                            TÌM KIẾM <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-content table-content-font-a mt-3">
                                <div class="table-responsive">
                                    <table class="table table-striped m-table m-table--head-bg-default"
                                        id="table-config">
                                        <thead class="bg">
                                            <tr>
                                                <th class="tr_thead_list">#</th>
                                                <th class="tr_thead_list">Hành động</th>
                                                <th class="tr_thead_list text-center">Tên dự án</th>
                                                <th class="tr_thead_list">Người quản trị</th>
                                                <th class="tr_thead_list text-center">Ngày bắt đầu</th>
                                                <th class="tr_thead_list text-center">Ngày hết hạn</th>
                                                <th class="tr_thead_list">Tiến độ</th>
                                                <th class="tr_thead_list">Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size: 13px">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="m-datatable m-datatable--default">
                                    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
                                        <div class="m-datatable__pager-info" style="float: left">

                                            <span class="m-datatable__pager-detail">Hiển thị - của 0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-content mt-3">
                                <div class="d-flex justify-contents-center overfollow-scroll">
                                    <div id="kanban2"></div>
                                </div>
                            </div>
                            <!-- end table-content -->

                        </div>
                    </div>
                    <div class="modal" id="comment-popup">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- The modal comment -->

                        </div>
                    </div>

                    <div class="d-none" id="date-single">
                        <div class="input-group date date-single">
                            <input type="text" class="form-control m-input date-timepicker" readonly
                                placeholder="Ngày hết hạn" name="date_issue">
                            <div class="input-group-append">
                                <span class="input-group-text"><i
                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="date-multiple">
                        <div class="input-group date date-multiple">
                            <input type="text" class="form-control m-input daterange-input" readonly
                                placeholder="Ngày hết hạn" name="date_issue">
                            <div class="input-group-append">
                                <span class="input-group-text"><i
                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal-config" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="config_search">
                                    <div class="modal-header">
                                        <h4 class="modal-title ss--title m--font-bold">
                                            <i class="fa fa-cog ss--icon-title m--margin-right-5"></i>
                                            CẤU HÌNH TÌM KIẾM
                                        </h4>
                                    </div>
                                    <div class="modal-body modal-body-config">
                                        <div class="row m-0">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="search[]" value="1">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Thông tin tìm kiếm</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked disabled
                                                                    type="checkbox" name="search[]" value="2">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Trạng thái</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked disabled
                                                                    type="checkbox" name="search[]" value="3">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Yêu cầu</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked disabled
                                                                    type="checkbox" name="search[]" value="4">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Ngày bắt đầu</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked disabled
                                                                    type="checkbox" name="search[]" value="5">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Ngày hết hạn</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="11">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Tags</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked disabled
                                                                    type="checkbox" name="search[]" value="12">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người thực hiện</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="13">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người hỗ trợ</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="14">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người tạo</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="15">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người duyệt</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="16">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người cập nhật</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="17">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Loại thẻ</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked disabled
                                                                    type="checkbox" name="search[]" value="18">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Dự án</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked disabled
                                                                    type="checkbox" name="search[]" value="19">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Phòng ban</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="20">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Loại công việc</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="21">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Mức độ ưu tiên</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="22">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Ngày hoàn thành</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="23">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Ngày cập nhật</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="search[]" value="24">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Khách hàng</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked disabled
                                                                    type="checkbox" name="search[]" value="25">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Kiểu công việc</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="config_column">
                                    <div class="modal-header">
                                        <h4 class="modal-title ss--title m--font-bold">
                                            <i class="fa fa-cog ss--icon-title m--margin-right-5"></i>
                                            CẤU HÌNH DANH SÁCH
                                        </h4>
                                    </div>
                                    <div class="modal-body modal-body-config">
                                        <div class="row m-0">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="0">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">ID</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="1">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Chức năng</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="2">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Loại công việc</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="3">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Tiêu đề</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="4">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Trạng thái</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="5">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Tiến độ</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="6">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người thực hiện</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="7">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Ngày bắt đầu</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" checked type="checkbox"
                                                                    name="column[]" value="8">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Ngày hết hạn</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="9">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Tag</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="10">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người hỗ trợ</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="11">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người tạo</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="12">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người duyệt</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="13">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Người cập nhật</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="14">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Loại thẻ</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="15">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Mức độ ưu tiên</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="16">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Ngày cập nhật</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="17">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Ngày hoàn thành</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="18">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Khách hàng</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 p-3">
                                                        <div class="ss--font-size-13 text-center">
                                                            <label class="m-checkbox m-checkbox--air">
                                                                <input class="check-page" type="checkbox"
                                                                    name="column[]" value="19">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 p-3">
                                                        <div class="ss--font-size-13">Dự án</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div
                                        class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                                        <div class="m-form__actions m--align-right">
                                            <button data-dismiss="modal"
                                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                                <span class="ss--text-btn-mobi">
                                                    <i class="la la-arrow-left"></i>
                                                    <span>HỦY</span>
                                                </span>
                                            </button>

                                            <button type="button" onclick="ManagerWork.saveConfig()"
                                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                                <span class="ss--text-btn-mobi">
                                                    <i class="la la-check"></i>
                                                    <span>LƯU THÔNG TIN</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="remind-item">
                        <div class="remind-item row m-0">
                            <div
                                class="col-11 alert alert-light m-alert--outline alert-dismissible fade show custom-remind-item">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <strong>{date_remind}</strong>
                                        <input type="hidden" name="processor_id_remind[]"
                                            value="{processor_id_remind}">
                                        <input type="hidden" name="date_remind[]" value="{date_remind}">
                                        <input type="hidden" name="time_remind[]" value="{time_remind}">
                                        <input type="hidden" name="time_type_remind[]" value="{time_type_remind}">
                                        <input type="hidden" name="description_remind[]"
                                            value="{description_remind}">
                                    </div>
                                    <div class="col-lg-9 text-left">
                                        <h4 class="m-0">{description_remind}</h4>
                                        <span>Admin_dùng thử
                                            tạo nhắc nhở cho {processor_name}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 d-flex align-items-center text-right">
                                <button
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-custom-remind-item"
                                    title="Xóa"><i class="la la-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="percent-template">
                        <div class="single-chart percentage-update">
                            <svg viewBox="0 0 36 36" class="circular-chart orange">
                                <path class="circle-bg"
                                    d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="circle" stroke-dasharray="{data}, 100"
                                    d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <text x="18" y="20.35" class="percentage">{data}%</text>
                            </svg>
                        </div>
                    </div>

                    <form id="form-work" autocomplete="off">
                        <div id="append-add-work"></div>
                    </form>
                    <div class="append-popup"></div>


                    <!-- Button to Open the Modal -->


                </div>
            </div>
        </div>
        <!-- end:: Body -->
        <!-- begin::Footer -->
        <div id="show-modal-checkin"></div>
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
    <script type="text/template" id="oncall-icon-popup-messenger-tpl">
    <li class="oncall-li" id="oncall-{phone}" onclick="layout.getModalFromIcon('220', '{history_id}', '{id}', '{type}', '{phone}', 'hoangdatbk')">
        <span class="icon-css-hover">{phone}</span>
        <img class="oncall-icon-css"
             src="{avatar}">
    </li>
</script><!-- begin::Scroll Top -->
    <div id="m_scroll_top" class="m-scroll-top">
        <i class="la la-arrow-up"></i>
    </div>

    <!-- end::Scroll Top -->

    <!--begin:: Global Mandatory Vendors -->
    <script src="http://piospa.com.dev.com/vendors/jquery/dist/jquery.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/static/backend/js/mylib/helper.js" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            if (!localStorage.getItem('tranlate')) {
                $.getJSON(laroute.route('translate'), function(json) {
                    localStorage.setItem('tranlate', JSON.stringify(json));
                });
            }
        });
    </script>
    <script>
        const brand_code = 'hoangdatbk';
        // $(document).ready(function () {

        //     translate._init();
        // });
    </script>


    <script src="http://piospa.com.dev.com/vendors/popper.js/dist/umd/popper.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/js-cookie/src/js.cookie.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/moment/min/moment.min.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/wnumb/wNumb.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/js/laroute.js?v=1665044292" type="text/javascript"></script>

    <!--end:: Global Mandatory Vendors -->


    <!--begin:: Global Optional Vendors -->
    <script src="http://piospa.com.dev.com/vendors/jquery.repeater/src/lib.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/jquery.repeater/src/jquery.input.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/jquery.repeater/src/repeater.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/jquery-form/dist/jquery.form.min.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/block-ui/jquery.blockUI.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-datepicker/js/locales/bootstrap-datepicker.vi.js"
        type="text/javascript"></script>

    <script src="http://piospa.com.dev.com/vendors/js/framework/components/plugins/forms/bootstrap-datepicker.init.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/js/framework/components/plugins/forms/bootstrap-timepicker.init.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/js/framework/components/plugins/forms/bootstrap-daterangepicker.init.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-maxlength/src/bootstrap-maxlength.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-switch/dist/js/bootstrap-switch.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/js/framework/components/plugins/forms/bootstrap-switch.init.js"
        type="text/javascript"></script>
    <script
        src="http://piospa.com.dev.com/vendors/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/select2/dist/js/select2.full.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/typeahead.js/dist/typeahead.bundle.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/handlebars/dist/handlebars.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/nouislider/distribute/nouislider.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/owl.carousel/dist/owl.carousel.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/autosize/dist/autosize.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/clipboard/dist/clipboard.min.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/ion-rangeslider/js/ion.rangeSlider.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/dropzone/dist/dropzone.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/summernote/dist/summernote.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/jquery-validation/dist/jquery.validate.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/jquery-validation/dist/additional-methods.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/js/framework/components/plugins/forms/jquery-validation.init.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/js/framework/components/plugins/base/bootstrap-notify.init.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/toastr/build/toastr.min.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/jstree/dist/jstree.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/raphael/raphael.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/chartist/dist/chartist.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/js/framework/components/plugins/charts/chart.init.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/vendors/jquery-idletimer/idle-timer.min.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/waypoints/lib/jquery.waypoints.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/counterup/jquery.counterup.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/es6-promise-polyfill/promise.min.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/js/framework/components/plugins/base/sweetalert2.init.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript">
    </script>
    <script src="http://piospa.com.dev.com/vendors/inputmask/dist/inputmask/inputmask.date.extensions.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/vendors/inputmask/dist/inputmask/inputmask.numeric.extensions.js"
        type="text/javascript"></script>

    <!-- Menu mobile -->
    <script src="http://piospa.com.dev.com/vendors/menu-hc/hc-offcanvas-nav.js" type="text/javascript"></script>

    <!--end:: Global Optional Vendors -->
    <!--bein::Page Vendors -->
    <script src="http://piospa.com.dev.com/static/backend/assets/demo/base/scripts.bundle.js" type="text/javascript">
    </script>
    <!--end::Page Vendors -->
    <!--begin::Global Theme Bundle -->
    <script src="http://piospa.com.dev.com/static/backend/js/mylib/table-manager.js" type="text/javascript"></script>

    <!--end::Global Theme Bundle -->
    <script src="http://piospa.com.dev.com/static/backend/js/chathub/inbox/index.js?v=1665044292" type="text/javascript">
    </script>


    <script type="text/javascript">
        var tenant_id = '';
        $(window).on('load', function() {
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
        $(document).ajaxStart(function() {
            mApp.block("#div-loading", {
                overlayColor: "#000000",
                type: "loader",
                state: "success",
                message: "Loading..."
            });
        });
        $(document).ajaxStop(function() {
            mApp.unblock("#div-loading");
        });
        $(document).ajaxError(function() {
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

        $('#hc-nav-2 li.nav-item.nav-parent > div > a').click(function() {
            var par = $(this).closest('li');
            $('.nav-parent.level-open').not(par).each(function() {
                $(this).removeClass('level-open');
                $(this).find('.hc-chk').first().attr('checked', false);
            });
        });
    </script>
    <script src="http://piospa.com.dev.com/static/backend/js/admin/layout/script.js?v=1665044292" type="text/javascript">
    </script>








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
    <script src="http://piospa.com.dev.com/static\backend/js/manager-work/table-excel/jquery.table2excel.js"
        type="text/javascript"></script>
    <script src="http://piospa.com.dev.com/static/backend/js/manager-work/managerWork/kanban.js?v=1665044292"
        type="text/javascript"></script>

    <link rel="stylesheet"
        href="http://piospa.com.dev.com/static/backend/js/manager-work/managerWork/kanban/jqwidgets/styles/jqx.base.css"
        type="text/css" />
    <script src="http://piospa.com.dev.com/static/backend/js/admin/service/autoNumeric.min.js?v=1665044292"></script>
    <script>
        var decimal_number =
            0;
    </script>
    <script
        src="http://piospa.com.dev.com/static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxcore.js?v=1665044292"
        type="text/javascript"></script>
    <script
        src="http://piospa.com.dev.com/static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxsortable.js?v=1665044292"
        type="text/javascript"></script>
    <script
        src="http://piospa.com.dev.com/static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxkanban.js?v=1665044292"
        type="text/javascript"></script>
    <script
        src="http://piospa.com.dev.com/static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxsplitter.js?v=1665044292"
        type="text/javascript"></script>
    <script
        src="http://piospa.com.dev.com/static/backend/js/manager-work/managerWork/kanban/jqwidgets/jqxdata.js?v=1665044292"
        type="text/javascript"></script>
    <script
        src="http://piospa.com.dev.com/static/backend/js/manager-work/managerWork/kanban/jqwidgets/demos.js?v=1665044292"
        type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            WorkAll.changeCustomerList();
            var fields = [{
                    name: "id",
                    type: "string"
                },
                {
                    name: "status",
                    map: "state",
                    type: "string"
                },
                {
                    name: "text",
                    map: "label",
                    type: "string"
                },
                {
                    name: "tags",
                    type: "string"
                },
                {
                    name: "color",
                    map: "hex",
                    type: "string"
                },
                {
                    name: "resourceId",
                    type: "number"
                },
                {
                    name: "content",
                    map: "common",
                    type: "string"
                }
            ];
            var source = {
                localData: [

                ],
                dataType: "array",
                dataFields: fields
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var resourcesAdapterFunc = function() {
                var resourcesSource = {
                    localData: [],
                    dataType: "array",
                    dataFields: [{
                            name: "id",
                            type: "number"
                        },
                        {
                            name: "name",
                            type: "string"
                        },
                        {
                            name: "image",
                            type: "string"
                        },
                    ]
                };

                var resourcesDataAdapter = new $.jqx.dataAdapter(resourcesSource);
                return resourcesDataAdapter;
            }

            $('#kanban2').jqxKanban({
                resources: resourcesAdapterFunc(),
                source: dataAdapter,
                width: '100%',
                height: '100%',
                itemRenderer: function(element, item, resource) {
                    var percent = $('#percent-template').html();
                    percent = percent.replace(/{data}/g, parseInt(item.resourceId));
                    $(element).find(".jqx-kanban-item-avatar").addClass('custom-process').html(percent);
                    $(element).find(".jqx-kanban-item-color-status").before(item.content);
                },
                columns: [{
                        text: "Chưa thực hiện",
                        /* tên trạng thái */
                        dataField: "1",
                        /* id trạng thái */
                        color: "#f23607",
                        /* id trạng thái */
                        collapseDirection: "right",
                    },
                    {
                        text: "Đang thực hiện",
                        /* tên trạng thái */
                        dataField: "2",
                        /* id trạng thái */
                        color: "#1b0ced",
                        /* id trạng thái */
                        collapseDirection: "right",
                    },
                    {
                        text: "Đã thực hiện",
                        /* tên trạng thái */
                        dataField: "3",
                        /* id trạng thái */
                        color: "#bbeb0f",
                        /* id trạng thái */
                        collapseDirection: "right",
                    },
                    {
                        text: "Tạm ngừng",
                        /* tên trạng thái */
                        dataField: "4",
                        /* id trạng thái */
                        color: "#000000",
                        /* id trạng thái */
                        collapseDirection: "right",
                    },
                    {
                        text: "Chưa hoàn thành",
                        /* tên trạng thái */
                        dataField: "5",
                        /* id trạng thái */
                        color: "#f50bf9",
                        /* id trạng thái */
                        collapseDirection: "right",
                    },
                    {
                        text: "Hoàn thành",
                        /* tên trạng thái */
                        dataField: "6",
                        /* id trạng thái */
                        color: "#06d4ef",
                        /* id trạng thái */
                        collapseDirection: "right",
                    },
                    {
                        text: "Huỷ",
                        /* tên trạng thái */
                        dataField: "7",
                        /* id trạng thái */
                        color: "#f91aa7",
                        /* id trạng thái */
                        collapseDirection: "right",
                    },
                ],
                columnRenderer: function(element, collapsedElement, column) {
                    var columnItems = $("#kanban2").jqxKanban('getColumnItems', column.dataField)
                        .length;
                    // update header's status.
                    element.find(".jqx-kanban-column-header-status").html(" (" + columnItems + ")");
                    element.find(".jqx-window-collapse-button").addClass('jqx-icon-dot').css(
                        "background-color", column.color);
                    // update collapsed header's status.
                    collapsedElement.find(".jqx-kanban-column-header-status").html(" (" + columnItems +
                        ")");
                }
            });

            $('#kanban2').on('itemMoved', function(event) {
                var args = event.args;
                var itemId = args.itemId;
                var oldParentId = args.oldParentId;
                var newParentId = args.newParentId;
                var itemData = args.itemData;
                var oldColumn = args.oldColumn;
                var newColumn = args.newColumn;
                if (itemId != '' && newColumn.dataField != '') {
                    $.ajax({
                        url: laroute.route('manager-work.change-status'),
                        method: "POST",
                        data: {
                            manage_work_id: itemData.id,
                            manage_status_id: newColumn.dataField
                        },
                        success: function(res) {
                            if (res.status == 0) {
                                var priority =
                                    '<p class="status_work_priority status_work_priority_' +
                                    newColumn.dataField + ' mb-0">' + newColumn.text + '</p>';
                                $('#kanban2_' + itemData.id +
                                        ' .jqx-kanban-item-keyword .status_work_priority')
                                    .parent().html(priority);
                            } else {
                                //                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });

            $('#kanban2 img.avatar').each(function() {
                if ($(this).attr('src') == '') {
                    $(this).attr('src', $(this).attr('onerror'));
                }
            });

            $('#kanban2 .comment-button').on('click', function(e) {
                var mana_work_id = $(this).attr('manager-work-id');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.load-comment'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function(res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $('#comment-popup #description_comment').summernote('code');
                                $('#comment-popup').modal('show');
                            } else {
                                //                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
                if (e.target === $(this).find('.comment-button .fa-comments')[0]);

            });

            $('#kanban2 .avatars_overview__item').on('click', function(e) {
                var mana_work_id = $(this).attr('manage_work_id');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.kanban-view.show-popup-staff'),
                        data: {
                            manage_work_id: mana_work_id
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function(res) {
                            if (res.error == false) {
                                $('.append-popup').empty();
                                $('.append-popup').append(res.view);
                                $('#popup-list-staff').modal('show');
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function(res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function(a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                // if (e.target === $(this).find('.comment-button .fa-comments')[0]) ;

            });

            $(document).on('click', '.title-item-comment', function() {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var id = str.replace('kanban2_', '');
                // window.location.href = laroute.route('manager-work.detail', {id: id})
                window.open(laroute.route('manager-work.detail', {
                    id: id
                }), '_blank')
            });
            /*
            cập nhật tiến độ
             */
            $(document).on('click', '.percentage-update', function() {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var mana_work_id = str.replace('kanban2_', '');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.load-form-update-process'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function(res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $('#comment-popup').modal('show');
                            } else {
                                //                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
            /*
            cập nhật ngày hết hạn
             */
            $(document).on('click', '.date-end-update', function() {
                var str = $(this).closest('[id^=kanban2_]').attr('id');
                var mana_work_id = str.replace('kanban2_', '');
                if (mana_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.load-form-update-date-end'),
                        method: "POST",
                        data: {
                            manage_work_id: mana_work_id,
                        },
                        success: function(res) {
                            if (res.error == 0) {
                                $('#comment-popup .modal-dialog').html(res.data);
                                $(".time-input").timepicker({
                                    todayHighlight: !0,
                                    autoclose: !0,
                                    pickerPosition: "bottom-left",
                                    // format: "dd/mm/yyyy hh:ii",
                                    format: "HH:ii",
                                    defaultTime: "",
                                    showMeridian: false,
                                    minuteStep: 5,
                                    snapToStep: !0,
                                    // startDate : new Date()
                                    // locale: 'vi'
                                });

                                $(".daterange-input").datepicker({
                                    todayHighlight: !0,
                                    autoclose: !0,
                                    pickerPosition: "bottom-left",
                                    // format: "dd/mm/yyyy hh:ii",
                                    format: "dd/mm/yyyy",
                                    // startDate : new Date()
                                    // locale: 'vi'
                                });
                                $('#comment-popup').modal('show');
                            } else {
                                //                                swal.fire(res.message, '', 'error');
                            }
                        }
                    });
                }
            });
            $(document).on('submit', '#update_date_end', function() {
                console.log($('#update_date_end').serialize())
                var manage_work_id = $('#update_date_end [name="manage_work_id"]').val();
                if (manage_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.edit-element-item'),
                        data: $('#update_date_end').serialize(),
                        method: "POST",
                        dataType: "JSON",
                        success: function(res) {
                            if (res.error == false) {
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function(res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function(a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $(document).on('submit', '#update_process', function() {
                var progress = $('#update_process [name="progress"]').val();
                var manage_work_id = $('#update_process [name="manage_work_id"]').val();
                if (manage_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.edit-element-item'),
                        data: {
                            manage_work_id: manage_work_id,
                            progress: progress
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function(res) {
                            if (res.error == false) {
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function(res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function(a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $(document).on('submit', '#send_comment', function() {
                var code = $('#send_comment #description_comment').summernote('code');
                var manage_work_id = $('#send_comment #manage_work_id_comment').val();
                if (manage_work_id) {
                    $.ajax({
                        url: laroute.route('manager-work.detail.add-comment'),
                        data: {
                            manage_work_id: manage_work_id,
                            description: code
                        },
                        method: "POST",
                        dataType: "JSON",
                        success: function(res) {
                            if (res.error == false) {
                                // $('.table-message-main > tbody').prepend(res.view);
                                // $('.description').summernote('code', '');
                                $('#comment-popup').modal('hide');
                                swal('', res.message, 'success');
                                location.reload();
                            } else {
                                swal('', res.message, 'error');
                            }
                        },
                        error: function(res) {
                            var mess_error = '';
                            $.map(res.responseJSON.errors, function(a) {
                                mess_error = mess_error.concat(a + '<br/>');
                            });
                            swal('', mess_error, "error");
                        }
                    });
                }
                return false;
            });
            $('#kanban2 .date-end-update').parent().css({
                "padding-left": "5px"
            });
            $('#kanban2 .date-end-update').parent().css({
                "overflow": "initial"
            });

            $('.jqx-kanban-column').css('width', '400px');

            $('.jqx-kanban-column').click(function() {
                $('.jqx-kanban-column').each(function(i, obj) {
                    var width = $(this).width();
                    if (width > 100) {
                        $(this).css('width', '400px');
                    }
                });

            });
        });
    </script>
    <script>
        // $(document).ready(function () {
        //     $('#m_ver_menu').animate({
        //         scrollTop: $(".active").offset().top - 620
        //     }, 800);
        // });
        notification._init();
    </script>
    <script>
        $('.select2.select2-active').each(function() {
            let placeholder_value = $(this).find("option:first").text() != undefined ? $(this).find("option:first")
                .text() : "Vui lòng chọn";
            $(this).select2({
                placeholder: {
                    id: '',
                    text: placeholder_value
                },
            });
        });
        $('.select2.select2-active-choose-first').each(function() {
            let placeholder_value = $(this).find("option:first").text() != undefined ? $(this).find("option:first")
                .text() : "Vui lòng chọn";
            $(this).select2({
                placeholder: {
                    minimumResultsForSearch: -1,
                    text: placeholder_value
                },
            });
        });
        $('.btn-clear-form').click(function() {
            $(this).closest('form')[0].reset();
            $(this).closest('form').find('[name="page"]').val('');
            $(this).closest('form').find('.select2-active').val("").trigger("change");
        });
        $(document).on('click', '.coppy_button', function() {
            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(this).text()).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.success("Sao chép thành công", "Thông báo");
        })
    </script>
    <!--end::Page Scripts -->

</body>

</html>
