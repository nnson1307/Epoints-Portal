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
                        <i class="fa fa-plus-circle"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('TẠO PHIỂU CHUYỂN KHO')}}
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
                                       value="{{"CK".$code}}">
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
                            <div class="input-group m-input-group">
                                <select id="warehouse-output" class="form-control m_selectpicker">
                                    <option value="">{{__('Chọn kho xuất')}}</option>
                                    @foreach($wareHouse as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
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
                            <div class="input-group m-input-group">
                                <select id="warehouse-input" class="form-control m_selectpicker">
                                    <option value="">{{__('Chọn kho nhập')}}</option>
                                    @foreach($wareHouse as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--<span class="errs error-warehouse-input"></span>--}}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Trạng thái')}}:
                        </label>
                        <div class="input-group">
                            <div class="ss--m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn ss--button-cms-piospa color_button active rdo">
                                        <input type="radio" name="options" value="new" id="option1" autocomplete="off"
                                               checked="">
                                        {{__('Mới')}}
                                    </label>
                                    <label class="btn btn-default rdo">
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
                    <div class="form-group m-form__group" style="display: none">
                        <label>
                            {{__('Người tạo')}}:
                        </label>
                        <div class="input-group">
                            <div class="input-group m-input-group m-input-group--solid">
                                <input id="creeated-by" readonly type="text" class="form-control m-input class"
                                       value="{{$user->full_name}}">
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
                                                   aria-describedby="basic-addon1">
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
                                                   aria-describedby="basic-addon1">
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
                            {{__('Ghi chú')}}:
                        </label>
                        <div class="input-group">
                                <textarea placeholder="{{__('Nhập ghi chú')}}" rows="3" cols="40" name="description"
                                          id="note" class="form-control"></textarea>
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
                                        {{__('Danh sách sản phẩm')}}
                                    </label>
                                    <div class="col-xl-6 order-2 order-xl-1">
                                        <div class="form-group m-form__group row align-items-center">
                                            <div class="input-group col-xs-10">
                                                <select style="width: 100%" class="form-control select22 ss--width-100-"
                                                        name="list-product"
                                                        id="list-product">
                                                    <option value="">{{__('Chọn sản phẩm')}}</option>
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
                        <div class="table-responsive">
                            <table id="table-product"
                                   class="table table-striped m-table ss--header-table">
                                <thead class="bg">
                                <tr class="ss--nowrap">
                                    <th class="ss--font-size-th ss--text-center">#</th>
                                    <th class="ss--font-size-th">{{__('SẢN PHẨM')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
                                    <th></th>
                                    <th class="ss--font-size-th ss--text-center ss--width-150">{{__('TỒN KHO')}}</th>
                                    <th class="ss--font-size-th ss--text-center ss--width-150">{{__('SỐ LƯỢNG')}}</th>
                                    <th class="ss--font-size-th ss--text-center"></th>
                                    <th class="ss--font-size-th ss--text-center"></th>
                                </tr>
                                </thead>
                                <tbody class="tr_thead_od">
                                {{--<tr>--}}
                                {{--<td class="td_vtc">1</td>--}}
                                {{--<td class="td_vtc">--}}
                                {{--Kem tẩy tế bào chết--}}

                                {{--</td>--}}
                                {{--<td class="td_vtc">Chai</td>--}}
                                {{--<td class="td_vtc" style="text-align:center ">--}}
                                {{--<input style="text-align: center;" type="text" name="quantity" class="quantity form-control btn-ct">--}}
                                {{--<input type="hidden" name="quantity_hid" value="0">--}}
                                {{--</td>--}}
                                {{--<td class="td_vtc">--}}
                                {{--<a class="remove" href="javascript:void(0)" style="color: red">--}}
                                {{--<i class="la la-close cl_amount"></i>--}}
                                {{--</a>--}}
                                {{--</td>--}}
                                {{--</tr>--}}
                                </tbody>
                            </table>
                            <span class="errs error-product" style="float: right"></span>
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
                        {{--value="0">--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-lg-6">--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-lg-6"></div>--}}
                        {{--<div class="col-lg-6">--}}
                        {{--<div class="form-group m-form__group row pull-right">--}}
                        {{--<div class="col-12">--}}
                        {{--<span class="errs error-product"></span>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>


                    {{--Table version--}}


                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="form-group m-form__group">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button onclick="location.href='{{route('admin.product-inventory')}}'"
                                data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-arrow-left m--margin-right-10"></i>
                                    <span>{{__('HỦY')}}</span>
                                    </span>
                        </button>
                        <button type="button"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn btn-save-close color_button m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 m--margin-left-10">
                                        <span class="ss--text-btn-mobi">
                                        <i class="la la-check m--margin-right-10"></i>
                                        <span>{{__('LƯU THÔNG TIN')}}</span>
                                        </span>
                        </button>
                        <button type="button"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 btn-save m--margin-bottom-5">
                           <span class="ss--text-btn-mobi">
                            <i class="fa fa-plus-circle m--margin-right-10"></i>
                            <span>{{__('LƯU & TẠO MỚI')}}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="totalInput" value="">
@endsection
@section('after_script')
    <script type="text/template" id="product-childs">
        <tr class="ss--select2-mini">
            <td class="stt ss--width-20 ss--font-size-13 ss--text-center">{stt}</td>
            <td class="name-version ss--font-size-13">{name}<input name="hiddencode[]" type="hidden"
                                                                   value="{code}">
            </td>
            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                <select class="form-control unit ss--select-list ss--width-150 unit-{stt}">
                    {option}
                </select>
            </td>
            <td></td>
            <td>
                <div class="input-group m-input-group m-input-group--air">
                    <input style="text-align: center" type="text" readonly
                           class="form-control ss--btn-ct change-class ss--text-center productInventory"
                           value="{productInventory}">
                </div>
            </td>
            <td class="ss--font-size-13 ss--text-center ss--width-150">
                <div class="input-group bootstrap-touchspin ss--touchspin">
                    <span class="input-group-btn">
                        <button onclick="InventoryTransfer.tru(this)"
                                class="btn btn-secondary bootstrap-touchspin-down ss--btn-ct" type="button">-</button>
                    </span>
                    <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;">
                    </span>
                    <input onchange="sumNumberProduct(this,0)"
                           id="m_touchspin_1" type="text"
                           class="form-control ss--btn-ct number-product change-class ss--text-center"
                           value="{number}" name="number-product"
                    >
                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;">
                    </span>
                    <span class="input-group-btn">
                        <button onclick="InventoryTransfer.cong(this)"
                                class="btn btn-secondary bootstrap-touchspin-up ss--btn-ct" type="button">+</button>
                    </span>
                </div>
                <span class="errs err-quantity text-danger"></span>
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
    </script>
    <script src="{{asset('static/backend/js/admin/inventory-transfer/add-script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/inventory-transfer/jquery.bootstrap-touchspin.js?v='.time())}}"
            type="text/javascript"></script>
@endsection