<div class="modal fade show" id="modal-description">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM THÔNG TIN CHI TIẾT')}}
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label>
                        <i class="fa fa-edit"></i>
                        {{__('Thông tin chi tiết')}}
                    </label>
                    <div class="summernote"></div>
                    {{--<textarea class=" form-control m-input" name="detail_description"--}}
                    {{--id="detail_description"></textarea>--}}
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('THOÁT')}}</span>
						</span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>