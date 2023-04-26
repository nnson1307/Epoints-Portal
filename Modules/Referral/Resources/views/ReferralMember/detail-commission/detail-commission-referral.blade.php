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
            <form class="frmFilter ss--background">
                <input type="hidden" name="referral_member_id" value="{{$detail['referral_member_id']}}">
            </form>
            <div class="col-12">
                @include('referral::ReferralMember.detail')
                <div class="row">
                    <div class="col-12">
                        @include('referral::layouts.tab-header-detail')
                    </div>
                </div>
            </div>


            <div class="table-content m--padding-top-15">
{{--                @include('referral::ReferralMember.list')--}}
                <div class="m-portlet__body" style="padding: 0px">
                    @include('referral::ReferralMember.detail-commission.list')
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
    <script src="{{asset('static/backend/js/referral/referral-program-invite/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        referralMember._initDetailCommission();
    </script>
@stop
