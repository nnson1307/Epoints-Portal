<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_all" name="check_all" type="checkbox" onclick="allowance.chooseAllCommission(this)">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">{{__('LOẠI HOA HỒNG')}}</th>
            <th class="tr_thead_list">{{__('TÊN HOA HỒNG')}}</th>
{{--            <th class="tr_thead_list">{{__('THỜI GIAN HIỆU LỰC')}}</th>--}}
{{--            <th class="tr_thead_list">{{__('THỜI GIAN ÁP DỤNG MỖI')}}</th>--}}
{{--            <th class="tr_thead_list">{{__('LẤY GIÁ TRỊ TÍNH DỰA TRÊN')}}</th>--}}
            <th class="tr_thead_list">{{__('TAGS')}}</th>
{{--            <th class="tr_thead_list">{{__('MỨC ĐỘ ƯU TIÊN (?)')}}</th>--}}
        </tr>
        </thead>
        <tbody>
        @if (count($listCommission) > 0)
            @foreach($listCommission as $k => $v)
                <tr class="tr_commission">
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_one_commission" type="checkbox"
                                   {{isset($arrCheckTempCommission[$v['commission_id']]) ? 'checked' : ''}}
                                   onclick="allowance.chooseCommission(this)">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        @switch($v['commission_type'])
                            @case('order')
                                @lang('Hoa hồng theo đơn hàng')
                                @break
                            @case('kpi')
                                @lang('Hoa hồng theo kpi')
                                @break
                            @case('contract')
                                @lang('Hoa hồng theo hợp đồng')
                                @break
                        @endswitch
                    </td>
                    <td>
                        {{$v['commission_name']}}

                        <input type="hidden" class="commission_id" value="{{$v['commission_id']}}">
                    </td>
{{--                    <td>{{Carbon\Carbon::parse($v['start_effect_time'])->format('d/m/Y')}}</td>--}}
{{--                    <td>{{$v['apply_time'] }} @lang('tháng')</td>--}}
{{--                    <td>{{$v['calc_apply_time'] }} @lang('tháng')</td>--}}
                    <td>{{ implode(', ', $v['tags']) }}</td>
{{--                    <td>--}}
{{--                        <select class="form-control priority"  onchange="allowance.updateObjectCommission(this)"--}}
{{--                                {{isset($arrCheckTempCommission[$v['commission_id']]) ? '' : 'disabled'}}>--}}
{{--                            <option value="1" {{isset($arrCheckTempCommission[$v['commission_id']]) && $arrCheckTempCommission[$v['commission_id']]['priority'] == 1 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 1')--}}
{{--                            </option>--}}
{{--                            <option value="2" {{isset($arrCheckTempCommission[$v['commission_id']]) && $arrCheckTempCommission[$v['commission_id']]['priority'] == 2 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 2')--}}
{{--                            </option>--}}
{{--                            <option value="3" {{isset($arrCheckTempCommission[$v['commission_id']]) && $arrCheckTempCommission[$v['commission_id']]['priority'] == 3 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 3')--}}
{{--                            </option>--}}
{{--                            <option value="4" {{isset($arrCheckTempCommission[$v['commission_id']]) && $arrCheckTempCommission[$v['commission_id']]['priority'] == 4 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 4')--}}
{{--                            </option>--}}
{{--                            <option value="5" {{isset($arrCheckTempCommission[$v['commission_id']]) && $arrCheckTempCommission[$v['commission_id']]['priority'] == 5 ? 'selected': ''}}>--}}
{{--                                @lang('Mức 5')--}}
{{--                            </option>--}}
{{--                        </select>--}}
{{--                    </td>--}}
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $listCommission->links('helpers.paging') }}
</div>