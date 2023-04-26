@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> @lang('chathub::sub_brand.index.SUB_BRAND')</span>
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
                                @lang('chathub::sub_brand.index.SUB_BRAND')
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{route('chathub.sub_brand.add')}}" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> @lang('chathub::sub_brand.index.ADD_SUB_BRAND')</span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Search Form -->
                    <form class="frmFilter ss--background m--margin-bottom-30" action="{{route('chathub.sub_brand')}}" method="GET" >
                        <div class="ss--bao-filter">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="sub_brand_name"
                                                   placeholder="@lang('chathub::sub_brand.index.SEARCH_NAME')">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="entities"
                                                   placeholder="@lang('chathub::sub_brand.index.SEARCH_ENTITIES')">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <select class="form-control" style="width: 100%" name="chathub_sub_brand$sub_brand_status" placeholder="@lang('chathub::sub_brand.index.STATUS')">
                                                <option value="">@lang('chathub::sub_brand.index.SELECT_STATUS')</option>
                                                <option value="1">@lang('chathub::sub_brand.index.ACTIVE')</option>
                                                <option value="0">@lang('chathub::sub_brand.index.UNACTIVE')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2 form-group">
                                    <button type="submit"
                                            class="btn ss--btn-search">
                                            @lang('chathub::sub_brand.index.SEARCH')
                                        <i class="fa fa-search ss--icon-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end: Search Form -->

                    <div class="table-content">
                        @include('chathub::sub_brand.list')
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>

    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/sub_brand/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/chathub/sub_brand/script.js?v='.time())}}" type="text/javascript"></script>
@stop