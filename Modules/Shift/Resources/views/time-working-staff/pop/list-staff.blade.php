<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('HÀNH ĐỘNG')</th>
            <th class="tr_thead_list">{{__('TĂNG CA')}}</th>
            <th class="tr_thead_list">{{__('CHI NHÁNH LÀM VIỆC')}}</th>
            <th class="tr_thead_list">{{__('TÊN NHÂN VIÊN')}}</th>
            <th class="tr_thead_list">{{__('CHI NHÁNH TRỰC THUỘC')}}</th>
            <th class="tr_thead_list">{{__('PHÒNG BAN')}}</th>
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
                                   {{isset($arrCheckTemp[$v['staff_id']]) ? 'checked' : ''}}
                                   onclick="index.chooseStaff(this)">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_ot" type="checkbox" onchange="index.updateObjectStaff(this)"
                                    {{!isset($arrCheckTemp[$v['staff_id']]) ? 'disabled' : ''}}
                                    {{isset($arrCheckTemp[$v['staff_id']]) && $arrCheckTemp[$v['staff_id']]['is_ot'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <select class="form-control branch_id m_selectpicker" style="width:100%;"
                                {{!isset($arrCheckTemp[$v['staff_id']]) ? 'disabled' : ''}} onchange="index.updateObjectStaff(this)">
                            @foreach($v['branchShift'] as $v1)
                                <option value="{{$v1['branch_id']}}"
                                        {{$v1['branch_id'] == $v['branch_id'] ? 'selected': ''}}
                                        {{isset($arrCheckTemp[$v['staff_id']]) && $arrCheckTemp[$v['staff_id']]['branch_id'] == $v1['branch_id'] ? 'selected' : ''}}
                                >{{$v1['branch_name']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        {{$v['full_name']}}
                    </td>
                    <td>
                        {{$v['branch_name']}}

                        <input type="hidden" class="staff_id" value="{{$v['staff_id']}}">
                    </td>
                    <td>
                        {{$v['department_name']}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

<script>
    $('.branch_id').select2({
        width: '100%'
    });
</script>