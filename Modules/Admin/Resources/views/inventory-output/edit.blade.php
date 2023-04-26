@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/son.css')}}">--}}
@endsection
@section('before_style')
    <style>
        .errs {
            color: red;
        }

    </style>
@endsection
@section('content')
    <style>
        #popup-list-serial .modal-dialog {
            max-width: 80%;
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
                        {{__('CHỈNH SỬA PHIẾU XUẤT KHO')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group" style="display: none">
                        <div class="row">
                            <div class="col-lg-8">
                                <label>
                                    {{__('Mã phiếu')}}: <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <input readonly id="code-inventory" type="text"
                                               class="form-control m-input class"
                                               value="{{$inventoryOutput->po_code}}">
                                    </div>
                                    <span class="errs error-product-name"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>
                                    Loại: <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <select disabled id="type" class="form-control col-lg">
                                            <option value="normal">{{__('Thường')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <span class="errs error-product-code"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Kho')}}: <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group">
                                <input type="hidden" id="warehouse" value="{{$inventoryOutput->warehouse_id}}">
                                <select style="width: 100%" disabled
                                        class="form-control m_selectpicker"
                                        title="{{__('Chọn kho')}}">
                                    @foreach($warehouse as $key=>$value)
                                        @if($inventoryOutput->warehouse_id==$key)
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
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Người nhận')}}:
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group">
                                <input class="form-control" type="text">
                            </div>
                        </div>
                        <span class="errs error-supplier"></span>
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
                                        <input id="created-by" type="text" value="{{$user->full_name}}" readonly
                                               class="form-control m-input class">
                                    </div>
                                    <span class="errs error-product-name"></span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label>
                                    {{__('Ngày xuất')}}:
                                </label>
                                <div class="input-group">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <div class="input-group-append">
                                            <input id="created-at" type="text"
                                                   value="{{(new DateTime($inventoryOutput->created_at))->format('m/d/Y H:i:s')}}"
                                                   class="form-control m-input class" placeholder="{{__('Ngày tạo')}}"
                                                   aria-describedby="basic-addon1">
                                            <span class="input-group-text">
                                                <i class="la la-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <span class="errs error-created-at"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Trạng thái')}}:
                        </label>
                        <div class="input-group">
                            <div class="ss--m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn {{$inventoryOutput->status=='new'?'ss--button-cms-piospa active':'btn-default'}} rdo">
                                        <input type="radio" name="options" value="new" id="option1" autocomplete="off"
                                               checked="">
                                        {{__('Mới')}}
                                    </label>
                                    <label class="btn {{$inventoryOutput->status=='inprogress'?'ss--button-cms-piospa active':'btn-default'}} rdo">
                                        <input type="radio" name="options" value="inprogress" id="option2"
                                               autocomplete="off"> {{__('Đang xử lý')}}
                                    </label>
                                    <label class="btn {{$inventoryOutput->status=='success'?'ss--button-cms-piospa active':'btn-default'}} rdo">
                                        <input type="radio" name="options" value="success" id="option3"
                                               autocomplete="off"> {{__('Hoàn thành')}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Ghi chú')}}:
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group">
                                <textarea placeholder="{{__('Nhập ghi chú')}}" rows="4" cols="50" name="description"
                                          id="note" class="form-control">{{$inventoryOutput->note}}</textarea>
                            </div>
                        </div>
                    </div>
                    <span class="description"></span>
                </div>
                <div class="col-lg-12 mt-4">
                    <div class="form-group m-form__group">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                            <div class="m-form__actions m--align-right">
                                <button onclick="location.href='{{route('admin.product-inventory')}}'"
                                        data-dismiss="modal"
                                        class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                                </button>
                                <button type="button"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md btn-save m--margin-bottom-5  m--margin-left-10">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
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
                                            <div class="input-group w-25">
                                                <select style="width: 100%" class="form-control col-lg"
                                                        name="list-product"
                                                        id="list-product">
                                                    <option value="">{{__('Chọn sản phẩm')}}</option>
                                                    @foreach($productByWarehouse as $key=>$value)
                                                        <option value="{{$value['product_child_id']}}">{{$value['product_code'].' - '.$value['product_child_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group w-25 ml-3">
                                                <select style="width: 100%" class="form-control col-lg list-product-serial-{{$value['product_child_id']}}"
                                                        name="list-product-serial"
                                                        id="list-product-serial">
                                                    <option value="">{{__('Chọn sản phẩm')}}</option>
                                                    @foreach($getListProductSerial as $key=>$value)
                                                        <option value="{{$value['product_child_id']}}" >{{$value['serial'].' - '.$value['product_child_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <a href="javascript:void(0)"
                                               {{--                       onclick="location.href='{{route('admin.inventory-input.add')}}'"--}}
                                               onclick="InventoryOutput.showPopup()"
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
{{--                                            <select class="form-control  ss--width-150" disabled>--}}
{{--                                                @foreach($unit as $k=>$v)--}}
{{--                                                    @if($value['unitId']==$k)--}}
{{--                                                        <option selected value="{{$k}}">{{$v}}</option>--}}
{{--                                                    @else--}}
{{--                                                        <option value="{{$k}}">{{$v}}</option>--}}
{{--                                                    @endif--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
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
{{--                                                       value="{{$value['outputQuantity']}}" name="number-product">--}}
{{--                                                       value="{{$value['inventory_management'] == 'serial' ? (isset($listSerial[$value['inventory_output_detail_id']]) ? count($listSerial[$value['inventory_output_detail_id']]) : 0) : $value['quantity']}}" name="number-product">--}}
                                                       value="{{isset($value['outputQuantity']) ? $value['outputQuantity'] : ($value['inventory_management'] == 'serial' ? 0 : 1)}}" name="number-product">
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
{{--                                                {{number_format($value['cost']*$value['outputQuantity'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}--}}
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
                                <div class="col-lg-4">
                                    <div class="form-group m-form__group row">
                                        <label for="example-text-input" class="col-lg-12 col-form-label">
                                            <span>{{__('Tổng sản phẩm')}}: </span>
                                            <b id="" class="ss--text-color">{{count($product)}}</b>
                                            <b class="ss--text-color">{{__('sản phẩm')}}</b>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <div class="form-group m-form__group row">
                                        <label for="example-text-input" class="col-lg-12 col-form-label">
                                            <span>{{__('Tổng số lượng')}}: </span>
                                            <b id="total-product-text" class="ss--text-color">0</b>
                                            <b class="ss--text-color">{{__('sản phẩm')}}</b>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-7"></div>
                                        <label for="example-text-input" class="col-lg-5 col-form-label">
                                            {{__('Tổng tiền')}}: <b class="total-money text-danger">0</b> <b
                                                    class="text-danger">{{__('VNĐ')}}</b>
                                        </label>
                                    </div>
                                </div>
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

    </div>
    <input type="hidden" id="idHidden" value="{{$inventoryOutput->inventory_output_id}}">
    <div id="showPopup"></div>
@endsection
@section('after_script')

    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
{{--    <script src="{{asset('static/backend/js/admin/product/jquery.masknumber.js?v='.time())}}"--}}
{{--            type="text/javascript"></script>--}}
    <script type="text/template" id="product-childs">
        <tr class="ss--select2-mini d-none blockProductMain">
            <td class="stt ss--font-size-13 ss--text-center">{stt}</td>
            <td class="stt ss--font-size-13 ss--text-center">{code}</td>
            <td class="name-version ss--font-size-13 ss--max-width-200 ss--text-center">{name}
                <input name="hiddencode[]" type="hidden" value="{code}">
            </td>
            <td valign="top" style="width: 150px" class="ss--font-size-13 ss--text-center">
                {price}
            </td>
            <td style="width: 150px" class="ss--text-center">
                <select class="form-control unit ss--width-150 unit-{stt}">
                    {option}
                </select>
            </td>
            <td class="ss--text-center ss--font-size-13">
                {cost}
                <input name="cost-product-child"
                       style="text-align: center;display: none"
                       data-thousands=","
                       class="cost-product-child form-control2 m-input change-class ss--display-none"
                       value="{cost}">
            </td>
            <td valign="top" class="ss--text-center ss--font-size-13">
                <input readonly style="text-align: center;font-size: 0.8rem;height: 15px"
                       class="form-control product-inventory"
                       value="{productInventory}">
            </td>
            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                <div class="input-group bootstrap-touchspin ss--touchspin">
                    <span class="input-group-btn">
                        <button onclick="InventoryOutput.tru(this)"
                                class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct"
                                type="button">-</button>
                    </span>
                    <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;">
                                                </span>
                    <input onchange="changeOutputQuantity(this)"
                           onkeydown="onKeyDownInput(this)"
                           min="0" id="m_touchspin_1" type="text"
                           class="form-control ss--btn-ct outputQuantity number-product change-class ss--text-center"
                           value="{outputQuantity}" name="number-product">
                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;">
                                                </span>
                    <span class="input-group-btn">
                                                    <button onclick="InventoryOutput.cong(this)"
                                                            class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct"
                                                            type="button">+</button>
                                                </span>
                </div>
                <span class="errs error-output-quantity"></span>
            </td>
            <td valign="top" class="ss--text-center">
                <span class="total-money-product">
                    {totalMoney}
                </span>
            </td>
            <td style="width: 50px">
                <button onclick="deleteProductInList(this)"
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>
    <script type="text/template" id="product-childs-serial">
        <tr class="ss--select2-mini d-none blockProductMain">
            <td class="stt ss--font-size-13 ss--text-center">{stt}</td>
            <td class="stt ss--font-size-13 ss--text-center">{code}</td>
            <td class="name-version ss--font-size-13 ss--max-width-200 ss--text-center">{name}
                <input name="hiddencode[]" type="hidden" value="{code}">
            </td>
            <td valign="top" style="width: 150px" class="ss--font-size-13 ss--text-center">
                {price}
            </td>
            <td style="width: 150px" class="ss--text-center">
                <select class="form-control unit ss--width-150 unit-{stt}">
                    {option}
                </select>
            </td>
            <td class="ss--text-center ss--font-size-13">
                {cost}
                <input name="cost-product-child"
                       style="text-align: center;display: none"
                       data-thousands=","
                       class="cost-product-child form-control2 m-input change-class ss--display-none"
                       value="{cost}">
            </td>
            <td valign="top" class="ss--text-center ss--font-size-13">
                <input readonly style="text-align: center;font-size: 0.8rem;height: 15px"
                       class="form-control product-inventory"
                       value="{productInventory}">
            </td>
            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                <div class="input-group bootstrap-touchspin ss--touchspin">
                    <span class="input-group-btn">
                        <button onclick="InventoryOutput.tru(this)"
                                class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct"
                                type="button">-</button>
                    </span>
                    <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;">
                                                </span>
                    <input onchange="changeOutputQuantity(this)"
                           onkeydown="onKeyDownInput(this)"
                           min="0" id="m_touchspin_1" type="text"
                           class="form-control ss--btn-ct outputQuantity number-product change-class ss--text-center"
                           value="{outputQuantity}" name="number-product">
                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;">
                                            </span>
                    <span class="input-group-btn">
                        <button onclick="InventoryOutput.cong(this)"
                                class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct"
                                type="button">+</button>
                    </span>
                </div>
                <span class="errs error-output-quantity"></span>
            </td>
            <td valign="top" class="ss--text-center">
                <span class="total-money-product">
                    {totalMoney}
                </span>
            </td>
            <td style="width: 50px">
                <button onclick="deleteProductInList(this)"
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>
    <script type="text-template" id="tpl-data-error">
        <input type="hidden" name="export[{keyNumber}][product_code]" value="{product_code}">
        <input type="hidden" name="export[{keyNumber}][quantity]" value="{quantity}">
        <input type="hidden" name="export[{keyNumber}][price]" value="{price}">
        <input type="hidden" name="export[{keyNumber}][barcode]" value="{barcode}">
        <input type="hidden" name="export[{keyNumber}][serial]" value="{serial}">
        <input type="hidden" name="export[{keyNumber}][error_message]" value="{error_message}">
    </script>

    <script src="{{asset('static/backend/js/admin/inventory-output/edit-script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/inventory-input/jquery.bootstrap-touchspin.js?v='.time())}}"
            type="text/javascript"></script>
@endsection