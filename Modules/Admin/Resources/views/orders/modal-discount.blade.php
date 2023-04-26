<div class="modal fade" id="modal-discount">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-user-plus"></i> @lang("MÃ GIẢM GIÁ")
                </h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm tab-list m--margin-bottom-10" role="tablist">
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link active show" id="live-tag" show  data-toggle="tab" href="#live">@lang("Giảm giá trực tiếp")</a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link" id="code-tag" data-toggle="tab" href="#code">@lang("Giảm theo mã giảm giá")</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active show" id="live" role="tabpanel">
                            <input type="hidden" name="amount-tb" id="amount-tb">
                            <div class="m-form__group form-group">
                                <div class="m-radio-inline">
                                    <label class="m-radio m-radio--bold m-radio--state-success sz_dt">
                                        <input type="radio" checked name="type-discount" value="1"> @lang("Tiền mặt(VNĐ)")
                                        <span></span>
                                    </label>
                                    <label class="m-radio m-radio--bold m-radio--state-success sz_dt">
                                        <input type="radio" name="type-discount" value="2"> @lang("Phần trăm(%)")
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <div class="append-percent">
                                    <input class="form-control m-input btn-sm" id="discount-modal" name="discount_modal"
                                           placeholder="@lang("Nhập số tiền giảm giá")">
                                </div>
                                <span class="error-discount1" style="color: #ff0000"></span>
                                <span class="error-discount" style="color: #ff0000"></span>
                            </div>
    
                        </div>
                        <div class="tab-pane" id="code" role="tabpanel">
                            <div class="form-group m-form__group">
                                <div class="append-percent">
                                    <input type="text" class="form-control m-input btn-sm" id="discount-code-modal" name="discount_code_modal"
                                           placeholder="@lang("Nhập mã code giảm giá")">
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
              </div>
{{--                <div class="form-group m-form__group">--}}
{{--                    <label class="font-13">@lang("Lý do giảm giá")</label>--}}
{{--                    <select class="form-control" id="discount_cause_id" name="discount_cause_id" style="width:100%;">--}}
{{--                        <option></option>--}}
{{--                        @foreach($optionDiscountCause as $value)--}}
{{--                            <option value="{{$value['discount_causes_id']}}">{{$value['discount_causes_name']}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    <span class="error_discount_cause" style="color: #ff0000"></span>--}}
{{--                </div>--}}
            </div>
            <div class="modal-footer btn-click">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                    </button>
                    <button type="button" onclick="order.modal_customer_click()"
                            class="btn btn-primary  color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>