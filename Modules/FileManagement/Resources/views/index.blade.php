@extends('layout')
@section('title_header')
<style>
      .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-aside-right, .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-wrapper {
            margin-bottom: 0px !important;
        }
        .m-body .m-content {
            padding-bottom: 0px;
        }
        .m-portlet .m-portlet__body {
            padding-bottom: 0px !important;
        }
        .td_vtc{
            vertical-align: middle !important;
        }
</style>
@endsection
@section('content')

    <div class="m-portlet">

        <div class="m-portlet__body p-0">
            <div class="row">
                <div class="col-12">
                    <iframe id="ifDocument" src="{{env('URL_MANAGE_FILE_PUBLIC')}}/file/verify?token={{$access_token}}&brand-code={{$brand_code}}" width="100%" style="border:0 !important;" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
<script>
    $(document).ready(function () {
        var height = $(this).height() - 120;
        $('#ifDocument').height(height);
    })
</script>
@stop
