@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> {{ __('CHƯƠNG TRÌNH TÍCH ĐIỂM') }}</span>
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

        .primary-color {
            color: #027177 !important;
            font-weight: 500;
        }

        .primary-color_default {
            color: #027177 !important;
        }

        .type_select_question {
            color: #000000;
            font-weight: bold;
            background-color: #DFF7F9;
            text-align: center;
            font-size: 16px;
        }

        .btn-remove-question i {
            color: rgba(255, 0, 0, 0.66);
            font-size: 20px;
        }

        .btn-remove-question {
            transition: all 0.5s;
        }

        .btn-remove-question:hover i {
            color: #ff0101;
        }

        .handle-question .btn-copy-question {
            color: #787878;
        }

        .m-radio>span:after {
            background: #4FC4CA !important;
            border: 1px solid #4FC4CA !important;
        }

        .m-radio>span {
            border: 1px solid #4FC4CA !important;
        }

        @media only screen and (max-width: 1400px) {

            html,
            body {
                overflow: auto;
            }
        }

        #modal-survey .modal-body .title {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            color: #000000;
        }

        #modal-survey .modal-body .description {
            font-size: 14px;
            font-weight: 400;
            text-align: center;
            align-content: center;
            color: #000000;
        }

        .color_button_destroy {
            background-color: #FE4C4C !important;
            color: #fff;
            border-color: #FE4C4C !important;
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
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text tab">
                        {{ __('DANH SÁCH CHƯƠNG TRÌNH TÍCH ĐIỂM') }}
                    </h2>
                </div>
            </div>

            <div class="m-portlet__head-tools">
                <a href="{{ route('loyalty.accumulate-points.create') }}"
                    class="btn btn-info btn-sm m-btn m-btn--icon  color_button">
                    <span>
                        <i class="fa fa-plus-circle mr-3"></i>
                        <span style="font-weight:400; font-size:12px;">{{ __('TẠO') }}</span>
                    </span>
                </a>
            </div>

        </div>
        <div class="m-portlet__body">
            <div class="row padding_row frmFilter bg">
                <div class="col-lg-2">
                    <div class="form-group m-form__group input-group">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-search glyphicon-th"></i></span>
                        </div>
                        <input name="name_program" type="text" class="form-control"
                            placeholder="{{ __('Nhập tên chương trình') }}" value="">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class=" form-group input-group date" style="margin-right:30px">
                        <input type="text" class="form-control m-input" id="time_start"
                            placeholder="{{ __('Chọn thời gian tạo') }}" name="time_start">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class=" form-group input-group date">
                        <input type="text" class="form-control m-input" id="time_end"
                            placeholder="{{ __('Chọn thời gian kết thúc') }}" name="time_end">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group m-form__group">
                        <select class="form-control ss--width-100 ss--select-2" id="status" name="status">
                            <option value="">{{ __('Chọn trạng thái') }}</option>
                            <option value="1">
                                {{ __('Hoạt động') }}
                            </option>
                            <option value="0">
                                {{ __('Ngưng hoạt động') }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 d-flex justify-content-end handler_button ">
                    <button onclick="loyalty.resetSearchLoyalty()" class="btn btn-primary color_button_danger "
                        style="font-weight:400; font-size:12px;">
                        @lang('survey::survey.index.delete')
                    </button>
                    <button onclick="loyalty.loadListLoyalty()" class="btn btn-primary color_button btn-search "
                        style="font-weight:400; font-size:12px;">
                        @lang('survey::survey.index.search')
                    </button>
                </div>
            </div>
            <div class="row table-content mt-5">
            </div>
        </div>
    </div>
    <div id="modal__destroy--show">

    </div>
    </div>
    </div>
@endsection
@section('after_script')
    <script src="{{ asset('static/backend/js/loyalty/script.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/jquery.mask.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        loyalty.loadListLoyalty();
    </script>
@stop
