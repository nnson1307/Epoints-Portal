<div class="modal fade" id="modal_history_export" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-width-95" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('Xuất dữ liệu')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-lg-3">
                        <input class="form-control input-search"
                               name="keyword_import_export$code"
                               id="dt_code"
                               type="text"
                               placeholder="@lang('Số chứng từ')">
                    </div>
                    <div class="col-lg-3">
                        <select
                                name="import_export$type"
                                id="dt_type"
                                class="form-control">
                            <option value="">@lang('Loại chứng từ')</option>
                            <option value="Exp.Report.SurveyResult">@lang('Exp.Report.SurveyResult')</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <input class="form-control input-search"
                               name="keyword_import_export$description"
                               id="dt_description"
                               type="text"
                               placeholder="@lang('Mô tả chứng từ')">
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <input type="text"
                                   id="created_at_export_history"
                                   class="form-control"
                                   readonly=""
                                   placeholder="@lang('Thời gian tạo')"
                                   value="">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3">
                        <select
                                name="import_export$status"
                                id="dt_status"
                                class="form-control">
                            <option value="">
                                @lang('Trạng thái')
                            </option>
                            <option value="complete">
                                @lang('Đã xử lý')
                            </option>
                            <option value="processing">
                                @lang('Đang xử lý')
                            </option>
                            <option value="failed">
                                @lang('Thất bại')
                            </option>

                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-1">
                                Size
                            </div>
                            <div class="col-lg-11">
                                <input onchange="lib.formatInputNumber(this)"
                                       type="text"
                                       class="form-control d-inline form-group decimal-2"
                                       id="size_from"
                                       value="" style="width: 48%"
                                       placeholder="@lang('Từ khoảng')"> -
                                <input onchange="lib.formatInputNumber(this)"
                                       type="text"
                                       class="form-control d-inline form-group decimal-2"
                                       id="size_to"
                                       value=""
                                       style="width: 48%" placeholder="@lang('Đến khoảng')">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <button class="btn btn-primary kt-margin-l-5"
                                style="float: right"
                                onclick="libGeneral.historyExport('Exp.Report.SurveyResult')">
                            @lang('Tìm kiếm')
                        </button>
                        <button class="btn btn-secondary btn-background-orange"
                                style="float: right"
                                onclick="libGeneral.resetHistoryExport('Exp.Report.SurveyResult')">
                            @lang('Xóa')
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <!--begin: Datatable -->
                    <div class="kt-datatable" id="m_datatable_export_history"></div>
                    <!--end: Datatable -->
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>