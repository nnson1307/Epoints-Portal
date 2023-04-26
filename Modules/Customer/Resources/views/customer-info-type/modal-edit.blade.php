<div class="modal fade show" id="modal-edit" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA HOA HỒNG NHÂN VIÊN')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Loại thông tin kèm theo (tiếng Việt)'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="customer_info_type_name_vi"
                                   name="customer_info_type_name_vi" value="{{$item['customer_info_type_name_vi']}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Loại thông tin kèm theo (tiếng Anh)'):
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="customer_info_type_name_en"
                                   name="customer_info_type_name_en" value="{{$item['customer_info_type_name_en']}}">
                        </div>
                    </div>
                </form>
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
                    <button type="button" onclick="edit.save('{{$item['customer_info_type_id']}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>