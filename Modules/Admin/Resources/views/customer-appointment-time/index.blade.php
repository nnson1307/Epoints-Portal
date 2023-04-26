@extends('layout')
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

    </style>

    <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <div class="m-input-icon m-portlet__head-icon">
                    </div>
                    <h3 class="m-portlet__head-text">
                    </h3>
                    <h2 style=" white-space:nowrap"
                        class="m-portlet__head-label m-portlet__head-label--primary">
                        <span>{{__('DANH SÁCH KHUNG GIỜ HẸN')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)"
                   data-toggle="modal"
                   data-target="#add"
                   class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                        <span>
						    <i class="fa flaticon-plus"></i>
							<span> {{__('Thêm khung giờ hẹn')}}</span>
                        </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="m--margin-bottom-5 frmFilter">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="time">
                                <button class="btn btn-primary btn-search" style="display: none">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập khung giờ hẹn')}}">
                                <div class="input-group-append">
                                    <a href="javascript:void(0)" onclick="customer_appointment_time.refresh()"
                                       class="btn btn-primary m-btn--icon">
                                        <i class="la la-refresh"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-form m-form--label-align-right">
                    @include('helpers.filter')
                </div>
            </form>


            @include('admin::customer-appointment-time.add')
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>Success : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content">
                @include('admin::customer-appointment-time.list')
            </div><!-- end table-content -->

        </div>
    </div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/customer-appointment-time/script.js?v='.time())}}"
            type="text/javascript"></script>

@stop
