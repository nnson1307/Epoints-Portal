@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> @lang('chathub::brand.index.BRAND')</span>
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
                                @lang('chathub::brand.index.BRAND')
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{route('chathub.brand.add')}}" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> @lang('chathub::brand.index.ADD_BRAND')</span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Search Form -->
                    <form class="frmFilter ss--background m--margin-bottom-30" action="{{route('chathub.brand')}}" method="GET" >
                        <div class="ss--bao-filter">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="brand_name"
                                                   placeholder="@lang('chathub::brand.index.SEARCH_NAME')">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="entities"
                                                   placeholder="@lang('chathub::brand.index.SEARCH_ENTITIES')">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <select class="form-control" style="width: 100%" name="chathub_brand$brand_status" placeholder="@lang('chathub::brand.index.STATUS')">
                                                <option value="">@lang('chathub::brand.index.SELECT_STATUS')</option>
                                                <option value="1">@lang('chathub::brand.index.ACTIVE')</option>
                                                <option value="0">@lang('chathub::brand.index.UNACTIVE')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2 form-group">
                                    <button type="submit"
                                            class="btn ss--btn-search">
                                            @lang('chathub::brand.index.SEARCH')
                                        <i class="fa fa-search ss--icon-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end: Search Form -->

                    <div class="table-content">
                        @include('chathub::brand.list')
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>

    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/brand/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/chathub/brand/script.js?v='.time())}}" type="text/javascript"></script>
@stop