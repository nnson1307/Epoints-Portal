@extends('layout')
@section('title_header')
    <span class="title_header"> {{__('Báo cáo')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
    <style>
        .modal .select2.select2-container,.select2-search__field{
            width: 100% !important;
        }
        .nav-tabs .nav-item:hover , .sort:hover {
            cursor: pointer;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link.active {
            color:#6f727d;
            border-bottom: #6f727d;
            background: #EEF3F9;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link {
            padding: 15px;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
        .btn {
            font-family: "Roboto" !important;
        }
        .sort{
            border: 0;
            background: 0;
        }

        a {
            color:#6f727d;
        }

        a:hover {
            color:#6f727d;
            text-decoration: unset;
        }



    </style>
@endsection
@section('content')
    {{--    Tổng quan công việc--}}
    <div class="m-portlet" id="autotable_chart">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text text-uppercase">
                        {{__('Tổng quan công việc')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <div class="m-portlet__body">

        </div>
    </div>

    {{--    Tiến độ công việc--}}
    <div class="m-portlet" id="autotable_list">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text text-uppercase">
                        {{__('Tiến độ công việc')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools" id="accordion">

            </div>
        </div>

        <div class="m-portlet__body ">
            <div class="col-12">
                <iframe src="http://epoints-file-manager.local/file/verify?token=sondang" allowfullscreen width="100%" height="600"></iframe>
            </div>
        </div>
    </div>


@stop
@section('after_script')
{{--    <script>--}}
{{--        $(document).ready(function (){--}}
{{--            $('iframe').attr('src','http://file-stag.epoints.vn/file/verify?token=sondang');--}}
{{--        })--}}
{{--    </script>--}}
@stop
