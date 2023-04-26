@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Button điều hướng
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">Button điều hướng </li>
        </ol>
    </section>
    <style>
        .dataTables_filter, .dataTables_paginate {
            text-align: right;
        }

        #crudTable tr th, #crudTable tr td {vertical-align: middle; text-align: center}
        .pagination {
            display: inline-block;
            padding-left: 30px;
            margin: 20px 0;
            border-radius: 4px;
        }
        #crudTable tr th, #crudTable tr td {vertical-align: middle; text-align: center}
    </style>
@endsection


@section('content')
    <!-- Default box -->
    <div class="box">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="box-header with-border">

            <a href="{{route('button.add')}}" class="btn btn-primary ladda-button" data-style="zoom-in">
                <span class="ladda-label"><i class="fa fa-plus"></i> Add button</span>
            </a>&nbsp;&nbsp;&nbsp;
        </div>
        <div class="box-body">
            <form action="{{route('button')}}" method="GET" class="m-form m-form--fit m--margin-bottom-20">
                <div class="row m--margin-bottom-20">
                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <input type="text" class="form-control m-input data_value" name="data_value" placeholder="Nhập nội dung cần tìm" data-col-index="0" value="{{ isset($_GET['data_value']) ? $_GET['data_value'] : '' }}">
                    </div>
                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <select class="form-control select" id="data_type" name="data_type" style="width:100%;">
                            <option value="">--Type--</option>
                            <option value="postback" @if(isset($filter['data_type']) && $filter['data_type'] == 'postback') selected @endif>
                                Postback
                            </option>
                            <option value="web_url" @if(isset($filter['data_type']) && $filter['data_type'] == 'web_url') selected @endif>
                                Web_url
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                        <button type="submit" class="btn btn-success m-btn m-btn--icon" id="m_search">
                            <span>
                                <i class="la la-search"></i>
                                <span>Tìm</span>
                            </span>
                        </button>
                        <a class="btn btn-warning" href="{{route('button')}}"><i class="fa fa-ban"></i> Xóa tìm kiếm</a>
                    </div>
                </div>
            </form>
            <div class="table-responsive no-padding">
                <table id="crudTable" class="table table-hover" style="max-width: 100%; width: 100%">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Payload</th>
                        <th>Url</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($object->count())
                        @foreach ($object as $index => $item)
                            <tr>
                                <td align="center">{{$item['response_button_id']}}</td>
                                <td>{{$item['title']}}</td>
                                <td>{{$item['type']}}</td>
                                <td>{{$item['payload']}}</td>
                                <td>
                                    <a href="{{$item['url']}}">{{$item['url']}}</a>
                                </td>
                                <td>
                                    <a href="{{route('button.edit',['id'=>$item['response_button_id']])}}" class="btn btn-xs btn-default"><i class="fa fa-eye" aria-hidden="true"></i> Edit </a>
                                    <a onclick="myDelete(this,'{{$item['response_button_id']}}')" href="javascript:void(0)" class="btn btn-xs btn-default"><i class="fa fa-eye" aria-hidden="true"></i> Destroy </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="17" align="center">Tạm thời chưa có dữ liệu.</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Payload</th>
                            <th>Url</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div><!-- /.box-body -->
        @include('admin.inc.paging')
    </div><!-- /.box -->

    <div id="love-week" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"></div>

    <style>
        @media (min-width: 768px) {
            .modal-dialog {
                width: 750px !important;
            }
        }
    </style>

@endsection

@section('after_scripts')
    <script type="text/javascript" src="{{ asset('vendor/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/admin/js/main/user-campaign.js?v='.time()) }}"></script>

    <script>
        
        function myDelete(obj, id) {
            if (confirm("Are you sure you want to delete this item?") == true) {
                console.log('1');
                $.ajax({
                    url: laroute.route('button.remove'),
                    data: {
                        key: id
                    },
                    method: 'POST',
                    success: function(result) {
                        // Show an alert with the result
                        new PNotify({
                            title: "Item Deleted",
                            text: "The item has been deleted successfully.",
                            type: "success"
                        });
                        // delete the row from the table
                        $(obj).closest('tr').remove();
                    },
                    error: function(result) {
                        // Show an alert with the result
                        new PNotify({
                            title: "NOT deleted",
                            text: "There&#039;s been an error. Your item might not have been deleted.",
                            type: "warning"
                        });
                    }
                });
            } else {
                new PNotify({
                    title: "Not deleted",
                    text: "Nothing happened. Your item is safe.",
                    type: "info"
                });
            }
        }



    </script>
@endsection
