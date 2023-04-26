<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_all" name="check_all" type="checkbox" onclick="allowance.chooseAllStaff(this)">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">{{__('HỌ VÀ TÊN')}}</th>
            <th class="tr_thead_list">{{__('LOẠI NHÂN VIÊN')}}</th>
            <th class="tr_thead_list">{{__('CHI NHÁNH')}}</th>
            <th class="tr_thead_list">{{__('PHÒNG BAN')}}</th>
            <th class="tr_thead_list">{{__('HỆ SỐ HOA HỒNG')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($listStaff) > 0)
            @foreach($listStaff as $k => $v)
                <tr class="tr_staff">
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_one" type="checkbox"
                                   {{isset($arrCheckTemp[$v['staff_id']]) ? 'checked' : ''}}
                                   onclick="allowance.chooseStaff(this)">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        {{$v['staff_name']}}
                    </td>
                    <td>
                        @if ($v['staff_type'] == 'probationers')
                            @lang('Thử việc')
                        @elseif($v['staff_type'] == 'staff')
                            @lang('Chính thức')
                        @endif
                    </td>
                    <td>
                        {{$v['branch_name']}}

                        <input type="hidden" class="staff_id" value="{{$v['staff_id']}}">
                    </td>
                    <td>
                        {{$v['department_name']}}
                    </td>
                    <td>
                        <input type="text" class="form-control commission_coefficient"  onchange="allowance.updateObjectStaff(this)"
                               {{isset($arrCheckTemp[$v['staff_id']]) ? '' : 'disabled'}}
                            value="{{isset($arrCheckTemp[$v['staff_id']]) ? number_format($arrCheckTemp[$v['staff_id']]['commission_coefficient'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0): $v['commission_rate']}}">
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $listStaff->links('helpers.paging') }}
</div>

<script>
    new AutoNumeric.multiple('.commission_coefficient', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: 2,
        eventIsCancelable: true,
        minimumValue: 0
    });
</script>