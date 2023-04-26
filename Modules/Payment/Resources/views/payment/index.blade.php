@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('DANH SÁCH PHIẾU CHI')}}
    </span>
@endsection
@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"></i> {{__('DANH SÁCH PHIẾU CHI')}}</span>
                    </h2>
                    <h3 class="m-portlet__head-text">

                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <form action="{{route('payment.export-excel')}}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" id="search_export" name="search_export">
                    <input type="hidden" id="branch_code_export" name="branch_code_export">
                    <input type="hidden" id="status_export" name="status_export">
                    <input type="hidden" id="created_at_export" name="created_at_export">
                    <input type="hidden" id="created_by_export" name="created_by_export">

                    <button type="submit"
                            class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-10">
                    <span>
                        <i class="la la-files-o"></i>
                        <span> {{__('EXPORT')}}</span>
                    </span>
                    </button>
                </form>

                <a href="javascript:void(0)"
                   data-toggle="modal"
                   data-target="#add"
                   class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM PHIẾU CHI')}}</span>
                        </span>
                </a>
                <a href="javascript:void(0)"
                   data-toggle="modal"
                   data-target="#add"
                   class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                        color_button btn_add_mobile"
                   style="display: none">
                    <i class="fa fa-plus-circle" style="color: #fff"></i>
                </a>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-12 form-group row">
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="{{__('Nhập tên người chi hoặc mã phiếu')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select class="form-control m-input select2" name="branch_code">
                            <option value="" selected="selected">{{__('Chọn chi nhánh')}}</option>
                            @foreach($BRANCH as $v)
                                <option value="{{$v['branch_code']}}">{{$v['branch_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select class="form-control m-input select2" name="status">
                            <option value="" selected="selected">{{__('Chọn trạng thái')}}</option>
                            <option value="new">{{__('Mới')}}</option>
                            <option value="approved">{{__('Đã xác nhận')}}</option>
                            <option value="paid">{{__('Đã chi')}}</option>
                            <option value="unpaid">{{__('Đã huỷ chi')}}</option>
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input type="text"
                                   class="form-control m-input daterange-picker" id="created_at"
                                   name="created_at"
                                   autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select class="form-control m-input select2" name="created_by">
                            <option value="" selected="selected">{{__('Chọn người tạo')}}</option>
                            @foreach($STAFF as $v)
                                <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary btn-search color_button" onclick="payment.searchList()">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
                {{--                @include('helpers.filter')--}}
            </form>
            @include('payment::payment.add')
            <div id="modal-edit-payment"></div>
            <div id="modal-detail-payment"></div>
            <form id="form-print-bill" target="_blank" action="{{route('payment.print-bill')}}" method="GET">
                <input type="hidden" name="print_payment_id" id="payment_id" value="">
            </form>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>Success!</strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content m--padding-top-15">
                @include('payment::payment.list')
            </div><!-- end table-content -->

        </div>
    </div>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>

        $('.select2').select2();
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/payment/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        @if(isset($param['referral']))
            payment.popupEdit({{$param['payment_id']}},false,{{$param['referral']}})
        @endif
    </script>
@stop
