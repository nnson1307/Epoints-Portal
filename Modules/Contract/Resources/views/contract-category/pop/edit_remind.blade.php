<div class="modal fade show" id="edit_remind" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA NHẮC NHỞ')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-category">
                    <input type="text" hidden id="pop_contract_category_remind_id" name="pop_contract_category_remind_id" value="{{$item['contract_category_remind_id']}}">
                    <input type="text" hidden id="number_remind" name="number_remind">
                    <div class="row">
                        <div class="col-lg-12">
                            <span class=" float-right m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px">
                                    <input type="checkbox" {{$item['is_actived'] == 1 ? 'checked' : ''}} class="manager-btn" name="pop_is_actived" id="pop_is_actived">
                                    <span></span>
                                </label>
                            </span>
                            <label class="float-right">
                                {{__('Trạng thái')}}:
                            </label>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Loại nhắc nhở'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control select" id="pop_remind_type" name="pop_remind_type" style="width:100%;"
                                            onchange="$('#pop_title').val($('#pop_remind_type option:selected').text())">
                                        <option></option>
                                        @foreach($optionRemindType as $key => $value)
                                            @if($value['remind_type_code'] == $item['remind_type'])
                                                <option value="{{$value['remind_type_code']}}" selected>{{$value['remind_type_name']}}</option>
                                            @else
                                                <option value="{{$value['remind_type_code']}}">{{$value['remind_type_name']}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tiêu đề'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input id="pop_title" name="pop_title" value="{{$item['title']}}" type="text" class="form-control m-input class"
                                           placeholder="{{__('Tiêu đề')}}"
                                           aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
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
                                            <option value=""></option>
                                            @foreach($optionTabGeneral as $key => $value)
                                                <option value="{{$value['key']}}">{{$value['key_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <textarea class="form-control col-lg-12" placeholder="{{__('Nội dung')}}" id="pop_content" name="pop_content" style="height: 75px">{{$item['content']}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Thời gian gửi'):<b class="text-danger">*</b>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group m-form__group col-lg-4">
                                    <div class="input-group-prepend">
                                        <div class="m-input-icon m-input-icon--right">
                                            <select class="form-control select" style="width:60px !important;"
                                                    onchange="contractCategories.disabledUnitValue(this)"
                                                    id="pop_recipe"
                                                    name="pop_recipe">
                                                <option value="<" {{$item['recipe'] == '<' ? 'selected' : ''}}><</option>
                                                <option value="=" {{$item['recipe'] == '=' ? 'selected' : ''}}>=</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control" value="{{$item['unit_value']}}" {{$item['recipe'] == '=' ? 'disabled' : ''}}  name="pop_unit_value" id="pop_unit_value"
                                               placeholder="">
                                        <div class="m-input-icon m-input-icon--right">
                                            <select class="form-control select" style="width:80px !important;"
                                                    id="pop_unit"
                                                    name="pop_unit">
                                                <option value="D" {{$item['unit'] == 'D' ? 'selected' : ''}}>@lang('Ngày')</option>
                                                <option value="W" {{$item['unit'] == 'W' ? 'selected' : ''}}>@lang('Tuần')</option>
                                                <option value="M" {{$item['unit'] == 'M' ? 'selected' : ''}}>@lang('Tháng')</option>
                                                <option value="Q" {{$item['unit'] == 'Q' ? 'selected' : ''}}>@lang('Quý')</option>
                                                <option value="Y" {{$item['unit'] == 'Y' ? 'selected' : ''}}>@lang('Năm')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="pop_unit_value-error" class="form-control-feedback" hidden>
                                        @lang('Hãy nhập giá trị')
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-8">
                                    <div class="input-group">
                                        <select class="form-control" id="pop_compare_unit" name="pop_compare_unit" style="width:100%;">
                                            @foreach($optionDateTabGeneral as $key => $value)
                                                @if($value['key'] == $item['compare_unit'])
                                                    <option value="{{$value['key']}}" selected>{{$value['key_name']}}</option>
                                                @else
                                                    <option value="{{$value['key']}}">{{$value['key_name']}}</option>
                                                @endif
                                            @endforeach
                                            <option value="expected_receive_date" {{$item['compare_unit'] == 'expected_receive_date' ? 'selected' : ''}}>@lang('Ngày dự kiến thu')</option>
                                            <option value="expected_spend_date" {{$item['compare_unit'] == 'expected_spend_date' ? 'selected' : ''}}>@lang('Ngày dự kiến chi')</option>
                                            <option value="contract_due_date" {{$item['compare_unit'] == 'contract_due_date' ? 'selected' : ''}}>@lang('Ngày sắp hết hạn hợp đồng')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Người nhận'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control select" id="pop_receiver_by" name="pop_receiver_by" style="width:100%;" multiple>
                                        @foreach($optionReceiverBy as $key => $value)
                                            @if(in_array($value['key'], $lstReceiver))
                                                <option value="{{$value['key']}}" selected>{{$value['key_name']}}</option>
                                            @else
                                                <option value="{{$value['key']}}">{{$value['key_name']}}</option>
                                            @endif
                                        @endforeach
                                        <option value="created_by" {{in_array('created_by', $lstReceiver) ? 'selected' : ''}}>{{__('Người tạo')}}</option>
                                        <option value="updated_by" {{in_array('updated_by', $lstReceiver) ? 'selected' : ''}}>{{__('Người cập nhật')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Hình thức nhắc nhở'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control select" id="pop_remind_method" name="pop_remind_method" style="width:100%;" multiple>
                                        <option value="staff_notify" {{in_array('staff_notify', $lstMethod) ? 'selected' : ''}}>@lang('Thông báo')</option>
                                        <option value="email" {{in_array('email', $lstMethod) ? 'selected' : ''}}>@lang('Email')</option>
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
                    <button type="button" onclick="contractCategories.submitEditRemind()"
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
