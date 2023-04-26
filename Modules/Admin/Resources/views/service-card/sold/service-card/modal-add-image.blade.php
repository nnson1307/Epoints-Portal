<div class="modal fade show" id="addImage" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM HÌNH ẢNH')}}
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group ">
                    <form id="detail-service-card-sold">
                    <label>{{__('Ảnh kèm theo')}}:</label>
                    {{csrf_field()}}
                    <div class="m-dropzone dropzone dz-clickable"
                         action="{{route('admin.upload-image')}}"
                         id="dropzoneone">
                        <div class="m-dropzone__msg dz-message needsclick">
                            <h3 href="" class="m-dropzone__msg-title">
                                {{__('Hình ảnh')}}
                            </h3>
                            <span>{{__('Chọn hình sản phẩm')}}.</span>
                        </div>
                        <input type="hidden" id="file_image" name="product_image" value="">
                        <input type="hidden" id="order_code" name="order_code" value="">
                        <input type="hidden" id="type" name="type" value="">
                        <input type="hidden" id="key" value="">
                    </div>
                        <div class="mt-4">
                            <div id="up-ima" style="display:flex;justify-content: center;">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>
                        <button type="button" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 btn-save-image"
                            onclick="serviceCardSoldImage.save_image()">
							<span>
							<i class="la la-check"></i>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>