@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ KHÁCH HÀNG TIỀM NĂNG')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

        .select2 {
            width: 100% !important;
        }

        .m-portlet--head-sm {
            margin-top: 50px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phu-custom.css?v=' . time()) }}">
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CẤU HÌNH NGUỒN DỮ LIỆU KHÁCH HÀNG TIỀM NĂNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="config.showPopup()"
                    class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> @lang('Thêm nguồn')</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg" autocomplete="OFF">
                    <div class="padding_row">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <select class="form-control select2" name="team_marketing_id">
                                        <option value="">{{ __('Chọn team marketing') }}</option>
                                        @foreach ($listTeam as $item)
                                            <option value="{{ $item['team_id'] }}">{{ $item['team_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select class="form-control select2" name="department_id">
                                        <option value="">{{ __('Chọn phòng ban') }}</option>
                                        @foreach ($department as $item)
                                            <option value="{{ $item['department_id'] }}">{{ $item['department_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group m-form__group">
                                        <a href="{{route('customer-lead.config-source-lead')}}" class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                            {{ __('XÓA BỘ LỌC') }}
                                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                        </a>
                                        <button class="btn btn-primary color_button btn-search">
                                            @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('customer-lead::config-source-lead.list')
                </div>
            </div>
        </div>
    </div>

    <div class="show-popup"></div>
@endsection
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/customize.css') }}">
    {{-- <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}"> --}}
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/customer-lead/config-source-lead/script.js?v=' . time()) }}"
        type="text/javascript"></script>
@stop
