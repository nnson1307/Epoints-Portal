@extends('layout')
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
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
                            <label>
                                {{__('Tên sản phẩm')}} (EN): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input id="product-name-en" name="product_name_en" type="text"
                                       class="form-control m-input class"
                                       placeholder="{{__('Tên sản phẩm')}} (EN)" value="{{$product->productNameEn}}"
                                       aria-describedby="basic-addon1">
                            </div>
                            <span class="errs error-product-name-en text-danger"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Mô tả ngắn')}} (EN):
                            </label>
                            <div class="input-group">
                            <textarea name="description_en" id="description_en"
                                      class="form-control" cols="5" rows="5">{{$product->description_en}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label> {{__('Mô tả chi tiết')}} (EN):</label>
                            <div class="summernote">{!! $product->description_detail_en !!}</div>

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
                        <button type="button" onclick="product.update()"
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

@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/fnb/product/script.js?v='.time())}}"
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

@stop
