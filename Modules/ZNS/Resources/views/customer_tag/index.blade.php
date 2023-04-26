@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ NGƯỜI QUAN TÂM OA') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
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
                    <a class="nav-link" href="{{route('zns.customer-care')}}">{{__('NGƯỜI QUAN TÂM')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('zns.customer-care-tag')}}">{{__('QUẢN LÝ NHÃN')}}</a>
                </li>
            </ul>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background m--margin-bottom-30">
                <input type="hidden" name="page"
                       value="{{ isset($params['page']) && $params['page'] ? $params['page'] : '' }}">
            </form>
            <div class="table-content">
                @include('zns::customer_tag.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/zns/customer_tag/list.js?v=' . time()) }}" type="text/javascript"></script>
@stop
