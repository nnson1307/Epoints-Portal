<div class="modal fade show" id="modal-update-status" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-edit"></i> @lang('CẬP NHẬT TRẠNG THÁI')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-status">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Trạng thái'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class="form-control" id="status_update_code" name="status_update_code" style="width:100%;">
                                @foreach($optionStatusUpdate as $v1)
                                    <option value="{{$v1['status_code']}}" {{$item['status_code'] == $v1['status_code'] ? 'selected': ''}}>{{$v1['status_name']}}</option>
                                @endforeach
                            </select>
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
                    <button type="button" onclick="detail.updateStatus('{{$item['contract_id']}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('ĐỒNG Ý')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
