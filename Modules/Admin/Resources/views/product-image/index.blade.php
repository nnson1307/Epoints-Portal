@extends('layout')
@section('page_subheader')
    @include('components.subheader', ['title' => __('Hình sản phẩm')])
@stop
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
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                {{__('Danh sách ảnh sản phẩm')}}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" id="autotable">
                    <form class="m-form m-form--fit m-form--label-align-right frmFilter">
                        <div class="m-form m-form--label-align-right m--margin-bottom-30">
                            <div class="row align-items-center m--margin-bottom-10">
                                <div class="col-xl-6 order-2 order-xl-1">
                                    <div class="form-group m-form__group row align-items-center">
                                        <div class="input-group col-xs-10">
                                            <div class="input-group-append">
                                                <select class="form-control search-type" name="search_type">
                                                    <option value="name">{{__('Tên')}}</option>
                                                </select>
                                            </div>
                                            <input type="text" class="form-control" name="search_keyword"
                                                   placeholder="{{__('Nhập nội dung tìm kiếm')}}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 order-1 order-xl-2 m--align-right">
                                    <button class="btn m-btn--pill m-btn--air btn-primary" data-toggle="modal"
                                            data-target="#modalAdd">
                                        <i class="la la-plus-square"></i> {{__('Thêm hình sản phẩm')}}
                                    </button>
                                    <div class="m-separator m-separator--dashed d-xl-none"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-content">
                        @include('admin::product-image.list')
                    </div><!-- end table-content -->

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            @include('admin::product-image.add')
        </div>
    </div>
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            @include('admin::product-image.edit')
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/product-image/list.js')}}" type="text/javascript"></script>
@stop
