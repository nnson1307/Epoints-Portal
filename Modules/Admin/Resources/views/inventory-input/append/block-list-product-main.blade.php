<div class="table-responsive">
    <table id="table-product"
           class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th width="10%" class="ss--font-size-th ss--text-center">#</th>
            <th width="10%" class="ss--font-size-th ss--nowrap">{{__('MÃ SẢN PHẨM')}}</th>
            <th width="10%" class="ss--font-size-th ss--nowrap">{{__('SẢN PHẨM')}}</th>
            <th width="10%" class="ss--font-size-th ss--nowrap ss--text-center">{{__('GIÁ')}}</th>
            <th width="10%" class="ss--font-size-th ss--nowrap ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
            <th width="10%" class="ss--font-size-th ss--nowrap ss--text-center">{{__('GIÁ NHẬP')}}</th>
            <th width="10%" class="ss--font-size-th ss--nowrap ss--text-center">{{__('SỐ LƯỢNG')}}</th>
            <th width="10%" class="ss--font-size-th ss--nowrap ss--text-center">{{__('TỔNG TIỀN')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($inventoryInputDetail as $key=> $value)
            <tr class="ss--select2-mini blockProductMain">
                <td class="stt ss--font-size-13 text-center">{{$key+1}}</td>
                <td class="name-version ss--font-size-13">{{$value['code']}}
                <td class="name-version ss--font-size-13">{{$value['childName']}}
                    <input name="hiddencode[]" type="hidden" value="{{$value['code']}}">
                </td>
                <td valign="top" class="ss--font-size-13 ss--text-center ss--nowrap"
                    style="text-align: center;">
                    {{number_format($value['price'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    <input readonly class="form-control ss--display-none" type="text"
                           value="{{number_format($value['price'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                </td>
                <td class="ss--font-size-13 ss--text-center" style="width: 150px">
                    <input type="hidden" class="unit" value="{{$value['unitId']}}">
                    @foreach($unit as $k=>$v)
                        @if($value['unitId']==$k)
                            {{$v}}
                        @endif
                    @endforeach
{{--                    <select class="form-control ss--select-list ss--width-150" disabled>--}}
{{--                        @foreach($unit as $k=>$v)--}}
{{--                            @if($value['unitId']==$k)--}}
{{--                                <option selected value="{{$k}}">{{$v}}</option>--}}
{{--                            @else--}}
{{--                                <option value="{{$k}}">{{$v}}</option>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                </td>
                <td class="ss--font-size-13 ss--text-center" style="width: 150px">
                    <input onkeypress="maskNumberPriceProductChild()"
                           onchange="changeCost(this)"
                           id="id-child-{{$value['code']}}"
                           name="cost-product-child"
                           data-thousands=","
                           class="price form-control2 ss--text-center m-input change-class ss--width-150"
                           value="{{number_format($value['currentPrice'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                </td>
                <td class="ss--font-size-13 ss--text-center" style="width: 150px">

                    <div class="input-group bootstrap-touchspin ss--touchspin">
                        <span class="input-group-btn">
                            <button onclick="InventoryInput.tru(this)"
                                    class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct minus_{{$value['inventory_input_detail_id']}}"
                                    type="button">-</button>
                        </span>
                        <span class="input-group-addon bootstrap-touchspin-prefix"
                              style="display: none;">
                                                </span>
                        <input onchange="clickNumberProduct(this)"
                               type="text"
                               class="form-control ss--btn-ct number-product change-class ss--text-center"
                               value="{{$value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_input_detail_id']]) ? count($listSerial[$value['inventory_input_detail_id']]) : 0) : $value['quantity']}}" name="number-product">
                        <span class="input-group-addon bootstrap-touchspin-postfix"
                              style="display: none;">
                                                </span>
                        <span class="input-group-btn">
                            <button onclick="InventoryInput.cong(this)"
                                    class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct add_{{$value['inventory_input_detail_id']}}"
                                    type="button">+</button>
                        </span>
                </div>

                </td>
                <td valign="top" class="ss--font-size-13 ss--text-center"
                    style="width: 150px;text-align: center;">
                    {{--                                                <span class="total-money-product2">{{number_format($value['total'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>--}}
                    <span class="total-money-product2">{{number_format(($value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_input_detail_id']]) ? count($listSerial[$value['inventory_input_detail_id']])*$value['currentPrice'] : 0) : $value['quantity']*$value['currentPrice']),isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                    <input style="text-align: right" name="totalMoneyProduct[]" readonly
                           class="form-control total-money-product ss--display-none" type="text"
                           {{--                                                       value="{{number_format($value['total'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">--}}
                           value="{{number_format(($value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_input_detail_id']]) ? count($listSerial[$value['inventory_input_detail_id']])*$value['currentPrice'] : 0) : $value['quantity']*$value['currentPrice']),isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                </td>
                <td class="ss--font-size-13 ss--text-center" style="width: 50px">
                    <button onclick="deleteProductInList(this,{{$value['inventory_input_detail_id']}})"
                            class="change-class m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                            title="Xóa">
                        <i class="la la-trash"></i>
                    </button>
                </td>
            </tr>
            @if($value['inventory_management'] == 'serial')
                <tr class="ss--font-size-13 ss--nowrap serialBlock block_tr_{{$value['inventory_input_detail_id']}}">
                    <td><input type="text" class="form-control" style="width:250px" id="input_product_{{$value['inventory_input_detail_id']}}" onkeydown="InventoryInput.addSerialProduct(event,`{{$value['code']}}`,`{{$value['inventory_input_detail_id']}}`)" placeholder="{{__('Nhập số serial và enter')}}"></td>
                    <td colspan="7">
                        <h5 style="white-space: initial">
                            @if(isset($listSerial[$value['inventory_input_detail_id']]) && count($listSerial[$value['inventory_input_detail_id']]) != 0)
                                @foreach($listSerial[$value['inventory_input_detail_id']] as $key => $itemSerial)
                                    @if($key <= 9)
                                        <span class="badge badge-pill badge-secondary mr-3 mb-3" >{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryInput.removeSerial(`{{$itemSerial['inventory_input_detail_serial_id']}}`,`{{$value['inventory_input_detail_id']}}`)"></i></span>
                                    @endif
                                @endforeach
                            @endif
                        </h5>
                    </td>
                    <td class="text-center">
                        @if(isset($listSerial[$value['inventory_input_detail_id']]) && count($listSerial[$value['inventory_input_detail_id']]) > 10)
                            <a href="javascript:void(0)" onclick="InventoryInput.showPopupListSerial({{$value['inventory_input_detail_id']}})">{{__('Xem thêm')}}</a>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-lg-4 ">
        <div class="form-group m-form__group row">
            <label for="example-text-input" class="col-lg-12 col-form-label">
                {{__('Tổng số sản phẩm')}}: <b class="total-product ss--text-color">{{count($inventoryInputDetail)}} {{__('sản phẩm')}}</b>
            </label>
            <div class="col-4 ss--display-none">
                <div class="input-group m-input-group m-input-group--solid">
                    <input style="text-align: center" readonly id="total-product"
                           class="form-control m-input" type="text"
                           value="{{count($inventoryInputDetail)}}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 text-center">
        <div class="form-group m-form__group row">
            <label for="example-text-input" class="col-lg-12 col-form-label">
                {{__('Tổng số lượng')}}: <b class="total-quantity ss--text-color">{{$sumQuantity}}</b>
            </label>
            <div class="col-4 ss--display-none">
                <div class="input-group m-input-group m-input-group--solid">
                    <input style="text-align: center" readonly id="total-quantity"
                           class="form-control m-input" type="text"
                           value="{{$sumQuantity}}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group m-form__group row">
            <div class="col-lg-7"></div>
            <label for="example-text-input" class="col-lg-5 col-form-label">
                {{__('Tổng tiền')}}:
                <b class="total-money text-danger">{{number_format($sumTotal,isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</b> <b
                        class="text-danger">{{__('VNĐ')}}</b>
            </label>
            <div class="col-8">
                <div class="input-group m-input-group m-input-group--solid ss--display-none">
                    <input style="text-align: right" readonly class="form-control m-input"
                           type="text" value="{{number_format($sumTotal,isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                           id="total-money">
                    <div class="input-group-append">
                        <button class="btn btn-block"><b>{{__('VNĐ')}}</b>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>