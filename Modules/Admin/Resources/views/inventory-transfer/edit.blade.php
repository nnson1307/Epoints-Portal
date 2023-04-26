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
@endsection
@section('content')
    <div class="m-portlet ">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                   <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA PHIẾU CHUYỂN KHO')}}
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
                                <input id="code-inventory" readonly type="text" class="form-control m-input class"
                                       value="{{$inventoryTransfer->transferCode}}">
                            </div>
                            <span class="errs error-product-name"></span>
                        </div>
                        <span class="errs error-warehouse"></span>
                    </div>
                    <div class="form-group m-form__group">  
                        <label>
                            {{__('Kho xuất')}}: <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group m-input-group--solid">
                                <select id="warehouse-output" class="form-control col-lg">
                                    <option value="">Chọn kho xuất</option>
                                    @foreach($wareHouse as $key=>$value)
                                        @if($key==$inventoryTransfer->warehouseFrom)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <span class="errs error-warehouse-out"></span>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Kho nhập')}}: <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group m-input-group--solid">
                                <select id="warehouse-input" class="form-control col-lg">
                                    <option value="">Chọn kho nhập</option>
                                    @foreach($wareHouse as $key=>$value)

                                        @if($key==$inventoryTransfer->warehouseTo)
                                            <option selected value="{{$key}}">{{$value}}</option>
                                        @else
                                            @if($key==$inventoryTransfer->warehouseFrom)
                                            @else
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <span class="errs error-warehouse-input"></span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group" style="display: none">
                        <label>
                            {{__('Người tạo')}}:
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group m-input-group--solid">
                                <input id="creeated-by" readonly type="text" class="form-control m-input class"
                                       value="{{$inventoryTransfer->user}}">
                            </div>
                            <span class="errs error-product-name"></span>
                        </div>
                        <span class="errs error-warehouse"></span>
                    </div>
                    <div class="form-group m-form__group" style="display: none">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>
                                    {{__('Ngày xuất')}}:
                                </label>
                                <div class="input-group">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <div class="input-group-append">
                                            <input disabled id="day-output" type="text"
                                                   class="form-control m-input class" placeholder="{{__('Ngày tạo')}}"
                                                   aria-describedby="basic-addon1"
                                                   value="">
                                            <span class="input-group-text">
                                                <i class="la la-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <span class="errs error-day-output"></span>
                            </div>
                            <div class="col-lg-6">
                                <label>
                                    {{__('Ngày nhập')}}:
                                </label>
                                <div class="input-group">
                                    <div class="input-group m-input-group m-input-group--solid">
                                        <div class="input-group-append">
                                            <input disabled id="day-input" type="text"
                                                   class="form-control m-input class" placeholder="{{__('Ngày tạo')}}"
                                                   aria-describedby="basic-addon1"
                                                   value="">
                                            <span class="input-group-text">
                                                <i class="la la-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <span class="errs error-day-input"></span>
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
                                    <label class="btn {{$inventoryTransfer->status=='new'?'ss--button-cms-piospa active':'btn-default'}} rdo">
                                        <input type="radio" name="options" value="new" id="option1" autocomplete="off"
                                               checked="">
                                        {{__('Mới')}}
                                    </label>
                                    <label class="btn {{$inventoryTransfer->status=='inprogress'?'ss--button-cms-piospa active':'btn-default'}} rdo">
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
                                <textarea placeholder="{{__('Nhập ghi chú')}}" rows="6" cols="50" name="description"
                                          id="note" class="form-control">{{$inventoryTransfer->note}}</textarea>
                        </div>
                    </div>
                    <span class="note"></span>
                </div>
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" style="margin-bottom: 0;" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show son" data-toggle="tab" href="#" data-target="#inventory">
                                <h7>{{__('THỦ CÔNG')}}</h7>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link son" data-toggle="tab" href="#inventory-input">
                                <h7>{{__('BARCODE')}}</h7>
                            </a>
                        </li>
                    </ul>
                    <div class="bd-ct">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="inventory" role="tabpanel">
                                <div class="form-group m-form__group">
                                    <label class="label-son">
                                        {{__('Danh sách sản phẩm')}}:
                                    </label>
                                    <div class="col-xl-6 order-2 order-xl-1">
                                        <div class="form-group m-form__group row align-items-center">
                                            <div class="input-group">
                                                <select style="width: 100%" class="form-control ss--width-100-"
                                                        name="list-product"
                                                        id="list-product">
                                                    <option value="">{{__('Chọn sản phẩm')}}</option>
                                                    @foreach($productByWarehouse as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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
                        <div class="table-responsive">
                            <table id="table-product"
                                   class="table table-striped m-table ss--header-table">
                                <thead>
                                <tr class="ss--nowrap">
                                    <th class="ss--font-size-th ss--text-center">#</th>
                                    <th class="ss--font-size-th">{{__('SẢN PHẨM')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
                                    <th></th>
                                    <th class="ss--display-none">{{__('Số lượng tồn')}}</th>
                                    <th class="ss--font-size-th ss--text-center ss--width-150">{{__('TỒN KHO')}}</th>
                                    <th class="ss--font-size-th ss--text-center ss--width-150">{{__('SỐ LƯỢNG')}}</th>
                                    <th class="ss--font-size-th ss--text-center"></th>
                                    <th class="ss--font-size-th ss--text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($product))
                                    @foreach($product as $key=>$value)
                                        <tr class="ss--select2-mini">
                                            <td class="stt ss--width-20 ss--font-size-13 ss--text-center">{{($key+1)}}</td>
                                            <td class="name-version ss--font-size-13">
                                                {{ $value['productName'] }}
                                                <input name="hiddencode[]" type="hidden"
                                                       value="{{ $value['productCode'] }}">
                                            </td>
                                            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                                                <select class="form-control unit ss--width-150">
                                                    @foreach($unit as $k=>$v)
                                                        @if($k==$value['unitId'])
                                                            <option selected value="{{$k}}">{{$v}}</option>
                                                        @else
                                                            <option value="{{$k}}">{{$v}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td></td>
                                            <td>
                                                <div class="input-group m-input-group m-input-group--air">
                                                    <input type="text" readonly
                                                           class="form-control productInventory ss--btn-ct ss--text-center"
                                                           value="{{$value['productInventory']}}">
                                                </div>
                                            </td>
                                            <td class="ss--font-size-13 ss--text-center ss--width-150">
                                                {{--<input onchange="changeOutputQuantity(this)"--}}
                                                {{--data-bts-button-up-class="btn btn-block"--}}
                                                {{--data-bts-button-down-class="btn btn-block"--}}
                                                {{--style="text-align: center"--}}
                                                {{--class="outputQuantity form-control"--}}
                                                {{--value="{{$value['transferQuantity']}}">--}}


                                                <div class="input-group bootstrap-touchspin ss--touchspin">
                                                    <span class="input-group-btn">
                                                        <button onclick="InventoryTransfer.tru(this)"
                                                                class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct"
                                                                type="button">-</button>
                                                    </span>
                                                    <span class="input-group-addon bootstrap-touchspin-prefix"
                                                          style="display: none;">
                                                    </span>
                                                    <input onchange="changeOutputQuantity(this)"
                                                           id="m_touchspin_1" type="text"
                                                           class="form-control ss--btn-ct outputQuantity ss--text-center"
                                                           value="{{$value['transferQuantity']}}" name="number-product">
                                                    <span class="input-group-addon bootstrap-touchspin-postfix"
                                                          style="display: none;">
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <button onclick="InventoryTransfer.cong(this)"
                                                                class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct"
                                                                type="button">+</button>
                                                    </span>
                                                </div>


                                                <span class="errs error-output-quantity span-err text-danger"></span>
                                                @if($value['transferQuantity'] > $value['productInventory'])
                                                    <span class="span-err" style="color: red;">
                                                            @if($value['productInventory'] > 0)
                                                            Vượt quá số lượng
                                                            @endif
                                                    </span>
                                                @endif
                                            </td>
                                            <td></td>
                                            <td class="ss--text-center ss--width-50">
                                                <button onclick="deleteProductInList(this)"
                                                        class="change-class m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                        title="{{__('Xóa')}}">
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        {{--<div class="row">--}}
                        {{--<div class="col-lg-6">--}}
                        {{--<div class="form-group m-form__group row">--}}
                        {{--<label for="example-text-input" class="col-4 col-form-label">--}}
                        {{--<b>Tổng số sản phẩm:</b>--}}
                        {{--</label>--}}
                        {{--<div class="col-4">--}}
                        {{--<div class="input-group m-input-group m-input-group--solid">--}}
                        {{--<input style="text-align: center" readonly id="total-product"--}}
                        {{--class="form-control m-input" type="text"--}}
                        {{--value="">--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-lg-6">--}}
                        {{--</div>--}}
                        {{--</div>--}}
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
            <div class="form-group m-form__group">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button onclick="location.href='{{route('admin.product-inventory')}}'"
                                data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                                    <span class="ss--text-btn-mobi">
                                                    <i class="la la-arrow-left"></i>
                                                    <span>{{__('HỦY')}}</span>
                                                    </span>
                        </button>
                        <button type="button"
                                class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md btn-save m--margin-left-10 m--margin-bottom-5">
                                                        <span class="ss--text-btn-mobi">
                                                        <i class="la la-check"></i>
                                                        <span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
                                                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="idHidden" value="{{$id}}">
@endsection
@section('after_script')
    <script type="text/template" id="product-childs">
        <tr class="ss--select2-mini">
            <td class="stt ss--width-20 ss--font-size-13 ss--text-center">{stt}</td>
            <td class="name-version ss--font-size-13">{name}
                <input name="hiddencode[]" type="hidden"
                       value="{code}">
            </td>
            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                <select class="form-control unit ss--width-150 unit-{stt}">
                    {option}
                </select>
            </td>
            <td></td>
            <td style="width: 200px">
                <div class="input-group m-input-group m-input-group--air">
                    <input type="text" readonly
                           class="form-control productInventory ss--btn-ct ss--text-center"
                           value="{productInventory}">
                </div>
            </td>
            <td class="ss--font-size-13 ss--text-center ss--width-150">
                {{--<input onchange="changeOutputQuantity(this)"--}}
                {{--data-bts-button-up-class="btn btn-secondary"--}}
                {{--data-bts-button-down-class="btn btn-block" style="text-align: center"--}}
                {{--class="outputQuantity form-control"--}}
                {{--value="{outputQuantity}">--}}
                <div class="input-group bootstrap-touchspin ss--touchspin">
                                                    <span class="input-group-btn">
                                                        <button onclick="InventoryTransfer.tru(this)"
                                                                class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct"
                                                                type="button">-</button>
                                                    </span>
                    <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;">
                                                    </span>
                    <input onchange="changeOutputQuantity(this)"
                           id="m_touchspin_1" type="text"
                           class="form-control ss--btn-ct outputQuantity ss--text-center"
                           value="{outputQuantity}" name="number-product">
                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;">
                                                    </span>
                    <span class="input-group-btn">
                                                        <button onclick="InventoryTransfer.cong(this)"
                                                                class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct"
                                                                type="button">+</button>
                                                    </span>
                </div>
                <span class="errs error-output-quantity span-err text-danger"></span>
            </td>
            <td></td>
            <td class="ss--text-center ss--width-50">
                <button onclick="deleteProductInList(this)"
                        class="change-class m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>
    <script src="{{asset('static/backend/js/admin/inventory-transfer/edit-script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/inventory-transfer/jquery.bootstrap-touchspin.js?v='.time())}}"
            type="text/javascript"></script>
@endsection