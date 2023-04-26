@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> {{ __('CẤU HÌNH HIỂN THỊ') }}</span>
@stop
@section('after_style')
    <style>
        .m-datatable>.m-datatable__pager>.m-datatable__pager-nav>li>.m-datatable__pager-link.m-datatable__pager-link--active {
            background: #4fc4ca !important;
            color: #ffffff;
        }

        .m-datatable>.m-datatable__pager>.m-datatable__pager-nav>li>.m-datatable__pager-link:hover {
            background: #4fc4ca !important;
            color: #ffffff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #a9a9a9 !important;
        }
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text tab">
                        {{ __('Cài đặt hiển thị') }}
                    </h2>

                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row padding_row frmFilter bg">
                <div class="col-lg-3">
                    <div class="form-group m-form__group input-group">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-search glyphicon-th"></i></span>
                        </div>
                        <input name="name_page" id="namePage" type="text" class="form-control"
                            placeholder="{{ __('Tên trang') }}" value="">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <input name="position" id="position" type="number" class="form-control"
                            placeholder="{{ __('Vị trí trang') }}" value="">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <select type="text" name="type" id="typeTemplate" class="form-control ss--select-2"
                            style="width: 100%">
                            <option value="">{{ __('Loại template') }}</option>
                            @foreach ($typeTemplate as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 d-flex justify-content-end handler_button ">
                    <button onclick="configDisplay.resetResearchDisplay()" class="btn btn-primary color_button_danger "
                        style="font-weight:400; font-size:12px;">
                        {{ __('Xoá bộ lọc') }}
                    </button>
                    <button onclick="configDisplay.loadAll()" class="btn btn-primary color_button btn-search "
                        style="font-weight:400; font-size:12px;">
                        {{ __('Tìm kiếm') }}
                    </button>
                </div>
            </div>
            <div class="row table-content mt-5">

            </div>
        </div>
    </div>
    </div>
@endsection
@section('after_script')
    <script>
        const ID_CONFIG_DISPLAY = ''
    </script>
    <script src="{{ asset('static/backend/js/config-display/main.js?v=' . time()) }}" type="text/javascript">
    </script>
    <script>
        configDisplay.loadAll()
    </script>
@stop
