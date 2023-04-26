@extends('layout')
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">--}}
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">

@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM CON')}}
    </span>
@endsection
@section('content')
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

            </div>
        </div>
        <div class="m-portlet__body">
            <form id="edit-product">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label>{{__('Danh mục')}}<b class="text-danger">*</b></label>
                            <div class="input-group">
                                <input id="category_name" name="category_name" type="text"
                                       class="form-control m-input class"
                                       placeholder="{{__('Tên danh mục')}}" value="{{$productChild['category_name']}}"
                                       aria-describedby="basic-addon1" disabled>
                            </div>
                            <span class="errs error-category"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Tên sản phẩm')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <div class="input-group m-input-group">
                                    <input id="product_name" name="product_name" type="text"
                                           class="form-control m-input class"
                                           placeholder="{{__('Tên sản phẩm')}}"
                                           value="{{$productChild['product_name']}}"
                                           aria-describedby="basic-addon1" disabled>
                                </div>
                                <span class="errs error-product-name"></span>
                            </div>
                            <input type="hidden" id="product_id" value="{{$productChild['product_id']}}">
                        </div>
                        
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Tên sản phẩm con')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <div class="input-group m-input-group">
                                    <input id="product_child_name" name="product_child_name" type="text"
                                           class="form-control m-input class"
                                           placeholder="{{__('Tên sản phẩm')}}"
                                           value="{{$productChild['product_child_name']}}"
                                           aria-describedby="basic-addon1">
                                </div>
                                <span class="errs error-product-name"></span>
                            </div>
                            <input type="hidden" id="product_child_id" value="{{$productChild['product_child_id']}}">
                            <input type="hidden" id="product_child_code" value="{{$productChild['product_code']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Sku sản phẩm')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <div class="input-group m-input-group">
                                    <input id="product_child_sku" name="product_child_sku" type="text"
                                           class="form-control m-input class"
                                           placeholder="{{__('Sku sản phẩm')}}"
                                           value="{{$productChild['product_child_sku']}}"
                                           aria-describedby="basic-addon1">
                                </div>
                                <span class="errs error-product-name"></span>
                            </div>
                            <input type="hidden" id="product_id" value="{{$productChild['product_id']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Đơn vị tính')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input id="unit_name" name="unit_name" type="text" class="form-control m-input class"
                                       placeholder="{{__('Tên đơn vị')}}" value="{{$productChild['unit_name']}}"
                                       aria-describedby="basic-addon1" disabled>
                            </div>
                            <span class="errs error-unit"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Giá nhập')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <div class="input-group m-input-group">
                                    <input
                                            value="{{number_format($productChild['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                            id="cost" name="cost"
                                            class="form-control m-input class" placeholder="{{__('Giá nhập')}}"
                                            aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <span class="error-cost"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Giá bán')}}: <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <div class="input-group m-input-group">
                                    <input name="price" id="price"
                                           class="form-control m-input class"
                                           placeholder="{{__('Giá bán')}}"
                                           value="{{number_format($productChild['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                           aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <span class="error-price"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Mã vạch')}}:
                            </label>
                            <div class="input-group">
                                <div class="input-group m-input-group">
                                    <input name="barcode" id="barcode" class="form-control m-input class" value="{{$productChild['barcode']}}">
                                </div>
                            </div>
                            <span class="error-price"></span>
                        </div>


                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tag'):
                            </label>
                            <div>
                                <select class="form-control" id="tag_id" name="tag_id" multiple style="width:100%;">
                                    <option></option>
                                    @foreach($optionTag as $v)
                                        <option value="{{$v['product_tag_id']}}" {{in_array($v['tag_id'], $arrTagMap) ? 'selected': ''}}>{{$v['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="more_info">
                            @if(count($customDefine) > 0)
                                @foreach($customDefine as $v)
                                    <div class="form-group m-form__group">
                                        <label>
                                            {{$v['title']}}:
                                        </label>
                                        <div class="m-input-icon m-input-icon--right">
                                            @switch($v['type'])
                                                @case('text')
                                                <input type="text" id="{{$v['key']}}" name="{{$v['key']}}"
                                                       class="form-control m-input" value="{{$productChild[$v['key']]}}"
                                                       maxlength="190">
                                                @break;
                                                @case('boolean')
                                                <div class="input-group">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                <input type="checkbox" class="manager-btn"
                                                                       id="{{$v['key']}}" name="{{$v['key']}}"
                                                                       value="{{$productChild[$v['key']]}}"
                                                                       onchange="edit_prod_child.changeBoolean(this)" {{$productChild[$v['key']] == 1 ? 'checked': ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>
                                                </div>
                                                @break;
                                                @case('product_code')
                                                <input type="text" id="{{$v['key']}}" name="{{$v['key']}}"
                                                       class="form-control m-input" value="{{$productChild[$v['key']]}}"
                                                       maxlength="190">
                                                @break;
                                            @endswitch
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group ">
                            <div class="row">
                                <div class="col-lg-3 w-col-mb-100">
                                    <a href="javascript:void(0)"
                                       onclick="document.getElementById('getFile').click()"
                                       class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm ảnh đại diện')}}
                                                </span>
                                            </span>
                                    </a>
                                </div>

                                <div class="col-lg-9 w-col-mb-100 div_avatar">
                                    <input type="hidden" id="product_child_avatar"
                                           name="product_child_avatar"
                                           value="{{isset($productAvatar) ? $productAvatar['name'] : ''}}">

                                    <div class="wrap-img avatar float-left">
                                        @if(isset($productAvatar) && $productAvatar['name'] !=null)
                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                 src="{{$productAvatar['name']}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                            <span class="delete-img" style="display: block">
                                                    <a href="javascript:void(0)" onclick="productImage.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                 </span>
                                        @else
                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                 src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                            <span class="delete-img">
                                                    <a href="javascript:void(0)" onclick="productImage.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                    </span>
                                        @endif
                                        <input type="hidden" id="product_avatar" name="product_avatar" value="">
                                    </div>
                                    <div class="form-group m-form__group float-left m--margin-left-20 warning_img">

                                        <label for=""><b class="image-info">{{__('Tối đa 10MB (10240KB)')}}</b>
                                        </label><br>
                                        <span class="error_img" style="color:red;"></span>

                                    </div>
                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                           data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                           id="getFile"
                                           type="file"
                                           onchange="uploadImage(this);" class="form-control"
                                           style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 w-col-mb-100">
                                    <a href="javascript:void(0)"
                                       onclick="productImage.image_dropzone()"
                                       class="btn btn-sm m-btn--icon color">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>
                                                {{__('Thêm ảnh sản phẩm')}}
                                            </span>
                                        </span>
                                    </a>
                                </div>
                                <div class="col-lg-9 w-col-mb-100 div_avatar">
                                    <div class="image-show">
                                        @if (isset($listImage) && count($listImage) > 0)
                                            @foreach($listImage as $v)
                                                <div class="wrap-img image-show-child list-image-old">
                                                    <input type="hidden" name="product_image" class="product_image"
                                                           value="{{$v['name']}}">
                                                    <img class='m--bg-metal m-image img-sd '
                                                         src='{{$v['name']}}' alt='{{__('Hình ảnh')}}' width="100px"
                                                         height="100px">
                                                    <span class="delete-img-sv" style="display: block;">
                                                    <a href="javascript:void(0)"
                                                       onclick="productImage.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group m-form__group">
                            <label>{{__('Trạng thái')}}:</label>
                            <div class="input-group">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_actived"
                                                   {{$productChild['is_actived'] == 1 ? 'checked' : '' }}
                                                   type="checkbox" name="">
                                            <span></span>
                                        </label>
                                    </span>
                                <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>{{__('Hiển thị trên App')}}:</label>
                            <div class="input-group">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_display"
                                                   {{$productChild['is_display'] == 1 ? 'checked' : '' }}
                                                   type="checkbox" name="">
                                            <span></span>
                                        </label>
                                    </span>
                                <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <label>{{__('Phụ thu')}}:</label>
                            <div class="input-group">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_surcharge" name="is_surcharge"
                                                   {{$productChild['is_surcharge'] == 1 ? 'checked' : '' }}
                                                   type="checkbox">
                                            <span></span>
                                        </label>
                                    </span>
                                <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">
                                {{__('Nhắc sử dụng lại')}}:
                            </label>
                            <div class="input-group">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_remind" name="is_remind" type="checkbox"
                                                   value="{{$productChild['is_remind']}}"
                                                   onchange="list_prod_child.changeRemind(this)"
                                                    {{$productChild['is_remind'] == 1 ? 'checked': ''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">
                                {{__('Tính KPI hoa hồng lắp đặt')}}:
                            </label>
                            <div class="input-group">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_applied_kpi" name="is_applied_kpi" type="checkbox"
                                                   value="{{$productChild['is_applied_kpi']}}"
                                                    {{$productChild['is_applied_kpi'] == 1 ? 'checked': ''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>
                            </div>
                        </div>
                        <div class="form-group m-form__group div_remind_value"
                             style="display:{{$productChild['is_remind'] == 1 ? 'block': 'none'}};">
                            <label class="black-title">
                                {{__('Số ngày nhắc lại')}}:
                            </label>
                            <div class="input-group m-input-group">
                                <input type="text" class="form-control m-input"
                                       name="remind_value" id="remind_value"
                                       value="{{!empty($productChild['remind_value']) ? $productChild['remind_value'] : 1}}">
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
                        <a href="{{route('admin.product-child-new')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button"
                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_save m--margin-left-10"
                                onclick="edit_prod_child.save()">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin::product-child-new.modal-edit-image')
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product-child-new/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="avatar-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah"
             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img"><a href="javascript:void(0)" onclick="productImage.remove_avatar()">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="product_avatar" name="product_avatar" value="">
    </script>
    <script type="text/template" id="imgeShow">
        <div class="wrap-img image-show-child list-image-new">
            <input type="hidden" name="img-sv" value="{link_hidden}" class="product_image">
            <img class='m--bg-metal m-image img-sd '
                 src='{{'{link}'}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img-sv" style="display: block;">
                                                    <a href="javascript:void(0)"
                                                       onclick="productImage.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
        </div>


    </script>
    <script>
        edit_prod_child._init();
    </script>
@stop