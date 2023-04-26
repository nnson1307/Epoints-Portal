@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ REFERRAL')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .nav-item:hover {
            background-color: #4fc4cb;
            transition: 1s;

        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }
    </style>
    <div class="m-portlet" id="autotable">
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
        </div>
        <div class="m-portlet__body">
            @include('referral::layouts.tab-header')

            <div class="m-portlet__body" style="padding: 0px;padding-top: 40px;">
                <form class="frmFilter ss--background">
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="search" placeholder="{{__('Nhập mã đơn hàng, tên người giới thiệu')}}">
                        </div>
                        <div class="col-lg-3">
                            <select class="form-control" name="referral_program_id">
                                <option value="">{{__('Chính sách hoa hồng')}}</option>
                                @foreach($listProgram as $item)
                                    <option value="{{$item['referral_program_id']}}" >{{$item['referral_program_name']}}</option>
                                @endforeach
                            </select>
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
                                <a href="{{route('referral.commission-order.listCommissionOrder')}}"
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
            <div class="table-content">
                <div class="m-portlet__body" style="padding: 0px">
                    @include('referral::ReferralProgramInvite.commissionOrder.list')
                </div>
            </div><!-- end table-content -->
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/referral/referral-program-invite/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('select').select2();
        referralProgramInvite._initCommissionOrder();
    </script>
@stop
