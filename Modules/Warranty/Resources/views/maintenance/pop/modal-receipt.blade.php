<div class="modal fade" role="dialog" id="modal-receipt" style="display: none">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    {{__('THANH TOÁN PHÍ BẢO TRÌ')}}
                </h4>
            </div>
            <div class="m-widget4  m-section__content" id="load">
                <form id="form-receipt">
                    <div class="modal-body">
                        <input type="hidden" id="maintenance_receipt_id">
                        <input type="hidden" id="maintenance_id" value="{{$info['maintenance_id']}}">

                        <div class="form-group m-form__group type_modal">
                            <label>{{__('Hình thức thanh toán')}}:<span style="color:red;font-weight:400">*</span></label>
                            <select class="form-control" id="receipt_type" name="receipt_type[]" multiple="multiple"
                                    style="width: 100%">
                                @foreach($optionPaymentMethod as $item)
                                    @if($item['payment_method_code'] != 'MEMBER_MONEY')
                                        <option value="{{$item['payment_method_code']}}">{{$item['payment_method_name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="error_type" style="color:#ff0000"></span>
                        </div>

                        <div class="form-group m-form__group payment_method">
                            <div class="row mt-3 method payment_method_CASH">
                                <label class="col-lg-4 font-13">@lang('Tiền mặt'):<span
                                            style="color:red;font-weight:400">*</span></label>
                                <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                                    <input onkeyup="receipt.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input" placeholder="{{__('Nhập giá tiền')}}"
                                           name="payment_method" id="payment_method_CASH" value="{{$info['total_amount_pay'] - $totalReceipt}}">
                                    <div class="input-group-append">
                                    <span class="input-group-text">{{__('VNĐ')}}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <span class="error_receipt" style="color:#ff0000"></span>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40  font-13">{{__('Tiền phải thanh toán')}}:</label>
                            <div class="col-lg-9 w-me-40 ">
                                <span class="font-13 font-weight-bold cl_receipt_amount"
                                      style="float: right;color: red">{{number_format(($info['total_amount_pay'] - $totalReceipt), isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                                <input type="hidden" class="form-control m--font-bolder" disabled="disabled"
                                       name="receipt_amount" id="receipt_amount"
                                       value="{{($info['total_amount_pay'] - $totalReceipt)}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-13">{{__('Tổng tiền trả')}}:</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-13 font-weight-bold cl_amount_all"
                                      style="float: right;">{{number_format(($info['total_amount_pay'] - $totalReceipt), isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                                <input type="hidden" class="form-control m--font-info" disabled="disabled"
                                       name="amount_all" id="amount_all"
                                       value="{{($info['total_amount_pay'] - $totalReceipt)}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-13">{{__('Còn nợ')}}:</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-13 font-weight-bold cl_amount_rest" style="float: right;">0</span>
                                <input type="hidden" class="form-control  m--font-danger" disabled="disabled"
                                       name="amount_rest" id="amount_rest" value="0">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-13">{{__('Trả lại khách')}}:</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-13 font-weight-bold cl_amount_return" style="float: right;">0</span>
                                <input type="hidden" class="form-control m--font-info" disabled="disabled"
                                       name="amount_return" id="amount_return" value="0">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>{{__('Ghi chú')}}</label>
                            <textarea class="form-control" id="note" name="note" cols="5" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn"
                                data-dismiss="modal">
                            <span><i class="la la-arrow-left"></i><span>{{__('HỦY')}}</span></span>
                        </button>
{{--                        <button type="button" onclick="index.submit_receipt_bill('{{$itemReceipt['customer_debt_id']}}')"--}}
{{--                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10"><span>{{__('THANH TOÁN & IN HÓA ĐƠN')}}</span></button>--}}
                        <button type="button" onclick="receipt.submitReceipt('{{$info['maintenance_id']}}', '{{$info['customer_id']}}')"
                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10"><span>{{__('THANH TOÁN')}}</span></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
