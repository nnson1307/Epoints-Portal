@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ NGƯỜI QUAN TÂM OA') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('QUẢN LÝ NGƯỜI QUAN TÂM OA') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="d-flex m-portlet__head">
            <ul class="nav nav-tabs m-0 align-items-center justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('zns.customer-care')}}">{{__('NGƯỜI QUAN TÂM')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('zns.customer-care-tag')}}">{{__('QUẢN LÝ NHÃN')}}</a>
                </li>
            </ul>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background m--margin-bottom-30">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <div class="m-form__group">
                                <div class="input-group">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control" name="search"
                                        placeholder="{{ __('Nhập số điện thoại hoặc thông tin hiển thị') }}" value="{{ isset($params['search']) && $params['search'] ? $params['search'] : '' }}">
                                        <input type="hidden" name="page"
                                        value="{{ isset($params['page']) && $params['page'] ? $params['page'] : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <select name="zalo_customer_tag_id" class="form-control select2 select2-active-choose-first" id="">
                                <option value="">@lang('Chọn nhãn')</option>
                                @foreach ($tag_list as $key => $value)
                                    <option value="{{$key}}"{{ (isset($params['zalo_customer_tag_id']) && $params['zalo_customer_tag_id'] == $key ) ? 'selected': '' }}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="d-flex">
                                <a  href="{{route('zns.customer-care')}}" class="btn ss--button-cms-piospa m-btn--icon mr-3">
                                    {{ __('XÓA BỘ LỌC') }}
                                    <i class="fa fa-eraser" aria-hidden="true"></i>
                                </a>
                                <button class="btn btn-primary color_button btn-search" style="display: block">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                                <a href="javascript:void(0)" class="btn btn-primary color_button ml-3" style="display: block" onclick="CustomerCare.synchronized()">
                                    <i class="fa-cloud-download" aria-hidden="true"></i>@lang('ĐỒNG BỘ')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content">
                @include('zns::customer_care.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <div class="modal fade" id="edit-customer" role="dialog"></div>
    <div id="my-modal"></div>
    <div id="show-modal"></div>
@stop
@section('after_script')
{{--    <script src="{{ asset('static/backend/js/zns/customer_care/list.js?v=' . time()) }}" type="text/javascript"></script>--}}
{{--    <script src="{{asset('static/backend/js/zns/customer_care/script.js?v='.time())}}"--}}
{{--            type="text/javascript"></script>--}}
{{--    <script src="{{asset('static/backend/js/zns/customer_care/list-calendar.js?v='.time())}}"--}}
{{--            type="text/javascript"></script>--}}
@stop
