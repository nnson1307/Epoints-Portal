<div class="modal fade show" id="assign-staff" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-user-plus"></i>@lang('PHÂN BỔ NHÂN VIÊN')
                </h5>
            </div>
            <form id="form-assign">
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên nhân viên'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select name="staff_id" id="staff_id" class="form-control" style="width: 100%">
                                <option></option>
                                @foreach($optionStaff as $v)
                                    <option value="{{$v['staff_id']}}"
                                            {{$item['staff_id'] == $v['staff_id'] ? 'selected': ''}}>{{$v['full_name']}}</option>
                                @endforeach
                            </select>
                        </div>
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
                        <button type="button" onclick="list.submitAssign({{$item['extension_id']}})"
                                class="btn btn-info color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU')}}</span>
							</span>
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>