<div class="modal fade" id="modalReplace" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <form class="modal-content" id="form-replace-material">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold mt-2">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{ __('THAY THẾ VẬT TƯ') }}
                </h4>
                <div class="modal-title ss--title m--font-bold">
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-header p-0 mb-3">
                    <h5>@lang('Danh sách vật tư đề xuất')</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped m-table ss--header-table ss--nowrap table-propose">
                        <thead>
                            <tr>
                                <th class="ss--font-size-th">#</th>
                                <th class="ss--font-size-th">{{ __('Mã vật tư') }}</th>
                                <th class="ss--font-size-th">{{ __('Tên vật tư') }}</th>
                                <th class="ss--font-size-th">{{ __('Số lượng tạm ứng') }}</th>
                                <th class="ss--font-size-th">{{ __('Số lượng tồn kho') }}</th>
                                <th class="ss--font-size-th">{{ __('Số lượng duyệt') }}</th>
                                <th class="ss--font-size-th">{{ __('Đơn vị tính') }}</th>
                                <th class="ss--font-size-th">{{ __('Trạng thái') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-header p-0 mb-3">
                    <h5>@lang('Danh sách vật tư thay thế')</h5>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Chọn vật tư cần thay thế'):
                    </label>
                    <div class="input-group">
                        <select name="material_replace" class="form-control select2 select2-active">
                            <option value="">{{ __('Chọn vật tư cần thay thế') }}</option>
                            @foreach ($listMaterial as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped m-table ss--header-table ss--nowrap table-add-material text-center">
                        <thead>
                            <tr>
                                <th class="ss--font-size-th">#</th>
                                <th class="ss--font-size-th">{{ __('Mã vật tư') }}</th>
                                <th class="ss--font-size-th text-left">{{ __('Tên vật tư') }}</th>
                                <th class="ss--font-size-th">{{ __('Số lượng tạm ứng') }}</th>
                                <th class="ss--font-size-th">{{ __('Số lượng tồn kho') }}</th>
                                <th class="ss--font-size-th">{{ __('Số lượng duyệt') }}</th>
                                <th class="ss--font-size-th">{{ __('Đơn vị tính') }}</th>
                                <th class="ss--font-size-th">{{ __('Trạng thái') }}</th>
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
        
                        <button type="submit"
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
