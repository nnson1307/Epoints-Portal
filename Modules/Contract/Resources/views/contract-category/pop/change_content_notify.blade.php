<div class="modal fade show" id="change_content_notify" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA NỘI DUNG THÔNG BÁO')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-category">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Nội dung'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group-prepend row" style="margin-left: -3px;">
                                    <div class="m-input-icon m-input-icon--right col-lg-12">
                                        <select class="form-control" id="pop_parameter_for_content" name="pop_parameter_for_content" style="width:100%;"
                                                multiple
                                                onchange="contractCategories.appendContent();">
                                            <option value="contract_code" {{$checkContractCode == 1 ? 'selected' : ''}}>@lang('Mã hợp đông')</option>
                                            <option value="status_code" {{$checkStatusCode == 1 ? 'selected' : ''}}>@lang('Trạng thái')</option>
                                        </select>
                                    </div>
                                    <textarea class="form-control col-lg-12" placeholder="{{__('Nội dung')}}" id="pop_content" name="pop_content" style="height: 75px">{{$content}}</textarea>
                                </div>
                            </div>
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
                    <button type="button" onclick="contractCategories.saveChangeContentNotify('{{$status_code}}')"
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
