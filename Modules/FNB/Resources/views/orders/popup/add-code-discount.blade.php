<!-- Modal -->
<div class="modal fade" id="add-code-discount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="    max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="title">THÊM MÃ GIẢM GIÁ</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row" style="    margin-bottom: 5px;">

                            <div class="col">
                                <div class="choose-size" style="    margin-top: 12px;">
                                    <input type="radio" id="sizeM" name="size" value="M"><label for="sizeM"></label><br>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="sizeM" style="    margin-top: 10px;">Giảm giá trực tiếp</label>
                            </div>
                            <div class="col-6">
                                <div class="input-group" style="">
                                    <input type="text"
                                           class="form-control m-input numeric_child"
                                           id="order-commission-value"
                                           name="commission_value" value="0"
                                           aria-invalid="false">
                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle"
                                             data-toggle="buttons">

                                            <label class="btn btn-secondary"
                                                   onclick="discount.change('percent')">
                                                <input type="radio" id="commission_type_money" name="commission_type"
                                                       value="percent">
                                                %
                                            </label>
                                            <label class="btn btn-secondary active"
                                                   onclick="discount.change('money')">
                                                <input type="radio" id="commission_type_percent" name="commission_type"
                                                       value="money">
                                                VNĐ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="choose-size" style="    margin-top: 12px;">
                                    <input type="radio" id="sizeL" name="size" value="L"><label for="sizeL"></label>
                                </div>
                            </div>
                            <div class="col-4">
                                <label for="sizeM" style="    margin-top: 10px;">Giảm giá theo mã</label>
                            </div>
                            <div class="col-6">
                                <div class="search-table">
                                    <div class="form-search" style="width: 310px">
                                        <i class="fa fa-search"></i>
                                        <input type="text" class="form-control form-input"
                                               placeholder="Nhập mã giảm giá">
                                    </div>
                                </div>
                            </div>
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
                    LƯU THÔNG TIN
                </button>
            </div>
        </div>
    </div>
</div>