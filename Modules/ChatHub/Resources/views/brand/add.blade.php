@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">@lang('chathub::brand.index.BRAND')</span>
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
                    @lang('chathub::brand.index.ADD_BRAND')
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
                        @lang('chathub::brand.index.NAME'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="brand_name" class="form-control m-input" id="brand_name">
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::brand.index.ENTITIES'):
                    </label>
                    <input type="text" name="entities" class="form-control m-input" id="entities">
                    <span class="error-name"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('chathub::brand.index.STATUS'):
                    </label>
                    <div class="input-group">
                        <select class="form-control" style="width: 100%" id="brand_status" name="brand_status">
                            <option value="1">@lang('chathub::brand.index.ACTIVE')</option>
                            <option value="0">@lang('chathub::brand.index.UNACTIVE')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('chathub.brand')}}" class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                        <span>
                            <i class="la la-arrow-left"></i>
                            <span>@lang('chathub::brand.index.CANCEL')</span>
                        </span>
                    </a>
                    <button type="button" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10" onclick="brand.Add()">
                        <span>
                        <i class="la la-check"></i>
                        <span>@lang('chathub::brand.index.SAVE')</span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md btn-add m--margin-left-10" onclick="brand.AddNew()">
                        <span>
                            <i class="fa fa-plus-circle"></i>
                            <span>@lang('chathub::brand.index.SAVE_NEW')</span>
                        </span>
                    </button>

                </div>
            </div>
        </div>
    </form>
</div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/brand/add.js?v='.time())}}" type="text/javascript"></script>
@stop