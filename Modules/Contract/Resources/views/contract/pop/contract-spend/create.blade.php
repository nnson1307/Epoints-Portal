<div class="modal fade show" id="modal-create-spend" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i>
                    @lang('THÊM ĐỢT CHI')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-register">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nội dung'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="content_spend" name="content_spend">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Ngày chi'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input date_picker" readonly=""
                                       id="spend_date" name="spend_date">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Người chi'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control select" id="spend_by" name="spend_by"
                                        style="width:100%;">
                                    <option></option>
                                    @foreach($optionStaff as $v1)
                                        <option value="{{$v1['staff_id']}}">{{$v1['staff_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Số tiền dự chi'):
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input" id="prepayment" name="prepayment"
                                       disabled
                                       value="{{number_format($prepayment, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Số tiền còn lại'):
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input" id="amount_remain" name="amount_remain"
                                       disabled
                                       value="{{number_format($amountRemain, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Giá trị thanh toán'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input input_float" id="amount_spend" name="amount_spend">
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Phương thức thanh toán'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control select" id="payment_method_spend_id" name="payment_method_spend_id"
                                        style="width:100%;">
                                    <option></option>
                                    @foreach($optionPaymentMethod as $v1)
                                        <option value="{{$v1['payment_method_id']}}">{{$v1['payment_method_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Ngày xuất hoá đơn'):
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input date_picker" readonly=""
                                       id="invoice_date" name="invoice_date">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Số hoá đơn'):
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input" id="invoice_no" name="invoice_no">
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Ghi chú'):
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" cols="3" id="note_spend" name="note_spend"></textarea>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div id="contract_revenue_files" class="row">

                        </div>
                        <div class="m-widget19__action">
                            <button type="button" onclick="document.getElementById('upload_file_revenue').click()"
                                    class="btn btn-primary btn-sm color_button m-btn text-center">
                                {{ __('Tải hồ sơ') }}
                            </button>
                        </div>
                    </div>
                </form>
                <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .docx"
                       id="upload_file_revenue" type="file"
                       class="btn btn-primary btn-sm color_button m-btn text-center"
                       style="display: none" oninvalid="setCustomValidity('Please, blah, blah, blah ')"
                       onchange="expectedRevenue.uploadFile(this)">
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="contractSpend.create()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>