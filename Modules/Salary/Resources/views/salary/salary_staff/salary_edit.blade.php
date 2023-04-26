@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ LƯƠNG') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css?v='.time())}}">
    <style>
        .color-brown i , .color-brown  {
            color: #9699a2;
        }
        .color-brown:hover{
            text-decoration:unset;
            color: #9699a2;
        }
        .border-drown {
            border: 1px solid #9699a2;
            border-radius: 20px;
            padding: 5px 10px;
        }
    </style>
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
                        {{ __('CHỈNH SỬA BẢNG LƯƠNG') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('salary.detail',['id' => $detail['salary_id']])}}"
                   class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3" id="cancle-button">
                    <span>
                        <i class="la la-arrow-left"></i>
                        <span> {{ __('HUỶ') }}</span>
                    </span>
                </a>

                <a href="javascript:void(0)" onclick="SalaryData.saveChangeMoney()"
                    class="btn btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                    <span>
                        <i class="la la-check"></i>
                        <span> {{ __('LƯU THÔNG TIN') }}</span>
                    </span>
                </a>
            </div>
        </div>
{{--        <div class="m-portlet__body">--}}
{{--        </div>--}}
    </div>
    <div class="row">
        <div class="col-3">
            <div class="row">
                <div class="col-12">
                    <div class="m-portlet" id="autotable">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon">
                                        <i class="la la-th-list"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        {{ __('Thông tin bảng lương') }}
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <p class="w-25 d-inline"><strong>Kỳ lương:</strong></p><p class="d-inline w-50 ml-3">Tháng {{$info['season_month'].'/'.$info['season_year']}}</p>
                                </div>
                                <div class="col-12 mb-2">
                                    <p class="w-25 d-inline"><strong>Thời gian:</strong></p><p class="d-inline w-50 ml-3">{{\Carbon\Carbon::parse($info['date_start'])->format('d/m/Y').' - '.\Carbon\Carbon::parse($info['date_end'])->format('d/m/Y')}}</p>
                                </div>
                                <div class="col-12 mb-2">
                                    <p class="w-25 d-inline"><strong>Tên bảng lương:</strong></p><p class="d-inline w-50 ml-3">{{$info['name']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="m-portlet" id="autotable">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon">
                                        <i class="fas fa-user-circle"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        {{ __('Thông tin nhân sự') }}
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <p class="w-25 d-inline"><strong>Mã nhân viên:</strong></p><p class="d-inline w-50 ml-3">{{$detail['staff_code']}}</p>
                                </div>
                                <div class="col-12 mb-2">
                                    <p class="w-25 d-inline"><strong>Tên nhân viên:</strong></p><p class="d-inline w-50 ml-3">{{$detail['staff_name']}}</p>
                                </div>
                                <div class="col-12 mb-2">
                                    <p class="w-25 d-inline"><strong>Phòng ban:</strong></p><p class="d-inline w-50 ml-3">{{$detail['department_name']}}</p>
                                </div>
                                <div class="col-12 mb-2">
                                    <p class="w-25 d-inline"><strong>Chức vụ:</strong></p><p class="d-inline w-50 ml-3">{{$detail['staff_title_name']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="m-portlet" id="autotable">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                {{ __('THÔNG TIN CHÍNH') }}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <form id="editSalary" autocomplete="off">
                        <input type="hidden" name="salary_staff_id" id="salary_staff_id" value="{{$detail['salary_staff_id']}}">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Lương cơ bản') (VND):
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" id="salary" name="salary" onkeyup="SalaryData.changeMoney()"
                                                value="{{number_format($detail['salary'])}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Tăng') (VND):
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" id="plus" name="plus" onkeyup="SalaryData.changeMoney()"
                                                value="{{number_format($detail['plus'])}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Thưởng hoa hồng') (VND):
                                    </label>
                                    <div class="input-group">
                                        <input type="hidden" name="total_commission" id="total_commission" value="{{$detail['total_commission']}}">
                                        <input type="text" class="form-control m-input"  disabled
                                               value="{{number_format($detail['total_commission'])}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Giảm') (VND):
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" id="minus" name="minus" onkeyup="SalaryData.changeMoney()"
                                               value="{{number_format($detail['minus'])}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Thưởng KPIs') (VND):
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" id="total_kpi" name="total_kpi" onkeyup="SalaryData.changeMoney()"
                                               value="{{number_format($detail['total_kpi'])}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Ghi chú'):
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" id="note" name="note"
                                               value="{{$detail['note']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Phụ cấp') (VND):
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" id="total_allowance" name="total_allowance" onkeyup="SalaryData.changeMoney()"
                                               value="{{number_format($detail['total_allowance'])}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Tổng tiền thực lĩnh') (VND):
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" id="total" name="total" disabled
                                               value="{{number_format($detail['total'])}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head" style="border-bottom:0px">
            <div class="m-portlet__head-caption" style="border-bottom:5px solid #027177">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{ __('Danh sách hoa hồng') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <input type="hidden" name="page" id="page" value="1">
                <div class="col-12 table-commission">

                </div>
            </div>
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{ asset('static/backend/js/salary/salary/import-export.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            SalaryData.showTableCommission();
        })
        new AutoNumeric.multiple('#salary, #plus , #minus, #total_kpi , #total_allowance', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: 0,
            eventIsCancelable: true,
            minimumValue: 0,
            maximumValue: 1000000000,
        });
    </script>
@stop
