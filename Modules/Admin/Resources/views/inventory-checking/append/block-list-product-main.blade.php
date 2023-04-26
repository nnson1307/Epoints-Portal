<div class="table-responsive">
    <table id="table-product"
           class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th width="7%" class="ss--font-size-th ss--text-center">#</th>
            <th class="ss--font-size-th">{{__('MÃ SẢN PHẨM')}}</th>
            <th class="ss--font-size-th">{{__('SẢN PHẨM')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('HỆ THỐNG')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('THỰC TẾ')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('CHÊNH LỆCH')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('XỬ LÝ')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('GHI CHÚ')}}</th>
            <th class="ss--font-size-th"></th>
        </tr>
        </thead>
        <tbody>

        @foreach($inventoryCheckingDetail as $key => $valueDetail)
            <tr class="ss--select2-mini  blockProductMain">
                <td class="stt ss--font-size-13 ss--text-center">{{($key+1)}}</td>
                <td class="ss--font-size-13">{{ $valueDetail['productCode'] }}</td>
                <td class="ss--font-size-13">{{ $valueDetail['productName'] }}
                    <input type="hidden" class="productCode"
                           value="{{ $valueDetail['productCode'] }}">
                </td>
                <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                    <input type="hidden" class="unit" value="{{$valueDetail['unitId']}}">
                    @foreach($unit as $k=>$v)
                        @if($k==$valueDetail['unitId'])
                            {{$v}}
                        @endif
                    @endforeach
                </td>
                <td valign="top" style="width: 140px" class="ss--text-center ss--font-size-13">
                    {{$valueDetail['quantityOld']!=null?$valueDetail['quantityOld']:0}}
                    @if($valueDetail['quantityOld']!=null)
                        <input readonly style="text-align: center" type="hidden"
                               class="form-control quantityOld quantityOld_{{$valueDetail['inventory_checking_detail_id']}}"
                               value="{{ $valueDetail['quantityOld'] }}">
                    @else
                        <input readonly style="text-align: center" type="hidden"
                               class="form-control quantityOld quantityOld_{{$valueDetail['inventory_checking_detail_id']}}"
                               value="0">
                    @endif
                </td>
                <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                    @if($valueDetail['inventory_management'] == 'serial')
                        <input type="hidden" class="quantityNew quantityNew_{{$valueDetail['inventory_checking_detail_id']}}" value="{{$valueDetail['inventory_management'] == 'serial' ? (isset($listSerial[$valueDetail['inventory_checking_detail_id']]) ? count($listSerial[$valueDetail['inventory_checking_detail_id']]) : 0) : $valueDetail['quantityNew'] }}">
                        <input min="0" disabled
                               style="text-align: center" type="text"
                               class="form-control ss--btn-ct quantityNewClass quantityNewClass_{{$valueDetail['inventory_checking_detail_id']}} ss--width-150"
                               value="{{ $valueDetail['inventory_management'] == 'serial' ? (isset($listSerial[$valueDetail['inventory_checking_detail_id']]) ? count($listSerial[$valueDetail['inventory_checking_detail_id']]) : 0) : $valueDetail['quantityNew'] }}">
                    @else
                        <input onfocusout="changeQuantityNew(this)"
                               min="0"
                               style="text-align: center" type="text"
                               class="form-control ss--btn-ct quantityNew ss--width-150 quantityNew_{{$valueDetail['inventory_checking_detail_id']}}"
                               value="{{ $valueDetail['quantityNew'] }}">
                    @endif
                </td>
                <td valign="top" style="width: 140px" class="ss--font-size-13 ss--text-center">
                    <span class="quantityDifference quantityDifference_{{$valueDetail['inventory_checking_detail_id']}}">{{$valueDetail['quantityOld']- ($valueDetail['inventory_management'] == 'serial' ? (isset($listSerial[$valueDetail['inventory_checking_detail_id']]) ? count($listSerial[$valueDetail['inventory_checking_detail_id']]) : 0) : $valueDetail['quantityNew'])}}</span>
                    <input style="text-align: center" readonly type="hidden"
                           class="form-control quantityDifference quantityDifference_{{$valueDetail['inventory_checking_detail_id']}}"
                           value="{{ $valueDetail['quantityOld']- ($valueDetail['inventory_management'] == 'serial' ? (isset($listSerial[$valueDetail['inventory_checking_detail_id']]) ? count($listSerial[$valueDetail['inventory_checking_detail_id']]) : 0) : $valueDetail['quantityNew'])}}">
                </td>
                <td style="width: 100px" class="ss--font-size-13 ss--text-center">
                    @if($valueDetail['inventory_management'] != 'serial')
                        @if($valueDetail['quantityOld']-$valueDetail['quantityNew']>0)
                            <b class="m--font-danger resolve">
                                {{__('Xuất kho')}}
                            </b>
                        @elseif($valueDetail['typeResolve']=="input")
                            <b class="m--font-success resolve">
                                {{__('Nhập kho')}}
                            </b>
                        @else
                            <b class="m--font-success resolve"></b>
                        @endif
                        <input type="hidden" class="inventory_management" value="{{$valueDetail['inventory_management']}}">
                    @else
{{--                        @if($valueDetail['quantityOld'] != $valueDetail['quantityNew'])--}}
                            @if($valueDetail['total_export'] != 0)
                                <a href="javascript:void(0)" onclick="InventoryChecking.showPopupSerialProduct(`{{$valueDetail['inventory_checking_detail_id']}}`,`{{ $valueDetail['productCode'] }}`,'export')" >
                                    <b class="m--font-danger resolve">
                                        {{--                                                                    {{__('Xuất kho')}} : {{$valueDetail['quantityOld'] - $valueDetail['total_export']}}--}}
                                        {{__('Xuất kho')}} : {{$valueDetail['total_export']}}<br>
                                    </b>
                                </a>
                            @endif
                            @if($valueDetail['total_import'] != 0)
                                <a href="javascript:void(0)" onclick="InventoryChecking.showPopupSerialProduct(`{{$valueDetail['inventory_checking_detail_id']}}`,`{{ $valueDetail['productCode'] }}`,'import')" >
                                    <b class="m--font-success resolve">
                                        {{__('Nhập kho')}} : {{$valueDetail['total_import']}}
                                    </b>
                                </a>
                            @endif
{{--                        @else--}}
{{--                            <b class="m--font-success resolve"></b>--}}
{{--                        @endif--}}
                        <input type="hidden" class="inventory_management" value="{{$valueDetail['inventory_management']}}">
                        <input type="hidden" class="total_export" value="{{$valueDetail['total_export']}}">
                        <input type="hidden" class="total_import" value="{{$valueDetail['total_import']}}">
                    @endif
                </td>
                <td class="ss--font-size-13">
                    <input type="text" class="note form-control" onfocusout="saveProduct()" value="{{$valueDetail['note']}}">
                </td>
                <td style="width: 100px" class="ss--font-size-13 ss--text-center">
                    <button onclick="deleteProductInList(this,`{{$valueDetail['inventory_checking_detail_id']}}`,`{{ $valueDetail['productCode'] }}`)"
                            class="change-class m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                            title="Xóa">
                        <i class="la la-trash"></i>
                    </button>
                </td>
            </tr>
            @if($valueDetail['inventory_management'] == 'serial')
                <tr class="ss--font-size-13 ss--nowrap serialBlock block_tr_{{$valueDetail['inventory_checking_detail_id']}}">
                    <td>
                        <select class="form-control m_selectpicker inventory_checking_status" id="select_product_{{$valueDetail['inventory_checking_detail_id']}}">
                            @foreach($listCheckingStatus as $itemStatus)
                                {{--                                                            <option value="{{$itemStatus['inventory_checking_status_id']}}">{{$itemStatus['name']}}</option>--}}
                                <option value="{{$itemStatus['name']}}">{{$itemStatus['name']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" style="width:250px" id="input_product_{{$valueDetail['inventory_checking_detail_id']}}" onkeydown="InventoryChecking.addSerialProduct(event,`{{$valueDetail['productCode']}}`,`{{$valueDetail['inventory_checking_detail_id']}}`)" placeholder="{{__('Nhập số serial và enter')}}">
                    </td>
                    <td colspan="7">
                        <h5 style="white-space: initial">
                            @if(isset($listSerial[$valueDetail['inventory_checking_detail_id']]) && count($listSerial[$valueDetail['inventory_checking_detail_id']]) != 0)
                                @foreach($listSerial[$valueDetail['inventory_checking_detail_id']] as $key => $itemSerial)
                                    @if($key < 10)
                                        @if($itemSerial['is_new'] == 1)
                                            <span class="badge badge-pill badge-secondary mr-3 mb-3" style="background:#66C0B8">{{$itemSerial['is_default'] == 0 ? $itemSerial['inventory_checking_status_name'].' | ' : ''}}{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryChecking.removeSerial(`{{$itemSerial['inventory_checking_detail_serial_id']}}`,`{{$valueDetail['inventory_checking_detail_id']}}`,`{{ $valueDetail['productCode'] }}`,`{{$itemSerial['serial']}}`)"></i></span>
                                        @else
                                            <span class="badge badge-pill badge-secondary mr-3 mb-3">{{$itemSerial['is_default'] == 0 ? $itemSerial['inventory_checking_status_name'].' | ' : ''}}{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryChecking.removeSerial(`{{$itemSerial['inventory_checking_detail_serial_id']}}`,`{{$valueDetail['inventory_checking_detail_id']}}`,`{{ $valueDetail['productCode'] }}`,`{{$itemSerial['serial']}}`)"></i></span>
                                        @endif

                                    @endif
                                @endforeach
                            @endif
                        </h5>
                    </td>
                    <td class="text-center">
                        @if(isset($listSerial[$valueDetail['inventory_checking_detail_id']]) && count($listSerial[$valueDetail['inventory_checking_detail_id']]) > 9)
                            <a href="javascript:void(0)" onclick="InventoryChecking.showPopupListSerial({{$valueDetail['inventory_checking_detail_id']}})">{{__('Xem thêm')}}</a>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>