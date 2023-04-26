<div class="modal fade show" id="modal-edit-receipt" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i>
                    @lang('CHỈNH SỬA ĐỢT THU')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-edit">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nội dung'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="content_receipt" name="content_receipt"
                                   value="{{$item['content']}}" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Ngày thu'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input date_picker" readonly=""
                                       id="collection_date" name="collection_date" disabled
                                       value="{{\Carbon\Carbon::parse($item['collection_date'])->format('d/m/Y')}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Người thu'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control select" id="collection_by" name="collection_by"
                                        style="width:100%;" disabled>
                                    <option></option>
                                    @foreach($optionStaff as $v1)
                                        <option value="{{$v1['staff_id']}}"
                                                {{$item['collection_by'] == $v1['staff_id'] ? 'selected': ''}}>{{$v1['staff_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Số tiền dự thu'):
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
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Phương thức thanh toán'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class="form-control select" id="payment_method_receipt_id"
                                    name="payment_method_receipt_id" style="width:100%;" multiple disabled>
                                <option></option>
                                @foreach($optionPaymentMethod as $v1)
                                    <option value="{{$v1['payment_method_id']}}"
                                            {{in_array($v1['payment_method_id'], $arrMethodId) ? 'selected': ''}}>{{$v1['payment_method_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group div_append_payment_method">
                        @foreach($detail as $v)
                            <div class="row mt-3 method div_payment_method_{{$v['payment_method_id']}}">
                                <label class="col-lg-6 font-13">{{$v['payment_method_name']}}:</label>
                                <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                                    <input style="color: #008000" class="form-control m-input"
                                           placeholder="{{__('Nhập giá tiền')}}"
                                           aria-describedby="basic-addon1"
                                           name="payment_method" id="payment_method_{{$v['payment_method_id']}}"
                                           disabled
                                           value="{{number_format($v['amount_receipt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{__('VNĐ')}}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Ngày xuất hoá đơn'):
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input date_picker" readonly=""
                                       id="invoice_date" name="invoice_date"
                                       value="{{\Carbon\Carbon::parse($item['invoice_date'])->format('d/m/Y')}}">
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
                                <input type="text" class="form-control m-input" id="invoice_no" name="invoice_no"
                                       value="{{$item['invoice_no']}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Ghi chú'):
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" cols="3" id="note_receipt"
                                      name="note_receipt">{{$item['note']}}</textarea>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div id="contract_revenue_files" class="row">
                            @if (count($receiptFile) > 0)
                                @foreach($receiptFile as $v)
                                    <div class="col-lg-12">
                                        <a href="{{$v['link']}}" value="{{$v['$v']}}" name="contract_revenue_files[]"
                                           class="ss--text-black" download="{{$v['file_name']}}">{{$v['file_name']}}</a>
                                        <a href="javascript:void(0)" onclick="expectedRevenue.removeFile(this)"><i
                                                    class="la la-trash"></i></a>
                                        <br>
                                    </div>
                                @endforeach
                            @endif
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
                    <button type="button" onclick="contractReceipt.edit('{{$item['contract_receipt_id']}}')"
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