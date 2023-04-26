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
                        {{__('CHI TIẾT ĐƠN HÀNG HƯỞNG HOA HỒNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('referral.commission-order')}}" class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">

                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    {{__('Quay lại')}}
                </a>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter ss--background">
            </form>
            <br>
            <div class="col-12">

                <div class="row">

                    <div class="col-6">
                        <p>{{__('Tên chương trình')}} : <a href="{{route('referral.detailCommission', ['id' => $detail['obj_id']])}}">{{$detail['referral_program_name']}}</a> </p>
                        <p>{{__('Trạng thái')}} : @if($detail['invite_status'] == 'new')
                                {{__('Mới')}}
                            @elseif($detail['invite_status'] == 'approve')
                                {{__('$detail ghi nhận')}}
                            @elseif($detail['invite_status'] == 'reject')
                                {{__('Từ chối')}}
                            @elseif($detail['invite_status'] == 'waiting_payment')
                                {{__('Chờ thanh toán')}}
                            @elseif($detail['invite_status'] == 'payment')
                                {{__('Đã thanh toán')}}
                            @endif</p>
                        <p>{{__('Người giới thiệu')}} : <a href="{{route('referral.referral-member.detailReferral', ['id' => $detail['inviter_referral_member_id']])}}">{{$detail['inviter_full_name']}}</a></p>
                        <p>{{__('Ngày ghi nhận hoa hồng')}} : {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail['invite_created_at'])->format('d/m/Y H:i:s')}}</p>
                        <p>{{__('Ngày duyệt hoa hồng')}} : {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail['invite_approve_date'])->format('d/m/Y H:i:s')}}</p>

                    </div>

                    <div class="col-6">
                        <p>{{__('Mã đơn hàng')}} : <a href="{{route('admin.order.detail', ['id' => $detail['obj_id']])}}">{{$detail['obj_code']}}</a> </p>
                        <p>{{__('Người mua')}} : <a href="{{route('referral.referral-member.detailReferral', ['id' => $detail['invitee_referral_member_id']])}}">{{$detail['invitee_full_name']}}</a></p>
                        <p>{{__('Tổng tiền')}} : {{number_format($detail['total_amount'])}} VND</p>
                        <p>{{__('Ngày tạo')}} : {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail['orders_created_at'])->format('d/m/Y H:i:s')}}</p>
                        <p>{{__('Ngày thanh toán')}} : {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail['receipts_created_at'])->format('d/m/Y H:i:s')}}</p>

                    </div>




                </div>

            </div>

            <div class="col-12">
                <div class="m-portlet__body" style="padding: 0px;padding-top: 40px;">
                    <form class="frmFilter ss--background">
                        <div class="row ss--bao-filter">
                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="search" placeholder="{{__('Nhập mã đơn hàng, tên người giới thiệu')}}">
                            </div>

                            <div class="col-lg-3">
                                <select class="form-control" name="status">
                                    <option value="">{{__('Trạng thái hoa hồng')}}</option>
                                    <option value="new">{{__('Mới')}}</option>
                                    <option value="approve">{{__('Đã ghi nhận')}}</option>
                                    <option value="reject">{{__('Đã từ chối')}}</option>
                                    <option value="waiting_payment">{{__('Chờ thanh toán')}}</option>
                                    <option value="payment">{{__('Đã thanh toán')}}</option>
                                </select>
                            </div>

                            <div class="col-lg-3 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input type="text"
                                           class="form-control m-input daterange-picker" id="created_at"
                                           name="created_at"
                                           autocomplete="off" placeholder="{{__('Ngày tạo')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>

                            {{--                        <div class="col-lg-3">--}}
                            {{--                            <select class="form-control" name="referral_member_id">--}}
                            {{--                                <option value="">{{__('Người giới thiệu cấp 1')}}</option>--}}
                            {{--                                @foreach($listReferralLevel as $item)--}}
                            {{--                                    <option value="{{$item['referral_manent_id']}}">{{$item['full_name']}}</option>--}}
                            {{--                                @endforeach--}}
                            {{--                            </select>--}}
                            {{--                        </div>--}}
                            <div class="col-12" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                                <div class="text-right">
                                    <a href="{{route('referral.referral-program-invite.index')}}"
                                       class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                        {{ __('XÓA BỘ LỌC') }}
                                        <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>

                                    <button href="javascript:void(0)" onclick="product.search()"
                                            class="btn ss--btn-search">
                                        {{__('TÌM KIẾM')}}
                                        <i class="fa fa-search ss--icon-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="table-content m--padding-top-15">
                    {{--                @include('referral::ReferralMember.list')--}}
                    <div class="m-portlet__body" style="padding: 0px">
                        @include('referral::ReferralProgramInvite.list')
                    </div>
                </div><!-- end table-content -->
            </div>




        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <style>
        #autotable {padding : 0px !important;}
    </style>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/referral/referral-program-invite/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('select').select2();
        referralProgramInvite.program_invite_id = '{{$programInviteId}}'
        referralProgramInvite._initCommissionOrderDetail();
    </script>
@stop

