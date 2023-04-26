<div id="modal-survey">
    <div class="modal fade" id="coppyUrl_survey" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding:10px">
                    <h5 class="modal-title m-portlet__head-text tab" id="exampleModalLabel">
                        <i class="flaticon-list-1 pr-3"></i>
                        @lang('Sao chép URL')
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="kt-section__content row" id="popup-list-group" style="margin:0">
                        <div class="form-group" style="margin:0">
                            <h6 class="title" style="margin:0">{{ __('Link') }}</h6>
                        </div>
                        <div class="col-12" style="padding:0">
                            <div class="input-group date" style="padding:0">
                                <input type="text" id="url" readonly class="form-control m-input"
                                    style="border: none;
                                border-bottom: 1px solid #8080806e;
                                padding:0;
                                border-radius: 0px;"
                                    value="{{ $data['short_link'] }}">
                            </div>
                            <p id="text_url" style="display:none">{{ $data['short_link'] }}</p>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="kt-checkbox kt-checkbox--bold"
                                style="color: black;
                            font-weight: 500;">
                                <input type="checkbox" id="coppy_url"> {{ __('Shorten URL') }}
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-search" style="color:black; border:1px solid"
                        data-dismiss="modal">
                        @lang('Không')
                    </button>
                    <button type="button" onclick="survey.CoppyURL()"
                        id="btn-add-group-child-to-list" class="btn btn-primary color_button_destroy color_button">
                        @lang('Sao chép')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
