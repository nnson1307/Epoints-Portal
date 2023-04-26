<div class="modal fade show" id="modal-create-file" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i>
                    @lang('THÊM HỒ SƠ')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-register">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên hồ sơ'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="name" name="name">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Ghi chú'):
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" cols="3" id="note_file" name="note_file"></textarea>
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
                    <button type="button" onclick="contractFile.create()"
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