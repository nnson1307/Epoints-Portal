@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-email.png')}}" alt="" style="height: 20px;"> {{__('EMAIL')}}</span>
@stop
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/


    </style>
    @include('admin::marketing.email.auto.modal-setting')
    @include('admin::marketing.email.auto.modal-setting-content')
    @include('admin::marketing.email.auto.modal-template')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-large"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('CÀI ĐẶT EMAIL TỰ ĐỘNG')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-5">
                    <div class="bd-email m--padding-10">
                        <div class="sp_em">
                            <span>{{__('Cấu hình gửi email')}}</span>
                        </div>
                        <div class="sp_auto m--padding-10">
                            <div class="row">
                                <div class="col-lg-7">
                                    <label class="sz_dt">{{__('Chọn cấu hình gửi email bằng tài khoản google , Amazon SES hoặc Click Send')}} </label>
                                </div>
                                <div class="col-lg-5">
                                    @if(in_array('admin.email-auto.submit-config',session('routeList')))
                                        <a href="javascript:void(0)" onclick="auto.modal_setting(1)"
                                           class="m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary btn-sm-list_email float-right">
                                            <i class="la la-cog icon-sz"></i>
                                            <span>{{__('Cấu hình')}}</span>

                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="sp_auto m--padding-10">
                            <div class="row">
                                <div class="col-lg-7">
                                    <label class="sz_dt">{{__('Gửi thử email')}}: </label>
                                </div>
                                <div class="col-lg-5">
                                    <a href="javascript:void(0)" onclick="auto.send_mail_test()"
                                       class="m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary btn-sm-list_email float-right">
                                        <i class="la la-envelope icon-sz"></i>
                                        <span>{{__('Gửi thử')}}</span>

                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="m--padding-10">
                            <div class="row">
                                <div class="col-lg-7">
                                    <label class="sz_dt">{{__('Chọn template gửi email')}} </label>
                                </div>
                                <div class="col-lg-5">
                                    @if(in_array('admin.email-auto.email-template',session('routeList')))
                                        <a href="javascript:void(0)" onclick="auto.modal_template(1)"
                                           class="m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary btn-sm-list_email float-right">
                                            <i class="la la-cog icon-sz"></i>
                                            <span>{{__('Chọn')}}</span>

                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-lg-7">
                    <div class="bd-email m--padding-10">
                        <div class="sp_em">
                            <span>{{__('Cài đặt email')}}</span>
                        </div>
                        <div class="m-scrollable m-scroller ps ps--active-y  m--padding-10" data-scrollable="true"
                             style="height: 400px; overflow: hidden;">
                            @include('admin::marketing.email.auto.list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/marketing/email/auto/script.js')}}" type="text/javascript"></script>
    <script type="text/template" id="type-tpl">
        <div class="form-group m-form__group">
            <div class="row">
                <label class="col-lg-4">{{__('Hình thức gửi')}}:<b class="text-danger">*</b></label>
                <div class="col-lg-8">
                    <div class="input-group">
                        <select class="form-control" id="type" name="type" style="width: 100%">
                            <option></option>
                            <option value="gmail">Google Apps & Gmail</option>
                            <option value="amazone">Amazone SES</option>
                            <option value="clicksend">Click Send</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group m-form__group">
            <div class="row">
                <label class="col-lg-4">{{__('Email/Account')}}:<b class="text-danger">*</b></label>
                <div class="col-lg-8">
                    <input class="form-control" id="email" name="email" placeholder="{{__('Nhập email/account')}}...">
                </div>

            </div>
        </div>
        <div class="append_pass">

        </div>
        <div class="form-group m-form__group">
            <div class="row">

                <label class="col-lg-4">
                    {{__('Tên đại diện')}}:<b class="text-danger">*</b>
                </label><br/>

                <div class="col-lg-8">
                    <input class="form-control" id="name" name="name"
                           placeholder="{{__('Nhập tên đại diện')}}...">
                    <span>{{__('Có thể tùy chỉnh thay đổi tên đại diện')}}</span>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="pass-tpl">
        <div class="form-group m-form__group">
            <div class="row">
                <label class="col-lg-4">{{__('Mật khẩu')}}:<b class="text-danger">*</b></label>
                <div class="col-lg-8">
                    <input type="password" class="form-control " id="password" name="password"
                           placeholder="{{__('Nhập mật khẩu')}}...">
                </div>

            </div>
        </div>
    </script>
    <script type="text/template" id="apiKey-tpl">
        <div class="form-group m-form__group">
            <div class="row">
                <label class="col-lg-4">{{__('Api key')}}:<b class="text-danger">*</b></label>
                <div class="col-lg-8">
                    <input type="password" class="form-control " id="apikey" name="apikey"
                           placeholder="{{__('Api key')}}...">
                </div>

            </div>
        </div>
    </script>
    <script type="text/template" id="time-after-tpl">
        <div class="form-group m-form__group">
            <label>{{__('Số giờ gửi trước')}}:</label>

            <input type="number" class="form-control" id="value_time" name="value_time"
                   placeholder="{{__('Hãy nhập số giờ')}}...">

        </div>
    </script>
    <script type="text/template" id="day-after-tpl">
        <div class="form-group m-form__group">
            <label>{{__('Số ngày gửi trước')}}:</label>

            <input type="number" class="form-control" id="value_day" name="value_day"
                   placeholder="{{__('Hãy nhập số ngày')}}...">

        </div>
    </script>
    <script type="text/template" id="time-sent-tpl">
        <div class="form-group m-form__group">
            <label>{{__('Thời gian gửi')}}:</label>
            <div class="input-group timepicker">
                <input class="form-control m-input" id="time_sent" name="time_sent" readonly=""
                       placeholder="{{__('Chọn giờ gửi')}}..."
                       type="text">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="la la-clock-o"></i></span>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="tb-para-tpl">
        <div class="col-6 m--margin-top-10">
            <button type="button" class="btn btn-secondary active param_email_auto" value="{code}"
                    onclick="auto.append_para(this)">
                {note}
            </button>
        </div>
    </script>
    <script type="text/template" id="template-tpl">
        <div class="carousel-item {status}">
            <img class="d-block w-100" src="{image}"
                 alt="{id}" height="500px">
        </div>
    </script>
@stop
