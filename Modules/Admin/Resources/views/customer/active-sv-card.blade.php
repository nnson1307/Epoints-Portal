<div id="active-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-key"></i> @lang('KÍCH HOẠT THẺ DỊCH VỤ')
                </h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="customer_id">
                <div class="form-group m-form__group bdb">
                    <div class="row">
                        <div class="col-lg-8">
                            <input id="code_search" name="code_search" class="form-control btn-sm autosizeme"
                                   placeholder="@lang('Nhập mã thẻ dịch vụ')">
                            <span class="error-code" style="color: #ff0000"></span>
                        </div>
                        <div class="col-lg-4">
                            <input type="button"
                                   class="btn btn-primary btn-sm color_button  m-btn m-btn--icon m-btn--wide m-btn--md "
                                   id="check"
                                   value="@lang('Kiểm tra mã')">
                          
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table m-table m-table--head-separator-primary" id="tb-card">
                        <thead class="thead_active">
                        <tr>
                            <th>@lang('Mã thẻ')</th>
                            <th>@lang('Tên thẻ')</th>
                            <th>@lang('Ngày kích hoạt')</th>
                            <th>@lang('Ngày hết hạn')</th>
                            <th>@lang('Áp dụng')</th>
                            <th>@lang('Cộng tiền')</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="font-13">

                        </tbody>
                    </table>
                    <span class="error-tb" style="color: #ff0000"></span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang('HỦY')</span>
						</span>
                        </button>
                        <button class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-active m--margin-left-10"
                                onclick="customer.click_active()">
							<span>
							<i class="la la-check"></i>
							<span>@lang('KÍCH HOẠT')</span>
							</span>
                        </button>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
