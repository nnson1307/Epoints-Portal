<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-comment ss--icon-title m--margin-right-5"></i>
            {{ __('managerwork::managerwork.process') }}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body" id="form-repeat-modal">
        <form class="container" id="update_date_end">
            <div class="row">
                <div class="col-lg-12  d-none">
                    <input type="hidden" name="manage_work_id" id="manage_work_id_comment" value="{{$manage_work_id}}">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Ngày bắt đầu')
                        </label>
                        <div class="row">
                            <div class="col-4">
                                <input type="text" class="form-control m-input time-input" value="{{$detail != null && isset($detail['date_start']) && $detail['date_start'] != '' && $detail['date_start'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_start'])->format('H:i') : ''}}"
                                       placeholder="@lang('Giờ')" name="time_start">
                            </div>
                            <div class="col-8">
                                <div class="input-group date date-multiple">
                                    <input type="text" class="form-control m-input daterange-input" value="{{$detail != null && isset($detail['date_start']) && $detail['date_start'] != '' && $detail['date_start'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_start'])->format('d/m/Y') : ''}}"
                                           placeholder="@lang('Ngày bắt đầu')" name="date_start">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                    class="la la-calendar-check-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Ngày kết thúc') <b class="text-danger">*</b>
                        </label>
                        <div class="row">
                            <div class="col-4">
                                <input type="text" class="form-control m-input time-input"
                                       {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detail != null && isset($detail['date_end']) && $detail['date_end'] != '' && $detail['date_end'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_end'])->format('H:i') : ''}}"
                                       placeholder="@lang('Giờ')" name="time_end">
                            </div>
                            <div class="col-8">
                                <div class="input-group date date-multiple">
                                    <input type="text" class="form-control m-input daterange-input"
                                           {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detail != null && isset($detail['date_end']) && $detail['date_end'] != '' && $detail['date_end'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_end'])->format('d/m/Y') : ''}}"
                                           placeholder="@lang('Ngày kết thúc')" name="date_end">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                    class="la la-calendar-check-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-5 text-right">
                    <button type="submit"
                            class="mt-3 ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">{{ __('managerwork::managerwork.update') }}</button>
                    <button data-dismiss="modal"
                            class="mt-3 ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-arrow-left"></i>
                        <span>{{ __('HỦY') }}</span>
                    </span>
                    </button>
                </div>
        </form>
    </div>
    {{--    <div class="modal-footer">--}}
    {{--        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">--}}
    {{--            <div class="m-form__actions m--align-right">--}}
    {{--                --}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</div>