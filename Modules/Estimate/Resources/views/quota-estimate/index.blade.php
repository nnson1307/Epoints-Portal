@extends('layout')
@section('title_header')
    <span class="title_header">{{ __('CẤU HÌNH SỐ GIỜ LÀM VIỆC') }}</span>
@stop
@section('content')
    <style>
        .color_button_tab {
            background-color: #fff !important;
            color: #000 !important;
            border-color: #4fc4ca !important;
            font-weight: bold;
            font-size: 11px;

        }
        .color_button_tab.active {
            background-color: #4fc4ca!important;
            color: #fff!important;
        }
        .btn.btn-primary.color_button_tab:hover {
            color: #000 !important;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{ __('CẤU HÌNH SỐ GIỜ LÀM VIỆC & NGÂN SÁCH LƯƠNG DỰ KIẾN') }}
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if (in_array('admin.branch.add', session('routeList')))
                    <a href="javascript:void(0)" onclick="estimate.showModalAdd(this);" data-branch="{{ $branchId }}"
                    class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span>{{ __('THÊM CẤU HÌNH') }}</span>
                                    </span>
                    </a>
                    
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="row">
                <div class="col-sm-10">
                    <div>
                        <button class="btn btn-primary color_button_tab active" id="week-estimate-btn" data-id="{{ $branchId }}"
                            value="{{ route('estimate.quota.quota-estimate.list-week') }}">{{ __('Tuần') }}</button>
                        <button class="btn btn-primary color_button_tab" id="month-estimate-btn" data-id="{{ $branchId }}"
                            value="{{ route('estimate.quota.quota-estimate.list-month') }}">{{ __('Tháng') }}</button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <select class="form-control" 
                        id="filter-year"
                        data-id="{{ $branchId }}"
                        value="{{ route('estimate.quota.quota-estimate.list-week') }}">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ \Carbon\Carbon::now()->year == $year ? 'selected' : '' }}>{{ __('Năm') }} {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <form action=""></form>

            <div class="table-content m--padding-top-30" id="estimate-list">
                @include('estimate::quota-estimate.list')
            </div>
        </div>
    </div>

    <!-- Popup thêm hoặc chỉnh sửa cấu hình -->
    <div id="modal-estimate-add"></div>
    {{-- @include('estimate::quota-estimate.popup.add') --}}
    <div id="modal-estimate-edit"></div>
@stop
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/hao.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/customize.css') }}">

    <style>
        .modal {
            overflow-y: auto !important;
        }

    </styl>

@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{ asset('static/backend/js/estimate/quota/script.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/estimate/quota/event.js?v=' . time()) }}" type="text/javascript"></script>
@stop
