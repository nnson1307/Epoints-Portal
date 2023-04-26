@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('CẤU HÌNH HOA HỒNG') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
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
                        {{ __('CẤU HÌNH HOA HỒNG') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="SalaryCommissionConfig.addView()"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('THÊM CẤU HÌNH') }}</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background m--margin-bottom-30">
                <input type="hidden" name="page" value="{{ (isset($params['page']) && $params['page'] ) ? $params['page']:'' }}">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <select name="department_id" class="form-control select2 select2-active">
                                        <option value="">@lang('Chọn phòng ban')</option>
                                        @foreach ($all_department as $key => $value )
                                            <option value="{{ $key }}"{{ (isset($params['department_id']) && $params['department_id'] == $key ) ? ' selected':'' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="d-flex">
                                        <a class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3" href="{{ route('salary.salary_commission_config') }}">
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
                    </div>
                </div>
            </form>
            <div class="table-content">
                @include('Salary::salary_commission_config.list')
            </div><!-- end table-content -->
        </div>
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
    </div>
    <div class="modal fade" id="modalEdit" role="dialog"></div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = '{{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}}';
        new AutoNumeric.multiple('.money_format', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0,
            maximumValue: 999999999,
        });

        new AutoNumeric.multiple(".percent-format", {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            eventIsCancelable: true,
            minimumValue: 0,
            maximumValue: 100,
        });
    </script>
    <script src="{{ asset('static/backend/js/salary/salary_commission_config/list.js?v=' . time()) }}" type="text/javascript"></script>
@stop
