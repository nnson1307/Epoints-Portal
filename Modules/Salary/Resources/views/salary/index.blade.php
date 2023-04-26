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
                        {{ __('BẢNG LƯƠNG') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#modalAdd" onclick="Salary.clear()"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('TÍNH LƯƠNG') }}</span>
                    </span>
                </a>
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
                                    <div class="input-group date">
                                        <input type="text" class="form-control m-input month-picker"
                                               placeholder="@lang('Chọn kỳ lương')" name="salary_period"
                                               value="{{ isset($params['salary_period']) && $params['salary_period'] != '' ? $params['salary_period'] : '' }}" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i
                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="d-flex">
                                        <a class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3" href="{{ route('salary') }}">
                                            {{ __('XÓA BỘ LỌC') }}
                                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                        </a>
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
                @include('Salary::salary.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('Salary::salary.add')
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/salary/salary/import-export.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/salary/salary/list.js?v=' . time()) }}" type="text/javascript"></script>
@stop
