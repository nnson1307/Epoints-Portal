{{-- <div class="modal fade show" id="add-group-potential"> --}}
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title">
                    <i class="fa fa-address-book"></i> {{__('Tạo bản sao mẫu zns')}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        {{__('Tên mẫu zns')}}
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="zns_template_id" class="form-control">
                        <input type="text" name="template_name" class="form-control">
                    </div>
                </div>
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
                    <button type="button" onclick="Template.cloneAction()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Đồng ý')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}