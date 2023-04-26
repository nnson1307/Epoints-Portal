<?php
    $totalChecked = 0;

    if(isset($LIST)){
        $totalChecked = count($LIST);
    }
?>
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_all"
                           name="check_all"
                           type="checkbox"
                           onclick="expireContract.chooseAll(this, 'expire')">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">{{__('MÃ HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('TÊN HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('LOẠI HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
            <th class="tr_thead_list">{{__('NHÂN VIÊN PHỤ TRÁCH')}}</th>
            <th class="tr_thead_list">{{__('NGÀY HIỆU LỰC')}}</th>
            <th class="tr_thead_list">{{__('NGÀY HẾT HIỆU LỰC')}}</th>
            <th class="tr_thead_list">{{__('GIÁ TRỊ HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('GIÁ TRỊ CHƯA THANH TOÁN')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI CHĂM SÓC')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            @if($item['care_status'] == 'new')
                                <input class="check_one"
                                       name="check_one" type="checkbox"
                                       {{isset($arrContractExpire[$item['contract_code']]) ? 'checked' : ''}}
                                       onclick="expireContract.choose(this, 'expire')">
                                <span></span>
                            @else
                                <input class=""
                                       disabled
                                       name="" type="checkbox"
                                >
                                <span></span>
                            @endif
                            <input type="hidden" class="contract_code" value="{{$item['contract_code']}}">
                            <input type="hidden" class="contract_id" value="{{$item['contract_id']}}">
                        </label>
                    </td>
                    <td>
                        <a href="{{route("contract.contract.show",[ 'id' => $item['contract_id']])}}">
                            {{$item['contract_code']}}
                        </a>
                    </td>
                    <td>{{$item['contract_name']}}</td>
                    <td>{{$item['contract_category_name']}}</td>
                    <td>{{$item['status_name']}}</td>
                    <td>{{$item['partner_name']}}</td>
                    <td>
                        {{$item['effective_date'] != '' ? date("d/m/Y",strtotime($item['effective_date'])) : ''}}
                    </td>
                    <td>
                        {{$item['expired_date'] != '' ? date("d/m/Y",strtotime($item['expired_date'])) : ''}}
                    </td>
                    <td>{{number_format($item['last_total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{number_format($item['last_total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>
                        @switch($item['care_status'])
                            @case('new')
                                @lang('Chưa chăm sóc')
                                @break;
                            @case('in_care')
                                @lang('Đang chăm sóc')
                                @break;
                            @case('fail')
                                @lang('Chăm sóc thất bại')
                                @break;
                            @case('success')
                                @lang('Chăm sóc thành công')
                                @break;
                        @endswitch
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
