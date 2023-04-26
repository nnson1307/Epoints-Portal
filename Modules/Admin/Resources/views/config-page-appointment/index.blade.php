@extends('layout')
@section("after_style")

    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('TRANG ĐẶT LỊCH')}}
    </span>
@endsection

@section('content')
    <input type="hidden" id="img_default" name="img_default" value="{{asset('static/backend/images/default-placeholder.png')}}">
    <!--begin::Portlet-->

    <div class="m-portlet m-portlet--head-sm m-portlet--tabs m-portlet--info m-portlet--head-solid-bg m-portlet--bordered">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-calendar-1"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('Cấu hình đặt lịch')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand  m-tabs-line--right m-tabs-line-danger" role="tablist">
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link active" data-toggle="tab" href="#info" role="tab">
                            {{__('Thông tin đơn vị')}}
                        </a>
                    </li>

                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#banner" role="tab">
                            {{__('Banner')}}<small> ( Sider )</small>
                        </a>
                    </li>

                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#introduction" role="tab">
                            {{__('Trang giới thiệu')}}
                        </a>
                    </li>

                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#time-work" role="tab">
                           {{__('Thời gian hoạt động')}}
                        </a>
                    </li>

                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#menu" role="tab">
                            {{__('Tuỳ chỉnh menu')}}
                        </a>
                    </li>

                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#custom" role="tab">
                           {{__('Tuỳ chỉnh khác')}}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="tab-content">
                <div class="tab-pane active" id="info" role="tabpanel">
                    @include('admin::config-page-appointment.spa-info.edit')
                </div>
                <div class="tab-pane " id="banner" role="tabpanel">
                    @include('admin::config-page-appointment.banner-slider.index')
                </div>
                <div class="tab-pane " id="introduction" role="tabpanel">
                    @include('admin::config-page-appointment.introduction.index')
                </div>
                <div class="tab-pane " id="time-work" role="tabpanel">
                    @include('admin::config-page-appointment.time-working.list')
                </div>
                <div class="tab-pane " id="menu" role="tabpanel">
                    @include('admin::config-page-appointment.rule.rule-menu-booking.index')
                </div>
                <div class="tab-pane " id="custom" role="tabpanel">
                    @include('admin::config-page-appointment.rule.rule-other-booking-extra.index')
                </div>
                <div class="tab-pane " id="other" role="tabpanel">
                    @lang('Khác')
                </div>
            </div>
        </div>
    </div>
    <!--end::Portlet-->
@endsection

@section('after_script')
    <script type="text/template" id="img-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah_banner"
             src="{{asset('static/backend/images/default-placeholder.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img"><a href="javascript:void(0)" onclick="banner.remove_img()">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="banner_img" name="banner_img" value="">
    </script>
    <script type="text/template" id="img-edit-tpl">
        <img class="m--bg-metal m-image img-sd blah1"
             src="{name}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img" style="display: {display}">
            <a href="javascript:void(0)" onclick="banner.remove_img_edit()">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="banner_img_edit" name="banner_img_edit" class="banner_img_edit">
    </script>
    <script type="text/template" id="avatar-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah_info"
             src="{{asset('static/backend/images/default-placeholder.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img"><a href="javascript:void(0)" onclick="spa_info.remove_avatar_edit()">
<i class="la la-close"></i></a>
</span>
        <input type="hidden" id="logo" name="logo" value="">
    </script>
    <script type="text/template" id="img-share-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah_share_fb"
             src="{{asset('static/backend/images/default-placeholder.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img"><a href="javascript:void(0)" onclick="other_extra.remove_img('{id}')">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="blah_share_fb_hidden" name="blah_share_fb_hidden" value="">
    </script>
    <script>
        var Summernote = {
            init: function () {
                $(".summernote").summernote({
                    height: 208,
                    placeholder: 'Nhập nội dung...',
                    // toolbar: [
                    //     ['style', ['bold', 'italic', 'underline']],
                    //     ['fontsize', ['fontsize']],
                    //     ['color', ['color']],
                    //     ['para', ['ul', 'ol', 'paragraph']],
                    //     // ['insert', ['link', 'picture']]
                    // ]
                })
            }
        };
        jQuery(document).ready(function () {
            Summernote.init()
        });
    </script>
    <script src="{{asset('static/backend/js/admin/config-page-appointment/spa-info/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/config-page-appointment/banner-slider/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/config-page-appointment/time-working/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/config-page-appointment/rule/menu-booking.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/config-page-appointment/rule/other-extra.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/introduction/introduction.js?v='.time())}}" type="text/javascript"></script>
@stop
