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

        .banner {
            max-width: 100px;
            overflow: hidden;
        }

        .banner .banner__image {
            width: 100%;
            object-fit: cover;
            border-radius: 4px;
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
                        {{ __('DANH sách banner') }}
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
                        <input name="mainTitle" id="mainTitle" type="text" class="form-control"
                            placeholder="{{ __('Nhập tiêu đề chính ') }}" value="">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group m-form__group input-group date">
                        <input id="created_at" name="created_at" type="text" style="background-color: #fff"
                            class="form-control m-input daterange-picker" placeholder="{{ __('Chọn ngày tạo') }}" readonly
                            value="">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <select type="text" name="status" id="status" class="form-control ss--select-2"
                            style="width: 100%">
                            <option value="">{{ __('Chọn trạng thái') }}</option>
                            <option value="1">{{ __('Hoạt động') }}</option>
                            <option value="0">{{ __('Ngưng hoạt động') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 d-flex justify-content-end handler_button ">
                    <button onclick="configDisplayDetail.resetResearchDisplayDetail()"
                        class="btn btn-primary color_button_danger " style="font-weight:400; font-size:12px;">
                        {{ __('Xoá bộ lọc') }}
                    </button>
                    <button onclick="configDisplayDetail.loadAll()" class="btn btn-primary color_button btn-search "
                        style="font-weight:400; font-size:12px;">
                        {{ __('Tìm kiếm') }}
                    </button>
                </div>
            </div>
            <div class="row table__content--detail mt-5">

            </div>
        </div>
    </div>
    </div>
@endsection
@section('after_script')
    <script>
        const ID_CONFIG_DISPLAY = '{{ $id }}'
        const SITE = 'show'
    </script>
    <script>
        $.getJSON(laroute.route('admin.validation'), function(json) {
            $("#created_at").daterangepicker({
                autoApply: true,
                singleDatePicker: true,
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: [
                        json.content.CN,
                        json.content.T2,
                        json.content.T3,
                        json.content.T4,
                        json.content.T5,
                        json.content.T6,
                        json.content.T7
                    ],
                    "monthNames": [
                        json.content.month_1,
                        json.content.month_2,
                        json.content.month_3,
                        json.content.month_4,
                        json.content.month_5,
                        json.content.month_6,
                        json.content.month_7,
                        json.content.month_8,
                        json.content.month_9,
                        json.content.month_10,
                        json.content.month_11,
                        json.content.month_12
                    ],
                    "firstDay": 1
                }
            });
            @if (!isset($filters['created_at']))
                $('#created_at').val('');
            @endif
        });
    </script>
    <script src="{{ asset('static/backend/js/config-display/main.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        configDisplayDetail.loadAll()
    </script>
@stop
