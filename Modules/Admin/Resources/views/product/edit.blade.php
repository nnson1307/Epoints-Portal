@extends('layout')
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <style>
        #addTopping {
            font-size : 17px !important;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
    </span>
@endsection
@section('content')
    @include('admin::product.modal.edit-commission')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA SẢN PHẨM')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div>
                    @if(in_array('fnb.orders',session('routeList')))
                        <a href="{{route('fnb.product.edit',['id' => $id])}}" class="btn ss--button-cms-piospa btn-sm mr-3">
                            {{__('Cập nhật nội dung')}}
                        </a>
                    @endif
                </div>
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
                                            @if(in_array('fnb.product.add-topping',session('routeList')))
                                                <a target="_blank" href="{{route('fnb.product.add-topping',['id' => $id])}}" class="m-nav__link">
                                                    <i class="m-nav__link-icon la la-users"></i><span class="m-nav__link-text">{{__('Thêm sản phẩm đính kèm')}}</span>
                                                </a>
                                            @endif
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
            <form id="edit-product">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label>{{__('Danh mục')}}<b class="text-danger">*</b></label>
                            <div class="input-group">
                                <select style="width: 100%" id="category" name="category" class="form-control">
                                    <option value="{{$product->productCategoryId}}">{{$product->categoryName}}</option>
                                    @foreach($category as $c)
                                        <option value="{{$c->product_category_id}}">{{$c->category_name}}</option>
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
                                <div class="input-group m-input-group">
                                    <input id="product-name" name="product_name" type="text" class="form-control m-input class"
                                           placeholder="{{__('Tên sản phẩm')}}" value="{{$product->productName}}"
                                           aria-describedby="basic-addon1">
                                </div>
                                <span class="errs error-product-name"></span>
                            </div>
                        </div>
{{--                        <div class="form-group m-form__group">--}}
{{--                            <label>--}}
{{--                                {{__('Tên sản phẩm')}} (EN): <b class="text-danger">*</b>--}}
{{--                            </label>--}}
{{--                            <div class="input-group">--}}
{{--                                <input id="product-name-en" name="product_name_en" type="text" class="form-control m-input class"--}}
{{--                                       placeholder="{{__('Tên sản phẩm')}} (EN)" value="{{$product->productNameEn}}"--}}
{{--                                       aria-describedby="basic-addon1">--}}
{{--                            </div>--}}
{{--                            <span class="errs error-product-name-en"></span>--}}
{{--                        </div>--}}
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Đơn vị tính')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select style="width: 100%" id="unit" name="unit" class="form-control unit-new">
                                    <option value="{{$product->unitId}}">{{$product->unitName}}</option>
                                    @foreach($unit as $item)
                                        <option value="{{$item->unit_id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="errs error-unit"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>{{__('Trạng thái')}}:</label>
                            <div class="input-group">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="isActive" {{$product->isActived==1?"checked":""}} type="checkbox"
                                                   class="" name="">
                                            <span></span>
                                        </label>
                                    </span>
                                <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="m-checkbox m-checkbox--air">
                                        @if($product->isPromo==1)
                                            <input checked id="promo" name="example_3" type="checkbox">
                                            {{__('Sản phẩm quà tặng')}}
                                        @else
                                            <input id="promo" name="example_3" type="checkbox"> {{__('Sản phẩm quà tặng')}}
                                        @endif
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--air">
                                        @if($product->isSale==1)
                                            <input checked id="product-sale" name="example_3" type="checkbox">
                                            {{__('Sản phẩm giảm giá')}}
                                        @else
                                            <input id="product-sale" name="example_3" type="checkbox">
                                            {{__('Sản phẩm giảm giá')}}
                                        @endif
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Tỉ lệ giảm giá')}}:
                            </label>
                            <div class="input-group">
                                <input type="number" id="percent_sale" name="percent_sale" class="form-control m-input class"
                                       placeholder="{{__('Tỉ lệ giảm giá')}}" {{$product->isSale == 1 ? '' : 'disabled'}} value="{{$product->percent_sale}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Giá bán')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <div class="input-group m-input-group">
                                    <input @if($product->isPromo==1) disabled @endif
                                    value="{{number_format($product->price, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                           id="price" name="price"
                                           class="form-control m-input class" placeholder="{{__('Giá bán')}}"
                                           aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <span id="errorss" class="errs error-price error-price-2"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Giá nhập')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <div class="input-group m-input-group">
                                    <input @if($product->isPromo == 1) disabled @endif name="integer_default" id="cost"
                                           class="form-control m-input class"
                                           placeholder="{{__('Giá nhập')}}"
                                           value="{{number_format($product->cost, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                           aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <span class="errs error-cost"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <a class="btn btn-sm m-btn--icon color" data-toggle="modal" data-target="#edit-commission">
                                {{__('Thêm hoa hồng')}}
                            </a>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Chi nhánh')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select style="width: 100%" class="js-example-basic-multiple form-control col-lg branch-new"
                                        name="branch[]"
                                        multiple="multiple">
                                    @foreach($branch as $key=>$value)
                                        <option value="{{$key}}" {{in_array($key,$arrayProductBranchPrice)?'selected':''}}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="errs error-branch"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <label class="m-checkbox m-checkbox--state-success">
                                    @if($product->isAllBranchPrice==1)
                                        <input id="check-all-branch" checked type="checkbox">
                                    @else
                                        <input id="check-all-branch" type="checkbox">
                                    @endif
                                    {{__('Tất cả chi nhánh')}}
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        {{--                    <div class="form-group m-form__group">--}}
                        {{--                        <label>{{__('Hiển thị trên app')}}</label>--}}
                        {{--                        <div class="m-checkbox-list">--}}
                        {{--                            <label class="m-checkbox m-checkbox--air m-checkbox--state-success">--}}
                        {{--                                @if(in_array('new', explode(',',$product->type_app)))--}}
                        {{--                                    <input type="checkbox" id="new" class="type_app" value="new" checked> {{__('Mới')}}--}}
                        {{--                                @else--}}
                        {{--                                    <input type="checkbox" id="new" class="type_app" value="new"> {{__('Mới')}}--}}
                        {{--                                @endif--}}
                        {{--                                <span></span>--}}
                        {{--                            </label>--}}
                        {{--                            <label class="m-checkbox m-checkbox--air m-checkbox--state-success">--}}
                        {{--                                @if(in_array('best_seller', explode(',',$product->type_app)))--}}
                        {{--                                    <input type="checkbox" id="best_seller" class="type_app" value="best_seller" checked> {{__('Bán chạy')}}--}}
                        {{--                                @else--}}
                        {{--                                    <input type="checkbox" id="best_seller" class="type_app" value="best_seller"> {{__('Bán chạy')}}--}}
                        {{--                                @endif--}}
                        {{--                                <span></span>--}}
                        {{--                            </label>--}}
                        {{--                        </div>--}}
                        {{--                    </div>--}}

                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label> {{__('Nhãn hiệu')}}:</label>
                            <div class="input-group">
                                <select style="width: 100%" id="productModel" class="form-control col-lg model-new">
                                    @if($product->productModelId)
                                        <option value="{{$product->productModelId}}">{{$product->productModelName}}</option>
                                    @else
                                        <option value="">{{__('Chọn nhãn hiệu sản phẩm')}}</option>
                                    @endif
                                    @foreach($model as $item)
                                        <option value="{{$item->product_model_id}}">{{$item->product_model_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="errs error-product-model"></span>
                        </div>
                        <div class="form-group m-form__group ss--margin-bottom-2" style="margin-bottom: 14px">
                            <label>
                                {{__('Tồn kho')}}:
                            </label>
                            <div class="row">
                                <div class="input-group col-lg-4">
                                    <label class="m-checkbox m-checkbox--state-success m--margin-top-10">
                                        @if($product->isInventoryWarning==1)
                                            <input id="is-inventory-warning" checked class="check-inventory-warning"
                                                   type="checkbox">
                                        @else
                                            <input id="is-inventory-warning" class="check-inventory-warning"
                                                   type="checkbox">
                                        @endif
                                        {{__('Cảnh báo tồn kho')}}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-lg-8">
                                    <div class="input-group m-input-group">
                                        <input value="{{$product->inventoryWarning}}"
                                               id="inventory-warning" type="number"
                                               class="form-control m-input class"
                                               placeholder="{{__('Cảnh báo tồn kho')}}"
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
                                    <div class="m-widget19__action m--margin-top-4">
                                        <a href="javascript:void(0)"
                                           onclick="document.getElementById('getFile').click()"
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
                                        <div class="col-6">
                                            <div class="form-group m-form__group m-widget19 m--margin-top-4">
                                                <div class="m-widget19__pic">
                                                    <div class="wrap-imge avatar-temp">
                                                        @if($product->avatar!=null)
                                                            <div class="class-for-delete">
                                                                <div class="div-image-show">
                                                                    <div class="wrap-imge">
                                                                        <img class="m--bg-metal m-image-show imagoday"
                                                                             id="blah-edit"
                                                                             src="{{asset($product->avatar)}}"
                                                                             alt="Hình ảnh">
                                                                        <span class="delete-img-show">
                                                                        <span href="javascript:void(0)"
                                                                              class="ss--text-black"
                                                                              onclick="ProductDeleteImageEdit.deleteAvatar('{{$product->avatar}}',this)">
                                                                            <i class="la la-close"></i>
                                                                        </span>
                                                                    </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{--<img class="m--bg-metal m-image" id="blah-add"--}}
                                                            {{--src="{{asset($product->avatar)}}"--}}
                                                            {{--alt="Hình ảnh">--}}
                                                            {{--<span class="delete-img-show">--}}
                                                            {{--<a href="javascript:void(0)" class="ss--text-black"--}}
                                                            {{--onclick="ProductDeleteImageEdit.deleteAvatar('{{$product->avatar}}',this)">--}}
                                                            {{--<i class="la la-close"></i>--}}
                                                            {{--</a>--}}
                                                            {{--</span>--}}
                                                        @else
                                                            <img class="m--bg-metal m-image" id="blah-edit"
                                                                 src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                                                 alt="Hình ảnh">
                                                            <span class="delete-img">
                                                                        <span href="javascript:void(0)"
                                                                              class="ss--text-black"
                                                                              onclick="ProductDeleteImageEdit.deleteAvatar('{{$product->avatar}}',this)">
                                                                            <i class="la la-close"></i>
                                                                        </span>
                                                                    </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" id="getFile" type="file"
                                                       onchange="uploadImage(this);"
                                                       class="form-control"
                                                       style="display:none">
                                            </div>
                                        </div>
                                        <div class="col-6">
{{--                                            @if($product->avatar!=null)--}}
{{--                                                <label for="">{{__('Định dạng')}}: <b--}}
{{--                                                            class="image-info image-format">{{$type}}</b>--}}
{{--                                                </label>--}}
{{--                                                <br>--}}
{{--                                                <label for="">{{__('Kích thước')}}: <b class="image-info image-size">{{$width}}--}}
{{--                                                        x{{$height}}px</b> </label>--}}
{{--                                                <br>--}}
{{--                                                <label for="">{{__('Dung lượng')}}: <b class="image-info image-capacity">{{$size}}--}}
{{--                                                        kb</b>--}}
{{--                                                </label>--}}
{{--                                            @else--}}
{{--                                                <label for="">{{__('Định dạng')}}: <b--}}
{{--                                                            class="image-info image-format"></b>--}}
{{--                                                </label>--}}
{{--                                                <br>--}}
{{--                                                <label for="">{{__('Kích thước')}}: <b class="image-info image-size"></b></label>--}}
{{--                                                <br>--}}
{{--                                                <label for="">{{__('Dung lượng')}}: <b class="image-info image-capacity"></b>--}}
{{--                                                </label>--}}
{{--                                            @endif--}}
                                            <label class="max-size">{{__('Dung lượng tối đa: 10MB (10240kb)')}} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-5 form-group ss--col-lg-12-2">
                                    <div class="m--margin-top-0">
                                        <div class="">
                                            <a onclick="ProductDeleteImageAdd.clearDropzone()" href="javascript:void(0)"
                                               data-toggle="modal"
                                               data-target="#editImage"
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
                                <div class="form-group m-form__group col-lg-7 image-exist ss--col-lg-12-2">
                                    <div class="product-imagew product-image-parent">
                                        <div class="row col-lg-12 image-append-this">
                                            @if(isset($productImage)&&$productImage->count()>0)
                                                @foreach($productImage as $image)
                                                    <div class="class-for-delete">
                                                        <div class="product-imagew m--margin-right-10 m--margin-bottom-5 div-image-show">
                                                            <div class="wrap-imge">
                                                                <img src="{{asset($image['name'])}}"
                                                                     class="m--bg-metal m-image-show exist-image-db">
                                                                {{--<button onclick="removeImage('{{$image['name']}}',this)"--}}
                                                                {{--type="button"--}}
                                                                {{--class="btn m-btn--pill btn-sm btn-danger btn-delete-img">--}}
                                                                {{--Xóa--}}
                                                                {{--</button>--}}
                                                                <span class="delete-img-show" style="top: -10px;">
                                                                <span href="javascript:void(0)"
                                                                      onclick="ProductDeleteImageEdit.removeImage('{{$image['name']}}',this)">
                                                                    <i class="la la-close"></i>
                                                                </span>
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
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
                                       class="form-control" cols="5" rows="5">{{$product->description}}</textarea>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label> {{__('Mô tả chi tiết')}}:</label>
                            <div class="summernote">{!! $product->description_detail !!}</div>
                            {{--<textarea id="description" placeholder="{{__('Nhập mô tả cho sản phẩm')}}"--}}
                            {{--class="form-control m-input" rows="9">{{$product->description}}</textarea>--}}
                            {{--</div>--}}
                        </div>
                    </div>
{{--                    @if(in_array('fnb.orders',session('routeList')))--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <label class="m-checkbox m-checkbox--state-success m--margin-top-10">--}}
{{--                                <input class="is_topping" {{$product->is_topping == 1 ? 'checked' : ''}}--}}
{{--                                       type="checkbox">{{__('Loại sản phẩm đính kèm')}}--}}
{{--                                <span></span>--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    @endif--}}
                    <div class="col-lg-12 mb-3">
                        <span class="mr-3">{{__('Quản lý sản phẩm theo')}}</span>
                        <label class="m-checkbox m-checkbox--state-success m--margin-top-10">
                            <input class="manager-btn-fix" name="inventory_management" {{in_array($product['inventory_management'],['all','packet']) ? 'checked' : ''}}
                                   type="checkbox" value="packet">{{__('Số lô')}}
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--state-success m--margin-top-10 ml-3">
                            <input class="manager-btn-fix" name="inventory_management" {{in_array($product['inventory_management'],['all','serial']) ? 'checked' : ''}}
                                   type="checkbox" value="serial">{{__('Serial number/ IMEI')}}
                            <span></span>
                        </label>
                    </div>
                    <div class="col-lg-12 exist-product-child">
                        <div class="form-group m-form__group">
                            <button type="button" id="addGroupAttribute" class="btn ss--button-cms-piospa btn-sm">
                                <i class="la la-plus"></i>{{__('THÊM THUỘC TÍNH')}}
                            </button>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="select-group-attribute" id="add-product-attr">
{{--                                @foreach($productAttribute as $item)--}}
{{--                                    <div class="form-group m-form__group">--}}
{{--                                        <div class="input-group m-input-group m-input-group--solid">--}}
{{--                                            <div class="input-group row new-attribute-version" id="new-attribute-version">--}}
{{--                                                <div class="col-lg-3">--}}
{{--                                                    <select style="width: 100%" name="selectAttrGr[]" class="form-control">--}}
{{--                                                        <option value="{{$item->productAttrGroupId}}">{{$item->productAttrGroupName}}</option>--}}
{{--                                                    </select>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-lg-3">--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input id="product_sku" name="product_sku" type="text" class="form-control" value=""--}}
{{--                                                               placeholder="{{__('Sku sản phẩm')}}"--}}
{{--                                                               aria-describedby="basic-addon1">--}}
{{--                                                    </div>--}}
{{--                                                    <span class="errs error-product-sku" style="color: rgb(255, 0, 0);"></span>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-lg-6">--}}
{{--                                                    <div class="class-procuct-attibute">--}}
{{--                                                        <select style="width: 100%" class="form-control"--}}
{{--                                                                name="sProducAttribute[]"--}}
{{--                                                                multiple="multiple">--}}
{{--                                                            @foreach($productAttributeByProductId as $a)--}}
{{--                                                                @if($a->productAttributeGroupId==$item->productAttrGroupId)--}}
{{--                                                                    <option value="{{$a->productAttributeId}}"--}}
{{--                                                                            selected>{{$a->productAttributeLabel}}</option>--}}
{{--                                                                @endif--}}
{{--                                                            @endforeach--}}
{{--                                                            @foreach($productAttributeWhereNotIn as $productAttr)--}}
{{--                                                                @if($productAttr->product_attribute_group_id==$item->productAttrGroupId)--}}

{{--                                                                    <option value="{{$productAttr->product_attribute_id}}">{{$productAttr->product_attribute_label}}</option>--}}
{{--                                                                @endif--}}
{{--                                                            @endforeach--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <br>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}

                                <?php $arrNotIn = [] ?>
                                @foreach($productAttrGroupAndProductAttrSelect as $item)
                                    <div class="form-group m-form__group">
                                        <div class="input-group m-input-group m-input-group--solid">
                                            <div class="input-group row new-attribute-version" id="new-attribute-version">
                                                <div class="col-lg-3">
                                                    <select style="width: 100%" name="selectAttrGr[]" class="form-control" disabled>
                                                        <option value="">{{__('Nhóm thuộc tính')}}</option>
                                                        @foreach($productAttributeGroup as $key=>$value)
                                                            @if(!in_array($key,$arrNotIn))
                                                                <option value="{{$key}}" {{$item['productAttrGroupId'] == $key ? 'selected' : ''}}>{{$value}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <?php $arrNotIn[] = $item['productAttrGroupId'] ?>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="class-procuct-attibute">
                                                        <select style="width: 100%" class="form-control" name="sProducAttribute[]"
                                                                multiple="multiple">
                                                            @foreach($productAttributeByProductIdGroup[$item['productAttrGroupId']] as $itemAttr)
                                                                <option value="{{$itemAttr['product_attribute_id']}}" {{in_array($itemAttr['product_attribute_id'],collect($productAttributeByProductIdSelect[$item['productAttrGroupId']])->pluck('productAttributeId')->toArray()) ? 'selected' : ''}}>{{$itemAttr['product_attribute_label']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <br>

                                            </div>
                                            <span style="color: red;" class="errs-attribute"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{--Table version--}}
                        <div class="table-responsive">
                            <table id="edit-product-version"
                                   class="table table-striped m-table ss--header-table ss--nowrap">
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
                                            <input checked id="check-all" type="checkbox">
                                            <span></span>
                                        </label>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="llll">
                                @foreach($productChild as $key=>$value)
                                    <tr class="ss--font-size-13">
                                        <td class="stt">{{($key+1)}}</td>
                                        <td class="name-version">
                                            {{ $value['product_child_name'] }}
                                            <input name="hiddennameversion[]" type="hidden"
                                                   value="{{ $value['product_child_name'] }}">
                                            <input class="code-hidden" type="hidden"
                                                   value="{{ $value['product_code'] }}">
                                        </td>
                                        <td>
                                            <input name="costChild" style="text-align: center"
                                                   class="cost form-control m-input ss--btn-ct ss--width-150" readonly=""
                                                   value="{{number_format($value['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                        </td>
                                       
                                        <td>
                                            <input name="priceChild"
                                                   style="text-align: center"
                                                   class="price form-control m-input ss--btn-ct ss--width-150"
                                                   value="{{number_format($value['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                        </td>
                                        <td>
                                            <input name="product_sku" style="text-align: center"
                                                   class="cost form-control m-input ss--btn-ct ss--width-150" readonly=""
                                                   value="{{ $value['product_child_sku'] }}">
                                        </td>
                                        <td class="text-center">
                                            <label class="m-checkbox m-checkbox--air m-checkbox--solid">
                                                <input style="text-align: center" name="is_display[]" {{$value['is_display'] == 1 ? 'checked' :''}} type="checkbox">
                                                <span></span>
                                            </label>
                                        </td>
                                        @if(in_array('fnb.orders',session('routeList')))
                                            @include('admin::product.append.append-default',['is_master' => $value['is_master'],'name' => $value['product_child_name'] ])
                                        @endif
                                        <td class="checkBox">
                                            <label class="m-checkbox m-checkbox--air m-checkbox--solid pull-right">
                                                <input checked style="text-align: center"
                                                       name="check-product-child[]"
                                                       type="checkbox">
                                                <span></span>
                                            </label>
                                        </td>
                                        <input class="hiddenCode" type="hidden"
                                               value="{{ $value['product_code'] }}">
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div align="right">
                                <span class="errs-product-childs" style="color: red;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer m--margin-right-20">
            <div class="form-group m-form__group m--margin-top-10">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.product')}}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                            <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>{{__('HỦY')}}</span>
                                            </span>
                        </a>
                        <button type="button"
                                class="ss--btn-mobiles save-change btn-save m--margin-left-10 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
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
    <input id="idHidden" type="hidden" value="{{$id}}">
    <input type="hidden" id="inventory_management_hidden" value="{{$product['inventory_management']}}">
    <input type="hidden" id="hidedata" value="">
    <input type="hidden" id="file_name_avatar" value="">
    @if($product->avatar!=null)
        <input type="hidden" id="avatar-exist" value="{{$product->avatar}}">
    @else
        <input type="hidden" id="avatar-exist" value="">
    @endif
    <input type="hidden" id="link-image-fault" value="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}">
    {{--@include('admin::product.add-new')--}}
    @include('admin::product.edit-image')
    <div id="array-image-hidden">
        @if(isset($productImage)&&$productImage->count()>0)
            @foreach($productImage as $image)
                <input type="hidden" class="image-hide" value="{{$image['name']}}">
            @endforeach
        @endif
    </div>
    <div id="array-product-child">
        @if($productChild->count()>0)
            @foreach($productChild as $key=>$value)
                <input type="hidden" class="product-child1" value="{{$value['product_child_name']}}">
            @endforeach
        @endif
    </div>
    @include('admin::product.modal.add-product-category')
    @include('admin::product.modal.add-product-model')
    @include('admin::product.modal.add-unit')
@endsection
@section('after_script')
    <script type="text/template" id="product-childs">
        <tr class="product-child-appen ss--font-size-13">
            <td class="stt">{stt}</td>
            <td class="name-version">{name}
                <input name="hiddennameversion[]" type="hidden" value="{name}">
                <input class="code-hidden" name="hiddenId[]" type="hidden" value="{id}">
            </td>
            <td>
                <input name="costChild" style="text-align: center"
                       class="cost form-control m-input ss--btn-ct ss--width-150" readonly=""
                       value="{cost}">
            </td>
            <td class="ss--text-center">
                <input name="price-product-child"
                       style="text-align: center"
                       class="price price_{stt} form-control m-input ss--btn-ct ss--width-150" value="{price}">
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
            @include('admin::product.append.append-default')
            <td class="checkBox ss--text-center">
                <label class="m-checkbox m-checkbox--air m-checkbox--solid pull-right">
                    <input checked style="text-align: center" name="check-product-child[]" type="checkbox">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>--}}

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/admin/product/edit-product.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product/edit-version.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product/dropzone-edit.js?v='.time())}}"
            type="text/javascript"></script>
    {{--    <script src="{{asset('static/backend/js/admin/product/add-new.js')}}" type="text/javascript"></script>--}}
    <script src="{{asset('static/backend/js/admin/product/delete-image.js')}}"
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
            Summernote.init();
            $('.note-btn').attr('title', '');
        });
    </script>

    <script type="text/template" id="js-template-append-image">
        <div class="class-for-delete  image-temp">
            <div class="product-imagew m--margin-right-10 m--margin-bottom-5 div-image-show">
                <div class="wrap-imge">
                    <img src="{{'{link}'}}"
                         class="m--bg-metal m-image-show exist-image-db">
                    {{--<button onclick="removeImage('{link}',this)"--}}
                    {{--type="button"--}}
                    {{--class="btn m-btn--pill btn-sm btn-danger btn-delete-img">--}}
                    {{--Xóa--}}
                    {{--</button>--}}
                    <span class="delete-img-show" style="top:-10px">
                    <span href="javascript:void(0)" onclick="ProductDeleteImageEdit.removeImage('{link}',this)">
                        <i class="la la-close"></i>
                    </span>
                </span>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="js-template-append-dropzone">
        <div class="m-dropzone__msg dz-message needsclick">
            <h3 class="m-dropzone__msg-title">{{__('Hình sản phẩm')}}</h3>
            <span class="m-dropzone__msg-desc">{{__('Vui lòng chọn hình ảnh')}}.</span>
        </div>
        <input type="hidden" id="file_image" name="product_image" value="file_name">
        <div id="temp">

        </div>
    </script>
    <script type="text/template" id="JS-template-avatar">
        <div class="class-for-delete">
            <div class="div-image-show">
                <a href="#" target="_blank" data-lightbox="image-1">
                    <img class="m--bg-metal m-image-show imagoday"
                         id="blah"
                         src="{link1}"
                         alt="Hình ảnh">
                </a>
                <button onclick="deleteAvatar('{link1}',this)"
                        type="button"
                        class="btn m-btn--pill btn-sm btn-danger btn-delete-img">
                    {{__('Xóa')}}
                </button>
            </div>
        </div>
    </script>
    <script type="text/template" id="image-avatar-temp">
        <img class="m--bg-metal m-image" id="blah-add"
             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
             alt="Hình ảnh s">
        <span class="delete-img-show">
            <span href="javascript:void(0)" onclick="ProductDeleteImageAdd.deleteAvatar()">
                <i class="la la-close"></i>
            </span>
        </span>
    </script>
@stop
