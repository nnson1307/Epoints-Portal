@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ CHƯƠNG TRÌNH KHUYẾN MÃI')</span>
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
                        @lang('CHỈNH SỬA CHƯƠNG TRÌNH KHUYẾN MÃI')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <div class="m-portlet__body">
            <form id="form-edit">
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Tên chương trình') (EN):<b class="text-danger">*</b>
                    </label>
                    <div class="col-lg-9 col-xl-9">
                        <input type="text" class="form-control m-input"
                               id="promotion_name_en" name="promotion_name_en" placeholder="@lang('Nhập tên chương trình')"
                               value="{{$item['promotion_name_en']}}">
                    </div>
                </div>

                <div class="m-form__group form-group row">
                    <label class="col-lg-3 col-form-label">@lang('Ảnh đại diện') (EN):</label>
                    <div class="col-lg-2">
                        <div class="form-group m-form__group m-widget19">
                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd" id="blah_en" height="150px"
                                     src="{{$item['image_en'] != null ? $item['image_en'] : "https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"}}"
                                     alt="Hình ảnh"/>
                            </div>
                            <input type="hidden" id="image_en" name="image_en">
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                   data-msg-accept="Hình ảnh không đúng định dạng"
                                   id="getFileEn" type='file'
                                   onchange="uploadAvatar2(this,'en');"
                                   class="form-control"
                                   style="display:none"/>
                            <div class="m-widget19__action" style="max-width: 170px">
                                <a href="javascript:void(0)"
                                   onclick="document.getElementById('getFileEn').click()"
                                   class="btn  btn-sm m-btn--icon color w-100">
                                                <span class="m--margin-left-20">
                                                    <i class="fa fa-camera"></i>
                                                    <span>
                                                        @lang('Tải ảnh lên')
                                                    </span>
                                                </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3 black_title">
                        @lang('Mô tả ngắn') (EN):
                    </label>
                    <div class="col-lg-9 col-xl-9">
                            <textarea class="form-control" id="description_en" name="description_en" cols="5"
                                      rows="5">{{$item['description_en']}}</textarea>
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-xl-3 col-lg-3  black_title">
                        @lang('Mô tả chi tiết') (EN):
                    </label>
                    <div class="col-lg-9 col-xl-9 input-group">
                            <textarea class="form-control" id="description_detail_en"
                                      name="description_detail_en">{{$item['description_detail_en']}}</textarea>
                    </div>
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('promotion')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button"
                            onclick="view.submitEdit('{{$item['promotion_id']}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/fnb/promotion/script.js')}}"
            type="text/javascript"></script>
    <script>
        view._init();
    </script>
@stop


