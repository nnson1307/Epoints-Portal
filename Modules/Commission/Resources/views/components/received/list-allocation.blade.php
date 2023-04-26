<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table-allocation">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('LOẠI HOA HỒNG')}}</th>
            <th class="tr_thead_list">{{__('TÊN HOA HỒNG')}}</th>
{{--            <th class="tr_thead_list text-center">{{__('THỜI GIAN HIỆU LỰC')}}</th>--}}
{{--            <th class="tr_thead_list text-center">{{__('THỜI GIAN ÁP DỤNG MỖI')}}</th>--}}
            <th class="tr_thead_list">{{__('HỆ SỐ HOA HỒNG')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($listAllocation) > 0)
            @foreach($listAllocation as $k => $v)
                <tr class="tr_allocation">
                    <td style="vertical-align: middle;">

                    </td>
                    <td style="vertical-align: middle;">
                        <a href="javascript:void(0)" onclick="listStaff.removeTr(this)"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                           title="Delete">
                            <i class="la la-trash"></i>
                        </a>
                    </td>
                    <td style="vertical-align: middle;">
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
                    <td style="vertical-align: middle;">
                        {{$v['commission_name']}}

                        <input type="hidden" class="commission_id" value="{{$v['commission_id']}}">
                    </td>
{{--                    <td class="text-center" style="vertical-align: middle;">--}}
{{--                        {{Carbon\Carbon::parse($v['start_effect_time'])->format('d/m/Y')}}--}}
{{--                    </td>--}}
{{--                    <td class="text-center" style="vertical-align: middle;">--}}
{{--                        {{$v['apply_time'] }} @lang('tháng')--}}
{{--                    </td>--}}
                    <td style="vertical-align: middle;">
                        <input type="text" class="form-control commission_coefficient"
                               value="{{number_format($v['commission_coefficient'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>