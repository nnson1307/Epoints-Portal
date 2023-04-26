@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ CHIẾN DỊCH ZNS') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phieu-custom.css') }}">
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
                        {{ __('ZALO NOTIFICATION SERVICE (ZNS) BỊ ĐỘNG') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background m--margin-bottom-30">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-3 form-group">
                            <div class="m-form__group">
                                <div class="input-group">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control" name="search"
                                        placeholder="{{ __('Nhập thông tin tìm kiếm') }}"
                                        value="{{ isset($params['search']) && $params['search'] ? $params['search'] : '' }}">
                                    <input type="hidden" name="page"
                                        value="{{ isset($params['page']) && $params['page'] ? $params['page'] : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <select name="is_active" class="form-control select2 select2-active-choose-first">
                                <option value="">@lang('Chọn trạng thái')</option>
                                @foreach ($status_config as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ isset($params['is_active']) && $params['is_active'] == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="d-flex">
                                <a href="{{ route('zns.config') }}"
                                    class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
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
            </form>
            <div class="table-content">
                @include('zns::config.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <form class="modal" id="edit-config"></form>
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/zns/config/list.js?v=' . time()) }}" type="text/javascript"></script>
@stop
