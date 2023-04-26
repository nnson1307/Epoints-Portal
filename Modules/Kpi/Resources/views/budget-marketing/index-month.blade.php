@extends('layout')

@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('QUẢN LÝ NGÂN SÁCH MARKETING') }} 
    </span>
@endsection

@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/hao.css') }}" />
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
                        {{ __('NGÂN SÁCH MARKETING') }}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools nt-class">
                @if (in_array('kpi.marketing.budget.month', session('routeList')))
                    <a href="#" class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc btn-add-budget">
                        <span>
                            <i class="fa fa-plus-circle m--margin-right-5"></i>
                            <span> {{ __('THÊM NGÂN SÁCH') }}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>

        <div class="card-header tab-card-header ">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active show" href="{{ route('kpi.marketing.budget.month') }}">Theo tháng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('kpi.marketing.budget.day') }}">Theo ngày</a>
                </li>
            </ul>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter bg">

                <!-- Filter -->
                <div class="row padding_row">

                    <!-- Chọn phòng ban -->
                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="department_id" class="form-control m-input ss--select-2">
                            <option value="">{{ __('Chọn phòng ban') }}</option>
                            @foreach ($DEPARTMENT_LIST as $departmentItem)
                                <option value="{{ $departmentItem['department_id'] }}">{{ $departmentItem['department_name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chọn tháng -->
                    <div class="col-lg-3 form-group">
                        <input type="month" name="effect_time" id="effect_time" class="form-control">
                    </div>

                    <!-- Button tìm kiếm -->
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <a href="{{route('kpi.marketing.budget.month')}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
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

            <!-- Bảng ngân sách -->
            <div class="table-content m--padding-top-30">
                @include('kpi::budget-marketing.components.list-month')
            </div>
        </div>
    </div>

    <!-- Popup chỉnh sửa ngân sách -->
    @include('kpi::budget-marketing.components.edit-month')
    @include('kpi::budget-marketing.components.add-month')
@endsection

@section('after_script')
    <script src="{{ asset('static/backend/js/kpi/budget/script.js?v=' . time()) }}" type="text/javascript">
    </script>
@stop
