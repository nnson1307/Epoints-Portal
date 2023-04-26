@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ REFERRAL')}}
    </span>
@endsection
@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('QUẢN LÝ REFERRAL')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <form action="{{route('payment.export-excel')}}" method="post">
                    {{ csrf_field() }}
                </form>

{{--                <a href="javascript:void(0)"--}}
{{--                   data-toggle="modal"--}}
{{--                   data-target="#add"--}}
{{--                   class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">--}}
{{--                        <span>--}}
{{--						    <i class="fa fa-plus-circle"></i>--}}
{{--							<span> {{__('THÊM PHIẾU CHI')}}</span>--}}
{{--                        </span>--}}
{{--                </a>--}}
                <a href="javascript:void(0)"
                   data-toggle="modal"
                   data-target="#add"
                   class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                        color_button btn_add_mobile"
                   style="display: none">
                    <i class="fa fa-plus-circle" style="color: #fff"></i>
                </a>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            @include('referral::layouts.tab-header')

            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-4 form-group">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{__('Nhập mã ,tên , sđt người giới thiệu')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select class="form-control m-input select2" name="status">
                            <option value="" selected="selected">{{__('Chọn trạng thái')}}</option>
                            <option value="active">{{__('Hoạt động')}}</option>
                            <option value="inactive">{{__('Ngừng hoạt động')}}</option>
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input type="text"
                                   class="form-control m-input daterange-picker" id="created_at"
                                   name="created_at"
                                   autocomplete="off" placeholder="{{__('Chọn ngày tham gia')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <a href="{{route('referral.referral-member.index')}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                            {{__('XÓA BỘ LỌC')}}
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </a>
                        <button class="btn btn-primary btn-search color_button" onclick="payment.searchList()">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
                {{--                @include('helpers.filter')--}}
            </form>

            <div class="table-content m--padding-top-15">
                @include('referral::ReferralMember.list')
            </div><!-- end table-content -->

        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/referral/referral-member/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('select').select2();
        referralMember._init();
    </script>
@stop
