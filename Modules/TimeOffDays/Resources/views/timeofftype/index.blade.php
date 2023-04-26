@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CẤU HÌNH QUẢN LÝ ĐƠN PHÉP')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

        .select2 {
            width: 100% !important;
        }

    </style>

    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("CẤU HÌNH QUẢN LÝ ĐƠN PHÉP")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
               
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">

                </form>
                <div class="table-content m--padding-top-30">
                    @include('timeoffdays::timeofftype.list')
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
@stop
@section('after_script')
@include('ticket::language.lang')
    <script src="{{asset('static/backend/js/timeofftype/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        timeofftype._init();

        $(".m_selectpicker").select2({
            width: "100%"
        });
    </script>    
@stop