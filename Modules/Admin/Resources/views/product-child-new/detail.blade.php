@extends('layout')
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">

@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM CON')}}
    </span>
@endsection
@section('content')
    <style>
        #popup-list-serial .modal-dialog{
            min-width: 50% !important;
        }
        span.select2{
            width: 100% !important
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT SẢN PHẨM')}}
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
                                <input id="category_name" name="category_name" type="text" class="form-control m-input class"
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
                                    <input id="product_name" name="product_name" type="text" class="form-control m-input class"
                                           placeholder="{{__('Tên sản phẩm')}}" value="{{$productChild['product_name']}}"
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
                                    <input id="product_child_name" name="product_child_name" type="text" class="form-control m-input class"
                                           placeholder="{{__('Tên sản phẩm')}}" value="{{$productChild['product_child_name']}}"
                                           aria-describedby="basic-addon1" disabled>
                                </div>
                                <span class="errs error-product-name"></span>
                            </div>
                            <input type="hidden" id="product_child_id" value="{{$productChild['product_child_id']}}">
                            <input type="hidden" id="product_child_code" value="{{$productChild['product_code']}}">
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
                                            id="cost" name="cost" disabled
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
                                    <input name="price" id="price" disabled
                                           class="form-control m-input class"
                                           placeholder="{{__('Giá nhập')}}"
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
                                    <input name="barcode" id="barcode" class="form-control m-input class" value="{{$productChild['barcode']}}" disabled>
                                </div>
                            </div>
                            <span class="error-price"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group ">
                            <div class="row">
                                <div class="col-lg-3 w-col-mb-100">
                                    <label for="">{{__('Ảnh đại diện')}}</label>
                                </div>

                                <div class="col-lg-9 w-col-mb-100 div_avatar">
                                    <div class="wrap-img avatar float-left">
                                        @if($productAvatar !=null)
                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                 src="{{$productAvatar['name']}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                        @else
                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                 src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                        @endif
                                        <input type="hidden" id="product_avatar" name="product_avatar" value="">
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
                                    <label for="">{{__('Danh sách hình ảnh')}}:</label>
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
                                                </div>
                                            @endforeach
                                        @else
                                            <b>{{__('Không có hình ảnh')}}</b>
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
                                            <input id="is_actived" {{$productChild['is_actived'] == 1 ? 'checked' : '' }}
                                            type="checkbox" disabled>
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
                                            <input id="is_display" {{$productChild['is_display'] == 1 ? 'checked' : '' }}
                                            type="checkbox" disabled>
                                            <span></span>
                                        </label>
                                    </span>
                                <i class="m--margin-top-5 m--margin-left-5">{{__('Chọn để kích hoạt trạng thái')}}</i>
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <label>
                                {{__('Phụ thu')}}:
                            </label>
                            <div class="row">
                                <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_surcharge" name="is_surcharge"
                                                   type="checkbox" {{$productChild['is_surcharge']==1?'checked':''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-lg-6 m--margin-top-5">
                                    <i>{{__('Chọn để kích hoạt phụ thu')}}</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="col-12 bg">
                <form class="frmFilter ss--background">
                    <input type="hidden" name="page" id="page_inventory" value="1">
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <select name="warehouse_id" id="warehouse_id" class="form-control" onchange="detailProduct.changePageSerial(1)">
                                    <option value="">{{__('Chọn chi nhánh')}}</option>
                                    @foreach($listWarehouse as $itemWarehouse)
                                        <option value="{{$itemWarehouse['warehouse_id']}}">{{$itemWarehouse['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 block-list-inventory p-0 mt-3">

            </div>
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
                        @if (isset($productChild['product_child_id']) && $productChild['product_child_id'] != null)
                        <a type="button"
                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_save m--margin-left-10"
                                href="{{route('admin.product-child-new.edit',$productChild['product_child_id'])}}">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHỈNH SỬA')}}</span>
							</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="showPopup"></div>
    @include('admin::product-child-new.modal-edit-image')
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product-child-new/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product-child-new/detail.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('#warehouse_id').select2();
    </script>
@stop