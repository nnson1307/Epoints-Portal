<div class="modal fade show" id="modal-confirm" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    @lang('Xác nhận thanh toán phiếu giao hàng')
                </h5>
            </div>
            <form id="form-payment">
                <div class="modal-body">
                    <input type="hidden" id="delivery_payment_id" name="delivery_payment_id"
                           value="{{$payment != null ? $payment['delivery_payment_id'] : null}}">

                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Số tiền cần thu'):
                        </label>
                        <input type="text" class="form-control m-input" id="total" name="total" disabled
                               value="{{$infoHistory != null ? number_format($infoHistory['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : ''}}">
                    </div>

                    <div class="form-group m-form__group payment_method">
                        @if(count($paymentDetail) > 0)
                            @foreach($paymentDetail as $v)
                                <div class="row mt-3 method payment_method_{{$v['payment_type']}}">
                                    <label class="col-lg-6 font-13">{{$v['payment_method_name']}}:<span
                                                style="color:red;font-weight:400">*</span></label>
                                    <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                                        <input onkeyup="detail.changeAmountReceipt(this)" style="color: #008000"
                                               class="form-control m-input"
                                               placeholder="{{__('Nhập giá tiền')}}"
                                               name="payment_method" id="payment_method_{{$v['payment_type']}}"
                                               value="{{number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">

                                        <input type="hidden" name="payment_transaction_code"
                                               value="{{$v['payment_transaction_code']}}">
                                        <input type="hidden" name="delivery_history_payment_detail_id"
                                               value="{{$v['delivery_history_payment_detail_id']}}">

                                        <div class="input-group-append">
                                            <span class="input-group-text">{{__('VNĐ')}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mt-3 method payment_method_CASH">
                                <label class="col-lg-6 font-13">@lang('Tiền mặt'):<span
                                            style="color:red;font-weight:400">*</span></label>
                                <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                                    <input onkeyup="detail.changeAmountReceipt(this)" style="color: #008000"
                                           class="form-control m-input"
                                           placeholder="{{__('Nhập giá tiền')}}"
                                           name="payment_method" id="payment_method_CASH"
                                           value="{{$infoHistory != null ? number_format($infoHistory['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : ''}}">

                                    <input type="hidden" name="payment_transaction_code">
                                    <input type="hidden" name="delivery_history_payment_detail_id">

                                    <div class="input-group-append">
                                        <span class="input-group-text">{{__('VNĐ')}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Ghi chú'):
                        </label>
                        <textarea class="form-control m-input" id="note" name="note" rows="5"
                                  cols="5" {{$payment != null ? 'disabled' : ''}}>
                            {{$payment != null ? $payment['note'] : null}}
                        </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>
                            <button type="button" onclick="detail.confirmReceipt({{$delivery_history_id}})"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>