@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ LƯƠNG') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css?v='.time())}}">
    <style>
        .color-brown i , .color-brown  {
            color: #9699a2;
        }
        .color-brown:hover{
            text-decoration:unset;
            color: #9699a2;
        }
        .border-drown {
            border: 1px solid #9699a2;
            border-radius: 20px;
            padding: 5px 10px;
        }
        .color-danger i , .color-brown  {
            color: rgb(245, 86, 86);
        }
        .color-danger:hover{
            text-decoration:unset;
            color: rgb(245, 86, 86);
        }
        .border-danger {
            border: 1px solid rgb(245, 86, 86);;
            border-radius: 20px;
            padding: 5px 10px;
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
                        {{ $item->name }}
                    </h3>
                    <a href="javascript:void(0)" onclick="SalaryData.showModal({{$item['salary_id']}})" class="ml-2 color-brown"><i class="fas fa-pen"></i></a>
                    @if($item['is_active'] == 0)
                        <a href="javascript:void(0)" onclick="SalaryData.lockSalary({{$item['salary_id']}})" class="ml-4 color-brown border-brown border-drown"><i class="fas fa-unlock-alt mr-2 "></i> {{__('Chưa khoá')}}</a>
                    @else
                        <a href="javascript:void(0)" class="ml-4 text-danger color-danger border-danger border-danger"><i class="fas text-danger fa-lock mr-2 "></i> {{__('Đã khoá')}}</a>
                    @endif
                </div>
            </div>
            <div class="m-portlet__head-tools">
{{--                <a href="javascript:void(0)"--}}
{{--                   onclick="SalaryData.exportExcel()"--}}
{{--                   class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">--}}
{{--                    <span>--}}
{{--                        <i class="fas fa-print"></i>--}}
{{--                        <span> {{ __('XUẤT DỮ LIỆU') }}</span>--}}
{{--                    </span>--}}
{{--                </a>--}}
                <a href="{{route('salary')}}"
                   class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                    <span>
                        <i class="la la-arrow-left"></i>
                        <span> {{ __('HUỶ') }}</span>
                    </span>
                </a>

                <input type="hidden" name="salary_id" id="salary_id" value="{{$item['salary_id']}}">
                @if($item['is_active'] == 0)
                    <button type="button" onClick="SalaryData.importExcel()"
                            class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                        <span>
                            <i class="fas fa-print"></i>
                            <span> {{ __('IMPORT') }}</span>
                        </span>
                    </button>
                @endif
                <form action="{{route('salary.export-excel-salary')}}" method="POST">
                    @csrf
                    <button type="submit"
                            class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                        <span>
                            <i class="fas fa-print"></i>
                            <span> {{ __('EXPORT') }}</span>
                        </span>
                    </button>
                </form>
                {{-- <div class="dropdown show">
                    <a class="btn ss--button-cms-piospa m-btn--icon mr-3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>
                            <i class="fas fa-print"></i>
                            <span> {{ __('XUẤT DANH SÁCH') }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                      <a class="dropdown-item" href="javascript:void(0)" onclick="SalaryData.exportSalaryCommission({{$item['salary_id']}},'kd')">{{__('HOA HỒNG BÁN HÀNG')}}</a>
                      <a class="dropdown-item" href="javascript:void(0)" onclick="SalaryData.exportSalaryCommission({{$item['salary_id']}},'kt')">{{__('HOA HỒNG LẮP ĐẶT')}}</a>
                    </div>
                  </div> --}}
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background m--margin-bottom-30">
                <input type="hidden" name="page" value="{{ (isset($params['page']) && $params['page'] ) ? $params['page']:'' }}">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <select name="department_id" class="form-control select2 select2-active">
                                        <option value="">@lang('Chọn phòng ban')</option>
                                        @foreach ($department_list as $key => $value )
                                            <option value="{{ $key }}"{{ (isset($params['department_id']) && $params['department_id'] == $key ) ? ' selected':'' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select name="staff_id" class="form-control select2 select2-active">
                                        <option value="">@lang('Chọn nhân viên')</option>
                                        @foreach ($arrStaff as $key => $value)
                                        <option value="{{$key}}" {{ (isset($params['staff_id']) && $params['staff_id'] == $key ) ? ' selected':'' }}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="d-flex">
                                        <button class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                            {{ __('XÓA BỘ LỌC') }}
                                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-primary color_button btn-search" style="display: block">
                                            @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content">
                @include('Salary::salary.list_detail')
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <div id="addModel"></div>
    @include('Salary::salary.modal-excel')
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/salary/salary/import-export.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/salary/salary/list.js?v=' . time()) }}" type="text/javascript"></script>
@stop
