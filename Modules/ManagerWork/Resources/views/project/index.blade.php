@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
            style="height: 20px;">
        {{ __('managerwork::managerwork.manage_work') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phu-custom.css') }}">
    <style>
        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .ss--text-center {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('DANH SÁCH DỰ ÁN') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="Project.configList()"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-cog"></i>
                        <span> {{ __('TUỲ CHỈNH HIỂN THỊ') }}</span>
                    </span>
                </a>
                <a href="{{ route('manager-work.project.add') }}"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('THÊM DỰ ÁN') }}</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="frmFilter ss--background m--margin-bottom-30">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                @if (count($listColumnConfig['listColumnSearchConfig']) > 0)
                                    @foreach ($listColumnConfig['listColumnSearchConfig'] as $item)
                                        <div class="col-lg-3 form-group">
                                            @if ($item['type'] == 'select2')
                                                @include('manager-work::project.colum_config.select')
                                            @elseif($item['type'] == 'text_group')
                                                @include('manager-work::project.colum_config.text_group')
                                            @elseif($item['type'] == 'date')
                                                @include('manager-work::project.colum_config.date')
                                            @endif
                                        </div>
                                    @endForeach
                                @endif

                                <div class="col-lg-2 form-group">
                                    <div class="d-flex">
                                        <button onclick="Project.refresh()"
                                            class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                            {{ __('XÓA BỘ LỌC') }}
                                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                        </button>
                                        <button onclick="Project.search()" class="btn ss--button-cms-piospa m-btn--icon">
                                            {{ __('TÌM KIẾM') }}
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-content">
                @include('manager-work::project.list')
            </div><!-- end table-content -->
        </div>
    </div>
    <!-- Modal config !-->
    <div id="modal">
        @include('manager-work::project.popup.config_list')
    </div>
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/manager-work/project/list.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        $('.select2').select2();
    </script>
@stop
