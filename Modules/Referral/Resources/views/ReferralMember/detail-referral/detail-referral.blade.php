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
                        {{__('CHI TIẾT NGƯỜI GIỚI THIỆU')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="col-12">
                @include('referral::ReferralMember.detail')
                <div class="row">
                    <div class="col-12">
                        @include('referral::layouts.tab-header-detail')
                    </div>

                    <div class="col-12">
                        <form class="frmFilter ss--background w-100">
                            <input type="hidden" name="referral_member_id" value="{{$detail['referral_member_id']}}">
                            <div class="row padding_row">
                                <div class="col-lg-3 form-group">
                                    <div class="form-group m-form__group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" placeholder="Nhập mã, tên hoặc sđt người giới thiệu">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <select class="form-control m-input select2" name="status">
                                        <option value="" selected="selected">{{__('Chọn trạng thái')}}</option>
                                        <option value="active">{{__('Kích hoạt')}}</option>
                                        <option value="inactive">{{__('Ngưng kích hoạt')}}</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text"
                                               class="form-control m-input daterange-picker" id="created_at"
                                               name="created_at"
                                               autocomplete="off" placeholder="{{__('Ngày tham gia')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <a href="{{route('referral.referral-member.detailReferral',['id'=> $detail['referral_member_id']])}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                        {{__('XÓA BỘ LỌC')}}
                                        <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                    <button type="button" onclick="referralMember.searchReferral()" class="btn btn-primary btn-search color_button">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="table-content m--padding-top-15">
                <div class="m-portlet__body listTable" style="padding: 0px">
                    @include('referral::ReferralMember.detail-referral.list')
                </div>
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
        referralMember._initDetailReferral();
    </script>
@stop
