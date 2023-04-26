@extends('layout')

@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/hao.css') }}" />
@endsection

@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('QUẢN LÝ TIÊU CHÍ TÍNH KPI') }}
    </span>
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
                        {{ __('DANH SÁCH TIÊU CHÍ TÍNH KPI') }}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools nt-class">
                @if (in_array('kpi.criteria.list', session('routeList')))
                    <a href="#" class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc btn-add-criteria">
                        <span>
                            <i class="fa fa-plus-circle m--margin-right-5"></i>
                            <span> {{ __('TẠO TIÊU CHÍ') }}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter bg">

                <!-- Filter tiêu chí -->
                <div class="row padding_row">

                    <!-- Nhập thông tin tìm kiếm -->
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="kpi_criteria_name"
                                    placeholder="{{ __('Nhập thông tin tìm kiếm') }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Chọn chiều hướng -->
                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="kpi_criteria_trend" class="form-control m-input ss--select-2">
                            <option value="">{{ __('Chọn chiều hướng tốt') }}</option>
                            <option value="0">{{ __('Giảm') }}</option>
                            <option value="1">{{ __('Tăng') }}</option>
                        </select>
                    </div>

                    <!-- Chọn trạng thái -->
                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="status" class="form-control m-input ss--select-2">
                            <option value="">{{ __('Chọn trạng thái') }}</option>
                            <option value="0">{{ __('Ngưng hoạt động') }}</option>
                            <option value="1">{{ __('Hoạt động') }}</option>
                        </select>
                    </div>

                    <!-- Button tìm kiếm -->
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <a href="{{route('kpi.criteria')}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                {{ __('XÓA BỘ LỌC') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </a>
                            <button class="btn btn-primary color_button btn-search">{{ __('TÌM KIẾM') }} <i
                                    class="fa fa-search ic-search m--margin-left-5"></i></button>
                        </div>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible"><strong>{{ __('Success') }} : </strong>
                        {!! session('status') !!}.</div>
                @endif
            </form>

            <!-- Bảng danh sách tiêu chí -->
            <div class="table-content m--padding-top-30">
                @include('kpi::criteria.components.list')
            </div>
        </div>
    </div>

    <!-- Popup chỉnh sửa tiêu chí -->
    @include('kpi::criteria.components.edit')

    <!-- Popup thêm tiêu chí -->
    @include('kpi::criteria.components.add')
@endsection

@section('after_script')
    <script src="{{ asset('static/backend/js/kpi/criteria/script.js?v=' . time()) }}" type="text/javascript"></script>
@stop

