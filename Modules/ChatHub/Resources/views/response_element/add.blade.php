@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">@lang('chathub::response_element.index.RESPONSE_ELEMENT')</span>
@endsection
@section('after_styles')
    <link href="{{ asset('vender/select2/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vender/select2/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('static/backend/css/chathub/response_element/response_element.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<style>
    .modal-backdrop {
        position: relative !important;
    }
</style>

<!--begin::Portlet-->
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-open-box"></i>
                </span>
                <h2 class="m-portlet__head-text">
                    @lang('chathub::response_element.index.ADD_RESPONSE_ELEMENT')
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
        </div>
    </div>
    <form id="form">
        {!! csrf_field() !!}
        <div class="m-portlet__body" id="autotable">
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_element.index.TITLE'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="title" class="form-control m-input" id="title">
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_element.index.SUB_TITLE'):
                    </label>
                    <input type="text" name="subtitle" class="form-control m-input" id="subtitle">
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_element.index.IMAGE'):<b class="text-danger"></b>
                    </label>
                    <input type="file" accept=".png, .jpg, .jpeg" name="getFile" class="form-control m-input" id="getFile"  style="display:none"  onchange="uploadImage(this);">
                    
                    <span class="error-name"></span>
                </div>
                <div class="form-group m-form__group m-widget19">
                    <div class="m-widget19__pic">
                        <div class="wrap-imge avatar-temp">
                            <img class="m--bg-metal m-image" id="blah-add"
                                 src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                 alt="{{__('Hình ảnh')}}"
                                 onclick="document.getElementById('getFile').click()" height="100px"> 
                            <span class="s-delete-img d-none">
                                <span href="javascript:void(0)"
                                    onclick="deleteAvatar()">
                                    <i class="la la-close"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_element.index.BUTTON'):<b class="text-danger"></b>
                    </label>
                    <select name="button[]" class="form-control m-input select2" id="response_button" multiple>
                        @if (isset($response_button))
                            @foreach ($response_button as $item)
                                <option value="{{$item['response_button_id']}}">{{$item['title']}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="error-name"></span>
                </div>
            </div>
            
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('chathub.response_element')}}" class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                        <span>
                            <i class="la la-arrow-left"></i>
                            <span>@lang('chathub::response_element.index.CANCEL')</span>
                        </span>
                    </a>
                    <button type="button" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10" onclick="response_element.Add()">
                        <span>
                        <i class="la la-check"></i>
                        <span>@lang('chathub::response_element.index.SAVE')</span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md btn-add m--margin-left-10" onclick="response_element.AddNew()">
                        <span>
                            <i class="fa fa-plus-circle"></i>
                            <span>@lang('chathub::response_element.index.SAVE_NEW')</span>
                        </span>
                    </button>

                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('after_script')
    <script src="{{asset('static/backend/js/chathub/response_element/add.js?v='.time())}}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function($) {
            // trigger select2 for each untriggered select2_multiple box
            $('.select2').each(function (i, obj) {
                if (!$(obj).data("select2"))
                {
                    $(obj).select2();
                }
            });
        });
    </script>
    <script type="text/template" id="image-avatar-temp">
        <img class="m--bg-metal m-image" id="blah-add"
                src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                alt="{{__('Hình ảnh')}}"
                onclick="document.getElementById('getFile').click()" height="100px"> 
        <span class="s-delete-img d-none">
            <span href="javascript:void(0)"
                onclick="deleteAvatar()">
                <i class="la la-close"></i>
            </span>
        </span>
    </script>
@stop