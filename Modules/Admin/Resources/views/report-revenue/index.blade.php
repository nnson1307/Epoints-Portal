@extends('layout')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi" id="autotable">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <div class="m-input-icon m-portlet__head-icon">
                            </div>
                            <h3 class="m-portlet__head-text">
                            </h3>
                            <h2 style=" white-space:nowrap"
                                class="m-portlet__head-label m-portlet__head-label--primary">
                                <span><i class="la la-indent m--margin-right-5"></i> {{__('BÁO CÁO DOANH THU')}} </span>
                            </h2>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="javascript:void(0)"
                           data-toggle="modal"
                           data-target="#modalAdd"
                           onclick="OrderSource.clearAdd()"
                           class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                        <span>
						    <i class="fa flaticon-plus"></i>
							<span> {{__('Thêm nguồn đơn hàng')}}</span>
                        </span>
                        </a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Search Form -->

                    <!--end: Search Form -->
                    <div class="table-content">
                        @include('admin::order-source.list')
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@stop
@section('after_script')

@stop
