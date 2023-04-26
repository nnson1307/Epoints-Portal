<div class="modal fade" id="modal-commission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title_index" id="exampleModalLabel">{{__('QUY ĐỔI TIỀN HOA HỒNG')}}</h5>
            </div>
            <form id="form-commission">
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{__('Tiền hoa hồng còn lại')}}: <strong>{{number_format($commission_money)}} đ</strong></label>
                    </div>
                    <div class="form-group">
                        <label>{{__('Nhập số tiền')}}:</label>
                        <input class="form-control m-input" name="money" id="money" placeholder="@lang('Nhập số tiền quy đổi')"
                               maxlength="11">
                        <span class="text-danger error-amount-debt"></span>
                    </div>
                    <div class="form-group">
                        <label>{{__('Chọn hình thức quy đổi')}}:</label>
                        <div class="input-group m-input-group m-input-group--solid">
                            <select class="form-control" id="type" name="type" style="width:100%;">
                                <option></option>
                                <option value="cash_out">{{__('Tạo phiếu chi')}}</option>
                                <option value="tranfer_money">{{__('Cộng vào tiền tài khoản')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__('Ghi chú')}}</label>
                        <textarea class="form-control" id="note" name="note" cols="5" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn "
                            data-dismiss="modal">
                        {{__('HUỶ')}}
                    </button>
                    <button onclick="detail.submit_commission({{$customer_id}})" type="button"
                            class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        {{__('ĐỒNG Ý')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>