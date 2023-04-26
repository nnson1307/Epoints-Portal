<div class="modal fade" id="modal_staff_auto" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 70%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('Thêm nhân viên động')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group kt-margin-b-10">
                    <div class="form-group">
                        <h6 class="title_tab">@lang('Loại điều kiện áp dụng')</h6>
                    </div>
                    <div class="form-group div-condition">
                        @if (empty($listItemDepartmentPopup) && empty($listItemTitilePopup) && empty($listItemBranchPopup))
                            <div class="form-group row A-condition-1 div-A-1-condition">
                                <div class="col-lg-4" style="padding-left: 0px">
                                    <select name="" id="" class="form-control ss--select-2 condition"
                                        onchange="branch.chooseCondition(this)" style="width: 100%">
                                        <option value="">
                                            {{ __('Chọn điều kiện') }}
                                        </option>
                                        <option value="condition_branch">
                                            @lang('Theo chi nhánh')
                                        </option>
                                        <option value="condition_department">
                                            @lang('Theo phòng ban')
                                        </option>
                                        <option value="condition_title">
                                            @lang('Theo chức vụ')
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6 div-content-condition">

                                </div>
                                <div class="col-lg-2">
                                    <button style="float: right;" onclick="branch.removeCondition(this)" type="button"
                                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                                        <i class="la la-close"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        @if (!empty($listItemDepartmentPopup))
                            <div class="form-group row A-condition-1 div-A-1-condition">
                                <div class="col-lg-4" style="padding-left: 0px">
                                    <select disabled name="" id=""
                                        class="form-control ss--select-2 condition"
                                        onchange="branch.chooseCondition(this)" style="width: 100%">
                                        <option seleted value="condition_department">
                                            @lang('Theo phòng ban')
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6 div-content-condition">
                                    <select name="department[]" id="condition_department" multiple="multiple"
                                        class=" form-control  chooses-condition" style="width: 100%">
                                        <option value="">
                                            {{ __('Chọn phòng ban') }}
                                        </option>
                                        @foreach ($department as $item)
                                            <option value="{{ $item['department_id'] }}"
                                                @if (in_array($item['department_id'], $listItemDepartmentPopup)) {{ 'selected' }} @endif>
                                                {{ $item['department_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button style="float: right;" onclick="branch.removeCondition(this)" type="button"
                                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                                        <i class="la la-close"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        @if (!empty($listItemBranchPopup))
                            <div class="form-group row A-condition-1 div-A-1-condition">
                                <div class="col-lg-4" style="padding-left: 0px">
                                    <select disabled name="" id=""
                                        class="form-control ss--select-2 condition"
                                        onchange="branch.chooseCondition(this)" style="width: 100%">
                                        <option seleted value="condition_branch">
                                            @lang('Theo chi nhánh')
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6 div-content-condition">
                                    <select name="branch[]" id="condition_branch" multiple="multiple"
                                        class="form-control  chooses-condition" style="width: 100%">
                                        <option value="">
                                            {{ __('Chọn chi nhánh') }}
                                        </option>
                                        @foreach ($branch as $item)
                                            <option value="{{ $item['branch_id'] }}"
                                                @if (in_array($item['branch_id'], $listItemBranchPopup)) {{ 'selected' }} @endif>
                                                {{ $item['branch_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button style="float: right;" onclick="branch.removeCondition(this)" type="button"
                                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                                        <i class="la la-close"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        @if (!empty($listItemTitilePopup))
                            <div class="form-group row A-condition-1 div-A-1-condition">
                                <div class="col-lg-4" style="padding-left: 0px">
                                    <select disabled name="" id=""
                                        class="form-control ss--select-2 condition"
                                        onchange="branch.chooseCondition(this)" style="width: 100%">
                                        <option seleted value="condition_title">
                                            @lang('Theo chức vụ')
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6 div-content-condition">
                                    <select name="title[]" id="condition_titile" multiple="multiple"
                                        class=" form-control chooses-condition" style="width: 100%">
                                        <option value="">
                                            {{ __('Chọn chức vụ') }}
                                        </option>
                                        @foreach ($staffTitle as $item)
                                            <option value="{{ $item['staff_title_id'] }}"
                                                @if (in_array($item['staff_title_id'], $listItemTitilePopup)) {{ 'selected' }} @endif>
                                                {{ $item['staff_title_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button style="float: right;" onclick="branch.removeCondition(this)"
                                        type="button"
                                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                                        <i class="la la-close"></i>
                                    </button>
                                </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 padding-left-0">
                            <button onclick="branch.addCondition()"
                                class="btn btn-primary m-btn m-btn--custom m-btn--icon color_button btn-add-condition">
                                <span>
                                    <i class="fa fa-plus"></i>
                                    <span>{{ __('Thêm điều kiện') }}</span>
                                </span>
                            </button>
                        </div>

                    </div>

                </div>
                <div class="form-group kt-margin-b-10">
                    <div class="form-group">
                        <h6 class="title_tab">@lang('Điều kiện lọc')</h6>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <select name="type_condition" id="type_condition" class="form-control ss--select-2">
                                <option selected {{ isset($typeCondition) && $typeCondition == 'or' ? 'selected' : '' }} value="or">
                                    @lang('Bất kỳ')
                                </option>
                                <option value="and" {{ isset($typeCondition) && $typeCondition == 'and' ? 'selected' : '' }}>
                                    @lang('Bao gồm')
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-left">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    @lang('Hủy')
                </button>
                <button type="button" class="btn btn-success color_button"
                    onclick="branch.submitAddItemTempStaffAuto()">
                    @lang('Thêm')
                </button>
            </div>
        </div>
    </div>
</div>
