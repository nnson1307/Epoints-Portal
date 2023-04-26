@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
    </span>
@endsection
@section('content')
    @include('admin::product.modal.add-commission')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THÊM SẢN PHẨM')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div onmouseover="product.onMouseOverAddNew()" onmouseout="product.onMouseOutAddNew()"
                     class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--open btn-hover-add-new"
                     m-dropdown-toggle="hover" aria-expanded="true">
                    <a href="#"
                       class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                        <i class="la la-plus m--hide"></i>
                        <i class="la la-ellipsis-h"></i>
                    </a>
                    <div class="m-dropdown__wrapper dropdow-add-new" style="z-index: 101;display: none">
                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"
                                  style="left: auto; right: 21.5px;"></span>
                        <div class="m-dropdown__inner">
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav">
                                        <li class="m-nav__item">
                                            <a data-toggle="modal"
                                               data-target="#modal-add-product-category" href="" class="m-nav__link">
                                                <i class="m-nav__link-icon la la-users"></i>
                                                <span class="m-nav__link-text">{{__('Thêm danh mục sản phẩm')}} </span>
                                            </a>
                                            <a data-toggle="modal"
                                               data-target="#modal-add-unit" href="" class="m-nav__link">
                                                <i class="m-nav__link-icon la la-users"></i>
                                                <span class="m-nav__link-text">{{__('Thêm đơn vị tính')}} </span>
                                            </a>
                                            <a data-toggle="modal"
                                               data-target="#modal-add-product-model" href="" class="m-nav__link">
                                                <i class="m-nav__link-icon la la-users"></i>
                                                <span class="m-nav__link-text">{{__('Thêm nhãn hiệu')}} </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form id="add-product">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label>{{__('Danh mục')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select onchange="pppp.getSku(this);" style="width: 100%" id="category" name="category" class="form-control categoryssss m_selectpicker"
                                        title="{{__('Chọn danh mục')}}">
                                    <option value="">{{__('Chọn danh mục')}}</option>
                                    @foreach($PRODUCTCATEGORY as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="errs error-category"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Tên sản phẩm')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input id="product-name" name="product_name" type="text" class="form-control m-input class"
                                       placeholder="{{__('Tên sản phẩm')}}"
                                       aria-describedby="basic-addon1">
                            </div>
                            <span class="errs error-product-name"></span>
                        </div>

{{--                        <div class="form-group m-form__group">--}}
{{--                            <label>--}}
{{--                                {{__('Tên sản phẩm')}} (EN): <b class="text-danger">*</b>--}}
{{--                            </label>--}}
{{--                            <div class="input-group">--}}
{{--                                <input id="product-name-en" name="product_name_en" type="text" class="form-control m-input class"--}}
{{--                                       placeholder="{{__('Tên sản phẩm')}} (EN)"--}}
{{--                                       aria-describedby="basic-addon1">--}}
{{--                            </div>--}}
{{--                            <span class="errs error-product-name-en"></span>--}}
{{--                        </div>--}}
                       
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Đơn vị tính')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select style="width: 100%" id="unit" name="unit" class="form-control m_selectpicker">
                                    <option value="">{{__('Chọn đơn vị tính')}}</option>
                                    @foreach($UNIT as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="errs error-unit"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="m-checkbox m-checkbox--air">
                                <input id="promo" name="example_3" type="checkbox">
                                {{__('Sản phẩm quà tặng')}}
                                <span></span>
                            </label>
                            <label class="m-checkbox m-checkbox--air pull-right input-group">
                                <input id="product-sale" name="example_3" type="checkbox">
                                {{__('Sản phẩm giảm giá')}}
                                <span></span>
                            </label>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Tỉ lệ giảm giá')}}:
                            </label>
                            <div class="input-group">
                                <input type="number" id="percent_sale" name="percent_sale" class="form-control m-input class"
                                       placeholder="{{__('Tỉ lệ giảm giá')}}" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Giá Bán')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input  id="price" name="price" class="form-control m-input class"
                                        placeholder="{{__('Giá Bán')}}"
                                        aria-describedby="basic-addon1">
                            </div>
                            <span class="errs error-price error-price-111"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Giá nhập')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input  name="integer_default" id="cost"
                                        class="form-control m-input class"
                                        placeholder="{{__('Giá nhập')}}"
                                        aria-describedby="basic-addon1">
                            </div>
                            <span class="errs error-cost"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <a class="btn btn-sm m-btn--icon color" data-toggle="modal" data-target="#add-commission">
                                {{__('THÊM HOA HỒNG')}}
                            </a>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-8">
                                <label>
                                    {{__('Chi nhánh')}}: <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select style="width: 100%" class="js-example-data-ajax form-control col-lg branch-new"
                                            name="branch[]"
                                            multiple="multiple">
                                        @foreach($BRANCH as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="errs error-branch"></span>
                            </div>
                            <div class="col-lg-4">
                                <label class="m-checkbox m-checkbox--state-success m--margin-top-35">
                                    <input id="check-all-branch" type="checkbox">{{__('Tất cả chi nhánh')}}
                                    <span></span>
                                </label>
                            </div>

                        </div>
                        {{--                    <div class="form-group m-form__group">--}}
                        {{--                        <label>{{__('Hiển thị trên app')}}</label>--}}
                        {{--                        <div class="m-checkbox-list">--}}
                        {{--                            <label class="m-checkbox m-checkbox--air m-checkbox--state-success">--}}
                        {{--                                <input type="checkbox" id="new" class="type_app" value="new"> {{__('Mới')}}--}}
                        {{--                                <span></span>--}}
                        {{--                            </label>--}}
                        {{--                            <label class="m-checkbox m-checkbox--air m-checkbox--state-success">--}}
                        {{--                                <input type="checkbox" id="best_seller" class="type_app" value="best_seller"> {{__('Bán chạy')}}--}}
                        {{--                                <span></span>--}}
                        {{--                            </label>--}}
                        {{--                        </div>--}}
                        {{--                    </div>--}}
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Nhãn hiệu')}}:
                            </label>
                            <div class="input-group">
                                <select style="width: 100%" id="productModel"
                                        class="form-control col-lg model-new m_selectpicker">
                                    <option value="">{{__('Chọn nhãn hiệu')}}</option>
                                    @foreach($PRODUCTMODEL as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="errs error-product-model"></span>
                        </div>
                        <div class="form-group m-form__group ss--margin-bottom-2" style="margin-bottom: 22px">
                            <label>
                                {{__('Tồn kho')}}:
                            </label>
                            <div class="row">
                                <div class="input-group col-lg-4">
                                    <label class="m-checkbox m-checkbox--state-success m--margin-top-5">
                                        <input id="is-inventory-warning" class="check-inventory-warning"
                                               type="checkbox">{{__('Cảnh báo tồn kho')}}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-lg-8">
                                    <div class="input-group m-input-group">
                                        <input onkeydown="onKeyDownInputNumber(this)" id="inventory-warning" readonly
                                               class="form-control m-input class"
                                               placeholder={{__('Cảnh báo tồn kho')}}
                                                       aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>

                            </label>
                            <div class="row">
                                <div class="form-group col-lg-5 ss--col-lg-5">
                                    <div class="m-widget19__action">
                                        <a href="javascript:void(0)" onclick="document.getElementById('getFile').click()"
                                           class="btn m-btn--square btn-outline-successsss m-btn m-btn--icon">
															<span>
                                            <i class="la la-plus"></i>
																<span>
																	{{__('Thay đổi ảnh đại diện')}}
																</span>
															</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-7 form-group">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group m-form__group m-widget19">
                                                <div class="m-widget19__pic">
                                                    <div class="wrap-imge avatar-temp">
                                                        <img class="m--bg-metal m-image" id="blah-add"
                                                             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                                             alt="{{__('Hình ảnh')}}">
                                                        <span class="delete-img">
                                                <span href="javascript:void(0)"
                                                      onclick="ProductDeleteImageAdd.deleteAvatar()">
                                                    <i class="la la-close"></i>
                                                </span>
                                            </span>
                                                    </div>
                                                </div>
                                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" id="getFile" type="file"
                                                       onchange="uploadImage(this);"
                                                       class="form-control"
                                                       style="display:none">
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
{{--                                            <label for="">{{__('Định dạng')}}: <b class="image-info image-format"></b> </label>--}}
{{--                                            <br>--}}
{{--                                            <label for="">{{__('Kích thước')}}: <b class="image-info image-size"></b> </label>--}}
{{--                                            <br>--}}
{{--                                            <label for="">{{__('Dung lượng')}}: <b class="image-info image-capacity"></b> </label>--}}
                                            <label class="max-size">{{__('Dung lượng tối đa: 10MB (10240kb)')}} </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="image-show">
                                <div class="row">
                                    <div class="col-lg-5 form-group ss--col-lg-12-2">
                                        <div class="m--margin-top-5">
                                            <div class="">
                                                <a onclick="ProductDeleteImageAdd.clearDropzone()" href="javascript:void(0)"
                                                   data-toggle="modal"
                                                   data-target="#addImage"
                                                   class="btn m-btn--square btn-outline-successsss m-btn m-btn--icon">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>
                                                {{__('Thêm ảnh sản phẩm')}}
                                            </span>
                                        </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="imagesss" class="col-lg-7 ss--col-lg-12-2">
                                        <div class="form-group m-form__group  aaaaad" style="display: none">
                                            <div class="product-imagew">
                                                <div class="row col-lg-12 append-image-poduct">


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Mô tả ngắn')}}:
                            </label>
                            <div class="input-group">
                            <textarea  name="description" id="description"
                                       class="form-control" cols="5" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label> {{__('Mô tả chi tiết')}}:</label>
                            <div class="summernote"></div>
                            {{--<textarea id="description" placeholder="Nhập mô tả cho sản phẩm" class="form-control m-input"--}}
                            {{--rows="9"></textarea>--}}
                        </div>
                    </div>
{{--                    @if(in_array('fnb.orders',session('routeList')))--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <label class="m-checkbox m-checkbox--state-success m--margin-top-10">--}}
{{--                                <input class="is_topping"--}}
{{--                                       type="checkbox">{{__('Loại sản phẩm đính kèm')}}--}}
{{--                                <span></span>--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    @endif--}}
                    <div class="col-lg-12">
                        <label class="m-checkbox m-checkbox--state-success m--margin-top-10">
                            <input class="manager-btn"
                                   type="checkbox">{{__('Quản lý sản phẩm theo thuộc tính')}}
                            <span></span>
                        </label>
                    </div>
                    <div class="col-lg-12">
                        <span class="mr-3">{{__('Quản lý sản phẩm theo')}}</span>
                        <label class="m-checkbox m-checkbox--state-success m--margin-top-10">
                            <input class="manager-btn-fix" name="inventory_management"
                                   type="checkbox" value="packet">{{__('Số lô')}}
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success m--margin-top-10 ml-3">
                            <input class="manager-btn-fix" name="inventory_management"
                                   type="checkbox" value="serial">{{__('Serial number/ IMEI')}}
                            <span></span>
                        </label>
                    </div>

                    <div class="col-lg-12" id="attribute-manager" style="display: none">
                        <div class="form-group m-form__group">
                            <button type="button" id="adGroupAttribute" class="btn ss--button-cms-piospa btn-sm">
                                <i class="la la-plus"></i>{{__('THÊM THUỘC TÍNH')}}
                            </button>
                        </div>
                        <div class="select-group-attribute" id="add-product-attr">
                        </div>
                        {{--Table version--}}
                        <div class="table-responsive">
                            <table id="add-product-version"
                                   class="table table-striped m-table ss--header-table">
                                <thead>
                                <tr class="ss--font-size-th ss--nowrap">
                                    <th>#</th>
                                    <th>{{__('TÊN PHIÊN BẢN')}}</th>
                                    <th class="ss--text-center ss--width-150">{{__('GIÁ NHẬP')}}</th>
                                    <th class="ss--text-center ss--width-150">{{__('GIÁ BÁN')}}</th>
                                    <th class="ss--text-center ss--width-150">{{__('SKU')}}</th>
                                    <th class="ss--text-center ss--width-150">{{__('HIỂN THỊ APP')}}</th>
                                    @if(in_array('fnb.orders',session('routeList')))
                                        <th class="ss--text-center ss--width-150">{{__('MẶC ĐỊNH')}}</th>
                                    @endif
                                    <th>
                                        <label class="m-checkbox m-checkbox--air m-checkbox--solid pull-right m--margin-bottom-20">
                                            <input id="check-all" type="checkbox">
                                            <span></span>
                                        </label>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div align="right">
                                <span class="errs-product-childs" style="color: red;"></span>
                            </div>
                        </div>
                        <div class="form-group m-form__group m--margin-top-10">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                                <div class="m-form__actions m--align-right">
                                    <a href="{{route('admin.product')}}"
                                       class="ss--btn-mobiles m--margin-bottom-5 btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                            <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>{{__('HỦY')}}</span>
                                            </span>
                                    </a>
                                    <button onclick="pppp.addProduct(1)" type="button"
                                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span class="ss--text-btn-mobi">
                                            <i class="la la-check"></i>
                                            <span>{{__('LƯU THÔNG TIN')}}</span>
                                            </span>
                                    </button>
                                    <button onclick="pppp.addProduct(0)" type="button"
                                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                    <span class="ss--text-btn-mobi">
                                             <i class="fa fa-plus-circle"></i>
                                            <span> {{__('LƯU & TẠO MỚI')}}</span>
                                            </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer save-attribute m--margin-right-20">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('admin.product')}}"
                       class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                        <i class="la la-arrow-left"></i>
                        <span>{{__('HỦY')}}</span>
                        </span>
                    </a>
                    <button onclick="pppp.addProduct(1)" type="button"
                        class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span class="ss--text-btn-mobi">
                        <i class="la la-check"></i>
                        <span>{{__('LƯU THÔNG TIN')}}</span>
                        </span>
                    </button>
                    <button onclick="pppp.addProduct(0)"
                            class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span class="ss--text-btn-mobi">
                         <i class="fa fa-plus-circle"></i>
                        <span> {{__('LƯU & TẠO MỚI')}}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--@include('admin::product.add-new')--}}
    @include('admin::product.add-image')
    @include('admin::product.modal.add-product-category')
    @include('admin::product.modal.add-product-model')
    @include('admin::product.modal.add-unit')
    <input type="hidden" id="hidedata" value="">
    <input type="hidden" id="hide-price" value="">
    <input type="hidden" id="hide-cost" value="">
    <input type="hidden" id="hide-name" value="">
    <input type="hidden" id="avatarImg" value="">
    <input type="hidden" id="file_name_avatar" value="">
@endsection
@section('after_script')
    <script type="text/template" id="product-childs">
        <tr>
            <td>{stt}</td>
            <td class="name-version">{name}<input name="hiddennameversion[]" type="hidden" value="{name}">
            </td>
            <td><input name="costChild" style="text-align: center"
                       class="cost form-control m-input ss--btn-ct ss--width-150"
                       readonly=""
                       value="{cost}"></td>
            <td class="ss--text-center">
                <input name="price-product-child"
                       style="text-align: center"
                       class="price price_{stt} form-control m-input ss--btn-ct ss--width-150"
                       value="{price}">
            </td>
            <td><input name="sku" style="text-align: center"
                class="form-control m-input ss--btn-ct ss--width-150"
                value=""></td>
            <td class="text-center">
                <label class="m-checkbox m-checkbox--air m-checkbox--solid">
                    <input style="text-align: center" name="is_display[]" type="checkbox">
                    <span></span>
                </label>
            </td>
            @if(in_array('fnb.orders',session('routeList')))
                @include('admin::product.append.append-default')
            @endif
            <td class="checkBox">
                <label class="m-checkbox m-checkbox--air m-checkbox--solid pull-right">
                    <input style="text-align: center" name="check-all-branch[]" checked type="checkbox">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>
{{--    <script src="{{asset('static/backend/js/admin/product/jquery.masknumber.js')}}"--}}
            {{--type="text/javascript"></script>--}}
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product/dropzone.js?v='.time())}}" type="text/javascript"></script>
    {{--<script src="{{asset('static/backend/js/admin/general/ckeditor.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('static/backend/js/admin/product/add-new.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product/add-new-image.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product/delete-image.js?v='.time())}}"
            type="text/javascript"></script>

    <script>
        var Summernote = {
            init: function () {
                $.getJSON(laroute.route('translate'), function (json) {
                    $(".summernote").summernote({
                        height: 208,
                        placeholder: json['Nhập nội dung'],
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'codeview', 'help']],
                        ],
                        callbacks: {
                            onImageUpload: function (files) {
                                for (let i = 0; i < files.length; i++) {
                                    uploadImg(files[i]);
                                }
                            },
                        },
                    })
                });
            }
        };
        jQuery(document).ready(function () {
            Summernote.init()
            $('.note-btn').attr('title', '');
        });
    </script>
    <script type="text/template" id="imgeShow">
        <img class='m--bg-metal m--margin-right-5 m--margin-top-5 m-image-show'
             src='{{asset('{link}')}}' alt='{{__('Hình ảnh')}}'>
    </script>
    <script type="text/template" id="JS-template-avatar">
        <img class="m--bg-metal m--margin-top-5 m-image-show" id="blah" src="{link1}"
             alt="{{__('Hình đại diện')}}">
    </script>
    <script type="text/template" id="JS-template-image-product">
        <div class="product-imagew delete-tempss m--margin-right-10 m--margin-bottom-5">
            <div class="wrap-imge">
                <img src="{link2}"
                     class="m--bg-metal  m--margin-top-5  m-image-show exist-image-db"
                     alt="{{__('Hình sản phẩm')}}">
                <span class="delete-img2">
                    <a style="color: #000;" href="javascript:void(0)" onclick="ProductDeleteImageAdd.deleteImageProduct(this)">
                        <i class="la la-close"></i>
                    </a>
                </span>
            </div>
            <input type="hidden" class="valuelinkimg" value="{linkImg}">
        </div>
    </script>
    <script type="text/template" id="image-avatar-temp">
        <img class="m--bg-metal m-image" id="blah-add"
             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
             alt="{{__('Hình ảnh')}}">
        <span class="delete-img">
            <span href="javascript:void(0)" onclick="ProductDeleteImageAdd.deleteAvatar()">
                <i class="la la-close"></i>
            </span>
        </span>
    </script>
@stop
