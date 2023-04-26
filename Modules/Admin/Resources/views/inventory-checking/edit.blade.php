@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
@stop
@section('content')
    <style>
        span.select2 {
            width:100% !important;
        }

        #popup-list-serial .modal-dialog ,#popup-list-serial-product .modal-dialog {
            max-width: 50%;
        }
        .badge-secondary i:hover {
            cursor: pointer
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CẬP NHẬT PHIẾU KIỂM KHO')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group" style="display: none">
                        <label>
                            {{__('Mã phiếu')}}: <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group m-input-group--solid">
                                <input readonly class="form-control" value="{{$inventoryChecking->code}}"
                                       id="checking-code" type="text">
                            </div>
                        </div>
                        <span class="errs error-supplier"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Kho')}}: <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group">
                                <input type="hidden" class="form-control" id="warehouse" value="{{$inventoryChecking->warehouseId}}">
                                <select class="form-control m_selectpicker" disabled>
                                    @foreach($warehouse as $key=>$value)
                                        @if($inventoryChecking->warehouseId==$key)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <span class="errs error-warehouse"></span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group" style="display: none">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>
                                    {{__('Người tạo')}}:
                                </label>
                                <div class="input-group">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <input id="created-by" type="text" value="{{$inventoryChecking->user}}" readonly
                                               class="form-control m-input class">
                                    </div>
                                    <span class="errs error-product-name"></span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label>
                                    {{__('Ngày kiểm tra')}}:
                                </label>
                                <div class="input-group">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <div class="input-group-append">
                                            <input disabled id="created-at" type="text"
                                                   class="form-control m-input class"
                                                   value="{{(new DateTime($inventoryChecking->createdAt))->format('d/m/Y')}}"
                                                   aria-describedby="basic-addon1">
                                            <span class="input-group-text">
                                                <i class="la la-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <span class="errs error-day-checking"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Lý do')}}:<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                                <textarea placeholder="{{__('Nhập lý do kiểm kho')}}" rows="5" cols="50" name="description"
                                          id="note" class="form-control">{{$inventoryChecking->reason}}</textarea>
                        </div>
                    </div>
                    <span class="errs error-note"></span>
                </div>
                <div class="col-lg-12 mt-3">
                    <div class="form-group m-form__group">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                            <div class="m-form__actions m--align-right">
                                <button onclick="location.href='{{route('admin.product-inventory')}}'"
                                        class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                                                    <span class="ss--text-btn-mobi">
                                                    <i class="la la-arrow-left"></i>
                                                    <span>{{__('HỦY')}}</span>
                                                    </span>
                                </button>
                                <button id="btn-save-draft" type="button"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-file-o"></i>
                                    <span>{{__('LƯU NHÁP')}}</span>
                                    </span>
                                </button>
                                <button type="button"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md btn-save m--margin-left-10 m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                        <i class="la la-check"></i>
                                        <span>{{__('LƯU THÔNG TIN')}}</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" style="margin-bottom: 0;" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show son" data-toggle="tab" href="#" data-target="#inventory">
                                <h7>{{__('THỦ CÔNG')}}</h7>
                            </a>
                        </li>
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link son" data-toggle="tab" href="#inventory-input">--}}
{{--                                <h7>{{__('BARCODE')}}</h7>--}}
{{--                            </a>--}}
{{--                        </li>--}}
                    </ul>
                    <div class="bd-ct">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="inventory" role="tabpanel">
                                <div class="form-group m-form__group">
                                    <label class="label-son">
                                        {{__('Danh sách sản phẩm')}}:
                                    </label>
                                    <div class="col-xl-12 order-2 order-xl-1">
                                        <div class="form-group m-form__group row align-items-center">
                                            <div class="input-group w-25 mr-3">
                                                <select style="width: 100%" class="form-control ss--width-100"
                                                        name="list-product"
                                                        id="list-product">
                                                    <option value="">{{__('Chọn sản phẩm')}}</option>
                                                    @foreach($productList as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group w-25">
                                                <select style="width: 100%" class="form-control ss--width-100 "
                                                        name="list-product-serial"
                                                        id="list-product-serial">
                                                    <option value="">{{__('Chọn sản phẩm theo seri')}}</option>
                                                    @foreach($listProductByWarehouse as $key=>$value)
                                                        <option value="{{$value['product_child_id']}}" data-serial="{{$value['serial']}}" data-product-code="{{$value['product_code']}}">{{$value['serial'].' - '.$value['product_child_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <form action="{{route('admin.inventory-checking.export-checking-list')}}">
                                                <input type="hidden" name="inventory_checking_id" value="{{$inventoryChecking->id}}">
                                                <button type="submit"
                                                   {{--                       onclick="location.href='{{route('admin.inventory-input.add')}}'"--}}
                                                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill pull-right ml-3">
                                                <span>
                                                    <i class="fas fa-download"></i>
                                                    <span> {{__('Xuất dữ liệu')}}</span>
                                                </span>
                                                </button>
                                            </form>
                                            <a href="javascript:void(0)"
                                               {{--                       onclick="location.href='{{route('admin.inventory-input.add')}}'"--}}
                                               onclick="InventoryChecking.showPopup()"
                                               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill pull-right ml-3">
                                                <span>
                                                    <i class="fa fa-plus-circle"></i>
                                                    <span> {{__('Nhập dữ liệu')}}</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="inventory-input" role="tabpanel">
                                <div class="form-group m-form__group">
                                    <label class="label-son">
                                        {{__('Mã sản phẩm')}}:
                                    </label>
                                    <div class="col-xl-6 order-2 order-xl-1">
                                        <div class="form-group m-form__group row align-items-center">
                                            <div class="input-group col-xs-10">
                                                <div class="input-group m-input-group">
                                                    <input placeholder="{{__('Nhập mã sản phẩm')}}" autofocus id="product-code"
                                                           type="text" value=""
                                                           class="form-control m-input class">
                                                </div>
                                                <span class="errs error-code-product"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Table version--}}
                        <div class="block-list-product-main">
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
                                    @foreach($inventoryCheckingDetail as $key=>$value)
                                        <tr class="ss--select2-mini  blockProductMain">
                                            <td class="stt ss--font-size-13 ss--text-center">{{($key+1)}}</td>
                                            <td class="ss--font-size-13">{{ $value['productCode'] }}</td>
                                            <td class="ss--font-size-13">{{ $value['productName'] }}
                                                <input type="hidden" class="productCode"
                                                       value="{{ $value['productCode'] }}">
                                            </td>
                                            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                                                <input type="hidden" class="unit" value="{{$value['unitId']}}">
                                                @foreach($unit as $k=>$v)
                                                    @if($k==$value['unitId'])
                                                        {{$v}}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td valign="top" style="width: 140px" class="ss--text-center ss--font-size-13">
                                                {{$value['quantityOld']!=null?$value['quantityOld']:0}}
                                                @if($value['quantityOld']!=null)
                                                    <input readonly style="text-align: center" type="hidden"
                                                           class="form-control quantityOld quantityOld_{{$value['inventory_checking_detail_id']}}"
                                                           value="{{ $value['quantityOld'] }}">
                                                @else
                                                    <input readonly style="text-align: center" type="hidden"
                                                           class="form-control quantityOld quantityOld_{{$value['inventory_checking_detail_id']}}"
                                                           value="0">
                                                @endif
                                            </td>
                                            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                                                @if($value['inventory_management'] == 'serial')
                                                    <input type="hidden" class="quantityNew quantityNew_{{$value['inventory_checking_detail_id']}}" value="{{$value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_checking_detail_id']]) ? count($listSerial[$value['inventory_checking_detail_id']]) : 0) : $value['quantityNew'] }}">
                                                    <input min="0" disabled
                                                           style="text-align: center" type="text"
                                                           class="form-control ss--btn-ct quantityNewClass quantityNewClass_{{$value['inventory_checking_detail_id']}} ss--width-150"
                                                           value="{{ $value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_checking_detail_id']]) ? count($listSerial[$value['inventory_checking_detail_id']]) : 0) : $value['quantityNew'] }}">
                                                @else
                                                    <input onfocusout="changeQuantityNew(this)"
                                                           min="0"
                                                           style="text-align: center" type="text"
                                                           class="form-control ss--btn-ct quantityNew ss--width-150 quantityNew_{{$value['inventory_checking_detail_id']}}"
                                                           value="{{ $value['quantityNew'] }}">
                                                @endif
                                            </td>
                                            <td valign="top" style="width: 140px" class="ss--font-size-13 ss--text-center">
                                                <span class="quantityDifference quantityDifference_{{$value['inventory_checking_detail_id']}}">{{$value['quantityOld']- ($value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_checking_detail_id']]) ? count($listSerial[$value['inventory_checking_detail_id']]) : 0) : $value['quantityNew'])}}</span>
                                                <input style="text-align: center" readonly type="hidden"
                                                       class="form-control quantityDifference quantityDifference_{{$value['inventory_checking_detail_id']}}"
                                                       value="{{ $value['quantityOld']- ($value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_checking_detail_id']]) ? count($listSerial[$value['inventory_checking_detail_id']]) : 0) : $value['quantityNew'])}}">
                                            </td>
                                            <td style="width: 100px" class="ss--font-size-13 ss--text-center">
                                                @if($value['inventory_management'] != 'serial')
                                                    @if($value['quantityOld']-$value['quantityNew']>0)
                                                        <b class="m--font-danger resolve">
                                                            {{__('Xuất kho')}}
                                                        </b>
                                                    @elseif($value['typeResolve']=="input")
                                                        <b class="m--font-success resolve">
                                                            {{__('Nhập kho')}}
                                                        </b>
                                                    @else
                                                        <b class="m--font-success resolve"></b>
                                                    @endif
                                                    <input type="hidden" class="inventory_management" value="{{$value['inventory_management']}}">
                                                @else
                                                    @if($value['quantityOld'] != $value['quantityNew'] || $value['total_import'] != 0)
{{--                                                        @if($value['total_export'] != 0 || $value['quantityOld'] - $value['total_export'] > 0)--}}
                                                        @if($value['total_export'] != 0)
                                                            <a href="javascript:void(0)" onclick="InventoryChecking.showPopupSerialProduct(`{{$value['inventory_checking_detail_id']}}`,`{{ $value['productCode'] }}`,'export')" >
                                                                <b class="m--font-danger resolve">
{{--                                                                    {{__('Xuất kho')}} : {{$value['quantityOld'] - $value['total_export']}}--}}
                                                                    {{__('Xuất kho')}} : {{$value['total_export']}}<br>
                                                                </b>
                                                            </a>
                                                        @endif
                                                        @if($value['total_import'] != 0)
                                                            <a href="javascript:void(0)" onclick="InventoryChecking.showPopupSerialProduct(`{{$value['inventory_checking_detail_id']}}`,`{{ $value['productCode'] }}`,'import')" >
                                                                <b class="m--font-success resolve">
                                                                    {{__('Nhập kho')}} : {{$value['total_import']}}
                                                                </b>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <b class="m--font-success resolve"></b>
                                                    @endif
                                                @endif
                                                <input type="hidden" class="inventory_management" value="{{$value['inventory_management']}}">
                                                <input type="hidden" class="total_export" value="{{$value['total_export']}}">
                                                <input type="hidden" class="total_import" value="{{$value['total_import']}}">
                                            </td>
                                            <td class="ss--font-size-13">
                                                <input type="text" class="note form-control" onfocusout="saveProduct()" value="{{$value['note']}}">
                                            </td>
                                            <td style="width: 100px" class="ss--font-size-13 ss--text-center">
                                                <button onclick="deleteProductInList(this,`{{$value['inventory_checking_detail_id']}}`,`{{ $value['productCode'] }}`)"
                                                        class="change-class m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                        title="Xóa">
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @if($value['inventory_management'] == 'serial')
                                            <tr class="ss--font-size-13 ss--nowrap serialBlock block_tr_{{$value['inventory_checking_detail_id']}}">
                                                <td>
                                                    <select class="form-control m_selectpicker inventory_checking_status" id="select_product_{{$value['inventory_checking_detail_id']}}">
                                                        @foreach($listCheckingStatus as $itemStatus)
{{--                                                            <option value="{{$itemStatus['inventory_checking_status_id']}}">{{$itemStatus['name']}}</option>--}}
                                                            <option value="{{$itemStatus['name']}}">{{$itemStatus['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" style="width:250px" id="input_product_{{$value['inventory_checking_detail_id']}}" onkeydown="InventoryChecking.addSerialProduct(event,`{{$value['productCode']}}`,`{{$value['inventory_checking_detail_id']}}`)" placeholder="{{__('Nhập số serial và enter')}}">
                                                </td>
                                                <td colspan="7">
                                                    <h5 style="white-space: initial">
                                                        @if(isset($listSerial[$value['inventory_checking_detail_id']]) && count($listSerial[$value['inventory_checking_detail_id']]) != 0)
                                                            @foreach($listSerial[$value['inventory_checking_detail_id']] as $key => $itemSerial)
                                                                @if($key < 10)
                                                                    @if($itemSerial['is_new'] == 1)
                                                                        <span class="badge badge-pill badge-secondary mr-3 mb-3" style="background:#66C0B8">{{$itemSerial['is_default'] == 0 ? $itemSerial['inventory_checking_status_name'].' | ' : ''}}{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryChecking.removeSerial(`{{$itemSerial['inventory_checking_detail_serial_id']}}`,`{{$value['inventory_checking_detail_id']}}`,`{{ $value['productCode'] }}`,`{{$itemSerial['serial']}}`)"></i></span>
                                                                    @else
                                                                        <span class="badge badge-pill badge-secondary mr-3 mb-3">{{$itemSerial['is_default'] == 0 ? $itemSerial['inventory_checking_status_name'].' | ' : ''}}{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryChecking.removeSerial(`{{$itemSerial['inventory_checking_detail_serial_id']}}`,`{{$value['inventory_checking_detail_id']}}`,`{{ $value['productCode'] }}`,`{{$itemSerial['serial']}}`)"></i></span>
                                                                    @endif

                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </h5>
                                                </td>
                                                <td class="text-center">
                                                    @if(isset($listSerial[$value['inventory_checking_detail_id']]) && count($listSerial[$value['inventory_checking_detail_id']]) > 9)
                                                        <a href="javascript:void(0)" onclick="InventoryChecking.showPopupListSerial({{$value['inventory_checking_detail_id']}})">{{__('Xem thêm')}}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group row pull-right">
                                    <div class="col-12">
                                        <span class="errs error-product"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="row">
                <div class="col-lg-6"></div>

            </div>
        </div>
    </div>
    <input type="hidden" id="idHidden" value="{{$inventoryChecking->id}}">
    <input type="hidden" id="status_detail" value="{{$inventoryChecking->status}}">
    <div id="showPopup"></div>
@endsection
@section('after_script')
    <script type="text/template" id="product-childs">
        <tr class="ss--select2-mini d-none blockProductMain">
            <td class="stt ss--font-size-13 ss--text-center">{stt}</td>
            <td class="name-version ss--font-size-13">{name}
                <input type="hidden" class="productCode" value="{code}">
                <input class="cost" type="hidden"
                       value="{cost}">
            </td>
            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                <select class="form-control unit ss--width-150">
                    <option></option>
                    {option}
                </select>
            </td>
            <td valign="top" style="width: 140px" class="ss--text-center ss--font-size-13">
                {quantityOld}
                <input readonly style="text-align: center" type="hidden" class="form-control quantityOld"
                       value="{quantityOld}">
            </td>
            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                <input min="0" onchange="changeQuantityNew(this)"
                       style="text-align: center"
                       type="text" class="form-control quantityNew ss--btn-ct ss--width-150"
                       value="{quantityNew}">
            </td>
            <td valign="top" style="width: 140px" class="ss--font-size-13 ss--text-center">
                <span class="quantityDifference2">{quantityDifference}</span>
                <input style="text-align: center" readonly type="hidden"
                       class="form-control quantityDifference"
                       value="{quantityDifference}">
            </td>
            <td valign="top" style="width: 140px" class="ss--font-size-13 ss--text-center">
                <input type="hidden" class="note">
            </td>
            <td class="typeResolve ss--font-size-13 ss--text-center" style="width: 100px">
                <b class="resolve">
                </b>
            </td>
            <td style="width: 100px" class="ss--font-size-13 ss--text-center">
                <button onclick="deleteProductInList(this)"
                        class="change-class m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>
    <script type="text-template" id="tpl-data-error">
        <input type="hidden" name="export[{keyNumber}][product_code]" value="{product_code}">
        <input type="hidden" name="export[{keyNumber}][serial]" value="{serial}">
        <input type="hidden" name="export[{keyNumber}][quantity_old]" value="{quantity_old}">
        <input type="hidden" name="export[{keyNumber}][quantity_new]" value="{quantity_new}">
        <input type="hidden" name="export[{keyNumber}][status_name]" value="{status_name}">
        <input type="hidden" name="export[{keyNumber}][error_message]" value="{error_message}">
    </script>
    <script src="{{asset('static/backend/js/admin/inventory-checking/edit-script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        $('.inventory_checking_status').select2({
            tags: true
        });
    </script>
@endsection