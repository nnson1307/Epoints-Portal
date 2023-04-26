@extends('layout')

@section('page_subheader')
    @include('components.subheader', ['title' => 'Đơn hàng'])
@stop

@section('content')
 <style>
     .modal-backdrop {
         position:relative; !important;
     }
 </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Danh sách trạng thái giao hàng
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
                                                    <option value="order_delivery_status_name">Tên trạng thái</option>

                                                </select>
                                            </div>
                                            <input type="text" class="form-control" name="search_keyword" placeholder="Nhập nội dung tìm kiếm">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 order-1 order-xl-2 m--align-right">
                                    <!-- <button type="button" class="btn m-btn--pill m-btn--air btn-primary"><i class="fa fa-file-excel-o"></i> Import excel</button>
                                    <button type="button" class="btn m-btn--pill m-btn--air btn-primary"><i class="fa fa-file-excel-o"></i> Export excel</button>  -->
                                    <a href="{{ route('order-delivery-status.add') }}" class="btn m-btn--pill m-btn--air btn-primary"><i class="la la-plus-square"></i>Thêm trạng thái giao hàng</a>
                                    <div class="m-separator m-separator--dashed d-xl-none"></div>
                                </div>
                            </div>

                            @include('helpers.filter')
                        </div>
                    </form>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible">
                            <strong>Success!</strong> {!! session('status') !!}.
                        </div>
                    @endif

                    <a href="{{route('order-delivery-status.delete-all')}}" class="btn btn-danger">Delete ALL</a>
                    <a data-toggle="modal" data-target="#myModal2"  class="btn btn-success">Export</a>
                    <a data-toggle="modal" data-target="#myModal1"  class="btn btn-success">Import</a>
                    <div id="myModal1" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                                </div>
                                <div class="modal-body">
                                    <form action="{{route('order-delivery-status.submit-import-excel')}}" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}" >
                                        <input class="btn btn-info" type="file" name="fileMuonImport">
                                        <input class="btn btn-success" type="submit" value="Import">

                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>


                        </div>
                    </div>


                    <div id="myModal2" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Import Excel</h4>
                                </div>

                                <div class="modal-body">


                                    {{--<form  id="formExport" method="GET"--}}{{--   //lam export kieu ajax ma ko duoc--}}
                                          {{--enctype="multipart/form-data">--}}

                                        {{--<div class="form-group m-form__group row">--}}
                                            {{--<div class="col-lg-3">--}}
                                                {{--<label><input type="checkbox" id="dat" name="checkboxes[]" value="order_delivery_status_id">STT</label>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-lg-3">--}}
                                                {{--<label><input type="checkbox" id="asd" name="checkboxes[]" value="order_delivery_status_name"><p>Tên trạng thái</p></label>--}}
                                            {{--</div>--}}

                                            {{--<div class="col-lg-3">--}}
                                                {{--<label><input type="checkbox" id="description" name="checkboxes[]" value="order_delivery_status_description"--}}
                                                    {{-->{{__('Ghi chú')}}</label>--}}
                                            {{--</div>--}}

                                            {{--<div class="col-lg-3">--}}
                                                {{--<label><input type="checkbox" id="detail" name="checkboxes[]" value="created_at">{{__('Ngày tạo')}}</label>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-lg-3">--}}
                                                {{--<label><input type="checkbox" id="is_active" name="checkboxes[]" value="is_active">Trạng--}}
                                                    {{--thái</label>--}}
                                            {{--</div>--}}

                                        {{--</div>--}}
                                        {{--<div class="modal-footer"></div>--}}
                                        {{--<div class="pull-right">--}}
                                            {{--<input class="btn btn-success" onclick="exportAjax()" type="button" value="Export">--}}
                                            {{--<input type="button" class="btn btn-danger" data-dismiss="modal" value="Close">--}}

                                        {{--</div>--}}
                                    {{--</form>--}}



                                    <form  action="{{route('order-delivery-status.export-excel')}}" id="formExport" method="GET" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                        <div class="form-group m-form__group row">
                                            <div class="col-lg-3">
                                                <label><input type="checkbox" id="dat" name="checkbox1" value="order_delivery_status_id,STT">STT</label>
                                            </div>
                                            <div class="col-lg-3">
                                                <label><input type="checkbox" id="asd" name="checkboxes2" value="order_delivery_status_name,TEN TRANG THAI GIAO"><p>Tên trạng thái</p></label>
                                            </div>

                                            <div class="col-lg-3">
                                                <label><input type="checkbox" id="description" name="checkboxes3" value="order_delivery_status_description,CHI TIET"
                                                    >{{__('Ghi chú')}}</label>
                                            </div>

                                            <div class="col-lg-3">
                                                <label><input type="checkbox" id="detail" name="checkboxes4" value="created_at,NGAY TAO">{{__('Ngày tạo')}}</label>
                                            </div>
                                            <div class="col-lg-3">
                                                <label><input type="checkbox" id="is_active" name="checkboxes5" value="is_active,TRANG THAI">Trạng
                                                    thái</label>
                                            </div>

                                        </div>
                                        <div class="modal-footer"></div>
                                        <div class="pull-right">
                                            <input class="btn btn-success"  type="submit" value="Export">
                                            <input type="button" class="btn btn-danger" data-dismiss="modal" value="Close">

                                        </div>
                                    </form>

                                </div>


                            </div>


                        </div>
                    </div>









                    <div class="btn-group">
                        <button type="button" class="btn btn-info">Export</button>
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu" id="export-menu">
                            <li id="export-to-excel"><a href="{{route('order-delivery-status.export-excel')}}">Export to Excel</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Other</a></li>
                        </ul>
                    </div>
                    <div class="table-content">
                        @include('admin::order-delivery-status.list')
                    </div><!-- end table-content -->

                </div>
            </div>
        </div>
    </div>

@stop

@section('after_script')

    <script src="{{asset('static/backend/js/admin/order-delivery-status/list.js?v='.time())}}" type="text/javascript"></script>

@stop
