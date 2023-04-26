<div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <form class="modal-content" id="form-edit-material">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold mt-2">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{ __('CẬP NHẬT PHIẾU YÊU CẦU VẬT TƯ') }}
                </h4>
                <div class="modal-title ss--title m--font-bold">
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-header p-0 mb-3">
                    <h5>@lang('Thông tin đề xuất')</h5>
                </div>
                <div class="d-flex">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mã ticket'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" name="ticket_code2" class="form-control" value="{{ $item['ticket_code'] }}" disabled>
                                <input type="hidden" name="ticket_request_material_id">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{ __('Nội dung đề xuất') }}:<b class="text-danger">*</b>
                            </label>
                            <textarea class="form-control m-input" name="description" rows="5" cols="5"
                                placeholder="{{ __('Nhập nội dung đề xuất') }}..."></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{ __('Người đề xuất') }}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="proposer_by" class="form-control m-input" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{ __('Ngày đề xuất') }}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="proposer_date" class="form-control m-input" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="status_material" class="form-control select2 select2-active" disabled>
                                    <option value="">{{ __('Chọn trạng thái') }}</option>
                                    @foreach ($filter as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
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
{{--                                <th class="ss--font-size-th">{{ __('Số lượng duyệt') }}</th>--}}
                                <th class="ss--font-size-th">{{ __('Số lượng tồn kho') }}</th>
                                <th class="ss--font-size-th">{{ __('Số lượng tạm ứng') }}</th>
                                <th class="ss--font-size-th">{{ __('Đơn vị tính') }}</th>
                                {{-- <th class="ss--font-size-th">{{ __('Trạng thái') }}</th> --}}
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
        
                        <button type="submit" onclick="Material.submitEdit()"
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
    </div>
</div>
