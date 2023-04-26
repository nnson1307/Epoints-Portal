<div class="modal fade" id="modal-discount">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    {{__('MÃ GIẢM GIÁ')}}
                </h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills nav-fill" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show link-dc discount-live" id="live-tag" show  data-toggle="tab" href="#live">{{__('Giảm giá trực tiếp')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-dc discount-code" id="code-tag" data-toggle="tab" href="#code">{{__('Giảm theo mã giảm giá')}}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active show" id="live" role="tabpanel">
                        <input type="hidden" name="amount-tb" id="amount-tb">
                        <div class="m-form__group form-group">
                            <div class="m-radio-inline">
                                <label class="m-radio m-radio--bold m-radio--state-success sz_dt">
                                    <input type="radio" checked name="type-discount" value="1"> {{__('Tiền mặt(VNĐ)')}}
                                    <span></span>
                                </label>
                                <label class="m-radio m-radio--bold m-radio--state-success sz_dt">
                                    <input type="radio" name="type-discount" value="2"> {{__('Phần trăm(%)')}}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="append-percent">
                                <input class="form-control m-input btn-sm" id="discount-modal" name="discount_modal"
                                       placeholder="@lang('Nhập số tiền giảm giá')">
                            </div>
                            <span class="error-discount1" style="color: #ff0000"></span>
                            <span class="error-discount" style="color: #ff0000"></span>
                        </div>

                    </div>
                    <div class="tab-pane" id="code" role="tabpanel">
                        <div class="form-group m-form__group">
                            <div class="append-percent">
                                <input type="text" class="form-control m-input btn-sm" id="discount-code-modal" name="discount_code_modal"
                                       placeholder="@lang('Nhập mã code giảm giá')">
                            </div>
                            <span class="branch_not" style="color: #ff0000"></span>
                            <span class="error_discount_null" style="color: #ff0000"></span>
                            <span class="error_discount_code" style="color: #ff0000"></span>
                            <span class="error_discount_expired" style="color: #ff0000"></span>
                            <span class="error_discount_not_using" style="color: #ff0000"></span>
                            <span class="error_discount_amount_error" style="color: #ff0000"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer btn-click">

            </div>
        </div>
    </div>
</div>