@if (isset($listItemDepartment) || isset($listItemBranch) || isset($listItemTitile))
    <div class="list__staff--auto mt-5 mb-5">
        @if (isset($listItemDepartment) && $listItemDepartment->count() > 0)
            <div class="form-group d-flex A-condition-1">
                <div class="col-lg-4" style="padding-left: 0px">
                    <input type="text" class="input_condition_seleted form-control" disabled
                        placeholder="@lang('Chọn theo phòng ban')">
                </div>
                <div class="col-lg-6 div-content-condition">
                    <select name="department_seleted[]" id="department_seleted" disabled class="condition_selected"
                        multiple="multiple" style="width: 100%">
                        @foreach ($listItemDepartment as $item)
                            <option selected value="{{ $item->department_id }}">
                                {{ $item->department_name }}
                            </option>
                        @endforeach
                    </select>
                    </option>
                </div>
                <div class="col-lg-2">
                    {{-- <button style="float: right;" onclick="branch.removeConditionSelected(this ,'condition_department')"
                        type="button" class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                        <i class="la la-close"></i>
                    </button> --}}
                </div>
            </div>
        @endif
        @if (isset($listItemBranch) && $listItemBranch->count() > 0)
            <div class="form-group d-flex A-condition-1">
                <div class="col-lg-4" style="padding-left: 0px">
                    <input type="text" class="input_condition_seleted form-control" disabled
                        placeholder="@lang('Chọn theo chi nhánh')">
                </div>
                <div class="col-lg-6 div-content-condition">
                    <select name="branch_seleted[]" id="branch_seleted" disabled class="condition_selected"
                        multiple="multiple" style="width: 100%">
                        @foreach ($listItemBranch as $item)
                            <option selected value="{{ $item->branch_id }}">
                                {{ $item->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    </option>
                </div>
                <div class="col-lg-2">
                    {{-- <button style="float: right;" onclick="branch.removeConditionSelected(this , 'condition_branch')"
                        type="button" class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                        <i class="la la-close"></i>
                    </button> --}}
                </div>
            </div>
        @endif
        @if (isset($listItemTitile) && $listItemTitile->count() > 0)
            <div class="form-group d-flex A-condition-1">
                <div class="col-lg-4" style="padding-left: 0px">
                    <input type="text" class="input_condition_seleted form-control" disabled
                        placeholder="@lang('Chọn theo chức vụ')">
                </div>
                <div class="col-lg-6 div-content-condition">
                    <select name="title_seleted[]" id="title_seleted" disabled class="condition_selected"
                        multiple="multiple" style="width: 100%">
                        @foreach ($listItemTitile as $item)
                            <option selected value="{{ $item->staff_title_id }}">
                                {{ $item->staff_title_name }}
                            </option>
                        @endforeach
                    </select>
                    </option>
                </div>
                <div class="col-lg-2">
                    {{-- <button style="float: right;" onclick="branch.removeConditionSelected(this, 'condition_titile')"
                        type="button" class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                        <i class="la la-close"></i>
                    </button> --}}
                </div>
            </div>
        @endif
        @if (isset($typeCondition))
            <div class="form-group d-flex" style="margin: 0px">
                <div class="col-lg-4" style="padding-left: 0px">
                    <input type="text" value="{{ $typeCondition }}" hidden id="type_condition_seleted">
                </div>
            </div>
        @endif
    </div>
@else
    <div class="list__staff--auto mt-5 mb-5">
        <div class="form-group d-flex" style="margin: 0px">
        </div>
    </div>
@endif
