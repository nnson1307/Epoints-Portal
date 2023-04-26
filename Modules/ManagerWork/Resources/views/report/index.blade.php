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
                        {{__('BÁO CÁO CHI TIẾT CÔNG VIỆC THEO NHÂN VIÊN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('manager-work.report.export')}}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <span> {{__('Export')}}</span>
                    </span>
                </a>
            </div>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter">
                <div class="row">
                    <div class="col-3 form-group">
                        <input type="text" class="form-control searchDate" name="dateSelect" value="{{$filter['dateSelect']}}">
                    </div>
                    <div class="col-3 form-group">
                        <select class="form-control searchSelect" name="branch_id" id="branch_id">
                            <option value="">{{__('Chi nhánh')}}</option>
                            @foreach($listBranch as $item)
                                <option value="{{$item['branch_id']}}" {{ $item['branch_id'] == $filter['branch_id'] ? 'selected' : '' }}>{{$item['branch_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 form-group">
                        <select class="form-control searchSelect" name="department_id" id="department_id">
                            <option value="">{{__('Phòng ban')}}</option>
                            @foreach($listDepartment as $item)
                                <option value="{{$item['department_id']}}" {{ $item['department_id'] == $filter['department_id'] ? 'selected' : '' }}>{{$item['department_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 form-group">
                        <select class="form-control searchSelect" name="staff_id" id="staff_id">
                            <option value="">{{__('Nhân viên')}}</option>
                            @foreach($listStaff as $item)
                                <option value="{{$item['staff_id']}}">{{$item['full_name']}}</option>
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

                <input type="hidden" id="sort_key" name="sort_key" value="{{$filter['sort_key']}}">
                <input type="hidden" id="sort_type" name="sort_type" value="{{$filter['sort_type']}}">

                <div class="table-content m--padding-top-30">
                    @include('manager-work::report.list')
                </div>
            </form>
        </div>
        <div class="popup"></div>
    </div>

@stop
@section('after_script')
    <script>
        var branch_id = '{{\Illuminate\Support\Facades\Auth::user()->branch_id}}';
        var department_id = '{{\Illuminate\Support\Facades\Auth::user()->department_id}}';
    </script>
    <script src="{{asset('static/backend/js/manager-work/report/script.js?v='.time())}}" type="text/javascript"></script>

@stop
