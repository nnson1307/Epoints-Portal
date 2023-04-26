<div class="modal fade" id="modal_customer_auto" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 70%" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title color_title pt-2" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('Chọn nhóm khách hàng')
                </h5>
                <div class="form-group d-flex" style="gap:50px">
                    <a href="{{ route('admin.customer-group-filter.add-group-define', [
                        'survey' => 'survey',
                        'id' => $id,
                    ]) }}"
                        class="btn btn-primary color_button btn-search" style="color:#ffff">
                        <i class="fa fa-plus-circle"></i>
                        @lang('Thêm nhóm khách hàng tự định nghĩa')
                    </a>
                    <a href="{{ route('admin.customer-group-filter.add-customer-group-auto', ['survey' => 'survey', 'id' => $id]) }}"
                        class="btn btn-primary color_button btn-search" style="color:#ffff">
                        <i class="fa fa-plus-circle"></i>
                        @lang('Thêm nhóm khách hàng động')
                    </a>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
            </div>

            <div class="modal-body">
                <div class="form-group kt-margin-b-10">
                    <div class="form-group row kt-margin-b-10">
                        <div class="col-4 form-group">
                            <input class="form-control" placeholder="@lang('Tên nhóm khách hàng')" id="name_group_customer"
                                name="name_group_customer" type="text" value="">
                        </div>
                        <div class="col-4 form-group">
                            <select class="form-control ss--select-2" style="width: 100%;" id="type_group" name="type_group">
                                <option value="">@lang('Loại nhóm')</option>
                                <option value="auto">@lang('Nhóm tự động')</option>
                                <option value="user_define">@lang('Nhóm được định nghĩa')</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <button type="button" onclick="branch.searchCustomerAuto()"
                                class="btn btn-primary color_button btn-search kt-margin-l-5  btn-list-store"
                                style="float: right">@lang('Tìm kiếm')</button>
                            <button type="button" onclick="branch.resetSearchCustomerAuto()"
                                class="btn btn-primary color_button color_button_destroy   btn-search"
                                style="float: right">@lang('Xóa bộ lọc')</button>
                        </div>
                    </div>
                </div>
                <div class="form-group row kt-margin-b-0">
                    <div class="col-lg-12">
                        <div class="kt-section__content table-list-customer_auto">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-left">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    @lang('Hủy')
                </button>
                <button type="button" class="btn btn-success color_button"
                    onclick="branch.submitAddItemTempCustomerAuto()">
                    @lang('Thêm nhóm khách hàng')
                </button>
            </div>
        </div>
    </div>
</div>
