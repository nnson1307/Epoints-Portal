@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;">{{ __('CHƯƠNG TRÌNH TÍCH ĐIỂM') }}</span>
@stop
@section('after_style')
    <style>
        .kt-radio.kt-radio--brand.kt-radio--bold>input:checked~span {
            border: 2px solid #000000 !important;
        }

        .kt-avatar.kt-avatar--circle .kt-avatar__holder {
            border-radius: 0% !important;
        }

        .ss--kt-avatar__upload {
            width: 20px !important;
            height: 20px !important;
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
            border: 2px solid #027177;
        }

        .kt-radio>span:after {
            border: solid #027177;
            background: #027177;
            margin-left: -4px;
            margin-top: -4px;
            width: 8px;
            height: 8px;
        }

        .kt-checkbox-fix {
            padding: 5px 15px;
        }

        .kt-checkbox-fix span {
            position: absolute;
            top: unset !important;
            bottom: -20px !important;
            left: 30px !important;
        }

        .m-portlet__body {
            color
        }

        .primary-color {
            color: #575962 !important;
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

        .fw_title {
            font-weight: bold !important;
            color: #000000;
            font-size: 18px;
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
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('static/backend/css/survey/vu-custom.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head" style="align-items: center">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{ __('TẠO CHƯƠNG TRÌNH TÍCH ĐIỂM') }}
                    </h2>
                </div>
            </div>
            <div class="">
                <div class="btn-group btn-group" role="group" aria-label="...">
                    <button 
                    onclick="loyalty.showModalConfig()"
                    type="button" 
                    class="btn btn-secondary btn-search ml-2" 
                    style="color:#000000; border: 1px solid">
                        {{ __('Cấu hình mẫu thông báo') }}
                    </button>

                </div>
            </div>
        </div>
        <div class="kt-portlet kt-portlet--mobile">
            <form id="form-data" action="" style="margin-bottom:100px" method="POST">
                <div class="m-portlet__body">
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12 mt-2">
                            <div class="kt-portlet__head-label">
                                <h5 class="kt-portlet__head-title fw_title">
                                    @lang('Thông tin chung') </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-3 label col-form-label">
                                    {{ __('Tên chương trình') }}
                                </label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="accumulation_program_name"
                                        name="accumulation_program_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 label col-form-label">
                                    {{ __('Loại chương trình áp dụng') }}
                                </label>
                                <div class="col-lg-8">
                                    <input type="text" disabled class="form-control" id="source_point_key"
                                        name="source_point_key" value="{{ __('Khảo sát') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    {{ __('Chương trình khảo sát') }}
                                </label>
                                <div class="col-lg-8 col-xl-8">
                                    <select type="text" name="survey" id="survey"
                                        class="form-control ss--width-100 ss--select-2" style="width: 100%">
                                        @foreach ($listSurvey as $value)
                                            <option value="{{ $value->survey_id }}">{{ $value->survey_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 label col-form-label">
                                    {{ __('Thời gian hiệu lực chương trình') }}
                                </label>
                                <div class="col-lg-8">
                                    <div class="kt-radio-list">
                                        <div class="mb-3">
                                            <label class="m-radio cus">
                                                <input type="radio" name="validity_period_type" checked
                                                    onclick="loyalty.togglePeriodType(this)" value="no_limit">
                                                {{ __('Không giới hạn') }}
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="m-radio cus">
                                                <input type="radio" onclick="loyalty.togglePeriodType(this)"
                                                    name="validity_period_type" value="date_comfirm_survey">
                                                {{ __('Theo thời gian hiệu lực của khảo sát') }}
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label class="m-radio cus">
                                                    <input type="radio" onclick="loyalty.togglePeriodType(this)"
                                                        name="validity_period_type" value="time_limit">
                                                    {{ __('Giới hạn thời gian thực hiện khảo sát trong ngày') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="input-group date">
                                                    <input type="text" class="form-control m-input" id="date_start"
                                                        placeholder="@lang('survey::survey.create.time_start')" disabled readonly name="date_start">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="input-group date">
                                                    <input type="text" disabled readonly class="form-control m-input"
                                                        placeholder="@lang('survey::survey.create.time_end')" id="date_end" name="date_end">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    {{ __('Trạng thái') }}
                                </label>
                                <div class="col-lg-8 col-xl-8" style="padding:0px">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label style="margin: 0 0 0 10px; padding-top: 4px">
                                            <input type="checkbox" checked class="manager-btn" id="is_active"
                                                name="is_active">
                                            <span></span>
                                        </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">
                                    {{ __('Ghi chú') }}
                                </label>
                                <div class="col-lg-8 col-xl-8">
                                    <textarea name="loylaty_description" class="form-control" id="loylaty_description" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12 mt-2">
                            <div class="kt-portlet__head-label">
                                <h5 class="kt-portlet__head-title fw_title">
                                    {{ __('Điều kiện') }} </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-3 label col-form-label">
                                    {{ __('Khảo sát được') }}
                                </label>
                                <div class="col-lg-8">
                                    <input type="text" disabled class="form-control" id="condition" name="condition"
                                        value="{{ __('Gửi thành công') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12 mt-2">
                            <div class="kt-portlet__head-label">
                                <h5 class="kt-portlet__head-title fw_title">
                                    {{ __('Hoạt động tích luỹ') }} </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xl-12 col-lg-12">
                            <div class="form-group row">
                                <label class="col-lg-3 label col-form-label">
                                    {{ __('Cộng điểm tích luỹ') }}
                                </label>
                                <div class="col-lg-8 mt-5">
                                    <div class="row">
                                        <div class="col-lg-4" style="margin-top: auto">
                                            <label class="m-radio cus">
                                                <input type="radio" name="apply_type"
                                                    onclick="loyalty.toggleApplyPoint(this)" value="all">
                                                {{ __('Điểm tích luỹ cho tất cả người dùng') }}
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-lg-7">
                                            <input type="number" disabled class="form-control" id="accumulate_point_all"
                                                name="accumulate_point_all"
                                                placeholder="{{ __('Nhâp số điểm tích luỹ') }}">
                                            <span class="accumulate_point_all_error mt-2"
                                                style="color:red; display:block"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-lg-12" style="margin-top: auto">
                                            <div>
                                                <label class="m-radio cus">
                                                    <input type="radio" name="apply_type"
                                                        onclick="loyalty.toggleApplyPoint(this)"
                                                        id="accumulate_point_rank" value="rank">
                                                    {{ __('Điểm tích luỹ theo hạng thành viên') }}
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div class="list_rank mt-5">
                                                @foreach ($listRank as $key => $item)
                                                    <div class="row item_rank mt-4">
                                                        <div class="col-lg-4">
                                                            <label class="m-radio cus">
                                                                {{ $item['name'] }}
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-7">
                                                            <input type="number" disabled
                                                                data-index="{{ $key }}"
                                                                class="form-control rank_point"
                                                                data-rank_id="{{ $item['member_level_id'] }}"
                                                                name="rank_point"
                                                                placeholder="{{ __('Nhâp số điểm tích luỹ') }}">
                                                            <span class="accumulate_point_error_{{ $key }} mt-2"
                                                                style="color:red; display:block"></span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row form-group">
                <div class="col-xl-12 col-lg-12 d-flex justify-content-end pr-5">
                    <button type="button" class="btn btn-secondary  kt-padding-l-40 kt-padding-r-40 mr-5"
                        onclick="loyalty.back()">
                        <i class="la la-arrow-left"></i>
                        @lang('HUỶ')

                    </button>
                    <button type="button" class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40"
                        onclick="loyalty.save()">
                        <i class="la la-check"></i>
                        @lang('LƯU')
                    </button>
                </div>
            </div>
        </div>
        <div id="modal-show">

        </div>
    </div>
@endsection

@section('after_script')
    <script>
        $('#date_end').datetimepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'd/m/yyyy hh:ii',
            startDate: '+0d',
            minDate: new Date()
        });
        $('#date_start').datetimepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'd/m/yyyy hh:ii',
            startDate: '+0d',
            minDate: new Date()
        });
    </script>
    <script type="text/template" id="image-tpl">
        <div class="kt-avatar__holder" style="background-image: url({link});background-position: center;"></div></script>
    <script src="{{ asset('static/backend/js/jquery.mask.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/loyalty/script.js?v=' . time()) }}" type="text/javascript"></script>
@endsection
