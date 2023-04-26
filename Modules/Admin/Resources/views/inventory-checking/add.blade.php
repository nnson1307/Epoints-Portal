@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('TẠO PHIỂU KIỂM KHO')}}
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
                                <input readonly class="form-control" value="{{$code}}" id="checking-code" type="text">
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
                                <select id="warehouse" class="form-control m_selectpicker">
                                    <option value="">{{__('Chọn kho')}}</option>
                                    @foreach($wareHouse as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
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
                                        <input id="created-by" type="text" value="{{$user->full_name}}" readonly
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
                                                   class="form-control m-input class" placeholder="{{__('Ngày xuất')}}"
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
                                <textarea placeholder="{{__('Nhập lý do kiểm kho')}}" rows="3" cols="40" name="description"
                                          id="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <span class="errs error-note"></span>
                </div>
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" style="margin-bottom: 0;" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show son" data-toggle="tab" href="#" data-target="#inventory">
                                <h7>{{__('THỦ CÔNG')}}</h7>
                            </a>
                        </li>
                        <li class="nav-item ">
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
                                            <div class="input-group">
                                                <select style="width: 100%" class="form-control ss--width-100-"
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
                        {{--Table version--}}
                        <div class="table-responsive">
                            <table id="table-product"
                                   class="table table-striped m-table ss--header-table">
                                <thead>
                                <tr class="bg ss--nowrap">
                                    <th class="ss--font-size-th ss--text-center">#</th>
                                    <th class="ss--font-size-th">{{__('SẢN PHẨM')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('HỆ THỐNG')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('THỰC TẾ')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('CHÊNH LỆCH')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('XỬ LÝ')}}</th>
                                    <th class="ss--font-size-th"></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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
                <div class="col-lg-4"></div>
                <div class="col-lg-8">
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
                                    <button id="btn-save-add-new" type="button"
                                            class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                   <span class="ss--text-btn-mobi">
                                    <i class="fa fa-plus-circle m--margin-right-10"></i>
                                    <span>{{__('LƯU & TẠO MỚI')}}</span>
                                    </span>
                                    </button>
                                {{--<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"--}}
                                {{--style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">--}}
                                {{--<a class="dropdown-item" href="javascript:void(0)"--}}
                                {{--id="btn-save-draft"><i--}}
                                {{--class="la la-file-o"></i> {{__('Lưu nháp')}}</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="totalInput" value="">
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
@stop
@section('after_script')
    <script type="text/template" id="product-childs">
        <tr class="ss--select2-mini">
            <td class="stt ss--font-size-13 ss--text-center">{stt}</td>
            <td class="name-version ss--font-size-13">{name}
                <input name="hiddencode[]" type="hidden"
                       value="{code}">
                <input name="hiddencost[]" type="hidden"
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
                <input style="text-align: center" class="form-control" readonly name="quantityOld[]"
                       value="{quantityOld}" type="hidden" min="1">
            </td>
            <td style="width: 150px" class="ss--font-size-13 ss--text-center">
                <input style="text-align: center" class="form-control ss--btn-ct quantityNew ss--width-150"
                       onchange="changeQuantityNew(this)" type="text" name="quantityNew[]" value="{quantityNew}">
            </td>
            <td valign="top" style="width: 140px" class="ss--font-size-13 ss--text-center">
                <span class="quantityDifference">{quantityDifference}</span>
                <input style="text-align: center" class="form-control" readonly name="quantityDifference[]"
                       value="{quantityDifference}" type="hidden" min="0">
            </td>
            <td class="typeResolve ss--font-size-13 ss--text-center" style="width: 100px">
                <b></b>
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
    <script src="{{asset('static/backend/js/admin/inventory-checking/add-script.js?v='.time())}}"
            type="text/javascript"></script>
@endsection