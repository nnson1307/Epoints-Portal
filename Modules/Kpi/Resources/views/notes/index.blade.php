@extends('layout')


@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('QUẢN LÝ PHIẾU GIAO KPI') }} 
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
                        {{ __('DANH SÁCH PHIẾU GIAO KPI') }}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools nt-class">
                @if (in_array('kpi.note.add', session('routeList')))
                    <a href="{{ route('kpi.note.add') }}" class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
                            <i class="fa fa-plus-circle m--margin-right-5"></i>
                            <span> {{ __('TẠO PHIẾU GIAO') }}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter bg">

                <!-- Filter -->
                <div class="row padding_row">

                    <!-- Nhập thông tin tìm kiếm -->
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="kpi_note_name"
                                    placeholder="{{ __('Nhập thông tin tìm kiếm') }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Chọn thời gian tính kpi -->
                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="effect_month" class="form-control m-input ss--select-2">
                            <option value="">{{ __('Chọn thời gian tính kpi') }}</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ __("Tháng ".$i)}}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Chọn phòng ban -->
                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="department_id" class="form-control m-input ss--select-2">
                            <option value="">{{ __('Chọn phòng ban') }}</option>
                            @foreach ($DEPARTMENT_LIST as $departmentItem)
                                <option value="{{ $departmentItem['department_id'] }}">{{ $departmentItem['department_name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chọn trạng thái -->
                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="status" class="form-control m-input ss--select-2">
                            <option value="">{{ __('Chọn trạng thái') }}</option>
                            <option value="N">{{ __('Mới') }}</option>
                            <option value="A">{{ __('Đang áp dụng') }}</option>
                            <option value="D">{{ __('Đã chốt') }}</option>
                        </select>
                    </div>

                    <!-- Button tìm kiếm -->
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <a href="{{route('kpi.note')}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
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
                @include('kpi::notes.components.list')
            </div>
        </div>
    </div>
@endsection

@section('after_script')
    <script src="{{ asset('static/backend/js/kpi/notes/script.js?v=' . time()) }}" type="text/javascript">
    </script>
@stop
