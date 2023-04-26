<div class="modal fade" id="modal_customer" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 70%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('Thêm khách hàng cụ thể')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group kt-margin-b-10">
                    <div class="form-group row kt-margin-b-10">
                        <div class="col-3 form-group">
                            <input type="text" class="form-control" id="code_or_name_customer_modal"
                                name="code_or_name_customer_modal" placeholder="@lang('Nhập tên hoặc mã khách hàng')">
                        </div>
                        <div class="col-3 form-group">
                            <select type="text" id="customer_type_modal" name="customer_type_modal"
                                class="form-control  ss-select2" 
                                style="width: 100%">
                                <option value="" selected >@lang('Loại khách hàng')</option>
                                <option value="personal">@lang('Cá nhân')</option>
                                <option value="business">@lang('Doanh nghiệp')</option>
                                
                            </select>
                        </div>
                        <div class="col-3 form-group">
                            <select class="form-control  ss-select2" style="width: 100%" id="customer_group_modal" name="customer_group_modal">
                                <option value="">@lang('Nhóm khách hàng')</option>
                                @if (!empty($optionCustomer['customerGroup']))
                                    @foreach ($optionCustomer['customerGroup'] as $key => $value)
                                        <option value="{{ $key }}">
                                            @lang($value)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-3 form-group">
                            <select class="form-control  ss-select2" style="width: 100%" id="customer_source_modal" name="customer_source_modal">
                                <option value="">@lang('Nguồn khách hàng')</option>
                                @if (!empty($optionCustomer['customerSource']))
                                    @foreach ($optionCustomer['customerSource'] as $key => $value)
                                        <option value="{{ $key }}">
                                            @lang($value)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-3 form-group">
                            <select type="text" id="province_id_customer" name="province_main"
                                class="form-control  ss-select2" onchange="branch.getListProvinces()"
                                style="width: 100%">
                                <option></option>
                                @foreach ($optionProvince as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 form-group">
                            <select type="text" name="district" id="district_id_customer"
                            onchange="branch.getWard()"
                                class="form-control  ss-select2 district_customer" style="width: 100%">
                            </select>
                        </div>
                        <div class="col-3 form-group">
                            <select type="text" name="ward_main" id="ward_id_customer"
                                class="form-control  ss-select2" style="width: 100%">
                                <option value="">@lang('Phường/xã')</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <button type="button" onclick="branch.searchCustomer()"
                                class="btn btn-primary color_button btn-search kt-margin-l-5 btn-list-store"
                                style="float: right">@lang('Tìm kiếm')</button>
                            <button type="button" onclick="branch.resetSearchCustomer()"
                                class="btn btn-primary color_button color_button_destroy  btn-search"
                                style="float: right">@lang('Xóa bộ lọc')</button>
                        </div>
                    </div>
                </div>
                <div class="form-group row kt-margin-b-0">
                    <div class="col-lg-12">
                        <div class="kt-section__content table-list-customer">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-left">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    @lang('Hủy')
                </button>
                <button type="button" class="btn btn-success color_button"
                    onclick="branch.submitAddItemTempCustomer()">
                    @lang('Thêm')
                </button>
            </div>
        </div>
    </div>
</div>
