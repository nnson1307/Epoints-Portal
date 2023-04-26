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
                            <i class="la la-edit"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            {{ __('CHỈNH SỬA PHIẾU GIAO KPI') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                        @if (isset($DETAIL_DATA))
                            <?php
                                $kpiNoteId    = $DETAIL_DATA['generalDetail']['kpi_note_id'];   // ID Phiếu giao
                                $kpiNoteType  = $DETAIL_DATA['generalDetail']['kpi_note_type']; // Loại phiếu giao
                                $kpiNoteName  = $DETAIL_DATA['generalDetail']['kpi_note_name']; // Tên phiếu giao
                                $branch       = $DETAIL_DATA['generalDetail']['branch_id'];     // ID Chi nhánh
                                $department   = $DETAIL_DATA['generalDetail']['department_id']; // ID Phòng ban
                                $team         = $DETAIL_DATA['generalDetail']['team_id'];       // ID Nhóm
                                $effectYear   = $DETAIL_DATA['generalDetail']['effect_year'];   // Năm áp dụng cho phiếu giao
                                $effectMonth  = $DETAIL_DATA['generalDetail']['effect_month'];  // Tháng áp dụng cho phiếu giao
                                $currentYear  = date('Y');                                      // Năm hiện tại
                                $nextYear     = date('Y', strtotime('+1 year'));                // Năm kế tiếp
                                $currentMonth = date('m');                                      // Tháng hiện tại
                                $loop         = $DETAIL_DATA['generalDetail']['is_loop'];       // Lặp lại hằng tháng
                                $listStaff    = $DETAIL_DATA['listStaff'];                      // List data nhân viên
                            ?>
                            <div class="row">
                                <input type="hidden" id="kpi_note_id" name="kpi_note_id" value="{{ $kpiNoteId }}">
                                <!-- Input hidden loại phiếu giao -->
                                <input type="hidden" name="kpi_note_type" id="kpi_note_type" value="{{ $kpiNoteType }}">

                                <!-- Tên hoa hồng -->
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{ __('Tên phiếu giao KPI') }}: <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <input id="kpi_note_name" name="kpi_note_name" type="text" 
                                                class="form-control m-input class" aria-describedby="basic-addon1" 
                                                value="{{ $kpiNoteName }}">
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
                                        @if (isset($BRANCH_LIST))
                                            <div class="input-group">
                                                <select name="branch_id" id="branch_id" class="form-control m-input ss--select-2">
                                                    <option value="">{{ __('Chọn chi nhánh') }}</option>
                                                    @foreach ($BRANCH_LIST as $branchItem)
                                                        <option value="{{ $branchItem['branch_id'] }}" 
                                                            {{ $branchItem['branch_id'] == $branch ? 'selected' : '' }}>
                                                            {{ $branchItem['branch_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
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
                                                    <option value="{{ $currentYear }}" 
                                                    {{ $currentYear == $effectYear ? 'selected' : '' }}>
                                                        {{ $currentYear }}
                                                    </option>
                                                    <option value="{{ $nextYear }}"
                                                    {{ $nextYear == $effectYear ? 'selected' : '' }}>
                                                        {{ $nextYear }}
                                                    </option> 
                                                </select>
                                            </div>
                                            <span class="errs error-display"></span>
                                        </div>

                                        <div class="form-group m-form__group col-lg-6">
                                            <label>
                                                {{ __('Tháng áp dụng') }}: <b class="text-danger">*</b>
                                            </label>
                                            <div class="input-group">
                                                <select name="effect_month" id="effect_month" class="form-control m-input ss--select-2" data-month="{{ $effectMonth }}">
                                                    @for ($i = 1; $i <= 12; $i++) 
                                                        <option value="{{ $i }}" @if($i == $effectMonth) selected @elseif($i < $currentMonth && $effectYear == $currentYear) disabled @endif >
                                                            {{ __('Tháng ' .$i) }}</option>
                                                        </option>
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
                                                        <option value="{{ $departmentItem['department_id'] }}"
                                                            {{ $departmentItem['department_id'] == $department ? 'selected' : '' }}>
                                                            {{ $departmentItem['department_name'] }}
                                                        </option>
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
                                                        <option value="{{ $teamItem['team_id'] }}"
                                                            {{ $teamItem['team_id'] == $team ? 'selected' : '' }}>
                                                            {{ $teamItem['team_name'] }}
                                                        </option>
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
                                                <option value="1" {{ $loop == 1 ? 'selected' : '' }}>{{ __('Lặp lại hằng tháng') }}</option>
                                                <option value="0" {{ $loop == 0 ? 'selected' : '' }}>{{ __('Không lặp lại') }}</option>
                                            </select>
                                        </div>
                                        <span class="errs error-display"></span>
                                    </div>
                                </div>

                                @if ($kpiNoteType === 'S')
                                    <!-- Nhân viên áp dụng -->
                                    <div class="col-lg-6">
                                        <div class="form-group m-form__group">
                                            <label>
                                                {{ __('Nhân viên áp dụng') }}: <b class="text-danger">*</b>
                                            </label>
                                            <div class="input-group">
                                                <select style="width: 100%;" name="staff_id[]" id="staff_id" class="form-control m-input ss--select-2 js-tags" multiple="multiple">
                                                        @foreach ($STAFF_LIST as $staffItem)
                                                            <option value="{{ $staffItem['staff_id'] }}" 
                                                                {{ in_array($staffItem['staff_id'], $listStaff) ? 'selected' : '' }}>
                                                                {{ $staffItem['full_name'] }}
                                                            </option>
                                                        @endforeach
                                                </select>
                                            </div>
                                            <span class="errs error-display"></span>
                                        </div>
                                    </div>
                                @endif

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
                                                <button type="button" onclick="EditKpiNote.update()"
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Popup thêm tiêu chí -->
    @include('kpi::notes.components.edit-popup')
@endsection

@section('after_script')
    <script src="{{ asset('static/backend/js/kpi/notes/edit.js?v=' . time()) }}" type="text/javascript">
    </script>
@stop
