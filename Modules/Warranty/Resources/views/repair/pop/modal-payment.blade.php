<div class="modal fade" role="dialog" id="modal-receipt" style="display: none">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    {{__('PHIẾU CHI CHO CHI PHÍ BẢO DƯỠNG')}}
                </h4>
            </div>
            <div class="m-widget4  m-section__content" id="load">
                <form id="form-payment">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Thông tin người nhận tiền'):<b class="text-danger"> *</b>
                                    </label>
                                    <input type="text" class="form-control m-input" disabled
                                           value="{{$info['staff_name']}}">
                                    <input type="hidden" id="staff_id" name="staff_id" value="{{$info['staff_id']}}">
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Loại phiếu chi'):<b class="text-danger"> *</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="payment_type" name="payment_type"
                                                style="width:100%;">
                                            @foreach($optionPaymentType as $v)
                                                <option value="{{$v['payment_type_id']}}">{{$v['payment_type_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Mã tham chiếu'):
                                    </label>
                                    <input type="text" name="document_code" class="form-control m-input" id="document_code"
                                           placeholder="{{__('Nhập mã tham chiếu')}}" value="{{$info['repair_code']}}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Số tiền'):<b class="text-danger"> *</b>
                                    </label>
                                    <input type="text" class="form-control m-input format-money" id="money" name="money"
                                           placeholder="@lang('Nhập số tiền')" disabled
                                           value="{{number_format($info['total_pay'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                </div>

                                <div class="form-group m-form__group type_modal">
                                    <label>@lang('Hình thức thanh toán'):<span style="color:red;font-weight:400">*</span></label>
                                    <select class="form-control" id="payment_method" name="payment_method"
                                            style="width: 100%">
                                        @foreach($optionPaymentMethod as $item)
                                            @if($item['payment_method_code'] != 'MEMBER_MONEY')
                                                <option value="{{$item['payment_method_code']}}">{{$item['payment_method_name']}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="error_type" style="color:#ff0000"></span>
                                </div>

                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Nội dung chi'):
                                    </label>
                                    <input type="text" class="form-control m-input"
                                           id="note" name="note" placeholder="@lang('Nhập nội dung chi')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn"
                                data-dismiss="modal">
                    <span>
                        <i class="la la-arrow-left"></i><span>{{__('HỦY')}}</span>
                    </span>
                        </button>
                        <button type="button" onclick="payment.submitPayment('{{$info['repair_id']}}')"
                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10"><span>{{__('THANH TOÁN')}}</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
