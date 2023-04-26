@extends('layout')

@section('content')
    <p>
        This view is loaded from module: {!! config('admin.name') !!}
    </p>


    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Danh sách nhãn sản phẩm
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
                                                <option value="product_label_name">Tên nhãn</option>
                                                <option value="product_label_code">Mã</option>
                                                </select>
                                            </div>
                                            <input type="text" class="form-control" name="search_keyword"
                                                   placeholder="Nhập nội dung tìm kiếm">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                            <div  class="input-group-append" style="margin-left: 10px;">
                                                <a href="{{route('admin.product-label')}}" class="btn btn-primary">
                                                    <i class="fa fa-refresh"> Refresh</i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 order-1 order-xl-2 m--align-right">
                                    <a href="{{route('admin.product-label.add')}}" class="btn m-btn--pill m-btn--air btn-primary">
                                        <i class="la la-plus-square"></i> Thêm nhãn sản phẩm</a>
                                    <div class="m-separator m-separator--dashed d-xl-none"></div>
                                </div>
                            </div>

                            @include('helpers.filter')
                        </div>
                    </form>

                    <div class="table-content">
                        @include('admin::product-label.list')
                    </div><!-- end table-content -->

                </div>
            </div>
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/product-label/list.js')}}" type="text/javascript"></script>
@stop