<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table-allocation">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
{{--            <th class="tr_thead_list">--}}
{{--                @lang('Mức độ ưu tiên')--}}
{{--            </th>--}}
            @if (count($arrStaff) > 0)
                @foreach($arrStaff as $v)
                    <th class="tr_thead_list text-center">{{$v['staff_name']}}</th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        <tr class="tr_coefficient">
            <td>@lang('Hệ số hoa hồng')</td>
{{--            <td></td>--}}
            @if (count($arrStaff) > 0)
                @foreach($arrStaff as $v)
                    <td class="text-center">
                        <input type="text" class="form-control allocation_coefficient"
                               value="{{number_format($v['commission_coefficient'], 2)}}">

                        <input type="hidden" class="staff_id" value="{{$v['staff_id']}}">
                    </td>
                @endforeach
            @endif
        </tr>
        @if(count($arrCommission) > 0)
            @foreach($arrCommission as $v)
                <tr class="tr_commission">
                    <td>
                        {{$v['commission_name']}}

                        <input type="hidden" class="commission_id" value="{{$v['commission_id']}}">
                    </td>
{{--                    <td>--}}
{{--                        <select class="form-control allocation_priority">--}}
{{--                            <option value="1" {{$v['priority'] == 1 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 1')--}}
{{--                            </option>--}}
{{--                            <option value="2" {{$v['priority'] == 2 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 2')--}}
{{--                            </option>--}}
{{--                            <option value="3" {{$v['priority'] == 3 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 3')--}}
{{--                            </option>--}}
{{--                            <option value="4" {{$v['priority'] == 4 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 4')--}}
{{--                            </option>--}}
{{--                            <option value="5" {{$v['priority'] == 5 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 5')--}}
{{--                            </option>--}}
{{--                        </select>--}}
{{--                    </td>--}}
                    @if (count($arrStaff) > 0)
                        @foreach($arrStaff as $v1)
                            <td class="text-center">
                                <label class="m-checkbox m-checkbox--state-success">
                                    <input class="check_commission" type="checkbox" checked>
                                    <span></span>
                                </label>

                                <input type="hidden" class="staff_id" value="{{$v1['staff_id']}}">
                            </td>
                        @endforeach
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<script>
    new AutoNumeric.multiple('.allocation_coefficient', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: 2,
        eventIsCancelable: true,
        minimumValue: 0
    });
</script>