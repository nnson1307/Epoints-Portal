<div class="modal fade show" id="modal-edit" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title text-uppercase" id="exampleModalLabel">
                    <i class="la la-edit"></i> @lang('CẤU HÌNH ĐƠN PHÉP') {{ $title }}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-edit">
                    <div class="row mt-1">
                        <div class="col-lg-8">
                            <div class="mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" id="ckb_limit_number_time_off_by_year" class="is_status" {{ isset($data['limit_number_time_off_by_year']) && $data['limit_number_time_off_by_year'] != '' ? 'checked': '' }} onclick="timeofftype.checkConfig(this, 'limit_number_time_off_by_year')"> 
                                    @lang('Giới hạn số ngày nghỉ phép theo năm'):                                       
                                    <span></span>
                                </label>
                            </div>
                            <span class="form-control-feedback" id="error_limit_number_time_off_by_year" style="display:none">
                                @lang('Chưa điền số ngày nghỉ phép theo năm')
                           </span>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="number" value="{{ $data['limit_number_time_off_by_year'] ?? '' }}" name="limit_number_time_off_by_year" id="limit_number_time_off_by_year" class="form-control" {{ isset($data['limit_number_time_off_by_year']) && $data['limit_number_time_off_by_year'] != '' ? '': 'disabled' }}>
                                <div class="input-group-append">
                                    <span class="input-group-text">@lang('Ngày')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-12 d-flex d-inline-flex">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" name="ckb_system_auto_reset_time_off" class="" {{ isset($data['system_auto_reset_time_off']) && $data['system_auto_reset_time_off'] != '' ? 'checked' : '' }} onclick="timeofftype.checkConfig(this, 'system_auto_reset_time_off')"> 
                                    @lang('Hệ thống tự động tự động thiết lập lại số ngày nghĩ vào tháng'):<span></span>
                                </label>
                            </div>
                            <div class="ml-2 w-50">
                                <select id="system_auto_reset_time_off" name="system_auto_reset_time_off" class="w-50 form-control op_day" {{ isset($data['system_auto_reset_time_off']) && $data['system_auto_reset_time_off'] != '' ? '' : 'disabled' }}>
                                    @for($i = 1; $i <= 12; $i ++)
                                        
                                    <option value="{{$i}}" {{ $data['system_auto_reset_time_off'] ?? '' == $i ? 'selected' : '' }} >{{$i}}</option>
                                    @endfor
                                </select> 
                                
                            </div>
                            <div class="ml-2 mt-3">
                                <label>@lang('mỗi năm')</label>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-12 d-flex d-inline-flex">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" name="ckb_number_contract_bonus_time_off" class="" {{ isset($data['number_contract_bonus_time_off']) && $data['number_contract_bonus_time_off'] != '' ? 'checked' : '' }} onclick="timeofftype.checkConfig(this, 'number_contract_bonus_time_off')"> 
                                    @lang('Yêu cầu ký hợp động phải từ'):<span></span>
                                </label>
                            </div>
                            <div class="ml-2 w-50">
                                <select id="number_contract_bonus_time_off" name="number_contract_bonus_time_off" class="w-50 form-control op_day" {{ isset($data['number_contract_bonus_time_off']) && $data['number_contract_bonus_time_off'] != '' ? '' : 'disabled' }}>
                                    @for($i = 1; $i <= 12; $i ++)
                                        
                                    <option value="{{$i}}" {{ $data['number_contract_bonus_time_off'] ?? '' == $i ? 'selected' : '' }} >{{$i}} @lang('tháng')</option>
                                    @endfor
                                </select> 
                                
                            </div>
                            <div class="ml-2 mt-3">
                                <label>@lang('trở lên mới được cộng vào quỹ năm')</label>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-12 d-flex d-inline-flex">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" name="ckb_request_time_off_from_1_3" class="" {{ isset($data['request_time_off_from_1_3']) && $data['request_time_off_from_1_3'] == 'true' ? 'checked' : '' }} onclick="timeofftype.checkConfigDefault(this, 'request_time_off_from_1_3')"> 
                                    @lang('Nghĩ phép từ 1-3 ngày yêu cầu gửi đơn trước 1 ngày'):<span></span>
                                </label>
                                <input type="hidden" value="{{ $data['request_time_off_from_1_3'] ?? 'false' }}" name="request_time_off_from_1_3" id="request_time_off_from_1_3">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-12 d-flex d-inline-flex">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" name="ckb_request_time_off_from_3_5" class="" {{ isset($data['request_time_off_from_3_5']) && $data['request_time_off_from_3_5'] == 'true' ? 'checked' : '' }} onclick="timeofftype.checkConfigDefault(this, 'request_time_off_from_3_5')"> 
                                    @lang('Nghĩ phép từ 3-5 ngày yêu cầu gửi đơn trước 3 ngày'):<span></span>
                                </label>
                            </div>
                            <input type="hidden" value="{{ $data['request_time_off_from_3_5'] ?? 'false' }}" name="request_time_off_from_3_5" id="request_time_off_from_3_5">
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-12 d-flex d-inline-flex">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" name="ckb_request_time_off_over_5" class="" {{ isset($data['request_time_off_over_5']) && $data['request_time_off_over_5'] == 'true' ? 'checked' : '' }} onclick="timeofftype.checkConfigDefault(this, 'request_time_off_over_5')"> 
                                    @lang('Nghĩ phép trên 5 ngày yêu cầu gửi đơn trước 7 ngày'):<span></span>
                                </label>
                            </div>
                        </div>
                        <input type="hidden" value="{{ $data['request_time_off_over_5'] ?? 'false' }}" name="request_time_off_over_5" id="request_time_off_over_5">
                    </div>

                    <div class="row mt-1">
                        <div class="col-lg-12 d-flex d-inline-flex">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" name="ckb_number_day_auto_approve" class="" {{ isset($data['number_day_auto_approve']) && $data['number_day_auto_approve'] != '' ? 'checked' : '' }} onclick="timeofftype.checkConfig(this, 'number_day_auto_approve')"> 
                                    @lang('Hệ thống tự động gửi lại yêu cầu duyệt đơn sau'):<span></span>
                                </label>
                            </div>
                            <div class="ml-2 w-50">
                                <select id="number_day_auto_approve" name="number_day_auto_approve" class="w-50 form-control op_day" {{ isset($data['number_day_auto_approve']) && $data['number_day_auto_approve'] != '' ? '' : 'disabled' }}>
                                    @for($i = 1; $i <= $day; $i ++)
                                        
                                    <option value="{{$i}}" {{ $data['number_day_auto_approve'] ?? '' == $i ? 'selected' : '' }} >{{$i}}</option>
                                    @endfor
                                </select> 
                                
                            </div>
                            <div class="ml-2 mt-3">
                                <label>@lang('giờ nếu người duyệt chưa duyệt')</label>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-12">
                            <div>
                                <label>
                                    <b>@lang('Thông tin người duyệt')</b>: 
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-6">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" id="ckb_approve_level_1" {{ isset($data['approve_level_1']) && $data['approve_level_1'] != '' ? 'checked' : '' }} onclick="timeofftype.checkConfig(this, 'approve_level_1')"> 
                                    @lang('Người duyệt cấp 1'):<span></span>
                                </label>
                            </div>
                            <span class="form-control-feedback" id="error_approve_level_1" style="display:none">
                                @lang('Chưa chọn người duyệt cấp 1')
                           </span>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <select id="approve_level_1" readonly="true" name="approve_level_1" placeholder="Chọn người duyệt" class="form-control m_selectpicker" {{ isset($data['approve_level_1']) && $data['approve_level_1'] != '' ? '' : 'disabled' }}>
                                    <option 
                                        value="">@lang('Chọn người duyệt')
                                    </option>
                                    @foreach($staffTitle as $items)
                                        <option 
                                            value="{{$items['staff_title_id']}}" {{ (string)$items['staff_title_id'] == ($data['approve_level_1'] ?? '') ? 'selected' : '' }}>{{$items['staff_title_name']}}
                                        </option>
                                    @endforeach
                                </select>
                            
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-6">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" name="ckb_approve_level_2" {{ isset($data['approve_level_2']) && $data['approve_level_2'] != '' ? 'checked' : '' }} onclick="timeofftype.checkConfig(this, 'approve_level_2')"> 
                                    @lang('Người duyệt cấp 2'):<span></span>
                                </label>
                            </div>
                            <span class="form-control-feedback" id="error_approve_level_2" style="display:none">
                                @lang('Chưa chọn người duyệt cấp 2')
                           </span>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <select id="approve_level_2" readonly="true" name="approve_level_2" placeholder="Chọn người duyệt" class="form-control m_selectpicker" {{ isset($data['approve_level_2']) && $data['approve_level_2'] != '' ? '' : 'disabled'  }}>
                                    <option 
                                        value="">@lang('Chọn người duyệt')
                                    </option>
                                    @foreach($staffTitle as $items)
                                        <option 
                                            value="{{$items['staff_title_id']}}" {{ (string)$items['staff_title_id'] == ($data['approve_level_2'] ?? '') ? 'selected' : '' }}>{{$items['staff_title_name']}}
                                        </option>
                                    @endforeach
                                </select>
                            
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-6">
                            <div class="m-checkbox-inline mt-3">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" id="ckb_approve_level_3" {{ isset($data['approve_level_3']) && $data['approve_level_3'] != '' ? 'checked' : '' }} onclick="timeofftype.checkConfig(this, 'approve_level_3')"> 
                                    @lang('Người duyệt cấp 3'):<span></span>
                                </label>
                            </div>
                            <span class="form-control-feedback" id="error_approve_level_3" style="display:none">
                                @lang('Chưa chọn người duyệt cấp 3')
                           </span>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <select id="approve_level_3" readonly="true" name="approve_level_3" placeholder="Chọn người duyệt" class="form-control m_selectpicker" {{ isset($data['approve_level_3']) && $data['approve_level_3'] != '' ? '' : 'disabled'  }}>
                                    <option 
                                        value="">@lang('Chọn người duyệt')
                                    </option>
                                    @foreach($staffTitle as $items)
                                        <option 
                                            value="{{$items['staff_title_id']}}" {{ (string)$items['staff_title_id'] == ($data['approve_level_3'] ?? '')  ? 'selected' : '' }}>{{$items['staff_title_name']}}
                                        </option>
                                    @endforeach
                                </select>
                            
                            </div>
                        </div>
                    </div>
                    <input name="time_off_type_code" id="time_off_type_code" value="{{$code}}" type="hidden"/>
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
                    <button onclick="timeofftype.update()" type="button" 
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
<style>
    .w-50{
        width: 70px !important;
    }
</style>
<script>
    $('#approve_level_1').select2({
            width: "100%",
        });
    $('#approve_level_2').select2({
            width: "100%"
        });
    $('#approve_level_3').select2({
            width: "100%"
        });
    $('#number_day_auto_approve').select2({
            width: "100%"
        });
</script>