@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ BÀI VIẾT')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('THÊM BÀI VIẾT')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-register">
            {!! csrf_field() !!}
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tiêu đề VI'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" name="title_vi" id="title_vi"
                                   placeholder="@lang('Nhập tiêu đề VI')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tiêu đề EN'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" name="title_en" id="title_en"
                                   placeholder="@lang('Nhập tiêu đề EN')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nội dung VI'):<b class="text-danger">*</b>
                            </label>
                            <textarea class="form-control m-input" name="description_vi" id="description_vi"
                                      placeholder="@lang('Nhập nội dung VI')..." rows="5"></textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nội dung EN'):<b class="text-danger">*</b>
                            </label>
                            <textarea class="form-control m-input" name="description_en" id="description_en"
                                      placeholder="@lang('Nhập nội dung EN')..." rows="5"></textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Sản phẩm liên quan'):
                            </label>
                            <select class="form-control" id="product" name="product" style="width:100%;" multiple>
                                <option value="0" selected>@lang('Tất cả')</option>
                                @foreach($optionProduct as $v)
                                    <option value="{{$v['product_child_id']}}">{{$v['product_child_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Dịch vụ liên quan'):
                            </label>
                            <select class="form-control" id="service" name="service" style="width:100%;" multiple>
                                <option value="0" selected>@lang('Tất cả')</option>
                                @foreach($optionService as $v)
                                    <option value="{{$v['service_id']}}">{{$v['service_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>
                                @lang('Nội dung chi tiết VI'):
                            </label>
                            <textarea name="description_detail_vi" id="description_detail_vi"
                                      class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('Nội dung chi tiết EN'):
                            </label>
                            <textarea name="description_detail_en" id="description_detail_en"
                                      class="form-control summernote"></textarea>
                        </div>
                        <div class="form-group m-form__group ">
                            <label>Ảnh đại diện app</label>
                            <div class="row">
                                <div class="col-lg-3  w-col-mb-100">
                                    <a href="javascript:void(0)"
                                       onclick="document.getElementById('getFile').click()"
                                       class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    @lang('Thêm ảnh bài viết')
                                                </span>
                                            </span>
                                    </a>
                                </div>
                                <div class="col-lg-9  w-col-mb-100 div_avatar">
                                    <input type="hidden" id="image" name="image" value="">
                                    <div class="wrap-img avatar float-left">
                                        <img class="m--bg-metal m-image img-sd" id="blah"
                                             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                        <span class="delete-img">
                                                    <a href="javascript:void(0)" onclick="create.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                    </div>
                                    {{--                                    <div class="form-group m-form__group float-left m--margin-left-20 warning_img">--}}

                                    {{--                                        <label for="">Định dạng: <b class="image-info image-format"></b> </label>--}}
                                    {{--                                        <br>--}}
                                    {{--                                        <label for="">Kích thước: <b class="image-info image-size"></b>--}}
                                    {{--                                        </label>--}}
                                    {{--                                        <br>--}}
                                    {{--                                        <label for="">Dung lượng: <b class="image-info image-capacity"></b>--}}
                                    {{--                                        </label><br>--}}
                                    {{--                                        <label for="">Cảnh báo: <b class="image-info">Tối đa 10MB (10240KB)</b>--}}
                                    {{--                                        </label><br>--}}
                                    {{--                                        <span class="error_img" style="color:red;"></span>--}}
                                    {{--                                    </div>--}}
                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                           data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                           id="getFile" type="file"
                                           onchange="uploadImage(this);" class="form-control"
                                           style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group m-widget19 image_app">
                            <label>Ảnh đại diện web</label>

                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd avatar image_app" height="150px" id="image_app" src="{{$item['image_app']??'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}" alt="Hình ảnh">
                            </div>
                            <input type="hidden" id="image_app" name="image_app" class="image_app">
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" data-msg-accept="Hình ảnh không đúng định dạng" type="file" onchange="uploadImage3(this,'.image_app');" class="form-control getFile" style="display:none">
                            <div class="m-widget19__action" style="max-width: 170px">
                                <a href="javascript:void(0)" onclick="$('.image_app .getFile').click()" class="btn  btn-sm m-btn--icon color w-100">
                                <span class="">
                                    <i class="fa fa-camera"></i>
                                    <span>
                                        Tải ảnh lên</span>
                                </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.new')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                        </a>

                        <button type="button" onclick="create.store()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script type="text/template" id="avatar-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah"
             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img"><a href="javascript:void(0)" onclick="Voucher.remove_avatar()">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="voucher_img" name="voucher_img">
    </script>
    <script type="text/template" id="imgShow">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="voucher_img" value="{link_hidden}">
            <img class='m--bg-metal m-image img-sd '
                 src='{{asset('{link}')}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img-sv" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="service.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
        </div>
    </script>
    <script src="{{asset('static/backend/js/admin/news/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        create._init();
    </script>
    <script>
        function uploadImage3(input, target = '#avatar') {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.readAsDataURL(input.files[0]);
                var file_data = $(input).prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('link', '_brand.');

                $.ajax({
                    url: laroute.route("admin.upload-image"),
                    method: "POST",
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        if (res.error == 0) {
                            $(target).val(res.file);
                            $(target).attr('src', res.file);
                        }

                    }
                });
            }
        }
    </script>
@stop


