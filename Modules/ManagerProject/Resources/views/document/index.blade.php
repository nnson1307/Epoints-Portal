@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section('after_style')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
{{--    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">--}}
{{--    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">--}}
<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
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
        .fa-trash-alt{
            font-weight: 900;
            color: red;
            border: 1px solid white;
            width: 30px;
            height: 30px;
            padding: 7px;
            border-radius: 50%;
            background-color: white;
        }
        .fa-trash-alt:hover{
            cursor:pointer;
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
        .number-status{
            font-size: 35px
        }
        .column-status {
            float: left;
            width: 25%;
            padding: 10px;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .datepicker{
            width: 310px;
        }

        .select2 {
            width : 100% !important;
        }
    </style>
@endsection
@section('content')
<div class="m-portlet" >
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
<div class="m-portlet" >
    <div class="m-portlet__head m-portlet__head-update">
        @include('manager-project::layouts.project-info-tab-header')
    </div>
</div>
<div class="m-portlet"  style="margin-bottom: 0.15rem;padding: 10px">
    <div class="m-portlet__head" style="height: 6.1rem !important">
        <div class="row" style="width:100%">
            <div class="column-status" style="background-color:#3399FF;">
                <p  class="mb-0">{{__('CÔNG VIỆC')}}</p>
                <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['totalWork'] : 0}}</p>
            </div>
{{--            <div class="column-status" style="background-color:#FFCC00;">--}}
{{--                <p class="mb-0">{{__('CÓ NGUY CƠ TRỄ HẠN')}}</p>--}}
{{--                <p class="mb-0 number-status">{{ $info['work-duration'] != [] ? $info['work-duration']['workMayBeLate'] : 0}}</p>--}}
{{--            </div>--}}
            <div class="column-status" style="background-color:#66CC33;">
                <p class="mb-0">{{__('HOÀN THÀNH ĐÚNG HẠN')}}</p>
                <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workCompleteOnTime'] : 0}}</p>
            </div>
            <div class="column-status" style="background-color:#CC33FF;">
                <p class="mb-0">{{__('HOÀN THÀNH TRỄ HẠN')}}</p>
                <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workCompleteLate'] : 0}}</p>
            </div>
            <div class="column-status" style="background-color:#FF3300;">
                <p class="mb-0">{{__('ĐÃ TRỄ HẠN')}}</p>
                <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workOutOfDate'] : 0}}</p>
            </div>
        </div>
    </div>
</div>
    <div class="m-portlet" id="autotable">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
{{--                <span class="m-portlet__head-icon">--}}
{{--                    <i class="la la-th-list"></i>--}}
{{--                </span>--}}
{{--                <h3 class="m-portlet__head-text">--}}
{{--                    {{ __('managerproject::managerproject.document') }}--}}
{{--                </h3>--}}
            </div>
        </div>
        @if(in_array(\Auth::id(),$listStaffProject))
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="Document.showPopup()"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Thêm tài liệu') }}</span>
                    </span>
                </a>
            </div>
        @endif
    </div>
    <div class="m-portlet__body">
        <form id="frm-search-document" class="frmFilter bg clear-form">
            <div class="row padding_row">
                <div class="col-lg-2">
                    <div class="form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input class="form-control"
                                    style="background-color: #fff" name="keyword"
                                    value=""
                                    autocomplete="off"
                                    placeholder="{{__('Nhập tên tài liệu')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                            <span><i class="la la-search"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <div class="m-input-icon m-input-icon--right daterange-picker">
                            <input readonly class="form-control daterange-picker-list"
                                    style="background-color: #fff" name="updated_at"
                                    value=""
                                    autocomplete="off"
                                    placeholder="{{__('Chọn ngày cập nhật')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                            <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <select name="created_by"
                                class="form-control select2 select2-active">
                            <option value="">{{__('Người tạo')}}</option>
                            @foreach($staffList as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <select name="type"
                                class="form-control select2 select2-active">
                            <option value="">{{__('Loại tài liệu')}}</option>
                            @foreach($docType as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                            <option value="link">Link</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <input type="hidden" name="manage_project_id" value="{{ request()->input('manage_project_id') }}">
                    <a href="javascript:void(0)" onclick="Document.clear()" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                        {{ __('XÓA') }}
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="Document.search()" class="btn btn-primary color_button btn-search">
                        {{ __('Tìm kiếm') }} <i class="fa fa-search ic-search"></i>
                    </a>
                </div>
            </div>
        </form>
        <div class="table-content table-content-font-a mt-3">
            <div class="append-list-document">
            @include('manager-project::work.append.append-list-document')
            </div>
        </div>
        <form id="form-file" autocomplete="off">
            <div id="block_append"></div>
            <input type="hidden" id="manage_project" name="manage_project_id" value="{{ request()->input('manage_project_id') }}">
        </form>
    </div>
    <div class="append_popup_show"></div>
    <script type="text/template" id="imageShow">
        <div class="image-show col-3">
            <img class="img-fluid" src="{link}">
            <p class="name_file">{file_name}</p>
            <input type="hidden" class="path" value="{link}">
            <input type="hidden" class="file_name" value="{file_name}">
            <input type="hidden" class="file_type" value="image">
            <span class="delete-img-document" style="display: block;">
                <a href="javascript:void(0)" onclick="Document.removeImage(this)">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </span>
        </div>
    </script>
    <script type="text/template" id="imageShowFile">
        <div class="image-show col-3">
            <img src="{{asset('static/backend/images/document.png')}}">
            <p class="name_file">{file_name}</p>
            <input type="hidden" class="file_name" value="{file_name}">
            <input type="hidden" class="file_type" value="file">
            <input type="hidden" class="path" value="{link}">
            <span class="delete-img-document" style="display: block;">
                <a href="javascript:void(0)" onclick="Document.removeImage(this)">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </span>
        </div>
    </script>
</div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{ asset('static\backend/js/manager-project/managerWork/table-excel/jquery.table2excel.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work-document.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script>
        // $('.btn-search').trigger('click');
    </script>
@stop
