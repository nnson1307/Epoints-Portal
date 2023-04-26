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
{{--            <ul class="nav nav-pills nav-fill" role="tablist" style="margin-bottom: -8px">--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link " href="{{route('referral.listReferrer')}}" style="font-weight: bold">Danh sách--}}
{{--                        người giới thiệu</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.referrerCommission')}}" style="font-weight: bold">Hoa--}}
{{--                        hồng cho người giới thiệu</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.policyCommission')}}"--}}
{{--                       style="font-weight: bold">Chính sách--}}
{{--                        hoa hồng</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#m_tabs_5_4"--}}
{{--                       style="font-weight: bold;background-color: #4fc4cb;color: white;border-radius: 0px">Thanh--}}
{{--                        toán</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.multiLevelConfig')}}" style="font-weight: bold">Cấu--}}
{{--                        hình nhiều cấp</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.generalConfig')}}" style="font-weight: bold">Cấu hình--}}
{{--                        chung</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--            <hr style="    margin-top: 6px;margin-bottom: 10px;border: 0;border-top: 2px solid #4fc4cb">--}}
            @include('referral::layouts.tab-header')

            {{--            <div class="m-portlet__head-tools">--}}
{{--                <a href="{{route('referral.addCommission')}}"--}}
{{--                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"--}}
{{--                   style="float:right">--}}
{{--                         <span>--}}
{{--						    <i class="fa fa-plus-circle m--margin-right-5"></i>--}}
{{--							<span> {{__('THÊM CHÍNH SÁCH')}}</span>--}}
{{--                        </span>--}}

{{--                </a>--}}
{{--            </div>--}}
            <div class="m-portlet__body" style="padding: 0px;padding-top: 40px;">
                <form class="frmFilter ss--background">
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly="" class="form-control date-picker-list"
                                           id="m_datepicker_1" style="background-color: #fff"
                                           name="date_start" value="" autocomplete="off"
                                           placeholder="Kỳ trả hoa hồng">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                            <div class="text-right">
                                <a href="{{route('referral.policyCommission')}}"
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
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table">
                            <thead>
                            <tr class="ss--nowrap">
                                <th class="ss--text-center">#</th>
                                <th class=" ss--text-center">{{__('Tên kỳ hoa hồng')}}</th>
                                <th class="ss--text-center">{{__('Kỳ trả')}}</th>
                                <th class="ss--text-center">{{__('Tổng hoa hồng')}}</th>
                                <th class="ss--text-center">{{__('Trạng thái')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">#</td>
                                <td class="ss--text-center">Hoa hồng 15/10/2022 - 31/10/2022</td>
                                <td class="ss--text-center">Tháng 10/2022</td>
                                <td class="ss--text-center"> 3.000.000đ</td>
                                <td class="ss--text-center">Đã Thanh toán</td>
                            </tr>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">#</td>
                                <td class="ss--text-center">Hoa hồng 15/10/2022 - 31/10/2022</td>
                                <td class="ss--text-center">Tháng 10/2022</td>
                                <td class="ss--text-center"> 3.000.000đ</td>
                                <td class="ss--text-center">Đã Thanh toán</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end table-content -->
        </div>
    </div>
    @include('admin::product.modal.excel-image')
@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('.select2').select2();
    </script>
@stop
