<div class="modal fade" id="popup-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content clear-form">

            <!-- Header modal -->
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{ __('CHỈNH SỬA NGÂN SÁCH') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Body modal -->
            <div class="modal-body">

                <!-- Form chỉnh sửa tiêu chí -->
                <form id="frm-edit-criteria" data-route="{{ route('kpi.marketing.budget.day.update') }}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-form__group">
                                <div class="criteria-content">
                                    <!-- Phân bổ ngân sách theo -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">
                                                {{ __('Phân bổ ngân sách theo') }}: <b class="text-danger">*</b>
                                            </label>
                                            
                                            <div>
                                                <div class="radio-row" style="display: inline-block;">
                                                    <input type="radio" id="department_allocate" name="budget_allocation" disabled>
                                                    <label for="budget_allocation">{{ __('Phòng ban') }}</label>
                                                </div>
            
                                                <div class="radio-row" style="display: inline-block;">
                                                    <input type="radio" id="team_allocate" name="budget_allocation" disabled>
                                                    <label for="budget_allocation">{{ __('Nhóm thuộc phòng ban') }}</label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <!-- Phòng ban -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Phòng ban') }}: </label> <b class="text-danger">*</b>
                                            <div class="input-group">
                                                <select name="department_id" id="department_id" class="form-control" disabled>
                                                    <option value="">{{ __('Chọn phòng ban') }}</option>
                                                    @foreach ($DEPARTMENT_LIST as $departmentItem)
                                                        <option value="{{ $departmentItem['department_id'] }}">{{ $departmentItem['department_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nhóm -->
                                    <div class="row modal-row team-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Nhóm') }}: </label> <b class="text-danger">*</b>
                                            <div class="input-group">
                                                <select name="team_id" id="team_id" class="form-control" disabled>
                                                    <option value="" selected>{{ __('Chọn nhóm') }}</option>
                                                    @foreach ($TEAM_LIST as $teamItem)
                                                        <option value="{{ $teamItem['team_id'] }}">{{ $teamItem['team_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ngân sách ngày -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Ngân sách ngày') }}: </label> <b class="text-danger">*</b>
                                            <div class="input-group input-currency" style="padding-left: 0px;">
                                                <input id="budget" name="budget" type="text" class="form-control m-input class"
                                                    aria-describedby="basic-addon1" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ngày áp dụng -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Tháng áp dụng') }}: </label> <b class="text-danger">*</b>
                                            <div class="input-group">
                                                <input type="date" name="effect_time" id="effect_time" 
                                                value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer modal -->
                    <div class="modal-footer">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                            <div class="m-form__actions m--align-right">
                                <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                    <span class="ss--text-btn-mobi">
                                        <i class="la la-arrow-left"></i>
                                        <span>{{ __('HỦY') }}</span>
                                    </span>
                                </button>

                                <button type="submit" id="btn-save-budget"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10">
                                    <span class="ss--text-btn-mobi">
                                        <i class="la la-check"></i>
                                        <span>{{ __('LƯU THÔNG TIN') }}</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
                
            </div>
        </div>
    </div>
</div>
