<div class="modal fade" id="modal_outlet" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 70%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('THÊM CHI NHÁNH')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group kt-margin-b-10">
                    <div class="form-group row kt-margin-b-10">
                        <div class="col-lg-3">
                            <input type="text" class="form-control branch_name" placeholder="@lang('Tên chi nhánh')"
                                value="">
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control branch_code" placeholder="@lang('Mã chi nhánh')"
                                value="">
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control representative_code"
                                placeholder="@lang('Mã đại diện')" value="">
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-secondary btn-background-orange ml-3"
                                onclick="branch.resetSearchBranch()" style="float: right">@lang('Xóa')
                            </button>
                            <button type="button" class="btn btn-success color_button" onclick="branch.searchBranch()"
                                style="float: right">@lang('Tìm kiếm')
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group row kt-margin-b-0">
                    <div class="col-lg-12">
                        <div class="kt-section__content table-list-outlet">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-left">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    @lang('Hủy')
                </button>
                <button type="button" class="btn btn-success color_button" onclick="branch.submitAddItemTemp()">
                    @lang('Thêm chi nhánh')
                </button>
            </div>
        </div>
    </div>
</div>
