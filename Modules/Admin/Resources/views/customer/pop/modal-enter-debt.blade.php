<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title_index" id="exampleModalLabel">{{__('THÊM CÔNG NỢ')}}</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{__('Nhập số tiền')}}:</label>
                    <input class="form-control m-input" name="amount_debt" id="amount_debt" placeholder="@lang('Nhập số tiền')" maxlength="11">
                    <span class="text-danger error-amount-debt"></span>
                </div>
                <div class="form-group">
                    <label>{{__('Ghi chú')}}</label>
                    <textarea class="form-control" id="note" name="note" cols="5" rows="5"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn " data-dismiss="modal">
                    {{__('HUỶ')}}
                </button>
                <button onclick="customer.enterDebt()" type="button" class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                    {{__('THÊM CÔNG NỢ')}}
                </button>
            </div>
        </div>
    </div>
</div>