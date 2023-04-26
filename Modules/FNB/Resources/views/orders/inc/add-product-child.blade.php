    <tr class="tr_table tr_table_{{$item['id']}} tr_table_group_">
        <td class="td_vtc" style="width: 30px;"></td>
        <td class="td_vtc" style="color: #000; font-weight: 500;">
            {{$item['product_name']}}
            <div class="block"></div>
            <input type="hidden" name="product_id" value="{{$item['product_id']}}">
            <input type="hidden" name="id" value="{{$item['id']}}">
            <input type="hidden" name="name" value="{{$item['name']}}">
            <input type="hidden" name="object_type" value="{{$item['type']}}">
            <input type="hidden" name="object_code" value="{{$item['code']}}">
        </td>
        <td>
{{--            <img src="{{asset('static/backend/images/menu_add.png')}}" id="{code}_{id}" onclick="order.selectTopping('{id}')"><label for="{code}_{id}">{{__('Món thêm')}}</label>--}}
        </td>
        <td class="td_vtc" style="width: 110px;">
            <span class="text_price">{{$item['price']}}{{__('đ')}}</span>
            <input type="hidden" name="price" class="value_price" value="{{str_replace(',', '', $item['price_hidden'])}}">
        </td>
        <td class="td_vtc" style="width: 130px;">
            <?php $stt_tr++ ?>
            <input style="text-align: center; height: 31px;font-size: 13px;" type="text" name="quantity"
                   class="quantity quantity_{{$item['id']}} form-control btn-ct-input" data-id="{{$stt_tr}}" value="1"
                   {{$customPrice == 1 ? 'disabled' : ''}} {{$item['is_surcharge'] == 1 ? 'disabled' : ''}}>
            <input type="hidden" name="quantity_hid" value="0">
        </td>
        <td class="discount-tr-{{$item['type']}}-{{$stt_tr}} td_vtc" style="text-align: center; width: 130px;">
            <input type="hidden" name="discount" class="form-control discount" value="0">
            <input type="hidden" name="voucher_code" value="">
            @if (!isset($customPrice) || $customPrice == 0)
                <a href="javascript:void(0)" id="discount_{{$stt_tr}}" class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}','{stt}')">
                    <i class="la la-plus icon-sz"></i>
                </a>
            @endif
        </td>
        <td class="amount-tr td_vtc" style="text-align: center; width: 130px;">
            @if (isset($customPrice) && $customPrice == 1)
                <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{{$stt_tr}}"
                       value="{{str_replace(',', '', $item['price'])}}">
            @else
                @if($item['is_surcharge'] == 1)
                    <div id="amount_surcharge_{{$stt_tr}}">
                        <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{{$stt_tr}}"
                               value="{{str_replace(',', '', $item['price'])}}">
                    </div>
                @else
                    <div id="amount_not_surcharge_{{$stt_tr}}">
                        {{$item['price']}}{{__('đ')}}
                        <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                               id="amount_{{$stt_tr}}" value="{{str_replace(',', '', $item['price'])}}">
                    </div>
                @endif
            @endif
        </td>
        <td class="td_vtc" style="width: 50px;">
            <input type="hidden" name="is_change_price" value="{{$item['is_surcharge'] == 1 ? 1 : 0}}">
            <input type="hidden" name="is_check_promotion" value="{{$item['is_surcharge'] == 1 ? 0 : 1}}">
            <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i
                        class='la la-trash'></i></a>
        </td>

        <input type="hidden" id="numberRow" value="{{$numberRow}}">
        <?php $numberRow++ ?>
    </tr>
@endforeach
