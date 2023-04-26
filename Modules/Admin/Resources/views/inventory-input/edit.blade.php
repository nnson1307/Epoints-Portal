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
    <style>
        #popup-list-serial .modal-dialog {
            max-width: 80%;
        }
        .badge-secondary i:hover {
            cursor: pointer
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA PHIẾU NHẬP KHO')}}
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
                                               value="{{$inventoryInput->pi_code}}">
                                    </div>
                                    <span class="errs error-product-name"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>
                                    {{__('Loại')}}:
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
                                <input type="hidden" id="warehouse" value="{{$inventoryInput->warehouse_id}}">
{{--                                <select onchange="checkInput()"  class="form-control m_selectpicker"--}}
                                <select disabled class="form-control m_selectpicker"
                                        title="Chọn kho">
                                    @foreach($warehouse as $key=>$value)
                                        @if($inventoryInput->warehouse_id==$key)
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
                            {{__('Nhà cung cấp')}}:
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group">
                                <select onchange="checkInput()" id="supplier" class="form-control m_selectpicker"
                                        title="{{__('Chọn nhà cung cấp')}}">
                                    <option value="">{{__('Chọn nhà cung cấp')}}</option>
                                    @foreach($supplier as $key=>$value)
                                        @if($inventoryInput->supplier_id==$key)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
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
                                        <input style="text-align: right" id="created-by" type="text"
                                               value="{{$user->full_name}}" readonly
                                               class="form-control m-input class">
                                    </div>
                                    <span class="errs error-product-name"></span>
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-4">
                                <label>
                                    {{__('Ngày tạo')}}:
                                </label>
                                <div class="input-group">
                                    <input style="text-align: right" disabled="" id="created-at" type="text"
                                           value="{{(new DateTime($inventoryInput->created_at))->format('d/m/Y')}}"
                                           class="form-control m-input class" placeholder="{{__('Ngày tạo')}}"
                                           aria-describedby="basic-addon1">
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
                                    <label class="btn {{$inventoryInput->status=='new'?'ss--button-cms-piospa active':'btn-default'}} rdo">
                                        <input type="radio" name="options" value="new" id="option1" autocomplete="off"
                                               checked="">
                                        {{__('Mới')}}
                                    </label>
                                    <label class="btn {{$inventoryInput->status=='inprogress'?'ss--button-cms-piospa active':'btn-default'}} rdo">
                                        <input type="radio" name="options" value="inprogress" id="option2"
                                               autocomplete="off"> {{__('Đang xử lý')}}
                                    </label>
                                    <label class="btn btn-default rdo">
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
                                <textarea placeholder="{{__('Nhập ghi chú')}}" rows="6" cols="40" name="description"
                                          id="note" class="form-control">{{$inventoryInput->note}}</textarea>
                        </div>
                    </div>
                    <span class="description"></span>
                </div>
                <div class="col-lg-12 mt-3">
                    <div class="form-group m-form__group">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                            <div class="m-form__actions m--align-right">
                                <button onclick="location.href='{{route('admin.product-inventory')}}'"
                                        data-dismiss="modal"
                                        class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                                    <span class="ss--text-btn-mobi">
                                                    <i class="la la-arrow-left"></i>
                                                    <span>{{__('HỦY')}}</span>
                                                    </span>
                                </button>
                                <button type="button"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md btn-save m--margin-left-10 m--margin-bottom-5">
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
                    <div class="ss--border">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="inventory" role="tabpanel">
                                <div class="form-group m-form__group">
                                    <label class="label-son">
                                        {{__('Danh sách sản phẩm')}}:
                                    </label>
                                    <div class="col-xl-12 order-2 order-xl-1">
                                        <div class="form-group m-form__group row align-items-center">
                                            <div class="input-group w-50">
                                                <select style="width: 100%" class="form-control col-lg"
                                                        name="list-product"
                                                        id="list-product">
                                                    <option value="">{{__('Chọn sản phẩm')}}</option>
                                                    @foreach($product as $key=>$value)
                                                        <option value="{{$value['product_child_id']}}">{{$value['product_code'].' - '.$value['product_child_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <a href="javascript:void(0)"
                                               {{--                       onclick="location.href='{{route('admin.inventory-input.add')}}'"--}}
                                               onclick="InventoryInput.showPopup()"
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
                        {{--Table version--}}
                        <div class="block-list-product-main">
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
{{--                                                <select class="form-control  ss--select-list ss--width-150" disabled>--}}
{{--                                                    @foreach($unit as $k=>$v)--}}
{{--                                                        @if($value['unitId']==$k)--}}
{{--                                                            <option selected value="{{$k}}">{{$v}}</option>--}}
{{--                                                        @else--}}
{{--                                                            <option value="{{$k}}">{{$v}}</option>--}}
{{--                                                        @endif--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
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
                                                {{--<input style="text-align: center" name="number-product"--}}
                                                {{--data-bts-button-down-class="btn btn-block"--}}
                                                {{--onchange="clickNumberProduct(this)"--}}
                                                {{--data-bts-button-up-class="btn btn-block"--}}
                                                {{--value="{{$value['quantity']}}"--}}
                                                {{--class="form-control number-product change-class"--}}
                                                {{--type="text" onkeydown="onKeyDownInput(this)">--}}

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
                                                <td colspan="7" >
                                                    @if(isset($listSerial[$value['inventory_input_detail_id']]) && count($listSerial[$value['inventory_input_detail_id']]) != 0)
                                                        <h5 style="white-space: initial">
                                                            @foreach($listSerial[$value['inventory_input_detail_id']] as $key => $itemSerial)
                                                                @if($key <= 9)
                                                                    <span class="badge badge-pill badge-secondary mr-3 mb-3" >{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryInput.removeSerial(`{{$itemSerial['inventory_input_detail_serial_id']}}`,`{{$value['inventory_input_detail_id']}}`)"></i></span>
                                                                @endif
                                                            @endforeach
                                                        </h5>
                                                    @endif
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
    <input type="hidden" id="idInventoryInput" value="{{$inventoryInput->inventory_input_id}}">
    <div id="showPopup"></div>
@endsection
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script>
        $(document).ready(function () {
        @foreach($inventoryInputDetail as $key=> $value)
        new AutoNumeric.multiple('#id-child-{{$value['code']}}' ,{
                currencySymbol : '',
                decimalCharacter : '.',
                digitGroupSeparator : ',',
                decimalPlaces: decimal_number,
                minimumValue: 0
            });
        @endforeach
        })
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script type="text/template" id="product-childs">
        <tr class="ss--select2-mini d-none blockProductMain">
            <td class="stt ss--font-size-13">{stt}</td>
            <td class="name-version name-version ss--font-size-13">{name}
                <input name="hiddencode[]" type="hidden" value="{code}"></td>
            <td valign="top" class="ss--font-size-13 ss--text-center ss--nowrap">{price}
                <input style="text-align: right" readonly class="form-control ss--display-none" type="text"
                       value="{price}">
            </td>
            <td class="ss--font-size-13 ss--text-center" style="width: 160px">
                <select class="form-control unit ss--select-list unit-{stt}" onchange="saveProduct()">
                    {option}
                </select></td>
            <td class="ss--font-size-13 ss--text-center" style="width: 150px">
                <input id="id-child-{code}" onkeypress="maskNumberPriceProductChild()" onchange="changeCost(this)" name="cost-product-child"
                       data-thousands="."
                       class="price ss--text-center form-control2 m-input change-class"
                       value="{cost}">
            </td>
            <td class="ss--font-size-13 ss--text-center" style="width: 150px">
                <div class="input-group bootstrap-touchspin ss--touchspin">
                    <span class="input-group-btn">
                        <button onclick="InventoryInput.tru(this)"
                                class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct"
                                type="button">-</button>
                    </span>
                    <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span>
                    <input onchange="clickNumberProduct(this)"
                           type="text"
                           class="form-control ss--btn-ct number-product change-class ss--text-center"
                           value="{number}" name="number-product">
                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;">
                                                </span>
                    <span class="input-group-btn">
                        <button onclick="InventoryInput.cong(this)"
                                class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct"
                                type="button">+</button>
                    </span>
                </div>
            </td>
            <td valign="top" class="ss--font-size-13 ss--text-center" style="width: 150px">
                <span class="total-money-product2">{total}</span>
                <input style="text-align: right" name="totalMoneyProduct[]" readonly
                       class="form-control total-money-product ss--display-none" type="text"
                       value="{total}">
            </td>
            <td style="width: 50px">
                <button onclick="deleteProductInList(this)"
                        class="change-class m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>
    <script type="text/template" id="product-childs-serial">
        <tr class="ss--select2-mini d-none blockProductMain">
            <td class="stt ss--font-size-13">{stt}</td>
            <td class="name-version name-version ss--font-size-13">{name}
                <input name="hiddencode[]" type="hidden" value="{code}"></td>
            <td valign="top" class="ss--font-size-13 ss--text-center ss--nowrap">{price}
                <input style="text-align: right" readonly class="form-control ss--display-none" type="text"
                       value="{price}">
            </td>
            <td class="ss--font-size-13 ss--text-center" style="width: 160px">
                <select class="form-control unit ss--select-list unit-{stt}" onchange="saveProduct()">
                    {option}
                </select></td>
            <td class="ss--font-size-13 ss--text-center" style="width: 150px">
                <input id="id-child-{code}" onkeypress="maskNumberPriceProductChild()" onchange="changeCost(this)" name="cost-product-child"
                       data-thousands="."
                       class="price ss--text-center form-control2 m-input change-class"
                       value="{cost}">
            </td>
            <td class="ss--font-size-13 ss--text-center" style="width: 150px">
                <div class="input-group bootstrap-touchspin ss--touchspin">
                    <span class="input-group-btn">
                        <button onclick="InventoryInput.tru(this)"
                                class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct"
                                type="button">-</button>
                    </span>
                    <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span>
                    <input onchange="clickNumberProduct(this)"
                           type="text"
                           class="form-control ss--btn-ct number-product change-class ss--text-center"
                           value="{number}" name="number-product">
                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;">
                                                </span>
                    <span class="input-group-btn">
                        <button onclick="InventoryInput.cong(this)"
                                class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct"
                                type="button">+</button>
                    </span>
                </div>
            </td>
            <td valign="top" class="ss--font-size-13 ss--text-center" style="width: 150px">
                <span class="total-money-product2">{total}</span>
                <input style="text-align: right" name="totalMoneyProduct[]" readonly
                       class="form-control total-money-product ss--display-none" type="text"
                       value="{total}">
            </td>
            <td style="width: 50px">
                <button onclick="deleteProductInList(this)"
                        class="change-class m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
        <tr class="ss--font-size-13 ss--nowrap d-none serialBlock block_tr_{inventory_input_detail_id}">
            <td><input type="text" class="form-control" id="input_product_{inventory_input_detail_id}" onkeydown="InventoryInput.addSerialProduct(event,{code},{inventory_input_detail_id})" placeholder="{{__('Nhập số serial và enter')}}"></td>
            <td colspan="6">
                <h5 style="white-space: initial"></h5>
            </td>
            <td class="text-center"></td>
        </tr>
    </script>
    <script src="{{asset('static/backend/js/admin/inventory-input/edit-script.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/inventory-input/jquery.bootstrap-touchspin.js')}}"
            type="text/javascript"></script>
    <!-- <script src="{{asset('static/backend/js/admin/product/jquery.masknumber.js')}}"
            type="text/javascript"></script> -->

    <script type="text-template" id="tpl-data-error">
        <input type="hidden" name="export[{keyNumber}][product_code]" value="{product_code}">
        <input type="hidden" name="export[{keyNumber}][quantity]" value="{quantity}">
        <input type="hidden" name="export[{keyNumber}][price]" value="{price}">
        <input type="hidden" name="export[{keyNumber}][barcode]" value="{barcode}">
        <input type="hidden" name="export[{keyNumber}][serial]" value="{serial}">
        <input type="hidden" name="export[{keyNumber}][error_message]" value="{error_message}">
    </script>
@endsection
