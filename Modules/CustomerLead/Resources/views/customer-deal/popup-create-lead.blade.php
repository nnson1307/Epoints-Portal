<div class="modal fade show" id="modal-create-lead" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('TẠO KHÁCH HÀNG TIỀM NĂNG')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create-lead">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Loại khách hàng'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" onchange="create.loadMoreInfo(this);" id="popup_customer_type" name="popup_customer_type"
                                            style="width:100%;">
                                        <option value="personal">@lang('Cá nhân')</option>
                                        <option value="business">@lang('Doanh nghiệp')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Họ & tên'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="popup_full_name" name="popup_full_name"
                                       placeholder="@lang('Họ và tên')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Số điện thoại'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input phone" id="popup_phone" name="popup_phone"
                                       placeholder="@lang('Số điện thoại')">
                            </div>
                            <div class="form-group m-form__group more_info " hidden>
                                <label class="black_title">
                                    @lang('Mã số thuế'):
                                </label>
                                <input type="text" class="form-control m-input" id="popup_tax_code" name="popup_tax_code"
                                       placeholder="@lang('Mã số thuế')">
                            </div>
                            <div class="form-group m-form__group more_info" hidden>
                                <label class="black_title">
                                    @lang('Người đại diện')
                                </label>
                                <input type="text" class="form-control m-input phone" id="popup_representative" name="popup_representative"
                                       placeholder="@lang('Người đại diện')">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Nguồn khách hàng'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="popup_customer_source" name="popup_customer_source"
                                            style="width:100%;">
                                        <option></option>
                                        @foreach($optionCustomerSource as $v)
                                            <option value="{{$v['customer_source_id']}}">{{$v['customer_source_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Pipeline'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="popup_pipeline_code" name="popup_pipeline_code"
                                            style="width:100%;">
                                        <option></option>
                                        @foreach($optionPipeline as $v)
                                            <option value="{{$v['pipeline_code']}}">{{$v['pipeline_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Hành trình'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control popup_journey" id="popup_journey_code" name="popup_journey_code"
                                            style="width:100%;">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button onclick="customerDealCreate.cancelLead()"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="customerDealCreate.saveLead()"
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
