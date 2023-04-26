@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ NHÓM')</span>
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
                        @lang('TẠO CÔNG TY')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <div class="m-portlet__body">
            <form id="form-register">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Tên công ty'):<b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control m-input"
                               id="company_name" name="company_name"
                               placeholder="@lang('Nhập tên công ty')">
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Mô tả'):
                    </label>
                    <div class="input-group">
                        <textarea class="form-control m-input" id="description" name="description" cols="5"></textarea>
                    </div>
                </div>
            </form>

            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('team.company')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="create.save()"
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
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/team/company/script.js?v='.time())}}"
            type="text/javascript"></script>
@stop


