<div class="modal fade" id="import-excel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{__('Thêm khách hàng vào danh sách bằng excel')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row kt-margin-b-10">
                    <div class="col-lg-12">
                        {{__('Chọn file để tải lên')}}
                    </div>
                </div>
                <div class="row kt-margin-b-10">
                    <div class="col-lg-8">
                        <input class="form-control" readonly id="show">
                        <span class="text-danger error-input-excel"></span>
                    </div>
                    <div class="col-lg-4">
                        <label for="file_excel" class="btn ss--btn-search">
                            {{__('Upload file')}}
                        </label>
                        <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                               id="file_excel" onchange="userGroupDefine.showNameFile()" type="file" style="display: none">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('admin.customer-group-filter.export-excel-example')}}">
                            {{__('Tải file mẫu')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="userGroupDefine.import()" class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('THÊM KHÁCH HÀNG')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>
