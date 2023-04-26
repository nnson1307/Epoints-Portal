<form action="" id="form-submit-template">
    <div class="modal fade" id="modal_point" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title primary-color" id="exampleModalLabel">
                        {{ __('Cài đặt khảo sát có tính điểm') }}
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="m-form__group form-group row">
                        <label class="col-xl-12 col-lg-12 mb-5" style="font-weight:bold; font-size:16px">
                            {{ __('Hiển thị đáp án') }}
                        </label>
                        <div class="col-lg-12 col-xl-12">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="m-radio cus">
                                        <input type="radio" name="show_answer"
                                            onclick="question.toggleConfigPoint(this)" value="N"
                                            {{ $data->show_answer == 'N' ? 'checked' : '' }}>
                                        {{ __('Ngay sau khi hoàn thành khảo sát') }}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="m-radio cus">
                                        <input type="radio" name="show_answer"
                                            onclick="question.toggleConfigPoint(this)"
                                            {{ $data->show_answer == 'E' ? 'checked' : '' }} value="E">
                                        {{ __('Khi hết thời gian hiệu lực của khảo sát') }}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-12">
                                    <div class="row" style="align-items: center;">
                                        <div class="col-3">
                                            <label class="m-radio cus">
                                                <input type="radio" name="show_answer"
                                                    {{ $data->show_answer == 'C' ? 'checked' : '' }}
                                                    onclick="question.toggleConfigPoint(this)" value="C">
                                                {{ __('Cấu hình thời gian') }}
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-4 d-flex justify-content-center align-items-center">
                                            <div class="input-group date">
                                                <input type="text" disabled readonly class="form-control m-input"
                                                    id="start_date" value="" placeholder="@lang('survey::survey.create.time_start')"
                                                    name="start_date">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i
                                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 ml-5">
                                            <div class="input-group date">
                                                <input type="text" disabled readonly class="form-control m-input"
                                                    placeholder="@lang('survey::survey.create.time_end')" value="" id="end_date"
                                                    name="end_date">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i
                                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-form__group form-group row align-items-center d-flex">
                        <div class="col-lg-7 col-xl-7">
                            <h6 style="font-weight:bold; font-size:14px">{{ __('Câu hỏi bị bỏ lỡ') }}</h6>
                            <p>{{ __('Người trả lời có thể biết câu hỏi nào đã được trả lời sai hoặc bỏ lỡ.') }}</p>
                        </div>
                        <div class="col-lg-4 col-xl-4 d-flex justify-content-end mr-5">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px;">
                                    <input type="checkbox" {{ $data->show_answer_wrong ? 'checked' : '' }}
                                        id="show_answer_wrong" class="manager-btn receipt_info_check">
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="m-form__group form-group row align-items-center d-flex">
                        <div class="col-lg-7 col-xl-7">
                            <h6 style="font-weight:bold; font-size:14px">{{ __('Câu trả lời đúng') }}</h6>
                            <p>{{ __('Người trả lời có thể thấy câu trả lời đúng sau khi đáp án được công bố.') }}</p>
                        </div>
                        <div class="col-lg-4 col-xl-4 d-flex justify-content-end mr-5">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px;">
                                    <input type="checkbox" id="show_answer_success"
                                        {{ $data->show_answer_success ? 'checked' : '' }}
                                        class="manager-btn receipt_info_check">
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="m-form__group form-group row align-items-center d-flex">
                        <div class="col-lg-7 col-xl-7">
                            <h6 style="font-weight:bold; font-size:14px">{{ __('Giá trị điểm') }}</h6>
                            <p>{{ __('Người trả lời có thể xem tổng số điểm và số điểm nhận được cho mỗi câu hỏi.') }}
                            </p>
                        </div>
                        <div class="col-lg-4 col-xl-4 d-flex justify-content-end mr-5">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px;">
                                    <input type="checkbox" id="show_point" {{ $data->show_point ? 'checked' : '' }}
                                        class="manager-btn receipt_info_check">
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="m-form__group form-group row align-items-center d-flex">
                        <div class="col-lg-7 col-xl-7">
                            <h6 style="font-weight:bold; font-size:14px">{{ __('Tính điểm cho câu hỏi tự luận') }}</h6>
                            <p>{{ __('Cho phép tính điểm câu hỏi tự luận') }}</p>
                        </div>
                        <div class="col-lg-4 col-xl-4 d-flex justify-content-end mr-5">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px;">
                                    <input type="checkbox" id="count_point_text"
                                        {{ $data->count_point_text ? 'checked' : '' }}
                                        class="manager-btn receipt_info_check">
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="m-form__group form-group row align-items-center d-flex">
                        <div class="col-lg-7 col-xl-7">
                            <h6 style="font-weight:bold; font-size:14px">
                                {{ __('Giá trị điểm mặc định cho mỗi câu hỏi') }}</h6>
                            <p>{{ __('Điểm mặc định cho mọi câu hỏi mới.') }}</p>
                        </div>
                        <div class="col-lg-4 col-xl-4 d-flex justify-content-end mr-5">
                            <input type="text" name="" value="{{ $data->point_default }}"
                                class="form-control numeric" style="width: 50px;" id="point_default">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary color_button btn-search"
                        onclick="question.updateConfigPoint('{{ $data->id_config_point }}')">@lang('Lưu')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Hủy')</button>
                </div>
            </div>
        </div>
    </div>
</form>
