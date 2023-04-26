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
        .status-cancel{
            border: 1px solid silver;
            color: white;
            background-color: silver;
            border-radius: 10px;
            width: 200px;
            padding: 0px 34px;
            margin: auto;
        }
        .status-active{
            border: 1px solid green;
            color: white;
            background-color: green;
            border-radius: 10px;
            width: 200px;
            padding: 0px 34px;
            margin: auto;
        }
        .status-new{
            border: 1px solid #4fc4cb;
            color: white;
            background-color: #4fc4cb;
            border-radius: 10px;
            width: 200px;
            padding: 0px 34px;
            margin: auto;
        }
        .status-waiting{
            border: 1px solid orange;
            color: white;
            background-color: orange;
            border-radius: 10px;
            width: 200px;
            padding: 0px 34px;
            margin: auto;
        }
        .status-pending{
            border: 1px solid yellow;
            color: black;
            background-color: yellow;
            border-radius: 10px;
            width: 200px;
            padding: 0px 34px;
            margin: auto;
        }
        .status-approved{
            border: 1px solid dodgerblue ;
            color: white;
            background-color: dodgerblue;
            border-radius: 10px;
            width: 200px;
            padding: 0px 34px;
            margin: auto;
        }
        .status-reject {
            border: 1px solid red ;
            color: white;
            background-color: red;
            border-radius: 10px;
            width: 200px;
            padding: 0px 34px;
            margin: auto;
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
{{--                    <a class="nav-link" href="#m_tabs_5_3"--}}
{{--                       style="font-weight: bold;background-color: #4fc4cb;color: white;border-radius: 0px">Chính sách--}}
{{--                        hoa hồng</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('referral.payment')}}" style="font-weight: bold">Thanh toán</a>--}}
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
            <div class="m-portlet__head-tools">
                <a href="{{route('referral.addCommission')}}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"
                   style="float:right">
                         <span>
						    <i class="fa fa-plus-circle m--margin-right-5"></i>
							<span> {{__('THÊM CHÍNH SÁCH')}}</span>
                        </span>

                </a>
            </div>
            <div class="m-portlet__body" style="padding: 0px;padding-top: 40px;">
                <form class="frmFilter ss--background search-policy">
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input id="config_content" name="referral_program_name" type="text"
                                           class="form-control m-input class" value="{{isset($param['referral_program_name']) ? $param['referral_program_name'] : ''}}"
                                           placeholder="{{__('Nhập tên chính sách')}}"
                                           aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <select class="form-control select2" name="status">
                                        <option value="">Trạng thái</option>
                                        <option value="all" {{isset($param['status']) && $param['status'] == 'all' ? 'selected' : ''}}>Tất cả</option>
                                        <option value="new" {{isset($param['status']) && $param['status'] == 'new' ? 'selected' : ''}}>Nháp</option>
                                        <option value="actived" {{isset($param['status']) && $param['status'] == 'actived' ? 'selected' : ''}}>Đang hoạt động</option>
                                        <option value="waiting" {{isset($param['status']) && $param['status'] == 'waiting' ? 'selected' : ''}}>Đang chờ duyệt</option>
                                        <option value="approved" {{isset($param['status']) && $param['status'] == 'approved' ? 'selected' : ''}}>Đã phê duyệt</option>
                                        <option value="pending" {{isset($param['status']) && $param['status'] == 'pending' ? 'selected' : ''}}>Tạm dừng</option>
                                        <option value="cancel" {{isset($param['status']) && $param['status'] == 'cancel' ? 'selected' : ''}}>Đã từ chối</option>
                                        <option value="reject" {{isset($param['status']) && $param['status'] == 'reject' ? 'selected' : ''}}>Đã hủy</option>
                                        <option value="finish" {{isset($param['status']) && $param['status'] == 'finish' ? 'selected' : ''}}>Kết thúc</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <select class="form-control select2" name="type">
                                        <option value="">Loại chính sách</option>
                                        <option value="all" {{isset($param['type']) && $param['type'] == 'all' ? 'selected' : ''}}>Tất cả</option>
                                        <option value="cpi" {{isset($param['type']) && $param['type'] == 'cpi' ? 'selected' : ''}}>CPI</option>
                                        <option value="cps" {{isset($param['type']) && $param['type'] == 'cps' ? 'selected' : ''}}>CPS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <select class="form-control select2" name="apply_for">
                                        <option value="">Áp dụng cho</option>
                                        <option value="all"{{isset($param['apply_for']) && $param['apply_for'] == 'all' ? 'selected' : ''}}>Tất cả</option>
                                        <option value="customer"{{isset($param['apply_for']) && $param['apply_for'] == 'customer' ? 'selected' : ''}}>Khách hàng</option>
                                    </select>
                                </div>
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
                            <button type="submit"
                                    class="btn ss--btn-search1 color_button">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-content" >
                @include('referral::PolicyCommission.listCommission')
            </div>
        </div>
    </div>
    @include('admin::product.modal.excel-image')
@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        $('.select2').select2();
    </script>
    <script>
        var commission = {
            editCommission: function (id) {
                $.ajax({
                    url: laroute.route("referral.editCommission"),
                    method: "POST",
                    data: {
                        referral_program_id: id,
                    },
                    success: function (res) {
                        window.location.href = res.link;
                    }
                })
            },
            delete: function (id, name) {
                Swal.fire({
                    title: 'Thông báo',
                    text: "Bạn xác nhận muốn xóa chính sách hoa hồng " + name + "?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                }).then(function (result) {
                    if (result.value){
                        $.ajax({
                            url: laroute.route("referral.deleteCommission"),
                            method: "POST",
                            data: {
                                referral_program_id: id,
                            },
                            success: function (res) {
                                if (res.error == true) {
                                    swal("Lỗi", res.message , "error").then(function () {
                                    });
                                } else{
                                    window.location.href = laroute.route("referral.policyCommission")

                                }
                            }
                        })
                    }

                })
            },
        }
    </script>
@stop
