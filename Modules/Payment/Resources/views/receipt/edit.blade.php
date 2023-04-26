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
                        @lang('CHỈNH SỬA PHIẾU THU')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-edit">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Loại phiếu thu'):<b class="text-danger"> *</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="receipt_type_code" name="receipt_type_code"
                                        style="width:100%;">
                                    <option></option>
                                    @foreach($optionReceiptType as $v)
                                        <option value="{{$v['receipt_type_code']}}"
                                                {{$v['receipt_type_code'] == $receiptInfo['receipt_type_code'] ? 'selected' : ''}}>
                                            {{$v['receipt_type_name']}}</option>
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
                                        <option value="{{$v['object_accounting_type_code']}}"
                                                {{$v['object_accounting_type_code'] == $receiptInfo['object_accounting_type_code'] ? 'selected' : ''}}>
                                            {{$v['object_accounting_type_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($receiptInfo['object_accounting_type_code'] == 'OAT_OTHER' || $receiptInfo['object_accounting_type_code'] == 'OAT_SHIPPER')
                            <div class="form-group m-form__group div_add_name" style="display:block;">
                                <label class="black_title">
                                    @lang('Nhập tên người trả tiền'):<b class="text-danger"> *</b>
                                </label>
                                <input type="text" class="form-control m-input"
                                       value="{{$receiptInfo['object_accounting_name']}}"
                                       id="object_accounting_name" name="object_accounting_name"
                                       placeholder="@lang('Nhập tên đối tượng')">
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
                        @else
                            <div class="form-group m-form__group div_add_name" style="display:none;">
                                <label class="black_title">
                                    @lang('Nhập tên người trả tiền'):<b class="text-danger"> *</b>
                                </label>
                                <input type="text" class="form-control m-input"
                                       id="object_accounting_name" name="object_accounting_name"
                                       placeholder="@lang('Nhập tên đối tượng')">
                                <span class="err error-obj-acc-name"></span>
                            </div>
                            <div class="form-group m-form__group div_add_id" style="display:block;">
                                <label class="black_title">
                                    @lang('Chọn người trả tiền'):<b class="text-danger"> *</b>
                                </label>
                                <select class="form-control" id="object_accounting_id" name="object_accounting_id"
                                        style="width:100%;">
                                    <option></option>
                                </select>
                                <span class="err error-obj-acc-id"></span>
                            </div>
                        @endif
                        <input type="hidden" id="obj_acc_hidden" value="{{$receiptInfo['object_accounting_id']}}">
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số tiền'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input format-money"
                                   id="money" name="money" value="{{$receiptInfo['amount']}}"
                                   placeholder="@lang('Nhập số tiền')">
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
                                            <option value="{{$v['payment_method_code']}}"
                                                    {{$v['payment_method_code'] == $receiptInfo['payment_method_code'] ? 'selected' : ''}}>
                                                {{$v['payment_method_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 div_payment_online"
                                     style="display: {{in_array($receiptInfo['payment_method_code'], ['VNPAY']) ? 'block':'none'}};">
                                    <button type="button" onclick='edit.save("{{$receiptInfo['receipt_id']}}", false, 1)'
                                            class="btn btn-primary m-btn m-btn--custom color_button">
                                        @lang('TẠO QR')
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nội dung thu'):
                            </label>
                            <input type="text" class="form-control m-input" value="{{$receiptInfo['note']}}"
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
                    <button type="button" onclick="edit.save('{{$receiptInfo['receipt_id']}}', false)"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                    @if ($receiptInfo['status'] == 'unpaid')
                        <button type="button" onclick="edit.save('{{$receiptInfo['receipt_id']}}', true)"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-reorder"></i>
                                <span>@lang('LƯU & THANH TOÁN')</span>
                        </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
        edit._init();
    </script>

@endsection
