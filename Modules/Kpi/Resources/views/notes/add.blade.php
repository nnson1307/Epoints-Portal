@extends('layout')

@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/hao.css') }}">
@endsection

@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('QUẢN LÝ PHIẾU GIAO KPI') }}
    </span>
@endsection

@section('content')
    <form id="form-banner" autocomplete="off">
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="fa fa-plus-circle"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            {{ __('TẠO PHIẾU GIAO') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="commission-info">
                        <div class="row" id="add-commission-step-1">
                            <!-- Checkbox loại phiếu giao theo nhóm / nhân viên -->
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Loại phiếu giao') }}: <b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select name="kpi_note_type" id="kpi_note_type" class="form-control m-input ss--select-2">
                                            <option value="B">{{ __('Phiếu giao cho chi nhánh') }}</option>
                                            <option value="D">{{ __('Phiếu giao cho phòng ban') }}</option>
                                            <option value="T">{{ __('Phiếu giao cho nhóm') }}</option>
                                            <option value="S">{{ __('Phiếu giao cho nhân viên') }}</option>
                                        </select>
                                    </div>
                                    <span class="errs error-display"></span>
                                </div>
                            </div>
                            <div class="col-lg-6"></div>

                            <!-- Tên phiếu giao -->
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Tên Phiếu Giao KPI') }}: <b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <input id="kpi_note_name" name="kpi_note_name" type="text"
                                            class="form-control m-input class"
                                            placeholder="{{ __('Nhập tên phiếu giao KPI') }}"
                                            aria-describedby="basic-addon1">
                                    </div>
                                    <span class="errs error-name"></span>
                                </div>
                            </div>

                            <!-- Chi nhánh -->
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Chi nhánh') }}: <b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select name="branch_id" id="branch_id" class="form-control m-input ss--select-2">
                                            <option value="">{{ __('Chọn chi nhánh') }}</option>
                                            @foreach ($BRANCH_LIST as $branchItem)
                                                <option value="{{ $branchItem['branch_id'] }}">{{ $branchItem['branch_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="errs error-display"></span>
                                </div>
                            </div>

                            <!-- Thời gian áp dụng -->
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="form-group m-form__group col-lg-6">
                                        <label>
                                            {{ __('Năm áp dụng') }}: <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="effect_year" id="effect_year" class="form-control m-input ss--select-2"> 
                                                <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                                                <option value="{{ date('Y', strtotime('+1 year')) }}">{{ date('Y', strtotime('+1 year')) }}</option>
                                            </select>
                                        </div>
                                        <span class="errs error-display"></span>
                                    </div>

                                    <div class="form-group m-form__group col-lg-6">
                                        <label>
                                            {{ __('Tháng áp dụng') }}: <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="effect_month" id="effect_month" class="form-control m-input ss--select-2">
                                                @for ($i = 1; $i <= 12; $i++) 
                                                    <option value="{{ $i }}" @if($i == date('m')) selected @elseif($i < date('m')) disabled  @endif >{{ __('Tháng ' .$i) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <span class="errs error-display"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Phòng ban / Nhóm -->
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="form-group m-form__group col-lg-6">
                                        <label>
                                            {{ __('Phòng ban') }}: <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="department_id" id="department_id" class="form-control m-input ss--select-2">
                                                <option value="">{{ __('Chọn phòng ban') }}</option>
                                                @foreach ($DEPARTMENT_LIST as $departmentItem)
                                                    <option value="{{ $departmentItem['department_id'] }}">{{ $departmentItem['department_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="errs error-display"></span>
                                    </div>

                                    <div class="form-group m-form__group col-lg-6">
                                        <label>
                                            {{ __('Nhóm') }}: <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="team_id" id="team_id" class="form-control m-input ss--select-2">
                                                <option value="">{{ __('Chọn nhóm') }}</option>
                                                @foreach ($TEAM_LIST as $teamItem)
                                                    <option value="{{ $teamItem['team_id'] }}">{{ $teamItem['team_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="errs error-display"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tần suất lặp lại -->
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label>
                                        {{ __('Tần suất lặp lại') }}: <b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select name="is_loop" id="is_loop" class="form-control m-input ss--select-2">
                                            <option value="">{{ __('Chọn tần suất lặp lại') }}</option>
                                            <option value="1">{{ __('Lặp lại hằng tháng') }}</option>
                                            <option value="0">{{ __('Không lặp lại') }}</option>
                                        </select>
                                    </div>
                                    <span class="errs error-display"></span>
                                </div>
                            </div>

                            <!-- Nhân viên áp dụng -->
                            <div class="col-lg-6">
                                <div class="form-group m-form__group d-none">
                                    <label>
                                        {{ __('Nhân viên áp dụng') }}: <b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select style="width: 100%;" name="staff_id[]" id="staff_id" class="form-control m-input ss--select-2 js-tags" multiple="multiple" disabled>
                                                @foreach ($STAFF_LIST as $staffItem)
                                                    <option value="{{ $staffItem['staff_id'] }}" selected>{{ $staffItem['full_name'] }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <span class="errs error-display"></span>
                                </div>
                            </div>

                            <!-- Bảng tiêu chí -->
                            <div class="col-lg-12">
                                <div class="table-responsive" style="padding-bottom: 7px;">
                                    <h5>
                                        {{ __('DANH SÁCH TIÊU CHÍ TÍNH KPI') }}
                                    </h5>
                                    <table class="table table-striped m-table ss--header-table" id="criteria-table">
                                    </table>
                                    <span class="text-danger criteria-list-err"></span>
                                </div>
                                <a class="btn btn-add-criteria  btn-sm m-btn--icon color" href="javascript:void(0)">
                                    <i class="la la-plus"></i> @lang('Thêm tiêu chí')
                                </a>
                            </div>

                            <div class="col-lg-6"></div>
                            <!-- Nút hủy và tiếp theo -->
                            <div class="col-lg-6">
                                <div class="modal-footer save-attribute" style="float: right;">
                                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                                        <div class="m-form__actions m--align-right">
                                            <a href="{{ route('kpi.note') }}"
                                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                                <span class="ss--text-btn-mobi">
                                                    <i class="la la-arrow-left"></i>
                                                    <span>{{ __('HỦY') }}</span>
                                                </span>
                                            </a>
                                            <button type="button" onclick="KpiNote.add()"
                                                class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span class="ss--text-btn-mobi">
                                                    <i class="la la-check"></i>
                                                    <span>{{ __('LƯU THÔNG TIN') }}</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Popup thêm tiêu chí -->
    @include('kpi::notes.components.criteria-popup')
@endsection

@section('after_script')
    <script src="{{ asset('static/backend/js/kpi/notes/script.js?v=' . time()) }}" type="text/javascript">
    </script>
@stop
