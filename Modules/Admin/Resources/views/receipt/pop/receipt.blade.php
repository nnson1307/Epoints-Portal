<div class="modal fade" role="dialog" id="modal-receipt" style="display: none">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    {{__('THANH TOÁN CÔNG NỢ')}}
                </h4>
            </div>
            <div class="m-widget4  m-section__content" id="load">
                <form id="form-receipt">
                    <div class="modal-body">
                        <input type="hidden" id="customer_debt_id" value="{{$itemReceipt['customer_debt_id']}}">
                        <input type="hidden" id="debt_receipt_id">

                        <div class="form-group m-form__group type_modal">
                            <label class="font-15">{{__('Hình thức thanh toán')}}:<span style="color:red;font-weight:400">*</span></label>
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
                        @if(isset($branchMoney))
                            @if($branchMoney['balance'] > 0)
                                <input type="hidden" id="member_money" name="member_money" value="{{$branchMoney['balance']}}">
                            @endif
                        @endif

                        <div class="form-group m-form__group payment_method">
                            <div class="row mt-3 method payment_method_CASH" style="margin-bottom: 2rem">
                                <label class="col-lg-4 font-15">Tiền mặt:<span
                                            style="color:red;font-weight:400">*</span></label>
                                <div class="input-group input-group col-lg-6" style="height: 30px;">
                                    <input onkeyup="indexDebt.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input" placeholder="{{__('Nhập giá tiền')}}"
                                           aria-describedby="basic-addon1"
                                           name="payment_method" id="payment_method_CASH" value="{{$itemReceipt['amount'] - $itemReceipt['amount_paid']}}">
                                    <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <span class="error_receipt" style="color:#ff0000"></span>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40  font-15">{{__('Tiền phải thanh toán')}}:</label>
                            <div class="col-lg-9 w-me-40 ">
                                <span class="font-15 font-weight-bold cl_receipt_amount"
                                      style="float: right;color: red">{{number_format($itemReceipt['amount'] - $itemReceipt['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                                <input type="hidden" class="form-control m--font-bolder" disabled="disabled"
                                       name="receipt_amount" id="receipt_amount"
                                       value="{{($itemReceipt['amount'] - $itemReceipt['amount_paid'])}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-15">{{__('Tổng tiền trả')}}:</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-15 font-weight-bold cl_amount_all"
                                      style="float: right;">{{number_format($itemReceipt['amount'] - $itemReceipt['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                                <input type="hidden" class="form-control m--font-info" disabled="disabled"
                                       name="amount_all" id="amount_all"
                                       value="{{($itemReceipt['amount'] - $itemReceipt['amount_paid'])}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-15">{{__('Còn nợ')}}:</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-15 font-weight-bold cl_amount_rest" style="float: right;">0</span>
                                <input type="hidden" class="form-control  m--font-danger" disabled="disabled"
                                       name="amount_rest" id="amount_rest" value="0">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-15">{{__('Trả lại khách')}}:</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-15 font-weight-bold cl_amount_return" style="float: right;">0</span>
                                <input type="hidden" class="form-control m--font-info" disabled="disabled"
                                       name="amount_return" id="amount_return" value="0">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="font-15">{{__('Ghi chú')}}</label>
                            <textarea class="form-control" id="note" name="note" cols="5" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn"
                                data-dismiss="modal">
                    <span>
                        <i class="la la-arrow-left"></i><span>{{__('HỦY')}}</span>
                    </span>
                        </button>
                        <button type="button" onclick="indexDebt.submit_receipt_bill('{{$itemReceipt['customer_debt_id']}}')"
                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10"><span>{{__('THANH TOÁN & IN HÓA ĐƠN')}}</span></button>
                        <button type="button" onclick="indexDebt.submit_receipt('{{$itemReceipt['customer_debt_id']}}')"
                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10"><span>{{__('THANH TOÁN')}}</span></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
