<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('CHỌN CA LÀM')</th>
            <th class="tr_thead_list">{{__('TÊN CA')}}</th>
            <th class="tr_thead_list">{{__('CHI NHÁNH')}}</th>
            <th class="tr_thead_list">{{__('TĂNG CA')}}</th>
            <th class="tr_thead_list">{{__('TÍNH TĂNG CA THEO')}}</th>
            <th class="tr_thead_list">{{__('HỆ SỐ CÔNG')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($list) > 0)
            @foreach($list as $k => $v)
                <tr class="tr_shift">
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_shift" type="checkbox"
                                   {{isset($arrCheckTemp[$v['shift_id']]) ? 'checked' : ''}}
                                   onclick="index.chooseShift(this)">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        {{$v['shift_name']}} <br/>
                        ({{\Carbon\Carbon::parse($v['start_work_time'])->format('H:i')}}
                        - {{\Carbon\Carbon::parse($v['end_work_time'])->format('H:i')}})

                        <input type="hidden" class="shift_id" value="{{$v['shift_id']}}">
                        <input type="hidden" class="is_disable_ot_type" value="{{$v['is_disable_ot_type']}}">
                    </td>
                    <td>
                        <select class="form-control branch_id" style="width:100%;"
                                {{!isset($arrCheckTemp[$v['shift_id']]) ? 'disabled' : ''}} onchange="index.updateObjectShift(this)">
                            @foreach($v['branch'] as $br)
                                <option value="{{$br['branch_id']}}"
                                        {{isset($arrCheckTemp[$v['shift_id']]) && $arrCheckTemp[$v['shift_id']]['branch_id'] == $br['branch_id'] ? 'selected' : ''}}>{{$br['branch_name']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_ot" type="checkbox" onchange="index.updateObjectShift(this)"
                                    {{!isset($arrCheckTemp[$v['shift_id']]) ? 'disabled' : ''}}
                                    {{isset($arrCheckTemp[$v['shift_id']]) && $arrCheckTemp[$v['shift_id']]['is_ot'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <select class="form-control overtime_type" style="width:100%;" onchange="index.updateObjectShift(this)"
                                {{isset($arrCheckTemp[$v['shift_id']]['is_ot']) && $arrCheckTemp[$v['shift_id']]['is_ot'] == 1 ? '' : 'disabled'}}
                                {{$v['is_disable_ot_type'] == 0 && !isset($arrCheckTemp[$v['shift_id']]['is_ot']) ? 'disabled' : ''}}>
                            <option value="S" {{isset($arrCheckTemp[$v['shift_id']]) && $arrCheckTemp[$v['shift_id']]['overtime_type'] == "S" ? 'selected' : ''}}
                                    {{$v['is_disable_ot_type'] == 0 && !isset($arrCheckTemp[$v['shift_id']]['is_ot']) ? 'selected' : ''}}>@lang("Ca")</option>
                            <option value="H" {{isset($arrCheckTemp[$v['shift_id']]) && $arrCheckTemp[$v['shift_id']]['overtime_type'] == "H" ? 'selected' : ''}}
                                    {{$v['is_disable_ot_type'] == 1 && !isset($arrCheckTemp[$v['shift_id']]['is_ot']) ? 'selected' : ''}}>@lang("Giờ")</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control timekeeping_coefficient" {{!isset($arrCheckTemp[$v['shift_id']]) ? 'disabled' : ''}} onchange="index.updateObjectShift(this)"
                               value="{{isset($arrCheckTemp[$v['shift_id']]) ? $arrCheckTemp[$v['shift_id']]['timekeeping_coefficient'] : $v['timekeeping_coefficient']}}">
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

<script>
    $('.branch_id, .overtime_type').select2({
        width: '100%'
    });

    new AutoNumeric.multiple('.timekeeping_coefficient', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: 2,
        eventIsCancelable: true,
        minimumValue: 0
    });

    @if ($focus_shift_id != null)
    $('.check_shift').prop('checked', false);
    $('.check_shift').trigger('click');
    @endif
</script>
