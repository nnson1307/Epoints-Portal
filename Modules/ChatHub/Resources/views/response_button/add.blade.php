@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">@lang('@lang('chathub::response_button.index.RESPONSE_BUTTON')')</span>
@endsection
@section("after_style")
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
                    @lang('chathub::response_button.index.ADD_RESPONSE_BUTTON')
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
                        @lang('chathub::response_button.index.TITLE'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="title" class="form-control m-input" id="title">
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_button.index.TYPE'):
                    </label>
                    <div class="input-group">
                        <select class="form-control" onchange="MyDisabled()" style="width: 100%" id="type" name="type">
                            <option value="postback">@lang('chathub::response_button.index.POSTBACK')</option>
                            <option value="web_url">@lang('chathub::response_button.index.WEB_URL')</option>
                        </select>
                    </div>
                </div>
            </div> 
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_button.index.URL'):<b class="text-danger"></b>
                    </label>
                    <input type="text" name="url" class="form-control m-input" id="url" disabled>
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::response_button.index.PAYLOAD'):<b class="text-danger"></b>
                    </label>
                    <input type="text" name="payload" class="form-control m-input" id="payload">
                    <span class="error-name"></span>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('chathub.response_button')}}" class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                        <span>
                            <i class="la la-arrow-left"></i>
                            <span>@lang('chathub::response_button.index.CANCEL')</span>
                        </span>
                    </a>
                    <button type="button" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10" onclick="response_button.Add()">
                        <span>
                        <i class="la la-check"></i>
                        <span>@lang('chathub::response_button.index.SAVE')</span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md btn-add m--margin-left-10" onclick="response_button.AddNew()">
                        <span>
                            <i class="fa fa-plus-circle"></i>
                            <span>@lang('chathub::response_button.index.SAVE_NEW')</span>
                        </span>
                    </button>

                </div>
            </div>
        </div>
    </form>
</div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/response_button/add.js?v='.time())}}" type="text/javascript"></script>
    <script>
        function MyDisabled(){
            if($('#url').attr('disabled')){
                $('#payload').val('');
                $("#url").attr("disabled", false);
                $("#payload").attr("disabled", true);
            }else{
                $('#url').val('');
                $("#url").attr("disabled", true);
                $("#payload").attr("disabled", false);
            }
        }
    </script>
@stop