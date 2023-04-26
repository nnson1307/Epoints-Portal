@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">@lang('chathub::setting.index.CHATHUB')</span>
@endsection
@section("after_style")
@endsection
@section('content')
<style>
    .modal-backdrop {
        position: relative !important;
    }
</style>

<!--begin::Portlet-->
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-open-box"></i>
                </span>
                <h2 class="m-portlet__head-text">
                    @lang('chathub::setting.index.SETTING')
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
                <a href="javascript:void(0)"
                   class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                    <span>
                        <i class="fa fa-plus-circle icon-sz"></i>
                        <span onclick="channel.add()">@lang('chathub::setting.index.ADD_CHANNEL')</span>
                    </span>
                </a>
                <a href="" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
             color_button btn_add_mobile"
                   style="display: none">
                    <i class="fa fa-plus-circle" style="color: #fff"></i>
                </a>
        </div>
    </div>

    <div class="m-portlet__body" id="autotable">
        <div class="table-content m--padding-top-10">
            @include('chathub::setting.list')
        </div><!-- end table-content -->
    </div>
    <div id="add-channel"></div>
    <div id="modal-setting"></div>
</div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/setting/script.js?v='.time())}}" type="text/javascript"></script>
@stop