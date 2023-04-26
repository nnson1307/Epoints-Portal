<div class="modal fade show" id="modal-list-staff" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-edit"></i> @lang('CHỌN NHÂN VIÊN SALE BỊ THU HỒI')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-assign">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Nhân viên'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="staff" name="staff"
                                            style="width:100%;" >
                                        <option></option>
                                        @foreach($optionStaff as $item)
                                            <option value="{{$item['staff_id']}}">{{$item['full_name']}}</option>
                                        @endforeach
                                    </select>
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
                    <button type="button" onclick="listLead.submitRevoke()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('THU HỒI')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
