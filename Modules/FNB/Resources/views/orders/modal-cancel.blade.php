<div class="modal fade show" id="modal_cancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     style="display: block;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title_index" id="exampleModalLabel">@lang("HỦY ĐƠN HÀNG")</h5>
            </div>
            <form id="form-cancel">
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{__('Ghi chú')}}</label>
                        <textarea class="form-control" id="order_description" name="order_description" cols="5" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn "
                            data-dismiss="modal">
                        {{__('HỦY')}}
                    </button>
                    <button onclick="cancel.submit_cancel({{$order_id}})" type="button"
                            class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            @lang("XÁC NHẬN")
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>