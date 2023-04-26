@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> @lang('NỘI DUNG PHẢN HỒI')</span>
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
    <div class="row">
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
                                @lang('Nội dung phản hồi')
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{route('chathub.response-content.create')}}" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> @lang('Thêm nội dung phản hồi')</span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="m-portlet__body" id="autotable">
                    <!--begin: Search Form -->
                    <form class="frmFilter">
                        <div class="ss--bao-filter">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search"
                                                   placeholder="@lang('Tìm kiếm theo tiêu đề hoặc nội dụng')">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <button class="btn btn-primary btn-search color_button">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end: Search Form -->

                    <div class="table-content m--padding-top-15">
                        @include('chathub::response_content.list')
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/response_content/script.js?v='.time())}}" type="text/javascript"></script>
@stop