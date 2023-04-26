<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modal-info" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 80% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" style="font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('NHẬN THÔNG TIN TIẾP NHẬN - CHƯA CÓ TRONG HỆ THỐNG - CÁ NHÂN')
                </h5>
            </div>

            <div class="modal-body">
                <form id="formCustomerRequest" method="post">
                    <input type="hidden" value="{{ $object_id }}" name="call_center_object_id" id="call_center_object_id">
                    <input type="hidden" value="{{ $object_type }}" name="call_center_object_type" id="call_center_object_type">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group m-form__group row">
                                <h6 style="text-transform: uppercase; font-weight: 600;">@lang('Thông tin liên hệ')</h6>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('SĐT liên hệ')<b class="text-danger">*</b>
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="text" value="{{ $phone }}" id="call_center_phone" name="call_center_phone">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Tên liên hệ')<b class="text-danger">*</b>
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="text" value="" id="call_center_full_name" name="call_center_full_name">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang("Giới tính")
                                </label>
                                <div class="col-9">
                                    <div class="m-form__group form-group">
                                        <div class="m-radio-inline">
                                            <label class="m-radio cus">
                                                <input type="radio" name="call_center_gender" id="call_center_gender" value="male" checked> @lang("Nam")
                                                <span class="span"></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="call_center_gender" id="call_center_gender" value="female"> @lang("Nữ")
                                                <span class="span"></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="call_center_gender" id="call_center_gender" value="other"> @lang("Khác")
                                                <span class="span"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang("Loại khách hàng")
                                </label>
                                <div class="col-9">
                                    <div class="m-form__group form-group">
                                        <div class="m-radio-inline">
                                            <label class="m-radio cus">
                                                <input type="radio" name="call_center_customer_type" id="call_center_customer_type" value="personal" checked> @lang("Cá nhân")
                                                <span class="span"></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="call_center_customer_type" id="call_center_customer_type" value="business"> @lang("Doanh nghiệp")
                                                <span class="span"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Hành trình')<b class="text-danger">*</b>
                                </label>
                                <div class="col-9">
                                    <div class="input-group">
                                        <select name="call_center_pipeline" id="call_center_pipeline" class="form-control"
                                        style="width: 100%" onchange="callCenter.loadJourney(this)">
                                            <option></option>
                                            @foreach($optionPipeline as $v)
                                                <option value="{{$v['pipeline_code']}}">{{$v['pipeline_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Trạng thái')<b class="text-danger">*</b>
                                </label>
                                <div class="col-9">
                                    <div class="input-group">
                                        <select name="call_center_journey" id="call_center_journey" class="form-control"
                                                style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Nhân viên phụ trách')
                                </label>
                                <div class="col-9">
                                    <select name="call_center_staff" id="call_center_staff" class="form-control"
                                            style="width: 100%">
                                        <option></option>
                                        @foreach($optionStaff as $v)
                                            <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                  @lang('Địa chỉ')
                                </label>
                                <div class="col-3">
                                    <select name="call_center_province" id="call_center_province" class="form-control" onchange='callCenter.changeProvince(this)'
                                        style="width: 100%">
                                        <option></option>
                                        @foreach($optionProvince as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select name="call_center_district" id="call_center_district" class="form-control" onchange='callCenter.changeDistrict(this)'
                                        style="width: 100%">
                                        <option></option>
                                        
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select name="call_center_ward" id="call_center_ward" class="form-control"
                                    style="width: 100%">
                                    <option></option>
                                
                                </select>
                                </div>
                            </div>
                            
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label"></label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="text" value="" id="call_center_address" name=call_center_address>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Nguồn khách hàng')<b class="text-danger">*</b>
                                </label>
                                <div class="col-9">
                                    <div class="input-group">
                                        <select class="form-control" id="call_center_customer_source" name="call_center_customer_source"
                                        style="width:100%;">
                                            <option></option>
                                            @foreach($optionSource as $v)
                                                <option value="{{$v['customer_source_id']}}">{{$v['customer_source_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5" style="border-left: 1px dashed #e0e0e0 !important;">
                            <div class="form-group m-form__group">
                                <h6 style="text-transform: uppercase; font-weight: 600;">
                                    @lang('Thông tin tiếp nhận')
                                </h6>
                            </div>
                            {{-- <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('SĐT liên hệ')<b class="text-danger">*</b>
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="text" value="" id="call_center_phone" name="call_center_phone">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Tên liên hệ')<b class="text-danger">*</b>
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="text" value="" id="call_center_full_name" name="call_center_full_name">
                                </div>
                            </div>
                             --}}
                             @foreach ($request_attribute as $item)
                             @switch($item['object_key'])
                                 @case('column_request_type')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             @lang("Loại yêu cầu")
                                         </label>
                                         <div class="col-9">
                                             <div class="m-form__group form-group">
                                                 <div class="m-radio-inline">
                                                     <label class="m-radio cus">
                                                         <input type="radio" name="call_center_customer_request_type" value="quote" checked> @lang("Yêu cầu báo giá")
                                                         <span class="span"></span>
                                                     </label>
                                                     <label class="m-radio cus">
                                                         <input type="radio" name="call_center_customer_request_type" value="consult"> @lang("Yêu cầu tư vấn")
                                                         <span class="span"></span>
                                                     </label>
                                                     <label class="m-radio cus">
                                                         <input type="radio" name="call_center_customer_request_type" value="other"> @lang("Khác")
                                                         <span class="span"></span>
                                                     </label>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_1')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_1" name="custom_column_value_1">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_1" name="custom_column_value_1">
                                             @endif
                                             <input type="hidden" name="object_data_type_1" id="object_data_type_1" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_1" id="custom_column_name_1" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_2')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_2" name="custom_column_value_2">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_2" name="custom_column_value_2">
                                             @endif
                                             <input type="hidden" name="object_data_type_2" id="object_data_type_2" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_2" id="custom_column_name_2" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_3')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_3" name="custom_column_value_3">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_3" name="custom_column_value_3">
                                             @endif
                                             <input type="hidden" name="object_data_type_3" id="object_data_type_3" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_3" id="custom_column_name_3" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_4')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_4" name="custom_column_value_4">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_4" name="custom_column_value_4">
                                             @endif
                                             <input type="hidden" name="object_data_type_4" id="object_data_type_4" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_4" id="custom_column_name_4" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_5')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_5" name="custom_column_value_5">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_5" name="custom_column_value_5">
                                             @endif
                                            <input type="hidden" name="object_data_type_5" id="object_data_type_5" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_5" id="custom_column_name_5" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_6')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_6" name="custom_column_value_6">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_6" name="custom_column_value_6">
                                             @endif
                                             <input type="hidden" name="object_data_type_6" id="object_data_type_6" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_6" id="custom_column_name_6" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_7')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_7" name="custom_column_value_7">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_7" name="custom_column_value_7">
                                             @endif
                                             <input type="hidden" name="object_data_type_7" id="object_data_type_7" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_7" id="custom_column_name_7" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_8')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_8" name="custom_column_value_8">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_8" name="custom_column_value_8">
                                             @endif
                                             <input type="hidden" name="object_data_type_8" id="object_data_type_8" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_8" id="custom_column_name_8" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_9')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_9" name="custom_column_value_9">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_9" name="custom_column_value_9">
                                             @endif
                                             <input type="hidden" name="object_data_type_9" id="object_data_type_9" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_9" id="custom_column_name_9" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @case('custom_column_10')
                                     <div class="form-group m-form__group row">
                                         <label for="example-password-input" class="col-3 col-form-label">
                                             {{$item['object_value']}}
                                         </label>
                                         <div class="col-9">
                                             @if ($item['object_data_type'] == 'int')
                                                 <input class="form-control m-input input_number" type="text" value="" id="custom_column_value_10" name="custom_column_value_10">
                                             @else
                                                 <input class="form-control m-input" type="text" value="" id="custom_column_value_10" name="custom_column_value_10">
                                             @endif
                                             <input type="hidden" name="object_data_type_10" id="object_data_type_10" value="{{$item['object_data_type']}}">
                                             <input type="hidden" name="custom_column_name_10" id="custom_column_name_10" value="{{$item['object_value']}}">
                                         </div>
                                     </div>
                                     @break
                                 @default
                             @endswitch
                         @endforeach
                            <div class="form-group m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang("Chi tiết thông tin yêu cầu")
                                </label>
                                <div class="col-9">
                                    <textarea class="form-control" rows="8" id="call_center_note" name="call_center_note" placeholder="{{__('Nhập ghi chú')}}"></textarea>
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
                    <button type="button" onclick="callCenter.saveCustomerRequest()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
                                <i class="la la-check m--margin-left-5"></i> 
							<span>{{__('LƯU THÔNG TIN')}}</span>
                            
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
<script>
    var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
</script>
<script>
    $('#call_center_journey').select2({
            placeholder: callCenter.jsontranslate['Chọn trạng thái'] 
    });
    $('#call_center_pipeline').select2({
            placeholder: callCenter.jsontranslate['Chọn hành trình']
    });
    $('#call_center_status').select2({
            placeholder: 'Chọn trạng thái'
    });
    $('#call_center_staff').select2({
            placeholder: callCenter.jsontranslate['Chọn nhân viên']
    });
    $('#call_center_province').select2({
            placeholder: callCenter.jsontranslate['Chọn Tỉnh/Thành phố']
    });
    $('#call_center_district').select2({
            placeholder: callCenter.jsontranslate['Chọn Quận/Huyện']
    });
    $('#call_center_ward').select2({
            placeholder: callCenter.jsontranslate['Chọn Phường/Xã']
    });
    $('#call_center_customer_source').select2({
            placeholder: callCenter.jsontranslate['Chọn nguồn khách hàng']
    });
    new AutoNumeric.multiple(".input_number", {
      currencySymbol: "",
      decimalCharacter: ".",
      digitGroupSeparator: ",",
      decimalPlaces: decimal_number,
      eventIsCancelable: true,
      minimumValue: 0,
    });
</script>