@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> @lang('chathub::response_button.index.RESPONSE_BUTTON')</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <style>
        .form-control-feedback {
            color: red;
        }
    </style>
    <div class="row" id="autotable">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                             <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                @lang('chathub::response_button.index.RESPONSE_BUTTON')
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{route('chathub.response_button.add')}}" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> @lang('chathub::response_button.index.ADD_RESPONSE_BUTTON')</span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Search Form -->
                    <form class="frmFilter ss--background m--margin-bottom-30" action="{{route('chathub.response_button')}}" method="GET" >
                        <div class="ss--bao-filter">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="response_button_name"
                                                   placeholder="@lang('chathub::response_button.index.SEARCH_TITLE')">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <select class="form-control" style="width: 100%" name="chathub_response_button$type" placeholder="@lang('chathub::response_button.index.SELECT_TYPE')">
                                                <option value="">@lang('chathub::response_button.index.SELECT_TYPE')</option>
                                                <option value="postback">@lang('chathub::response_button.index.POSTBACK')</option>
                                                <option value="web_url">@lang('chathub::response_button.index.WEB_URL')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group date" data-provide="datepicker">
                                            <input type="text" class="form-control" name="data_time" placeholder="@lang('chathub::response_button.index.DATE')" value="{{ isset($_GET['data_time']) ? $_GET['data_time'] : '' }}">
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <button type="submit"
                                            class="btn ss--btn-search">
                                            @lang('chathub::response_button.index.SEARCH')
                                        <i class="fa fa-search ss--icon-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end: Search Form -->

                    <div class="table-content">
                        @include('chathub::response_button.list')
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>

    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/response_button/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/chathub/response_button/script.js?v='.time())}}" type="text/javascript"></script>
@stop