@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CẤU HÌNH')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA THAM SỐ')
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-edit">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Tham số'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" class="form-control m-input" id="parameter_name" name="parameter_name" value="{{$item['parameter_name']}}">
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Nội dung'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" class="form-control m-input" id="content" name="content" value="{{$item['content']}}">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('config.customer-parameter')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="edit.save({{$item['config_customer_parameter_id']}})"
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
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/config/config-customer-parameter/script.js?v='.time())}}" type="text/javascript"></script>
@stop
