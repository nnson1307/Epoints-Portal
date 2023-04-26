<div class="modal fade" id="modal_staff" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 70%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('Thêm nhân viên')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group kt-margin-b-10">
                    <div class="form-group row kt-margin-b-10">
                        <div class="col-4 form-group">
                            <input type="text" class="form-control" id="name_or_code_staff_modal"
                                name="name_or_code_staff_modal" placeholder="@lang('Nhập tên hoặc mã nhân viên')">
                        </div>
                        <div class="col-4 form-group">
                            <select id="staff_branch_modal" name="staff_branch"
                                class="form-control ss--width-100 ss-select2" style="width: 100%">
                                <option value="" selected>@lang('Chi nhánh')</option>
                                @foreach ($branch as $item)
                                    <option value="{{ $item->branch_id }}">{{ $item->branch_name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-4 form-group">
                            <select type="text" class="form-control ss--width-100 ss-select2" style="width: 100%"
                                id="staff_department_modal" name="staff_department">
                                <option value="">@lang('Phòng ban')</option>
                                @foreach ($department as $item)
                                    <option value="{{ $item->department_id }}">{{ $item->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 form-group">
                            <select type="text" class="form-control ss--width-100 ss-select2" style="width: 100%"
                                id="staff_position_modal" name="staff_position">
                                <option value="">@lang('Chức vụ')</option>
                                @foreach ($staffTitle as $item)
                                    <option value="{{ $item->staff_title_id }}">{{ $item->staff_title_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 form-group">
                            <input type="text" class="form-control" id="address_staff_modal"
                                name="address_staff_modal" placeholder="{{ __('Nhập tên địa chỉ') }}">
                        </div>
                        <div class="form-group col-lg-4">
                            <button type="button" onclick="branch.resetSearchStaff()"
                                class="btn btn-primary color_button color_button_destroy kt-margin-r-5 btn-search"
                                style="float:left">@lang('Xóa bộ lọc')</button>
                            <button type="button" onclick="branch.searchStaff()"
                                class="btn btn-primary color_button btn-search btn-list-store"
                                style="float:left">@lang('Tìm kiếm')</button>
                        </div>
                    </div>
                </div>
                <div class="form-group row kt-margin-b-0">
                    <div class="col-lg-12">
                        <div class="kt-section__content table-list-staff">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-left">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    @lang('Hủy')
                </button>
                <button type="button" class="btn btn-success color_button" onclick="branch.submitAddItemTempStaff()">
                    @lang('Thêm')
                </button>
            </div>
        </div>
    </div>
</div>
