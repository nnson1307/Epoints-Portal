<div class="modal fade" id="modal-discount-bill" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-user-plus"></i> @lang("MÃ GIẢM GIÁ")
                </h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm tab-list m--margin-bottom-10" role="tablist">
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link active show" id="live-tag-bill" show data-toggle="tab" href="#live-bill">@lang("Giảm giá trực tiếp")</a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" id="code-tag-bill" data-toggle="tab" href="#code-bill">@lang("Giảm giá theo mã")</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active show" id="live-bill" role="tabpanel">
                        <input type="hidden" name="amount-bill" id="amount-bill">
                        <div class="m-form__group form-group">
                            <div class="m-radio-inline">
                                <label class="m-radio m-radio--bold m-radio--state-success sz_dt">
                                    <input type="radio" checked name="type-discount-bill" value="1"> @lang("Tiền mặt(VNĐ)")
                                    <span></span>
                                </label>
                                <label class="m-radio m-radio--bold m-radio--state-success sz_dt">
                                    <input type="radio" name="type-discount-bill" value="2"> @lang("Phần trăm(%)")
                                    <span></span>
                                </label>

                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="append-percent">
                                <input class="form-control m-input btn-sm" id="discount-bill" name="discount-bill"
                                       placeholder="@lang("Nhập số tiền giảm giá")">
                            </div>
                            <span class="error-discount-bill" style="color: #ff0000"></span>
                            <span class="error-discount-bill-percent" style="color: #ff0000"></span>
                        </div>

                    </div>
                    <div class="tab-pane" id="code-bill" role="tabpanel">
                        <div class="form-group m-form__group">
                            <div class="append-percent">
                                <input type="text" class="form-control m-input btn-sm" id="discount-code-bill-modal" name="discount_code_bill_modal"
                                       placeholder="{{__('Nhập mã code giảm giá')}}">
                                <span class="error_bill_null" style="color: #ff0000"></span>
                                <span class="error_bill_expired" style="color: #ff0000"></span>
                                <span class="error_bill_amount" style="color: #ff0000"></span>
                                <span class="error_bill_not_using" style="color: #ff0000"></span>
                                <span class="branch_not" style="color: #ff0000"></span>
                            </div>

                        </div>
                    </div>
{{--                    <div class="form-group m-form__group">--}}
{{--                        <label class="font-13">@lang("Lý do giảm giá")</label>--}}
{{--                        <select class="form-control" id="discount_cause_bill_id" name="discount_cause_bill_id" style="width:100%;">--}}
{{--                            <option></option>--}}
{{--                            @foreach($optionDiscountCause as $value)--}}
{{--                                <option value="{{$value['discount_causes_id']}}">{{$value['discount_causes_name']}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    <span class="error_discount_cause_bill" style="color: #ff0000"></span>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="modal-footer btn-click-bill">

            </div>
        </div>
    </div>
</div>