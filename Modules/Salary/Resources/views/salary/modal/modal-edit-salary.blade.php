<div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-edit ss--icon-title m--margin-right-5"></i>
                    {{ __('CHỈNH SỬA BẢNG LƯƠNG') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="modal-body" id="editForm">
                <div class="form-group m-form__group">
                    <label class="black_title d-block">
                        {{ __('Kỳ lương') }}:<b class="text-danger">*</b>
                    </label>
                    <div class="input-group date">
                        <input type="text" class="form-control m-input month-picker" disabled
                               placeholder="@lang('Chọn kỳ lương')"
                               value="{{$detail != null ? $detail['season_month'].'/'.$detail['season_year'] : ''}}">
                        <div class="input-group-append">
                    <span class="input-group-text"><i
                                class="la la-calendar-check-o glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title d-block">
                        {{ __('Thời gian') }}:<b class="text-danger">*</b>
                    </label>
                    <div class="input-group date">
                        <input type="text" class="form-control m-input"  disabled
                               placeholder="@lang('Thời gian')" value="{{$detail != null ? \Carbon\Carbon::parse($detail['date_start'])->format('d/m/Y').' - '.\Carbon\Carbon::parse($detail['date_end'])->format('d/m/Y') : ''}}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{ __('Tên bảng lương') }}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" name="name" class="form-control m-input"
                           placeholder="{{ __('Nhập tên bảng lương') }}..." value="{{$detail != null ? $detail['name'] : ''}}">
                </div>
                <input type="hidden" name="salary_id" value="{{$detail['salary_id']}}">
            </form>
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

                        <button type="button" onclick="SalaryData.editClose()"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-check"></i>
                        <span>{{ __('LƯU THÔNG TIN') }}</span>
                    </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>