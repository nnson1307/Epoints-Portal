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
                        <i class="fa fas fa-list-ul"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT NGƯỜI GIỚI THIỆU')}}
                    </h3>
                </div>
            </div>
        </div>
        {{--        /////////////////////////////lich su nhan hoa hong--}}
        <div class="m-portlet__body">
            <ul class="nav nav-pills nav-fill" role="tablist" style="margin-bottom: -8px">
                <li class="nav-item">
                    <a class="nav-link " href="{{route('referral.listReferrer')}}" style="font-weight: bold">Danh sách
                        người giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#m_tabs_5_2"
                       style="font-weight: bold;background-color: #4fc4cb;color: white;border-radius: 0px">Hoa hồng cho
                        người giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('referral.policyCommission')}}"
                       style="font-weight: bold">Chính sách
                        hoa hồng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#m_tabs_5_4" style="font-weight: bold">Thanh toán</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('referral.multiLevelConfig')}}" style="font-weight: bold">Cấu
                        hình nhiều cấp</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('referral.generalConfig')}}" style="font-weight: bold">Cấu hình
                        chung</a>
                </li>
            </ul>
            <hr style="    margin-top: 6px;margin-bottom: 10px;border: 0;border-top: 2px solid #4fc4cb">
            <div class="m-portlet__body" style="padding: 0px;padding-top: 12px;">
                <form class="frmFilter ss--background">
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control" name="search_keyword"
                                           placeholder="{{__('Nhập mã đơn hàng hoặc người giới thiệu')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <select class="form-control select2">
                                        <option value="">Chính sách hoa hồng</option>
                                        <option value="1">Loại 1</option>
                                        <option value="2">Loại 2</option>
                                        <option value="3">Loại 3</option>
                                        <option value="4">Loại 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <select class="form-control select2">
                                        <option value="">Trạng thái hoa hồng</option>
                                        <option value="1">Hoạt động</option>
                                        <option value="2">Không hoạt động</option>
                                        <option value="3">Đóng</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly="" class="form-control date-picker-list"
                                           id="m_datepicker_1" style="background-color: #fff"
                                           name="date_start" value="" autocomplete="off"
                                           placeholder="Ngày tạo">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <select class="form-control select2">
                                        <option value="">Người giới thiệu cấp 1</option>
                                        <option value="2">Người giới thiệu cấp 2</option>
                                        <option value="3">Người giới thiệu cấp 3</option>
                                        <option value="4">Người giới thiệu cấp 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly="" class="form-control date-picker-list"
                                           id="m_datepicker_1" style="background-color: #fff"
                                           name="date_note" value="" autocomplete="off"
                                           placeholder="Ngày ghi nhận hoa hồng">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                        <div class="text-right">
                            <a href="{{route('referral.listReferrer')}}"
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
                </form>
            </div>
            <div class="table-content">
                <div class="m-portlet__body" style="padding: 0px;padding-top:12px">
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table">
                            <thead>
                            <tr class="ss--nowrap">
                                <th class="ss--text-center">#</th>
                                <th class="">{{__('Hành động')}}</th>
                                <th class="ss--text-center">{{__('Chính sách hoa hồng')}}</th>
                                <th class="ss--text-center">{{__('Người giới thiệu cấp 1')}}</th>
                                <th class="ss--text-center">{{__('Hoa hồng')}}</th>
                                <th class="ss--text-center">{{__('Trạng thái hoa hồng')}}</th>
                                <th class="ss--text-center">{{__('Ngày tạo')}}</th>
                                <th class="ss--text-center">{{__('Ngày ghi nhận')}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">#</td>
                                <td>
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
                                                <input type="checkbox"
                                                       checked class="manager-btn" name="">
                                                <span></span>
                                            </label>
                                    </span>
                                </td>
                                <td class="ss--text-center">Hoa hồng đơn hàng DH_123456789</td>
                                <td class="ss--text-center">Nguyễn Văn A</td>
                                <td class="ss--text-center"> 100.000</td>
                                <td class="ss--text-center">Đã ghi nhận</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>

                            </tr>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">#</td>
                                <td>
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
                                                <input type="checkbox"
                                                       checked class="manager-btn" name="">
                                                <span></span>
                                            </label>
                                    </span>
                                </td>
                                <td class="ss--text-center">Hoa hồng đơn hàng DH_123456789</td>
                                <td class="ss--text-center">Nguyễn Văn A</td>
                                <td class="ss--text-center"> 100.000</td>
                                <td class="ss--text-center">Đã ghi nhận</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end table-content -->
        </div>
        {{--        /////////////////////////////--}}
    </div>
    @include('admin::product.modal.excel-image')
@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/referral/add.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        $('.select2').select2();
    </script>
@stop
