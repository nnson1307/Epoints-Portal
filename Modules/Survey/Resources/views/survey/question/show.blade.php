@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> {{ __('QUẢN LÝ KHẢO SÁT') }}</span>
@stop
@section('after_style')
    <link href="{{ asset('static/backend/css/survey/survey.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .kt-avatar .kt-avatar__upload {
            width: 25px;
            height: 25px;
        }

        html,
        body {
            overflow: hidden;
        }

        .btn-danger {
            margin: 0 10px !important;
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

        .count-point i.fa {
            color: #4FC4CA !important;
            font-size: 20px;
            margin-right: 10px;
            padding-left: 1.25rem;
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


        .icon-check__answer--success {
            position: absolute;
            right: 14px;
            font-size: 20px;
        }

        .icon-check__answer--hiden {
            display: none;
        }

        .kt-checkbox.kt-checkbox--bold span {
            border: 1px solid #4FC4CA;
        }

        .kt-checkbox.kt-checkbox--bold>input:checked~span {
            background: #4FC4CA;
            border: 2px solid #4FC4CA !important;
            border-radius: 3px !important;
        }

        .kt-checkbox>span:after {
            border: solid #fff;
        }

        .kt-radio.kt-radio--bold>input:checked~span {
            border: 2px solid #4FC4CA;
        }

        .background-checked__answer--success {
            background: #4fc4ca54 !important;
        }

        .primary-color {
            color: #027177 !important;
            font-weight: 500;
        }

        .form-control-feedback {
            color: red;
        }

        .m-radio>span:after {
            background: #4FC4CA !important;
            border: 1px solid #4FC4CA !important;
        }

        .m-radio>span {
            border: 1px solid #4FC4CA !important;
        }


        .kt-checkbox.kt-checkbox--bold span {
            border: 1px solid #4FC4CA;
        }

        

        #modal_point {
            color: black;
        }

        #modal_point p {
            color: #575962;
        }

        @media only screen and (max-width: 1400px) {

            html,
            body {
                overflow: auto;
            }
        }
    </style>
@endsection
@section('after_css')
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('static/backend/css/survey/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('static/backend/css/survey/survey.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="m-portlet  m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text">
                        {{ __('CHI TIẾT CÂU HỎI  KHẢO SÁT') }}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if ($detail['status'] == 'N')
                    <button type="button" onclick="survey.showModalDestroy()"
                        class="btn btn-primary color_button color_button_destroy  btn-search ml-2">
                        @lang('Xóa')
                    </button>
                    <button type="button" onclick="survey.showModalRefuse()" class="btn btn-secondary btn-search ml-2"
                        style="color:black; border:1px solid">
                        @lang('Từ chối')
                    </button>
                    <button type="button" onclick="survey.showModalConfirm()"
                        class="btn btn-primary color_button btn-search ml-2">
                        @lang('Duyệt')
                    </button>
                @elseif($detail['status'] == 'R')
                    @if ($detail['is_exec_time'] == 0 ||
                        \Carbon\Carbon::parse($detail['close_date'])->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d'))
                        <button type="button" onclick="survey.showModalEnd()"
                            class="btn btn-primary color_button color_button_destroy ml-2">
                            @lang('survey::survey.show.end')
                        </button>
                    @endif
                @endif
                <a href="{{ route('survey.index') }}" class="btn btn-secondary btn-search ml-2"
                    style="color:black; border:1px solid">
                    @lang('Quay lại trang trước')
                </a>
            </div>
        </div>
    </div>
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-padding-0" id="kt_content">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <div class="row form-group">
                    <div class="col-xl-8 col-lg-8">
                        <div class="btn-group btn-group" role="group" aria-label="...">
                            <a type="button" class="btn btn-secondary kt-padding-l-40 kt-padding-r-40"
                                href="{{ route('survey.show', [$detail['survey_id']]) }}">
                                {{ __('Thông tin chung') }}
                            </a>
                            <a href="{{ route('survey.show-question', [$detail['survey_id']]) }}" type="button"
                                class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">
                                {{ __('Câu hỏi khảo sát') }}
                            </a>
                            <a href="{{ route('survey.show-branch', [$detail['survey_id']]) }}" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                {{ __('Đối tượng áp dụng') }}
                            </a>
                            <a href="{{ route('survey.report', [$detail['survey_id']]) }}" type="button"
                                class="btn btn-secondary kt-padding-l-40 kt-padding-r-40">
                                {{ __('Báo cáo') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <button type="button" class="btn btn-secondary float-right"
                            onclick="question.showModalNotification()">
                            {{ __('Cài đặt trang hoàn thành') }}
                        </button>
                        @if ($detail['count_point'])
                            <button type="button" class="btn btn-secondary float-right" style="margin-right:10px"
                                onclick="question.showModalConfigPoint()">
                                {{ __('Cài đặt khảo sát có tính điểm') }}
                            </button>
                        @endif
                    </div>
                </div>
                <form id="form-data" action="" method="POST">
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12">
                            @if ($detail['status'] == 'N')
                                <a type="button" href="{{ route('survey.edit-question', [$detail['survey_id']]) }}"
                                    class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">
                                    {{ __('Chỉnh sửa thông tin') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row kt-margin-l-0" style="border: 1px solid #e2e5ec;">
                        <div class="col-lg-9 kt-padding-r-0">
                            <div class="kt-portlet">
                                <div class="kt-portlet__body kt-padding-0">
                                    <div class="m-scroller-y ps" data-scrollable="true" data-height="450"
                                        style="height: 450px; overflow: hidden;" id="div_list_block">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 border-left-1px kt-padding-l-0">
                            <div class="kt-portlet kt-padding-l-10">
                                <div class="kt-portlet__body kt-padding-0">
                                    <div class=" m-scroller-y ps" data-scrollable="true" data-height="450"
                                        style="height: 450px; overflow: hidden;" id="div_config_question">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end: Search Form -->
                </form>
            </div>
        </div>
    </div>
    <div id="div_modal"></div>
    @include('survey::survey.modal.destroy-survey')
    @include('survey::survey.modal.confirm-survey')
    @include('survey::survey.modal.refuse-survey')
    @include('survey::survey.modal.end-survey')
@endsection
@section('after_script')
    <script type="text/template" id="logo-tpl">
        <div class="kt-avatar__holder kt-width-height-100px"
                                                                 style="background-image: url({link});background-position: center; background-size: 100% 100%;"></div>
    </script>
    <script src="{{ asset('static/backend/js/general/sortable/jquery-ui.js') }}" type="text/javascript"></script>
    <script>
        const UNIQUE = '{{ $unique }}';
        const ID = '{{ $detail['survey_id'] }}';
        const ACTION = 'show';
    </script>
    <script src="{{ asset('static/backend/js/jquery.mask.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/survey/question/script.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/survey/edit.js?v=' . time()) }}" type="text/javascript"></script>
@endsection
