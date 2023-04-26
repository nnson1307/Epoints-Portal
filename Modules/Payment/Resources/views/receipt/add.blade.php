@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ PHIẾU THU')</span>
@stop
@section('content')
    <style>
        .err {
            color: red;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('THÊM PHIẾU THU')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-create">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Loại phiếu thu'):<b class="text-danger"> *</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="receipt_type_code" name="receipt_type_code"
                                        style="width:100%;">
                                    @foreach($optionReceiptType as $v)
                                        <option value="{{$v['receipt_type_code']}}">{{$v['receipt_type_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thông tin người trả tiền'):<b class="text-danger"> *</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="object_accounting_type_code"
                                        name="object_accounting_type_code"
                                        style="width:100%;" onchange="view.changeType(this)">
                                    <option></option>
                                    @foreach($optionObjAccType as $v)
                                        <option value="{{$v['object_accounting_type_code']}}">{{$v['object_accounting_type_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group div_add_name" style="display:none;">
                            <label class="black_title">
                                @lang('Nhập tên người trả tiền'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="object_accounting_name" name="object_accounting_name"
                                   placeholder="@lang('Nhập tên người trả tiền')">
                            <span class="err error-obj-acc-name"></span>
                        </div>
                        <div class="form-group m-form__group div_add_id" style="display:none;">
                            <label class="black_title">
                                @lang('Chọn người trả tiền'):<b class="text-danger"> *</b>
                            </label>
                            <select class="form-control" id="object_accounting_id" name="object_accounting_id"
                                    style="width:100%;">
                                <option></option>
                            </select>
                            <span class="err error-obj-acc-id"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số tiền'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input format-money"
                                   id="money" name="money" placeholder="@lang('Nhập số tiền')">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Hình thức thanh toán'):<b class="text-danger"> *</b>
                            </label>
                            <div class="input-group row">
                                <div class="col-lg-9">
                                    <select class="form-control" id="payment_method" name="payment_method"
                                            style="width:100%;">
                                        <option></option>
                                        @foreach($optionPaymentMethod as $v)
                                            <option value="{{$v['payment_method_code']}}">
                                                {{$v['payment_method_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 div_payment_online" style="display: none;">
                                    <button type="button" onclick="create.save()" class="btn btn-primary m-btn m-btn--custom color_button">
                                        @lang('TẠO QR')
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nội dung thu'):
                            </label>
                            <input type="text" class="form-control m-input format-money"
                                   id="note" name="note" placeholder="@lang('Nhập nội dung thu')">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('receipt')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="create.save()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <form id="form-print-bill" target="_blank" action="{{route('receipt.print-bill')}}" method="GET">
        <input type="hidden" name="print_receipt_id" id="receipt_id" value="">
    </form>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/payment/receipt/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        create._init();
    </script>
@endsection
