<div class="modal fade show" id="modal-create" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('THÊM HOA HỒNG NHÂN VIÊN')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nhân viên'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class="form-control" id="staff_id" name="staff_id"
                                    style="width:100%;" {{$staffAvailable != '' ? 'disabled' : ''}}>
                                <option></option>
                                @foreach($optionStaff as $v)
                                    <option value="{{$v['staff_id']}}" {{$staffAvailable == $v['staff_id'] ? 'selected' : ''}}>
                                        {{$v['full_name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tiền hoa hồng'):
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="staff_money" name="staff_money">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Ghi chú'):
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="note" name="note">
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
                    <button type="button" onclick="create.save()"
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