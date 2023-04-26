<div class="table-responsive">
    <table class="table m-table m-table--head-bg-default" id="table-goods">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('MÃ HÀNG HOÁ')}}</th>
            <th class="tr_thead_list">{{__('LOẠI HÀNG HOÁ')}}</th>
            <th class="tr_thead_list">{{__('TÊN HÀNG HOÁ')}}</th>
            <th class="tr_thead_list">{{__('ĐƠN VỊ TÍNH')}}</th>
            <th class="tr_thead_list">{{__('SỐ LƯỢNG')}}</th>
            <th class="tr_thead_list">{{__('GIÁ')}}</th>
            <th class="tr_thead_list">{{__('VAT')}}</th>
            <th class="tr_thead_list">{{__('GIẢM GIÁ')}}</th>
            <th class="tr_thead_list">{{__('THÀNH TIỀN')}}</th>
            <th class="tr_thead_list">{{__('MÃ ĐƠN HÀNG')}}</th>
            <th class="tr_thead_list">{{__('GHI CHÚ')}}</th>
            <th class="tr_thead_list">{{__('TÍNH KPI')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr class="tr-goods tr-goods-same">
                    <td class="td_action_{{$key+1}}">
                        @if(session()->get('is_detail') == 0 && in_array($item['object_type'], ['product', 'service', 'service_card']))
                            @if ($infoOrder == null || $infoOrder['process_status'] == 'new')
                                <a href="javascript:void(0)" onclick="contractGoods.clickEdit(this)"
                                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('Sửa')}}">
                                    <i class="la la-edit"></i>
                                </a>
                                <button onclick="contractGoods.removeObject(this)"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Xoá')}}">
                                    <i class="la la-trash"></i>
                                </button>
                            @endif
                        @endif
                    </td>
                    <td>
                        <input class="form-control object_code" value="{{$item['object_code']}}" disabled>
                        <input type="hidden" class="form-control object_name" value="{{$item['object_name']}}">
                        <input type="hidden" class="form-control number" value="{{$key+1}}">
                        <input type="hidden" class="form-control click_edit" value="0">
                        <input type="hidden" class="form-control contract_goods_id"
                               value="{{$item['contract_goods_id']}}">
                        <input type="hidden" class="type_object"
                               value="{{in_array($item['object_type'], ['product', 'service', 'service_card']) ? 'not_gift' : 'gift'}}">
                        <input type="hidden" class="staff_id" value="{{$item['staff_id']}}">
                    </td>
                    <td>
                        <select class="form-control object_type" style="width:100%;" disabled
                                onchange="contractGoods.changeObjectType(this)">
                            <option></option>
                            @if (in_array($item['object_type'], ['product', 'service', 'service_card']))
                                <option value="product" {{$item['object_type'] == 'product' ? 'selected': ''}}>@lang('Sản phẩm')</option>
                                <option value="service" {{$item['object_type'] == 'service' ? 'selected': ''}}>@lang('Dịch vụ')</option>
                                <option value="service_card" {{$item['object_type'] == 'service_card' ? 'selected': ''}}>@lang('Thẻ dịch vụ')</option>
                            @else
                                <option value="product_gift" {{$item['object_type'] == 'product_gift' ? 'selected': ''}}>@lang('Sản phẩm')</option>
                                <option value="service_gift" {{$item['object_type'] == 'service_gift' ? 'selected': ''}}>@lang('Dịch vụ')</option>
                                <option value="service_card_gift" {{$item['object_type'] == 'service_card_gift' ? 'selected': ''}}>@lang('Thẻ dịch vụ')</option>
                            @endif
                        </select>
                        <span class="error_object_type_{{$key+1}}" style="color: red;"></span>
                    </td>
                    <td>
                        <select class="form-control object_id" style="width:100%;" disabled
                                onchange="contractGoods.changeObject(this, 1)">
                            <option></option>
                            <option value="{{$item['object_id']}}" selected>{{$item['object_name']}}</option>
                        </select>
                        <span class="error_object_id_{{$key+1}}" style="color: red;"></span>
                    </td>
                    <td>
                        <select class="form-control unit_id" style="width:100%;" disabled>
                            <option></option>
                            @foreach($optionUnit as $v)
                                <option value="{{$v['unit_id']}}"
                                        {{$item['unit_id'] == $v['unit_id'] ? 'selected': ''}}>{{$v['name']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control quantity input_int" value="{{$item['quantity']}}"
                               onchange="contractGoods.changeQuantity(this)" disabled>
                        <span class="error_quantity_{{$key+1}}" style="color: red;"></span>
                    </td>
                    <td>
                        <input class="form-control price input_float" id="price_{{$key+1}}" disabled
                               value="{{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    </td>
                    <td>
                        <input class="form-control tax input_int" onchange="contractGoods.changePrice(this)" disabled
                               value="{{$item['tax']}}">
                        <span class="error_tax_{number}" style="color: red;"></span>
                    </td>
                    <td class="td_discount_{{$key+1}}">
                        <input class="form-control discount" id="discount_{{$key+1}}"
                               onchange="contractGoods.changePrice(this)" disabled
                               value="{{number_format($item['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    </td>
                    <td>
                        <input class="form-control amount input_float" disabled
                               value="{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    </td>
                    <td>
                        <input class="form-control order_code" disabled value="{{$item['order_code']}}">
                    </td>
                    <td>
                        <input class="form-control note" value="{{$item['note']}}" disabled>
                    </td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input class="is_applied_kpi" type="checkbox" disabled
                                       {{$item['is_applied_kpi'] == 1 ? 'checked' : ''}}>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

@if(session()->get('is_detail') == 0)
    <div class="div_add_goods">
        <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                onclick="contractGoods.addGoods()">
            <i class="la la-plus"></i> @lang('THÊM HÀNG HOÁ')
        </button>
    </div>
@endif

