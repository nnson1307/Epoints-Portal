<div class="table-responsive">
    <table id="table-product"
           class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th width="10%" class="ss--font-size-th ss--text-center">#</th>
            <th width="10%" class="ss--font-size-th ss--text-center">{{__('MÃ SẢN PHẨM')}}</th>
            <th width="10%" class="ss--font-size-th ss--text-center">{{__('SẢN PHẨM')}}</th>
            <th width="10%" class="ss--font-size-th ss--text-center">{{__('GIÁ BÁN')}}</th>
            <th width="10%" class="ss--font-size-th ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
            <th width="10%" class="ss--font-size-th ss--text-center">{{__('GIÁ NHẬP')}}</th>
            <th width="10%" class="ss--font-size-th ss--text-center ss--nowrap">{{__('TỒN KHO')}}</th>
            <th width="10%" class="ss--font-size-th ss--text-center ss--nowrap">{{__('SỐ LƯỢNG')}}</th>
            <th width="10%" class="ss--font-size-th ss--text-center">{{__('TỔNG TIỀN')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($product as $key=> $value)
            <tr class="ss--select2-mini blockProductMain">
                <td class="stt ss--font-size-13 ss--text-center">{{$key+1}}</td>
                <td class="name-version ss--font-size-13 ss--text-center">{{$value['productCode']}}
                <td class="name-version ss--font-size-13 ss--text-center">{{$value['productName']}}
                    <input name="hiddencode[]" type="hidden" value="{{$value['productCode']}}">
                </td>
                <td valign="top"
                    class="ss--text-center ss--font-size-13">{{number_format($value['price'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                    <input type="hidden" class="unit" value="{{$value['unitId']}}">
                    @foreach($unit as $k=>$v)
                        @if($value['unitId']==$k)
                            {{$v}}
                        @endif
                    @endforeach
{{--                    <select class="form-control  ss--width-150" disabled>--}}
{{--                        @foreach($unit as $k=>$v)--}}
{{--                            @if($value['unitId']==$k)--}}
{{--                                <option selected value="{{$k}}">{{$v}}</option>--}}
{{--                            @else--}}
{{--                                <option value="{{$k}}">{{$v}}</option>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                </td>
                <td valign="top"
                    class="ss--text-center ss--font-size-13">{{number_format($value['cost'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0      )}}
                    <input name="cost-product-child"
                           style="text-align: center;display: none"
                           data-thousands=","
                           class="cost-product-child form-control2 m-input change-class ss--display-none"
                           value="{{number_format($value['cost'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                </td>
                <td>
                    <input readonly
                           class="form-control product-inventory ss--btn-ct ss--text-center"
                           value="{{$value['productInventory']}}">
                </td>
                <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                    <div class="input-group bootstrap-touchspin ss--touchspin">
                        <span class="input-group-btn">
                            <button onclick="InventoryOutput.tru(this)"
                                class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct minus_{{$value['inventory_output_detail_id']}}"
                                type="button">-</button>
                        </span>
                        <span class="input-group-addon bootstrap-touchspin-prefix"
                              style="display: none;">
                                                </span>
                        <input onchange="changeOutputQuantity(this)"
                           min="0" id="m_touchspin_1" type="text"
                           class="form-control ss--btn-ct outputQuantity number-product change-class ss--text-center"
{{--                               value="{{$value['outputQuantity']}}" name="number-product">--}}
                           value="{{$value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_output_detail_id']]) ? count($listSerial[$value['inventory_output_detail_id']]) : 0) : $value['quantity']}}" name="number-product">
                        <span class="input-group-addon bootstrap-touchspin-postfix"
                              style="display: none;">
                        </span>
                        <span class="input-group-btn">
                            <button onclick="InventoryOutput.cong(this)"
                                class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct sum_{{$value['inventory_output_detail_id']}}"
                                type="button">+</button>
                        </span>
                    </div>
                    <span class="errs error-output-quantity"></span>
                </td>
                <td valign="top" class="ss--text-center">
                    <span class="total-money-product">
{{--                        {{number_format($value['cost']*$value['outputQuantity'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}--}}
                        {{number_format(($value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_output_detail_id']]) ? count($listSerial[$value['inventory_output_detail_id']])*$value['cost'] : 0) : $value['outputQuantity']*$value['cost']),isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </span>
                </td>
                <td style="width: 50px">
                    <button onclick="deleteProductInList(this,{{$value['inventory_output_detail_id']}})"
                            class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                            title="Xóa">
                        <i class="la la-trash"></i>
                    </button>
                </td>
            </tr>
            @if($value['inventory_management'] == 'serial')
                <tr class="ss--font-size-13 ss--nowrap serialBlock block_tr_{{$value['inventory_output_detail_id']}}">
                    <td><input type="text" class="form-control" style="width:250px" id="input_product_{{$value['inventory_output_detail_id']}}" onkeydown="InventoryOutput.addSerialProduct(event,`{{$value['code']}}`,`{{$value['inventory_output_detail_id']}}`)" placeholder="{{__('Nhập số serial và enter')}}"></td>
                    <td colspan="8">
                        <h5 style="white-space: initial">
                            @if(isset($listSerial[$value['inventory_output_detail_id']]) && count($listSerial[$value['inventory_output_detail_id']]) != 0)
                                @foreach($listSerial[$value['inventory_output_detail_id']] as $key => $itemSerial)
                                    @if($key <= 9)
                                        <span class="badge badge-pill badge-secondary mr-3 mb-3" >{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryOutput.removeSerial(`{{$itemSerial['inventory_output_detail_serial_id']}}`,`{{$value['inventory_output_detail_id']}}`)"></i></span>
                                    @endif
                                @endforeach
                            @endif
                        </h5>
                    </td>
                    <td class="text-center">
                        @if(isset($listSerial[$value['inventory_output_detail_id']]) && count($listSerial[$value['inventory_output_detail_id']]) > 10)
                            <a href="javascript:void(0)" onclick="InventoryOut.showPopupListSerial({{$value['inventory_output_detail_id']}})">{{__('Xem thêm')}}</a>
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
                {{__('Tổng số sản phẩm')}}: <b class="total-product ss--text-color">{{count($product)}} {{__('sản phẩm')}}</b>
            </label>
            <div class="col-4 ss--display-none">
                <div class="input-group m-input-group m-input-group--solid">
                    <input style="text-align: center" readonly id="total-product"
                           class="form-control m-input" type="text"
                           value="{{count($product)}}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 text-center">
        <div class="form-group m-form__group row">
            <label for="example-text-input" class="col-lg-12 col-form-label">
                {{__('Tổng số lượng')}}: <b class="total-quantity ss--text-color">{{$totalQuantity}}</b>
            </label>
            <div class="col-4 ss--display-none">
                <div class="input-group m-input-group m-input-group--solid">
                    <input style="text-align: center" readonly id="total-quantity"
                           class="form-control m-input" type="text"
                           value="{{$totalQuantity}}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group m-form__group row">
            <div class="col-lg-7"></div>
            <label for="example-text-input" class="col-lg-5 col-form-label">
                {{__('Tổng tiền')}}:
                <b class="total-money text-danger">{{number_format($totalMoney)}}</b> <b
                        class="text-danger">{{__('VNĐ')}}</b>
            </label>
            <div class="col-8">
                <div class="input-group m-input-group m-input-group--solid ss--display-none">
                    <input style="text-align: right" readonly class="form-control m-input"
                           type="text" value="{{number_format($totalMoney)}}"
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