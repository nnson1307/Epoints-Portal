@extends('layout')
@section("after_style")
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('THÔNG TIN DỰ ÁN')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .nav-item:hover {
            /*background-color: #4fc4cb;*/
            transition: 1s;

        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }

        .m-portlet .m-portlet__body {
            padding: 1.2rem 2.2rem;
            background-color: white;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both
        }

        .m-portlet {
            margin-bottom: 0.2rem;
        }

        .column-pie-chart {
            width: 100%;
            font-weight: bold;
        }

        .chart-name {
            font-size: 20px;
            font-weight: bold;
        }


        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 100%;
            border-radius: 5px;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        img {
            border-radius: 5px 5px 0 0;
        }

        .container {
            padding: 2px 16px;
        }

        table, th, td {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        .statistical td {
            border: none;
            /*display:flex*/
        }

        .card-title {
            padding: 10px 20px;
            margin: 0;
        }

        .card-status {
            font-size: 15px;
            color: #5CACEE;
            border: 1px solid #CAE1FF;
            border-radius: 4px;
            background: #CAE1FF;
            margin: 5px;
            padding: 5px 10px !important;
            margin-top: -5px;
        }


        .hight-risk {
            border: 1px solid #FFF0F5;
            background: #FFF0F5;
            border-radius: 5px;
            color: #A0522D;
            font-weight: 600;
        }

        .fs-15 {
            font-size: 15px;
        }

        .style-icon-statistical {
            font-size: 2rem;
            padding: 7px
        }

        .issue {
            border: 1px solid;
            border-radius: 10px;
            padding: 10px;
        }

        .display-flex {
            display: flex;
        }

        .inline-block {
            display: inline-block;
        }

        .edit-name {
            border: none;
            background-color: white;
            color: #66CCFF;
        }

        .edit-name:hover {
            border: none;
            background-color: #66CCFF;
            color: white;
            border-radius: 5px;
            transition: 1s;
            cursor: pointer
        }

        .fa-trash-alt {
            font-weight: 900;
            color: red;
            border: 1px solid white;
            width: 30px;
            height: 30px;
            padding: 7px;
            border-radius: 50%;
            background-color: white;
        }

        .fa-trash-alt:hover {
            cursor: pointer;
            background-color: red;
            color: white;
            transition: 0.5s
        }

        .card-status-important{
            font-size: 15px;
            color: #FFCC00;
            border: 1px solid #FAFAD2;
            border-radius: 4px;
            background: #FAFAD2;
            margin: 5px;
            margin-top: -5px !important;


        }
        .card-status-red{
            font-size: 15px;
            color: red;
            border: 1px solid #EEB4B4;
            border-radius: 4px;
            background: #EEB4B4;
            margin: 5px;
            margin-top: -5px !important;
        }

        .number-status {
            font-size: 35px
        }

        .column-status {
            float: left;
            width: 20%;
            padding: 10px;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .button-issue {
            color: white;
            border: none;
            border-radius: 5px;
            background-color: dodgerblue;
        }

        .button-issue:hover {
            color: white;
            background-color: #0067AC;
            cursor: pointer;
        }

        .processed {
            color: white;
            border: none;
            border-radius: 5px;

        }

    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                        <h3 class="m-portlet__head-text">
                            {{__('THÔNG TIN DỰ ÁN')}}
                        </h3>
                    </div>
                    <div style="    right: 1%;position: absolute;">
                        <a href="{{route('manager-project.project')}}" type="button" class="btn btn-secondary" data-dismiss="modal" style="    color: black;font-weight: bold;">
                            <span class="la 	la-arrow-left"></span>
                            {{__('TRỞ VỀ')}}
                        </a>
                    </div>
                </div>
            </div>
            @include('manager-project::project-info.block-project-info-master')
        </div>
        <div class="m-portlet">
            <div class="m-portlet__head m-portlet__head-update">
                @include('manager-project::layouts.project-info-tab-header')
            </div>
        </div>
        <div class="m-portlet" style="margin-bottom: 0.15rem;padding: 10px">
            <div class="m-portlet__head" style="height: 6.1rem !important">
                <div class="row" style="width:100%">
                    <div class="column-status" style="background-color:#3399FF;    width: 33.33333%;">
                        <p class="mb-0">{{__('NGÂN SÁCH')}}</p>
                        <p class="mb-0 number-status">{{$info['budget'] != [] ? number_format($info['budget']) : 0}}</p>
                    </div>
                    <div class="column-status" style="background-color:#FFCC00;    width: 33.33333%;">
                        <p class="mb-0">{{__('THU')}}</p>
                        <p class="mb-0 number-status">{{isset($info['totalReceipt']) ? number_format($info['totalReceipt']) : 0}}</p>
                    </div>
                    <div class="column-status" style="background-color:#66CC33;    width: 33.33333%;">
                        <p class="mb-0">{{__('CHI')}}</p>
                        <p class="mb-0 number-status">{{isset($info['totalPayment']) ? number_format($info['totalPayment']) : 0}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding-top: 10px;float:right">
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)"
                   onclick="projectInfo.showPopupAddReceipt({{$info['project_id'],'receipt'}})"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Thêm phiếu thu') }}</span>
                    </span>
                </a>
                <a href="javascript:void(0)"
                   onclick="projectInfo.showPopupAddPayment('{{$info['project_id']}}','payment')"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Thêm phiếu chi') }}</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body" style="padding: 0px;padding-top: 50px;">
            <form class="frmFilter ss--background search-work-by-phase">
                <div class="row ss--bao-filter">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input id="config_content" name="search" type="text"
                                       class="form-control m-input class"
                                       value="{{isset($param['search']) ? $param['search'] : ''}}"
                                       title="{{__('Nhập tên người chi hoặc mã phiếu')}}"
                                       placeholder="{{__('Tên người chi hoặc mã phiếu')}}"
                                       aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control select2" name="status">
                                    <option value="">{{__('Trạng thái')}}</option>
                                    <option value="new" {{isset($param['status']) && $param['status'] == 'new' ? 'selected' : ''}}>{{__('Mới')}}</option>
                                    <option value="approved" {{isset($param['status']) && $param['status'] == 'approved' ? 'selected' : ''}}>{{__('Đã xác nhận')}}</option>
                                    <option value="unpaid" {{isset($param['status']) && $param['status'] == 'unpaid' ? 'selected' : ''}}>{{__('Chưa thanh toán')}}</option>
                                    <option value="paid" {{isset($param['status']) && $param['status'] == 'paid' ? 'selected' : ''}}>{{__('Đã thanh toán')}}</option>
                                    <option value="part-paid" {{isset($param['status']) && $param['status'] == 'part-paid' ? 'selected' : ''}}>{{__('Đã thanh toán 1 phần')}}</option>
                                    <option value="cancel" {{isset($param['status']) && $param['status'] == 'cancel' ? 'selected' : ''}}>{{__('Đã hủy')}}</option>
                                    <option value="fail" {{isset($param['status']) && $param['status'] == 'fail' ? 'selected' : ''}}>{{__('Thanh toán không thành công')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control select2" name="staff_id">
                                    <option value="">{{__('Người tạo')}}</option>
                                    @foreach($listStaff as $key => $val)
                                        <option value="{{$val['manager_id']}}" {{isset($param['staff_id']) && $param['staff_id'] == $val['manager_id']? 'selected' : 'Người tạo'}}>{{$val['manager_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control select2" name="branch_id">
                                    <option value="">{{__('Chi nhánh')}}</option>
                                    @foreach( $branch as $key => $val)
                                        <option value="{{$val['branch_id']}}">{{$val['branch_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input readonly="" class="form-control date-picker-list"
                                       id="m_datepicker_1" style="background-color: #fff"
                                       name="created_at" value="" autocomplete="off"
                                       placeholder="{{isset($param['created_at']) && $param['created_at'] != null  ? $param['created_at'] : __('Ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding-right: 15px;padding-top: 15px;padding-bottom: 12px">
                    <div class="text-right">
                        <a href="{{route('manager-project.project.project-info-expenditure',['id'=> $info['project_id']])}}"
                           class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                            {{ __('XÓA BỘ LỌC') }}
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </a>
                        <button
                                class="btn ss--btn-search1 color_button">
                            {{__('TÌM KIẾM')}}
                            <i class="fa fa-search ss--icon-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="m-portlet table-content" id="" style="margin-top: 10px;">
                @include('manager-project::project-info.expenditure-list')
            </div>
        </div>
    </div>
    <input type="hidden" name="project_id" id="project_id" value="{{$info['project_id']}}">
    <div class="append-popup"></div>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/manager-project/info-project/script.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/payment/script.js/script.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/payment/script.js?v='.time())}}"></script>
    <script>
        $('#autotable').PioTable({
            baseUrl: laroute.route('manager-project.project.project-info-expenditure-list',{manage_project_id : $('#project_id').val()})
        });
    </script>


@stop

