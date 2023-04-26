<div class="modal fade" id="modal-person-contact" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    @lang("CHỈNH SỬA THÔNG TIN NGƯỜI LIÊN HỆ")
                </h4>
            </div>
            <div class="modal-body">
                <form id="form-person-contact">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên người liên hệ'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input id="person_name" name="person_name" type="text" class="form-control m-input class" value="{{$info['person_name']}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Số điện thoại'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input id="person_phone" name="person_phone" type="text" class="form-control m-input class" value="{{$info['person_phone']}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Email'):
                        </label>
                        <div class="input-group">
                            <input id="person_email" name="person_email" type="text" class="form-control m-input class" value="{{$info['person_email']}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Chức vụ'):
                        </label>
                        <div class="input-group">
                            <select id="staff_title_id" name="staff_title_id" class="form-control m-input class" style="width: 100%;">
                                <option></option>
                                @foreach($optionTitle as $v)
                                    <option value="{{$v['staff_title_id']}}" {{$v['staff_title_id'] == $info['staff_title_id'] ? 'selected': ''}}>
                                        {{$v['staff_title_name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                    </button>
                    <button type="button" onclick="detail.updatePersonContact('{{$info['customer_person_contact_id']}}', '{{$info['customer_id']}}')"
                            class="btn btn-primary  color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>@lang("CHỈNH SỬA")</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>