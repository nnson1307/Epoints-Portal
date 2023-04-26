@extends('layout')
@section('title_header')
    <span class="title_header">{{__('TẤT CẢ')}}</span>
@endsection
@section('content')
    <!-- menu  -->
    <div id="m-dashbroad">
        <div class="row">
            <div class="col-lg-12">
                <div class="m-portlet m-portlet--head-sm">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
{{--                                <input class="form-control" id="anythingSearch" type="text" placeholder="@lang('Tìm kiếm..')" onkeyup="menuAll.search(this)">--}}
                                <input class="form-control" id="anythingSearch" type="text" placeholder="@lang('Tìm kiếm..')" >
                                <a type="button" class="btn" onclick="menuAll.refresh()"><i class="la la-refresh" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="list-menu-all">
            @include('admin::menu-all.list')
        </div>
    </div>
    <!-- menu  -->
@endsection

@section('after_script')
    <script type="text/javascript" src="{{asset('static/backend/js/admin/menu-all/script.js?v='.time())}}"></script>
@stop