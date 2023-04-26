@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
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
        }
        .card-status-red{
            font-size: 15px;
            color: red;
            border: 1px solid #EEB4B4;
            border-radius: 4px;
            background: #EEB4B4;
            margin: 5px;
        }
        .number-status{
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
        .datepicker{
            width: 310px;
        }

        .select2 {
            width : 100% !important;
        }

    </style>
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
            </div>
        </div>
        @include('manager-project::project-info.block-project-info-master')
    </div>
    <div class="m-portlet" >
        <div class="m-portlet__head">
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
                <div class="column-status" style="background-color:#FFCC00;">
                    <p class="mb-0">{{__('CÓ NGUY CƠ TRỄ HẠN')}}</p>
                    <p class="mb-0 number-status">{{ $info['work-duration'] != [] ? $info['work-duration']['workMayBeLate'] : 0}}</p>
                </div>
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
    <div style="padding-top: 10px;float:right">
        <div class="m-portlet__head-tools">
            <a href="javascript:void(0)" onclick="ManagerWork.configSearch()"
               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Tùy chỉnh hiển thị') }}</span>
                    </span>
            </a>
            <a href=""

               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Danh sách') }}</span>
                    </span>
            </a>
            <a href="javascript:void(0)" onclick="ManagerWork.exportList()"

               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Xuất dữ liệu') }}</span>
                    </span>
            </a>
            <a href="javascript:void(0)" onclick="WorkChild.showPopup()"
               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Thêm công việc') }}</span>
                    </span>
            </a>
            <a href="{{route('fnb.areas')}}" data-toggle="modal" data-target="#modalAdd"
               class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
               style="display: none">
                <i class="fa fa-plus-circle" style="color: #fff"></i>
            </a>
        </div>
    </div>
    <div class="m-portlet__body" id="autotable" style="padding: 0px;padding-top: 50px;">
        <form method="get" id="frm_export" action="{{route('manager-project.work.export')}}" style="display: none">

        </form>
        <form id="frm-search" class="frmFilter clear-form">
            <div class="row padding_row">
                <div class="bg col-12 pt-3">
                    <div class="row">
                        <input type="hidden" name="manage_project_id" id="main_manage_project_id" value="{{isset($info['project_id']) ? $info['project_id'] : ''}}">
                        <input type="hidden" name="manage_project_phase_id" id="main_manage_project_phase_id" value="">
                        @foreach ($searchConfig as $config)
                            @if ($config['active'])
                                @if ($config['type'] == 'select2')
                                    @if ($config['name'] == 'customer_id')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="manage_work_customer_type" id="manage_work_customer_type_list"
                                                        onchange="WorkAll.changeCustomerList()"
                                                        class="form-control select2 select2-active">
                                                    <option value="customer">{{ __('managerwork::managerwork.customer') }}</option>
                                                    <option value="lead">{{ __('managerwork::managerwork.lead') }}</option>
                                                    <option value="deal">{{ __('managerwork::managerwork.deal') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="{{ $config['name'] }}" id="select_customer_id"
                                                        class="form-control select2 select2-active">
                                                    <option value="">{{ $config['placeholder'] }}</option>
                                                    {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                    @foreach ($config['data'] as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif ($config['name'] == 'processor_id')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="{{ $config['name'] }}"
                                                        class="form-control select2 select2-active">
                                                    <option value="">{{ $config['placeholder'] }}</option>
                                                    {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                    @foreach ($config['data'] as $key => $value)
                                                        <option value="{{ $key }}" {{isset($filter['processor_id']) && $filter['processor_id'] == $key ? 'selected' : ''}}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif ($config['name'] == 'department_id')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="{{ $config['name'] }}"
                                                        class="form-control select2 select2-active">
                                                    <option value="">{{ $config['placeholder'] }}</option>
                                                    @foreach ($config['data'] as $key => $value)
                                                        <option value="{{ $key }}"
                                                                {{isset($filter['department_id']) && $filter['department_id'] == $key ? 'selected' : ''}}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif ($config['name'] == 'manage_project_id')
                                        <input type="hidden" name="manage_project_id" value="{{isset($filter['manage_project_id']) ? $filter['manage_project_id'] : ''}}">
                                    @elseif ($config['name'] == 'manage_status_id')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="{{ $config['name'] }}[]"
                                                        class="form-control select2 select2-active" multiple>
                                                    <option value="">{{ $config['placeholder'] }}</option>
                                                    {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                    @foreach ($config['data'] as $key => $value)

                                                        <option value="{{ $key }}" {{isset($filter['manage_status_id']) && in_array($key,$filter['manage_status_id']) ? 'selected' : ''}}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif ($config['name'] == 'assign_by')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="{{ $config['name'] }}"
                                                        class="form-control select2 select2-active">
                                                    <option value="">{{ $config['placeholder'] }}</option>
                                                    {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                    @foreach ($config['data'] as $key => $value)
                                                        <option value="{{ $key }}" {{isset($param['assign_by']) && $param['assign_by'] == $key ? 'selected' : ''}}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="{{ $config['name'] }}"
                                                        class="form-control select2 select2-active">
                                                    <option value="">{{ $config['placeholder'] }}</option>
                                                    {{--                                            <option value="all">{{ $config['placeholder'] }}</option>--}}
                                                    @foreach ($config['data'] as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($config['type'] == 'daterange_picker')

                                    @if($config['name'] == 'date_end')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input readonly class="form-control daterange-picker-list"
                                                           style="background-color: #fff" name="{{ $config['name'] }}"
                                                           value="{{isset($filter['date_end']) ? $filter['date_end'] : ''}}"
                                                           autocomplete="off"
                                                           placeholder="{{ $config['placeholder'] }}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                                <span><i class="la la-calendar"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($config['name'] == 'created_at')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input readonly class="form-control daterange-picker-list"
                                                           style="background-color: #fff" name="{{ $config['name'] }}"
                                                           value="{{isset($filter['created_at']) ? $filter['created_at'] : ''}}"
                                                           autocomplete="off"
                                                           placeholder="{{ $config['placeholder'] }}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input readonly class="form-control daterange-picker-list"
                                                           style="background-color: #fff" name="{{ $config['name'] }}"
                                                           autocomplete="off"
                                                           placeholder="{{ $config['placeholder'] }}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                                <span><i class="la la-calendar"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($config['type'] == 'date_picker')
                                    @if($config['name'] == 'date_end')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input readonly class="form-control date-picker-list"
                                                           style="background-color: #fff" name="{{ $config['name'] }}"
                                                           value="{{isset($filter['date_end']) ? $filter['date_end'] : ''}}"
                                                           autocomplete="off"
                                                           placeholder="{{ $config['placeholder'] }}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($config['name'] == 'date_start')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input readonly class="form-control date-picker-list"
                                                           style="background-color: #fff" name="{{ $config['name'] }}"
                                                           value="{{isset($filter['date_start']) ? $filter['date_start'] : ''}}"
                                                           autocomplete="off"
                                                           placeholder="{{ $config['placeholder'] }}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($config['name'] == 'created_at')
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input readonly class="form-control date-picker-list"
                                                           style="background-color: #fff" name="{{ $config['name'] }}"
                                                           value="{{isset($filter['created_at']) ? $filter['created_at'] : ''}}"
                                                           autocomplete="off"
                                                           placeholder="{{ $config['placeholder'] }}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="m-input-icon m-input-icon--right">
                                                    <input readonly class="form-control date-picker-list"
                                                           style="background-color: #fff" name="{{ $config['name'] }}"
                                                           autocomplete="off"
                                                           placeholder="{{ $config['placeholder'] }}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($config['type'] == 'text')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="{{ $config['name'] }}"
                                                   placeholder="{{ $config['placeholder'] }}">
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        @if(isset($filter['type-search']))
                            <input type="hidden" name="type-search" value="{{$filter['type-search']}}">
                        @endif
                        @if(isset($filter['type-page']))
                            <input type="hidden" name="type-page" value="{{$filter['type-page']}}">
                        @endif

                        <div class="col-lg-3 mb-3">
                            <a href="{{route('manager-project.project.project-info-work',['manage_project_id' => $info['project_id']])}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                {{ __('managerwork::managerwork.remove_filter') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </a>
                            <button class="btn btn-primary color_button btn-search">
                                {{ __('managerwork::managerwork.search') }} <i
                                        class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-content table-content-font-a mt-3">
                @include('manager-project::project-info.work-list')
            </div>
        </form>
    </div>
    @include('manager-project::work.popup.config_search')
    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{ asset('static\backend/js/manager-project/managerWork/table-excel/jquery.table2excel.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}"
            type="text/javascript"></script>

    <script>
        $('.select2').select2();

        $('#m_datepicker_1').datepicker({format: 'dd/mm/yyyy'});

        $('#m_datepicker_2').datepicker({format: 'dd/mm/yyyy'});

    </script>
@stop
