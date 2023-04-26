<div class="modal fade" role="dialog" id="modal-receipt-index">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang("Thanh toán đơn hàng")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-receipt">
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        <label>@lang("Tiền cần thanh toán"):</label>
                        <input class="form-control" disabled="disabled" name="receipt_load" id="receipt_load">
                    </div>
                    <div class="form-group m-form__group">
                        <label>@lang("Hình thức thanh toán")<span style="color:red;font-weight:400">(*)</span></label>
                        <select class="form-control" id="receipt_type" name="receipt_type[]" multiple="multiple" style="width: 100%">
                            <option value="cash">@lang("Tiền mặt")</option>
                            <option value="transfer">@lang("Chuyển khoản (ATM)")</option>
                            <option value="visa">@lang("Visa")</option>
                            <option value="member_card">@lang("Thẻ dịch vụ")</option>
                            <option value="member_account">@lang("Điểm thành viên")</option>
                        </select>
                        <span class="error_type" style="color:#ff0000"></span>
                    </div>
                    <div class="form-group m-form__group cash">

                    </div>
                    <div class="form-group m-form__group transfer">

                    </div>
                    <div class="form-group m-form__group visa">

                    </div>
                    <div class="form-group m-form__group" >
                        <span class="error_amount_null" style="color:#ff0000"></span>
                        <span class="error_amount_large" style="color:#ff0000"></span>
                    </div>

                    <div class="form-group m-form__group">
                        <label>{{__('Ghi chú')}}</label>
                        <textarea class="form-control" id="note" name="note" cols="5" rows="5">

                        </textarea>
                    </div>
                </div>
                <div class="modal-footer btn_receipt">

                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#receipt_type').select2({
            placeholder: {{__('Chọn hình thức thanh toán')}}
        }).on('select2:select', function (event) {
            if (event.params.data.id == 'cash') {
                $('.cash').empty();
                var tpl = $('#type-receipt-tpl').html();
                tpl = tpl.replace(/{label}/g, {{__('Tiền mặt')}});
                tpl = tpl.replace(/{name_cash}/g, 'amount_receipt_detail');
                tpl = tpl.replace(/{id_cash}/g, 'amount_receipt_detail');
                $('.cash').append(tpl);
                $('#amount_receipt_detail').mask('000,000,000', {reverse: true});
            }
            if (event.params.data.id == 'transfer') {
                $('.transfer').empty()
                var tpl = $('#type-receipt-tpl').html();
                tpl = tpl.replace(/{label}/g, {{__('Tiền chuyển ATM')}});
                tpl = tpl.replace(/{name_cash}/g, 'amount_receipt_atm');
                tpl = tpl.replace(/{id_cash}/g, 'amount_receipt_atm');
                $('.transfer').append(tpl);
                $('#amount_receipt_atm').mask('000,000,000', {reverse: true});
            }
            if (event.params.data.id == 'visa') {
                $('.visa').empty()
                var tpl = $('#type-receipt-tpl').html();
                tpl = tpl.replace(/{label}/g, {{__('Tiền chuyển Visa')}});
                tpl = tpl.replace(/{name_cash}/g, 'amount_receipt_visa');
                tpl = tpl.replace(/{id_cash}/g, 'amount_receipt_visa');
                $('.visa').append(tpl);
                $('#amount_receipt_visa').mask('000,000,000', {reverse: true});
            }

        }).on('select2:unselect', function (event) {
            if (event.params.data.id == 'cash') {
                $('.cash').empty();
            }
            if (event.params.data.id == 'transfer') {
                $('.transfer').empty();
            }
            if (event.params.data.id == 'visa') {
                $('.visa').empty();
            }
        });
    })
</script>