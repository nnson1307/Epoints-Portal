<div class="modal fade" id="popup-print-size" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    {{__('Chọn kích thước in')}}
                </h5>
            </div>
            <div class="modal-body text-center">
                <button type="button" class="btn btn-primary color_button w-25" onClick="listHistory.printSelect(`{{$ghn_order_code}}`,`{{$partner}}`,`{{$ghn_shop_id}}`,`printA5`)">A5</button>
                <button type="button" class="btn btn-primary color_button w-25" onClick="listHistory.printSelect(`{{$ghn_order_code}}`,`{{$partner}}`,`{{$ghn_shop_id}}`,`print80x80`)">80x80</button>
                <button type="button" class="btn btn-primary color_button w-25" onClick="listHistory.printSelect(`{{$ghn_order_code}}`,`{{$partner}}`,`{{$ghn_shop_id}}`,`print52x70`)">52x70</button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>