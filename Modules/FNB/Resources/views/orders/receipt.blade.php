<div class="modal fade" role="dialog" id="modal-receipt" style="display: none">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    @lang("THANH TOÁN ĐƠN HÀNG")
                </h4>
            </div>
            <div class="m-widget4  m-section__content" id="load">
                <form id="form-receipt">
                    <div class="modal-body">
                        <div class="form-group m-form__group type_modal">
                            <label>@lang("Hình thức thanh toán")<span style="color:red;font-weight:400">*</span></label>
                            <select class="form-control" id="receipt_type" name="receipt_type[]" multiple="multiple" style="width: 100%">
                                @foreach($optionPaymentMethod as $item)
                                    <option value="{{$item['payment_method_code']}}">{{$item['payment_method_name']}}</option>
                                @endforeach
                            </select>
                            <span class="error_type" style="color:#ff0000"></span>
                            <span class="card_null_sv" style="color:#ff0000;"></span>
                        </div>

                        <div class="form-group m-form__group payment_method">

                        </div>

                        <div class="form-group m-form__group">
                            <span class="error_amount_null" style="color:#ff0000"></span>
                            <span class="error_amount_large" style="color:#ff0000"></span>
                            <span class="error_amount_small" style="color:#ff0000"></span>
                            <span class="error_account_money" style="color: #ff0000"></span>
                            <span class="error_account_money_null" style="color: #ff0000"></span>
                            <span class="count_using_card_error" style="color: #ff0000"></span>
                            <span class="money_owed_zero" style="color: #ff0000"></span>
                            <span class="money_large_moneybill" style="color: #ff0000"></span>
                        </div>

                        <span class="cus_not" style="color:#ff0000"></span>

                        <span class="error_card_pired_date" style="color:#ff0000"></span>
                        <span class="type_error" style="color:#ff0000"></span>
                        <span class="error_count" style="color:#ff0000"></span>
                        <span class="error_expired_member" style="color:#ff0000"></span>
                        <span class="error_count_member" style="color:#ff0000"></span>
                        <div class="m-form__group form-group checkbox_active_card">

                        </div>

                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40  font-13">@lang("Tiền phải thanh toán")</label>
                            <div class="col-lg-9 w-me-40 ">
                                <span class="font-13 font-weight-bold cl_receipt_amount" style="float: right;color: red"></span>
                                <input type="hidden" class="form-control m--font-bolder" disabled="disabled"
                                       name="receipt_amount"
                                       id="receipt_amount">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-13">@lang("Tổng tiền trả")</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-13 font-weight-bold cl_amount_all" style="float: right;"></span>
                                <input type="hidden" class="form-control m--font-info" disabled="disabled"
                                       name="amount_all" id="amount_all">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-13">@lang("Còn nợ")</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-13 font-weight-bold cl_amount_rest" style="float: right;"></span>
                                <input type="hidden" class="form-control  m--font-danger" disabled="disabled"
                                       name="amount_rest" id="amount_rest">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 w-me-40 font-13">@lang("Trả lại khách")</label>
                            <div class="col-lg-9 w-me-40">
                                <span class="font-13 font-weight-bold cl_amount_return" style="float: right;"></span>
                                <input type="hidden" class="form-control m--font-info" disabled="disabled"
                                       name="amount_return" id="amount_return">
                            </div>
                        </div>

                        <div class="form-group m-form__group">
                            <label>{{__('Ghi chú')}}</label>
                            <textarea class="form-control" id="note" name="note" cols="5" rows="5">

                        </textarea>
                        </div>
                        <div class="add-quick-appointment" style="display: none">
                            <div class="form-group m-form__group">
                                <div class="m-checkbox-list">
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input type="checkbox" name="cb_add_appointment" id="cb_add_appointment">
                                        @lang("Thêm lịch hẹn nhanh")
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="append-appointment">

                            </div>

                        </div>
                    </div>

                    <div class="modal-footer ">
                        <div class="m-form__actions m--align-right btn_receipt w-100">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
