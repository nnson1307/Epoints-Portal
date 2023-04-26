
<div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title color-primary m--font-bold font-primary fz-1_5rem fw-500">
                <i class="fa fa-edit ss--icon-title fz-1_5rem m--margin-right-5 fw-500"></i>
                {{ __('PHÊ DUYỆT VẬT TƯ') }}
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group m-form__group">
                <input type="hidden" name="id" value="{{$item->ticket_refund_item_id}}">
                @if($check == "true")
                    <label class="black_title">
                        {{ __('Số lượng duyệt') }}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="quantity" class="form-control m-input refund_id" data-id="{{$item->ticket_refund_item_id}}" id="total_amount"
                        placeholder="0" value="{{ $item->quantity }}" max="{{ $item->quantity }}">
                @else
                    <label class="black_title">
                        {{ __('Số tiền duyệt') }}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="money" class="form-control m-input format_money refund_id" data-id="{{$item->ticket_refund_item_id}}" id="total_amount"
                        placeholder="0" value="{{ $item->money }}">
                @endif
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('Ghi chú') }}:
                </label>
                <textarea class="form-control m-input" name="note" rows="5" cols="5"
                    placeholder="{{ __('Nhập nội dung ghi chú') }}...">{{ $item->note }}</textarea>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                    </button>

                    <button type="button"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                        <span class="ss--text-btn-mobi" onclick="Refund.update_approve_item({{$item->ticket_refund_item_id}},{{$check}});">
                            <i class="la la-check"></i>
                            <span>{{ __('LƯU THÔNG TIN') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>