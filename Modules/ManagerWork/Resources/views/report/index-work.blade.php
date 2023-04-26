@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('Báo cáo')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <style>
        .modal .select2.select2-container,.select2-search__field{
            width: 100% !important;
        }
        .nav-tabs .nav-item:hover , .sort:hover {
            cursor: pointer;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link.active {
            color:#6f727d;
            border-bottom: #6f727d;
            background: #EEF3F9;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link {
            padding: 15px;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
        .btn {
            font-family: "Roboto" !important;
        }
        .sort{
            border: 0;
            background: 0;
        }

        .m-checkbox > input:checked ~ span {
            border: 1px solid #4fc4ca;
        }

        .m-checkbox > input:disabled ~ span:after {
            border-color: #4fc4ca;
        }

        .m-checkbox > span {
            border: 1px solid #4fc4ca;
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
                        {{__('DANH SÁCH CHI TIẾT CÔNG VIỆC THEO NHÂN VIÊN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
{{--                <a href="{{route('manager-work.report.export')}}"--}}
{{--                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">--}}
{{--                    <span>--}}
{{--                        <span> {{__('Export')}}</span>--}}
{{--                    </span>--}}
{{--                </a>--}}
            </div>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter">
                <div class="row">
                    <div class="col-3 form-group">
                        <input type="text" class="form-control searchDateList" name="dateSelect" value="{{$filter['dateSelect']}}">
                    </div>
                    <div class="col-3 form-group">
                        <select class="form-control" name="type_card_work" id="type_card_work">
                            <option value="">{{__('Loại thẻ')}}</option>
                            <option value="bonus">{{__('Thường')}}</option>
                            <option value="kpi">{{__('Kpi')}}</option>
                        </select>
                    </div>
                    <div class="col-3 form-group">
                        <select class="form-control" name="type_work" id="type_work">
                            <option value="">{{__('Tình trạng')}}</option>
                            <option value="finish" {{isset($filter['type_work']) && $filter['type_work'] == 'finish' ? 'selected' : ''}}>{{__('Hoàn thành đúng tiến độ')}}</option>
                            <option value="finish_overdue" {{isset($filter['type_work']) && $filter['type_work'] == 'finish_overdue' ? 'selected' : ''}}>{{__('Hoàn thành quá hạn')}}</option>
                            <option value="unfinish" {{isset($filter['type_work']) && $filter['type_work'] == 'unfinish' ? 'selected' : ''}}>{{__('Chưa hoàn thành')}}</option>
                            <option value="overdue" {{isset($filter['type_work']) && $filter['type_work'] == 'overdue' ? 'selected' : ''}}>{{__('Quá hạn')}}</option>
                        </select>
                    </div>
                    <div class="col-3 form-group">
                        <select class="form-control" name="manage_status_id" id="manage_status_id">
                            <option value="">{{__('Trạng thái')}}</option>
                            @foreach($listStatusActive as $item)
                                <option value="{{$item['manage_status_id']}}" {{isset($filter['manage_status_id']) && $filter['manage_status_id'] == $item['manage_status_id'] ? 'selected' : ''}}>{{$item['manage_status_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 text-right  form-group">
                        <button onclick="Report.refresh()" class="btn  btn-refresh btn-danger">
                            {{ __('Xóa bộ lọc') }}
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-primary color_button btn-search">
                            @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>

                <div class="table-content m--padding-top-30">
                    @include('manager-work::report.list-work')
                </div>
                <input type="hidden" name="staff_id" value="{{$filter['staff_id']}}">
            </form>
        </div>
        <div class="popup"></div>
    </div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/manager-work/report/script-work.js?v='.time())}}" type="text/javascript"></script>
@stop
