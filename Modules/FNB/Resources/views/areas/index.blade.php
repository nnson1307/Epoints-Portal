@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('Quản lý phòng bàn') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/todh.css')}}">
    <style>
        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .timepicker {
            border: 1px solid rgb(163, 175, 251);
            text-align: center;
            /* display: inline; */
            border-radius: 4px;
            padding: 2px;
            height: 38px;
            line-height: 30px;
            width: 130px;
        }

        .timepicker .hh, .timepicker .mm {
            width: 50px;
            outline: none;
            border: none;
            text-align: center;
        }

        .timepicker.valid {
            border: solid 1px springgreen;
        }

        .timepicker.invalid {
            border: solid 1px red;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .custom-remind-item {
            color: #575962 !important;
            border: 1px solid #4bb072 !important;
            position: relative;
        }

        .custom-remind-item strong {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .custom-remind-item button {
            color: #575962 !important;
        }

        .custom-remind-item::before {
            content: '';
            position: absolute;
            left: -1px;
            background: #79cca8;
            width: 9px;
            height: calc(100% + 2px);
            top: -1px;
            /* border-radius: 0px 5px 5px 0px; */
            border-radius: 5px;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        .modal .modal-content .modal-body-config {
            padding: 25px;
            max-height: 400px;
            overflow-y: scroll;
        }

        .weekDays-selector input {
            display: none !important;
        }

        .weekDays-selector input[type=checkbox] + label {
            display: inline-block;
            border-radius: 6px;
            background: #dddddd;
            height: 40px;
            width: 30px;
            margin-right: 3px;
            line-height: 40px;
            text-align: center;
            cursor: pointer;
        }

        .weekDays-selector input[type=checkbox]:checked + label {
            background: #2AD705;
            color: #ffffff;
        }

        .table-content-font-a a {
            font-size: 1rem;
        }
        .areaa{
            background-color: #0067AC;
            font-weight: bold;
            font-size:20px;
            margin: 0 15px;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('Quản lý phòng bàn') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('fnb.areas.export')}}"

                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Xuất dữ liệu') }}</span>
                    </span>
                </a>
                <a href="javascript:void(0)" onclick="configColumn.showPopupConfig()"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Tùy chỉnh hiển thị') }}</span>
                    </span>
                </a>
                <a href="javascript:void(0)" onclick="areas.showPopup()"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('Thêm khu vực') }}</span>
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
        <div class="m-portlet__body">
            <div class="menu-bar">
                <div class="areas">
                    <button type="button" class="btn btn-info areaa ">
                        {{ __('Khu vực') }}
                    </button>
                </div>
                <div class="menu">
                    <a href="{{route('fnb.table')}}" class="btn btn-info menu-button table" style="color:black;font-size:20px; margin: 0 20px;">
                        {{ __('Bàn') }}
                    </a>
                </div>
            </div>
            <form method="get" id="frm_export" action="{{route('manager-work.export')}}" style="display: none">

            </form>
            <form id="frm-search" class="frmFilter bg clear-form" autocomplete="OFF">
                <div class="row padding_row">
                    @foreach($listConfigStaff['filter'] as $item)
                        <div class="form-group col-3">
                            @if($item['column_type'] == 'input')
                                <input type="text" class="form-control {{$item['column_class']}}" id="{{$item['column_id']}}" name="{{$item['column_name']}}" placeholder="{{$item[getValueByLang('column_placeholder_')]}}">
                            @elseif($item['column_type'] == 'select2')
                                <select class="form-control {{$item['column_class']}}" id="{{$item['column_id']}}" name="{{$item['column_name']}}">
                                    <option value="">{{$item[getValueByLang('column_placeholder_')]}}</option>
                                    @if($item['column_name'] == 'is_active')
                                        <option value="1">{{__('Đang hoạt động')}}</option>
                                        <option value="0">{{__('Ngừng hoạt động')}}</option>
                                    @elseif($item['column_name'] == 'branch_id')
                                        @foreach($getListBranch as $itemValue)
                                            <option value="{{$itemValue['branch_id']}}">{{$itemValue['branch_name']}}</option>
                                        @endforeach
                                    @elseif(in_array($item['column_name'],['created_by','updated_by']))
                                        @foreach($listStaff as $itemValue)
                                            <option value="{{$itemValue['staff_id']}}">{{$itemValue['full_name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @elseif($item['column_type'] == 'daterange_picker')
                                    <input type="text" class="form-control daterange_picker {{$item['column_class']}}" id="{{$item['column_id']}}" name="{{$item['column_name']}}" placeholder="{{$item[getValueByLang('column_placeholder_')]}}">
                            @endif
                        </div>
                    @endforeach

                    <div class="col-lg-3" style="padding-bottom: 10px;">
                        <a href="{{route('fnb.areas')}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                            {{ __('Xóa bộ lọc') }}
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </a>
                        <button class="btn ss--btn-search">
                            {{ __('Tìm kiếm') }} <i
                                    class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-content table-content-font-a mt-3">
                @include('fnb::areas.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <div class="append-popup"></div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/fnb/areas/script.js?v='.time())}}"></script>
    <script>
        area._init();
    </script>


@stop
