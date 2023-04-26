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
            transition: 1s
        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }

        .table {
            margin-top: 1rem;
        }

        .out-presenter {
            border: 1px solid silver;
            height: 40px
        }

        .presenter {
            margin: 0;
            margin-top: 10px
        }

        .percent {
            float: right;
            border: 1px solid #4fc4cb;
            width: 30px;
            font-size: .8rem;
            background-color: #4fc4cb;
            color: white;
            margin-top: 2px;
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
{{--                    <a class="nav-link" href="{{route('referral.listReferrer')}}" style="font-weight: bold">Danh sách--}}
{{--                        người giới thiệu</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.referrerCommission')}}" style="font-weight: bold">Hoa--}}
{{--                        hồng cho người giới thiệu</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.policyCommission')}}" style="font-weight: bold">Chính--}}
{{--                        sách hoa hồng</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.payment')}}" style="font-weight: bold">Thanh toán</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#m_tabs_5_5"--}}
{{--                       style="font-weight: bold;background-color: #4fc4cb;color: white;border-radius: 0px">Cấu hình--}}
{{--                        nhiều cấp</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.generalConfig')}}" style="font-weight: bold">Cấu hình--}}
{{--                        chung</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--            <hr style="    margin-top: 6px;margin-bottom: 10px;border: 0;border-top: 2px solid #4fc4cb">--}}
            @include('referral::layouts.tab-header')
            <div class="m-portlet__head-tools" style="    margin-bottom: 50px">
                <a href="{{route('referral.editMultiLevelConfig')}}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"
                   style="float:right">
                        <span>
						    <i class="la la-edit"></i>
							<span> {{__('CHỈNH SỬA')}}</span>
                        </span>
                </a>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm">
                        <span style="font-size: 15px;font-weight: bold">{{__('')}}</span>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                            <span id="config_content" name="config_content" type="text"
                                                  class="form-control m-input class">
                                                    <p>{{$info['level']. " Cấp"}}</p>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body" style="padding:0px">
                            <div class="table-responsive">
                                <table class="table table-striped m-table ss--header-table">
                                    <thead>
                                    <tr class="ss--nowrap">
                                        <th class="ss--font-size-th ss--text-center">{{__('Người giới thiệu')}}</th>
                                        <th class="ss--font-size-th ss--text-center">{{__('Chiết khấu nhận')}}</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="ss--font-size-13 ss--nowrap">
                                        <td class=" ss--text-center">
                                            <div class="out-presenter">
                                                <p class="presenter">{{__(' Cấp 1(Người giới thiệu trực tiếp)')}}</p>
                                            </div>
                                        </td>
                                        <td class="ss--text-center">
                                            <div class="col-lg-12" style="    padding-left: 0">
                                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                                    <div class="input-group">
                                                <span id="config_content" name="config_content" type="text"
                                                      class="form-control m-input class">
                                                    <p>
                                                        {{$info['percent']}}
                                                         <i class="fa fa-percent percent"></i>
                                                    </p>
                                                </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm" style="background-color:#FFEFD5">
                        <br>
                        <span style="font-size: 15px;font-weight: bold">{{__('Giải thích:')}}</span>
                        <li>{{__('Người giới thiệu có thể mời người khác làm dưới cấp của mình tối đa 1 cấp')}}</li>
                        <span style="font-weight: bold">{{__('Ví dụ:')}}</span>
                        <div>
                            <li>{{__('User A gửi link giới thiệu cho User B, sau khi đăng kí dưới link User A gửi, User B sẽ
                                là cấp 1 của User A.')}}
                            </li>
                            <li>{{__('User B gửi link giới thiệu cho User C, sau khi đăng kí dưới link User B gửi, User C sẽ là cấp 1 của User B và là cấp 2 của User A.')}}
                            </li>
                        </div>
                        <br>
                        <span style="font-weight: bold">{{__('Chế độ tính hoa hồng:')}}</span>
                        <div>
                            <li>{{__('Hoa hồng của người giới thiệu nhận được: Mức hoa hồng nhận được theo % hoa hồng của người giới thiệu cấp 1 nhận được.')}}
                            </li>
                            <li>{{__('Ví dụ: C giới thiệu 1 đơn hàng thành công giá 100.000đ và tương ứng với hoa hồng cho đơn hàng này là 20.000đ. Lúc này, hoa hồng tính như sau:')}}
                            </li>
                        </div>
                        <span>
                                <li style="    margin-left: 30px" ;>
                                    <span style="font-weight: bold;text-indent: 10px">
                                        <i>{{__('Hoa hồng cho B =  % hoa hồng cấp 1 x 20,000 =')}}</i>
                                    </span>
                                     20.000đ
                                </li>
                                <li style="    margin-left: 30px;">
                                    <span style="font-weight: bold;text-indent: 10px">
                                        <i>{{__('Hoa hồng cho A =')}}</i>
                                    </span>
                                     0đ
                                </li>
                            <br>
                        </span>
                    </div>

                </div>
            </div>

            @include('admin::product.modal.excel-image')
            @endsection
            @section('after_script')

                <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
                <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}"
                        type="text/javascript"></script>
                <script>
                    $('.select2').select2();
                </script>
@stop
