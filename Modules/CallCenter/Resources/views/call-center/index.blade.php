@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@stop
@section('content')
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list-1"></i>
                </span>
                <h2 class="m-portlet__head-text">
                    @lang("Tiếp nhận yêu cầu")
                </h2>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <a href="javascript:void(0)" onclick="callCenter.showModalSearchCustomer()" class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button">
                <span>
                    <i class="fa fa-plus-circle"></i>
                    <span> @lang("Tiếp nhận yêu cầu")</span>
                </span>
            </a>
        </div>

    </div>
    <div class="m-portlet__body">
        <div id="autotable">
            <form class="frmFilter bg">
                <input type="hidden" id="isValid" name="isValid" value="0">
                <div class="row padding_row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" placeholder="{{ __('Nhập tên, mã hoặc số điện thoại...') }}">
                        </div>
                        
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input readonly class="form-control m-input daterange-picker"
                                   style="background-color: #fff" name="created_at"
                                   autocomplete="off" placeholder="{{ __('Ngày tiếp nhận') }}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary color_button btn-search">
                            SEARCH <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                @include('call-center::call-center.list')
            </div>
        </div>
       
    </div>
</div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
<script src="{{asset('static/backend/js/customer-lead/customer-lead/script.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/call-center/list.js?v='.time())}}" type="text/javascript"></script>

@stop

