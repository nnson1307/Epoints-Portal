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
                            <select class="form-control m-input select2" name="period">
                                <option value="" selected="selected">{{__('Kỳ trả hoa hồng')}}</option>
                                @for($i = 1; $i <= 12 ;$i++)
                                    <option value="{{$i}}" >{{__('Tháng')}} {{$i}}/{{\Carbon\Carbon::now()->format('Y')}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="m-portlet__body" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                            <div class="text-right">
                                <a href="{{route('referral.referral-payment.index')}}"
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
                <div class="m-portlet__body" style="padding: 0px;padding-top:12px">
                    @include('referral::ReferralPayment.list')
                </div>
            </div><!-- end table-content -->
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/referral/referral-payment/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('.select2').select2();
        referralPayment._init();
    </script>
@stop
