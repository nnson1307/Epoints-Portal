<form class="modal-content" id="form-add-material">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold mt-2">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
            {{ __('THÊM PHIẾU YÊU CẦU VẬT TƯ') }}
        </h4>
        <div class="modal-title ss--title m--font-bold">
            <span>{{ __('Tải file import') }} <a href="{{url('uploads/admin/ticket/phieu_yeu_cau_vat_tu.xlsx')}}" target="_blank" class="text-danger">{{ __('tại đây') }}</a> </span>
            <label class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm" for="file-import">
                <span>
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    <span>{{ __('IMPORT') }}</span>
                </span>
            </label>
            <input type="file" class="d-none" name="import_file" id="file-import" accept=".xlsx, .xls, .csv" onchange="Material.upload()">
        </div>
    </div>
    <div class="modal-body">
        <div class="modal-header p-0 mb-3">
            <h5>@lang('Thông tin đề xuất')</h5>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                @lang('Mã ticket'):<b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select name="ticket_code" class="form-control select2 select2-active">
                    <option value="">{{ __('Chọn mã ticket') }}</option>
                    @foreach ($listTicket as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                {{ __('Nội dung đề xuất') }}:<b class="text-danger">*</b>
            </label>
            <textarea class="form-control m-input" name="description" rows="5" cols="5"
                placeholder="{{ __('Nhập nội dung đề xuất') }}..."></textarea>
        </div>
        <div class="modal-header p-0 mb-3">
            <h5>@lang('Thông tin vật tư đề xuất')</h5>
        </div>
        <div class="d-flex">
            <div class="col-md-6">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Chọn kho'):
                    </label>
                    <div class="input-group">
                        <select name="warehouse_id" class="form-control select2 select2-active">
                            <option value="">{{ __('Chọn kho') }}</option>
                            @foreach ($listWarehouses as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Chọn vật tư cần đề xuất'):
                    </label>
                    <div class="input-group">
                        <select name="material" class="form-control select2 select2-active">
                            <option value="">{{ __('Chọn vật tư cần đề xuất') }}</option>
                            {{-- @foreach ($listMaterial as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped m-table ss--header-table ss--nowrap table-add-material">
                <thead>
                    <tr>
                        <th class="ss--font-size-th">#</th>
                        <th class="ss--font-size-th">{{ __('Mã vật tư') }}</th>
                        <th class="ss--font-size-th">{{ __('Tên vật tư') }}</th>
                        <th class="ss--font-size-th">{{ __('Số lượng tạm ứng') }}</th>
                        <th class="ss--font-size-th">{{ __('Số lượng tồn kho') }}</th>
                        <th class="ss--font-size-th">{{ __('Đơn vị tính') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-arrow-left"></i>
                        <span>{{ __('HỦY') }}</span>
                    </span>
                </button>

                <button type="submit" onclick="Material.addClose()"
                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-check"></i>
                        <span>{{ __('LƯU THÔNG TIN') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</form>
