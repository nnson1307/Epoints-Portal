<div class="modal fade show" id="config" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-cog"></i>@lang('CẤU HÌNH GỬI EMAIL')
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--</button>--}}
            </div>
            <form id="form-config">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="pass_check" name="pass_check">
                    <div class="form-group m-form__group">
                        <div class="row">
                            {{--<label class="col-lg-4">Bật/Tắt cấu hình:</label>--}}
                            {{--<div class="col-lg-8">--}}
                            {{--<div class="m-checkbox-inline">--}}
                            {{--<label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success">--}}
                            {{--<input type="checkbox" id="is_actived" name="is_actived"--}}
                            {{--onclick="auto.click_auto(this)"> {{__('Chọn')}}--}}
                            {{--<span></span>--}}
                            {{--</label>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <label class="col-form-label col-lg-4 col-sm-12">{{__('Bật/tắt cấu hình')}}:</label>
                            <div class="col-lg-8 col-md-9 col-sm-12">
                                <input data-switch="true" type="checkbox" checked="checked" id="is_actived">
                            </div>

                        </div>
                    </div>
                    <div class="setting_on">

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-form__actions m--align-right w-100">
                        <a href="javascript:void(0)" data-dismiss="modal"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                           <span>
                            <i class="la la-arrow-left"></i>
                               <span>{{__('HỦY')}}</span>
                           </span>
                        </a>
                        <button type="button" onclick="auto.submit_config()"
                                class="btn btn-info color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHỈNH SỬA')}}</span>
							</span>
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>