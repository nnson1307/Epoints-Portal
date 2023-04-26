<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table-gift">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">{{__('TÊN')}}</th>
            <th class="tr_thead_list">{{__('SỐ LƯỢNG CẦN MUA')}}</th>
            <th class="tr_thead_list">{{__('SỐ LƯỢNG QUÀ TẶNG')}}</th>
            <th class="tr_thead_list">{{__('LOẠI QUÀ TẶNG')}}</th>
            <th class="tr_thead_list">{{__('QUÀ TẶNG')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($list) > 0)
            @foreach($list as $item)
                <tr>
                    <td style="width:20%;">
                        {{$item['object_name']}}
                        <input type="hidden" class="object_type" value="{{$item['object_type']}}">
                        <input type="hidden" class="object_code" value="{{$item['object_code']}}">
                    </td>
                    <td style="width:15%;">
                        <input class="form-control quantity_buy" placeholder="@lang('Nhập số lượng cần mua')"
                               onchange="view.changeQuantityBuy(this, '{{$item['object_code']}}')"
                               value="{{$item['quantity_buy']}}" disabled>
                        <span class="error_quantity_buy_{{$item['object_code']}}"></span>
                    </td>
                    <td style="width:15%;">
                        <input class="form-control quantity_gift" placeholder="@lang('Nhập số lượng quà tặng')"
                               onchange="view.changeNumberGift(this, '{{$item['object_code']}}')"
                               value="{{$item['quantity_gift']}}" disabled>
                        <span class="error_quantity_gift_{{$item['object_code']}}"></span>
                    </td>
                    <td style="width:15%;">
                        <select class="form-control gift_object_type" style="width:100%;"
                                onchange="view.changeGiftType(this, '{{$item['object_code']}}')" disabled>
                            <option></option>
                            <option value="product" {{$item['gift_object_type'] == 'product' ? 'selected' : ''}}>@lang('Sản phẩm')</option>
                            <option value="service" {{$item['gift_object_type'] == 'service' ? 'selected' : ''}}>@lang('Dịch vụ')</option>
                            <option value="service_card" {{$item['gift_object_type'] == 'service_card' ? 'selected' : ''}}>@lang('Thẻ dịch vụ')</option>
                        </select>
                        <span class="error_gift_object_type_{{$item['object_code']}}"></span>
                    </td>
                    <td style="width:25%;">
                        <select class="form-control gift_object_id" style="width:100%;" {{$item['gift_object_type'] == null ? 'disabled' : ''}}
                            onchange="view.changeGift(this, '{{$item['object_code']}}')" disabled>
                            <option></option>
                            <option value="{{$item['gift_object_id']}}" selected>{{$item['gift_object_name']}}</option>
                        </select>
                    </td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input class="is_detail_active" type="checkbox"
                                       {{$item['is_actived'] == 1 ? 'checked' : ''}} disabled
                                       onchange="view.changeStatus(this, '{{$item['object_code']}}')">
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging')}}
</div>

<script>
    $('.quantity_buy, .quantity_gift').ForceNumericOnly();

    $.getJSON(laroute.route('translate'), function (json) {
        $('.gift_object_type').select2({
            placeholder: json['Chọn loại quà tặng']
        });
        $('.gift_object_id').select2({
            placeholder: json['Chọn quà tặng']
        });

        $.each($('#table-gift').find("tr"), function () {
            var gift_object_type = $(this).find($('.gift_object_type')).val();

            if (gift_object_type != '' || gift_object_type != 'undefined') {
                $(this).find($('.gift_object_id')).select2({
                    width: '100%',
                    placeholder: json["Chọn quà tặng"],
                    ajax: {
                        url: laroute.route('promotion.list-option'),
                        data: function (params) {
                            return {
                                search: params.term,
                                page: params.page || 1,
                                type: gift_object_type
                            };
                        },
                        dataType: 'json',
                        method: 'POST',
                        processResults: function (data) {
                            data.page = data.page || 1;
                            return {
                                results: data.items.map(function (item) {
                                    if (gift_object_type == 'product') {
                                        return {
                                            id: item.product_child_id,
                                            text: item.product_child_name,
                                            code: item.product_code
                                        };
                                    } else if (gift_object_type == 'service') {
                                        return {
                                            id: item.service_id,
                                            text: item.service_name,
                                            code: item.service_code
                                        };
                                    } else if (gift_object_type == 'service_card') {
                                        return {
                                            id: item.service_card_id,
                                            text: item.card_name,
                                            code: item.code
                                        };
                                    }
                                }),
                                pagination: {
                                    more: data.pagination
                                }
                            };
                        },
                    }
                });
            }
        });
    });
</script>

