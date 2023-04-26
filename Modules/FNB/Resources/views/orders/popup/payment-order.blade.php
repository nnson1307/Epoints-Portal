
<!-- Modal -->
<div class="modal fade" id="payment-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-1000 " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="title">THANH TOÁN ĐƠN HÀNG</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <span class="note-font">Hình thức thanh toán:<b class="text-danger">*</b></span>
                    </div>
                </div>
                <div class="payments">
                    <div class="form-group">
                        <input type="checkbox" id="cash">
                        <label for="html">Tiền mặt</label>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <input type="checkbox" id="transfer">--}}
{{--                        <label for="css">Chuyển khoản</label>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <input type="checkbox" id="vissa">--}}
{{--                        <label for="javascript">Vissa</label>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <input type="checkbox" id="COD">--}}
{{--                        <label for="javascript">COD</label>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <input type="checkbox" id="VNPay">--}}
{{--                        <label for="javascript">VN Pay</label>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <input type="checkbox" id="zalo-pay">--}}
{{--                        <label for="javascript">Zalo Pay</label>--}}
{{--                    </div>--}}
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row" style="    margin-bottom: 5px;">
                            <div class="col-6">
                                <label style="    margin-top: 10px;">Tiền mặt:<b class="text-danger">*</b></label>
                            </div>
                            <div class="col-6">
                                <div class="input-group" id="cash" style="width: 60%;float: right">
                                    <input type="text" class="form-control m-input numeric_child" id="cash" name="cash" value="" aria-invalid="false"
                                    style=" height: 40px;">
                                    <div class="input-group-append" style="    height: 40px;">
                                        <span class="input-group-text text_type_default">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label style="    margin-top: 10px;">Chuyển khoản:<b class="text-danger">*</b></label>
                            </div>
                            <div class="col-6">
                                <div class="input-group" id="trainfers" style="width: 60%;float: right">
                                    <input type="text" class="form-control m-input numeric_child" id="trainfers" name="trainfers" value="" aria-invalid="false"
                                           style=" height: 40px;">
                                    <div class="input-group-append" style="    height: 40px;">
                                        <span class="input-group-text text_type_default">VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label style="    margin-top: 10px;">Tiền phải thanh toán:</label>
                            </div>
                            <div class="col-6">
                                    <span class="float-right" style="color:red;font-weight:bold">200,000</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label  style="    margin-top: 10px;">Tổng tiền đã trả:</label>
                            </div>
                            <div class="col-6">
                                    <span class="float-right" style="color:green;font-weight:bold">100,000</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label  style="    margin-top: 10px;">Còn nợ:</label>
                            </div>
                            <div class="col-6">
                                    <span class="float-right" style="font-weight:bold">100,000</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label  style="    margin-top: 10px;">Số tiền trả lại khách:</label>
                            </div>
                            <div class="col-6">
                                    <span class="float-right" style="font-weight:bold">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="la 	la-arrow-left"></span>
                    HỦY
                </button>
                <button type="button" class="btn btn-primary">
                    <span class="la 		la-check"></span>
                    THANH TOÁN VÀ IN HÓA ĐƠN
                </button>
                <button type="button" class="btn btn-primary">
                    <span class="la 		la-check"></span>
                    THANH TOÁN
                </button>
            </div>
        </div>
    </div>
</div>
</div>