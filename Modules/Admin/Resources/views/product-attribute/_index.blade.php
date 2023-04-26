@extends('layout')
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }
    </style>
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
                                <span><i class="la 	la-mars m--margin-right-5"></i> {{__('THUỘC TÍNH SẢN PHẨM')}} </span>
                            </h2>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="javascript:void(0)"
                           data-toggle="modal"
                           data-target="#modalAdd"
                           onclick="productAttribute.clearModalAdd()"
                           class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                        <span>
						    <i class="fa flaticon-plus"></i>
							<span> {{__('Thêm thuộc tính')}}</span>
                        </span>
                        </a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <form class="frmFilter">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <input type="hidden" name="key" value="label">
                                        <button class="btn btn-primary btn-search" style="display: none">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="text" class="form-control" name="search_keyword"
                                               placeholder="{{__('Nhập tên hoặc mã thuộc tính')}}">
                                        <div class="input-group-append">
                                            <a href="javascript:void(0)" onclick="productAttribute.refresh()"
                                               class="btn btn-primary m-btn--icon">
                                                <i class="la la-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-form m-form--label-align-right m--margin-bottom-20">
                            @include('helpers.filter')
                        </div>
                    </form>
                    <div class="table-content">
                        @include('admin::product-attribute.list')
                    </div><!-- end table-content -->
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            @include('admin::product-attribute.add')
        </div>
    </div>
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            @include('admin::product-attribute.edit')
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/product-attribute/list.js')}}" type="text/javascript"></script>
    {{--<script>--}}
    {{--$(document).ready(function () {--}}
        {{--$('.select2-22').select2();--}}
    {{--})--}}
    {{--</script>--}}
@stop



