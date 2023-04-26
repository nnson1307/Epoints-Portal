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
        .nav-child {
            display: inline-flex !important;
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
            <div class="m-portlet__body" style="padding: 0px">
                <div class="text-right">
                    <a href="{{route('referral.referral-payment.index')}}" class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>Trở về</span>
                        </span>
                    </a>
                </div>
                <h3><strong>{{$detail['name']}}</strong></h3>
                @include('referral::layouts.tab-header-payment')

                <form class="frmFilter ss--background">
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="search" placeholder="{{__('Nhập mã phiếu chi hoặc tên người giới thiệu')}}">
                        </div>
{{--                        <div class="col-lg-3">--}}
{{--                            <select class="form-control" name="object_accounting_type_code">--}}
{{--                                <option value="">{{__('Nhóm người nhận')}}</option>--}}
{{--                                <option value="OAT_CUSTOMER">{{__('Khách hàng')}}</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
                        <div class="col-lg-3">
                            <select class="form-control" name="payment_method">
                                <option value="">{{__('Hình thức')}}</option>
                                @foreach($listMethod as $item)
                                    <option value="{{$item['payment_method_code']}}">{{$item[getValueByLang('payment_method_name_')]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="m-portlet__body" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                            <div class="text-right">
                                <a href="{{route('referral.referral-payment-member.index',['id' => $detail['referral_payment_id']])}}"
                                   class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                    {{ __('XÓA BỘ LỌC') }}
                                    <i class="fa fa-eraser" aria-hidden="true"></i>
                                </a>

                                <button href="javascript:void(0)"
                                        class="btn ss--btn-search">
                                    {{__('TÌM KIẾM')}}
                                    <i class="fa fa-search ss--icon-search"></i>
                                </button>

                                <a href="javascript:void(0)"
                                   onclick="referralPaymentMember.rejectAll()"
                                   class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                    {{ __('Từ chối hoa hồng') }}
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="new">
                    <input type="hidden" name="referral_payment_id" value="{{$detail['referral_payment_id']}}">
                </form>
            </div>
            <div class="table-content">
                <div class="m-portlet__body" style="padding: 0px">
                    @include('referral::ReferralPaymentMember.list')
                </div>
            </div><!-- end table-content -->
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/referral/referral-payment-member/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('select').select2();
        referralPaymentMember.referral_payment_id = "{{$detail['referral_payment_id']}}";
        referralPaymentMember._init();
    </script>
@stop
