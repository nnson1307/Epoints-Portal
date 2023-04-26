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
                    <i class="fa fa-plus-circle"></i> @lang('NHẬN THÔNG TIN TIẾP NHẬN - KHÁCH HÀNG')
                </h5>
            </div>

            <div class="modal-body">
                <form id="formCustomerRequest" method="post">
                    <input type="hidden" value="{{ $object_id }}" name="call_center_object_id" id="call_center_object_id">
                    <input type="hidden" value="{{ $object_type }}" name="call_center_object_type" id="call_center_object_type">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="m-form__group">
                                <h6 style="text-transform: uppercase; font-weight: 600;">
                                    @lang('Thông tin tiếp nhận'):
                                </h6>
                            </div>
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('SĐT liên hệ'):
                                </label>
                                <label for="example-password-input" class="col-9 col-form-label">
                                    {{ $object_request['customer_request_phone'] }}
                                </label>
                            </div>
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Tên liên hệ'):
                                </label>
                                <label for="example-password-input" class="col-9 col-form-label">
                                    {{ $object_request['customer_request_name'] }}
                                </label>
                            </div>
                            
                            @foreach ($request_attribute as $item)
                                @switch($item['object_key'])
                                    @case('column_request_type')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                @lang("Loại yêu cầu"):
                                            </label>
                                            <div class="col-9">
                                                <div class="m-form__group form-group">
                                                    <div class="m-radio-inline" style="margin-top: 15px;">
                                                        <label class="m-radio cus">
                                                            <input type="radio" name="customer_request_type" value="quote" {{ $object_request['customer_request_type'] == 'quote' ? 'checked' : '' }} readonly> @lang("Yêu cầu báo giá")
                                                            <span class="span"></span>
                                                        </label>
                                                        <label class="m-radio cus">
                                                            <input type="radio" name="customer_request_type" value="consult" {{ $object_request['customer_request_type'] == 'consult' ? 'checked' : '' }} readonly> @lang("Yêu cầu tư vấn")
                                                            <span class="span"></span>
                                                        </label>
                                                        <label class="m-radio cus">
                                                            <input type="radio" name="customer_request_type" value="other" {{ $object_request['customer_request_type'] == 'other' ? 'checked' : '' }} readonly> @lang("Khác") 
                                                            <span class="span"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @break
                                    @case('custom_column_1')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_1'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_2')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_2'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_3')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_3'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_4')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_4'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_5')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_5'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_6')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_6'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_7')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_7'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_8')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_8'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_9')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_9'] }}
                                            </label>
                                        </div>
                                        @break
                                    @case('custom_column_10')
                                        <div class="m-form__group row">
                                            <label for="example-password-input" class="col-3 col-form-label">
                                                {{ $item['object_value'] }}:
                                            </label>
                                            <label for="example-password-input" class="col-9 col-form-label">
                                                {{ $object_request['custom_column_value_10'] }}
                                            </label>
                                        </div>
                                        @break
                                    @default
                                @endswitch
                            @endforeach
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang("Chi tiết thông tin yêu cầu"):
                                </label>
                                <div class="col-9">
                                    <textarea class="form-control" rows="8" id="customer_request_note" name="customer_request_note" placeholder="{{__('Nhập ghi chú')}}" disabled>{{ $object_request['customer_request_note'] }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7" style="border-left: 1px dashed #e0e0e0 !important;">
                            <div class="m-form__group">
                                <h6 style="text-transform: uppercase; font-weight: 600;">@lang('Thông tin khách hàng')</h6>
                            </div>
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Tên khách hàng'):
                                </label>
                                <label for="example-password-input" class="col-9 col-form-label">
                                    {{ $data['full_name'] }}
                                </label>
                              
                            </div>
                           
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Mã khách hàng'):
                                </label>
                                <label for="example-password-input" class="col-9 col-form-label">
                                    {{ $data['customer_code'] }}
                                </label>
                            </div>
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Mã số thuế'):
                                </label>
                                <label for="example-password-input" class="col-9 col-form-label">
                                    {{ $data['tax_code'] }}
                                </label>
                            </div>
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Địa chỉ'):
                                </label>
                                <label for="example-password-input" class="col-9 col-form-label">
                                    {{ $data['address'] }}
                                </label>
                            </div>
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Số điện thoại'):
                                </label>
                                <label for="example-password-input" class="col-3 col-form-label">
                                    {{ $data['phone'] }}
                                </label>
                            </div>
                            <div class="m-form__group row">
                                <label for="example-password-input" class="col-3 col-form-label">
                                    @lang('Người đại diện'):
                                </label>
                                <label for="example-password-input" class="col-3 col-form-label">
                                    {{ $data['representative'] }}
                                </label>
                              
                            </div>
                           
                        </div>
                    </div>  
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="tab-content m--margin-top-40">
                            <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 0;">
                              
                                <li class="nav-item">
                                    <a class="nav-link son active show" data-toggle="tab" href="#tab-deal">@lang("THÔNG TIN DEAL")</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link son" data-toggle="tab" href="#div-contract">@lang("HỢP ĐỒNG")</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                
                                <div id="tab-deal" aria-expanded="true" class="tab-pane active">
                                    <div class="m-demo__preview">
                                        @if (count($dataDeal) > 0)
                                            <div class="form-group">
                                                <div style="width: 100%; height: 300px;">
                                                    <div id="autotable-deal">
                                                        <form class="frmFilter">
                                                            <div class="form-group">
                                                                <input type="hidden" class="form-control" name="customer_lead_code" id="customer_lead_code"
                                                                       value="{{$data['customer_code']}}">
                                                            </div>
                                                        </form>
                                                        <div class="table-content m--padding-top-30" id="div-deal-list">
                                                            @include('customer-lead::customer-lead.list-deal')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @lang('Không có dữ liệu')
                                        @endif
                                    </div>
                                </div>
                                <div id="div-contract" aria-expanded="true" class="tab-pane">
                                    <div class="m-demo__preview">
                                        @if (count($dataContract) > 0)
                                            <div class="form-group">
                                                <div style="width: 100%; height: 300px;">
                                                    <div id="autotable-deal">
                                                        <div class="table-content m--padding-top-30" id="div-deal-list">
                                                            <div class="table-responsive" style="max-height: 300px;">
                                                                <table class="table table-striped m-table m-table--head-bg-default">
                                                                    <thead class="bg">
                                                                    <tr>
                                                                        <th class="tr_thead_list">@lang('MÃ HỢP ĐỒNG')</th>
                                                                        <th class="tr_thead_list">@lang('SỐ HỢP ĐỒNG')</th>
                                                                        <th class="tr_thead_list">@lang('TÊN HỢP ĐỒNG')</th>
                                                                        <th class="tr_thead_list">@lang('LOẠI ĐỐI TÁC')</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @if(isset($dataContract))
                                                                        @foreach ($dataContract as $key => $item)
                                                                        <tr>
                                                                            <td>
                                                                                @if(in_array('contract.contract.show', session()->get('routeList')))
                                                                                    <a href="{{route("contract.contract.show",[ 'id' => $item['contract_id']])}}">
                                                                                        {{$item['contract_code']}}
                                                                                    </a>
                                                                                @else
                                                                                    {{$item['contract_code']}}
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ $item['contract_no']}}
                                                                                </td>
                                                                            <td>
                                                                            {{ $item['contract_name']}}
                                                                            </td>
                                                                           
                                                                            <td>
                                                                                @switch($item['partner_object_type'])
                                                                                    @case('personal')
                                                                                        @lang('Cá nhân')
                                                                                    @break
                                                                                    @case('business')
                                                                                        @lang('Doanh nghiệp')
                                                                                    @break
                                                                                    @case('supplier')
                                                                                        @lang('Nhà cung cấp')
                                                                                    @break
                                                                                @endswitch
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @lang('Không có dữ liệu')
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
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
                    <button type="button" onclick="callCenter.showModalCreateLead('{{ $object_id }}', '{{ $object_type }}')"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="fa fa-plus-circle m--margin-left-5"></i> 
                            <span>{{__('THÊM CƠ HỘI BÁN HÀNG')}}</span>
                        
                            </span>
                    </button>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('static/backend/js/customer-lead/customer-deal/script.js?v='.time())}}" type="text/javascript"></script>
<script>
    $(document).on(
          "click",
          "#autotable-care a.m-datatable__pager-link",
          function (event) {
            event.preventDefault();
            var page = $(this).attr("data-page");
            console.log(page);
            if (page) {
              var code = $("#customer_lead_code").val();
              listLead.getDataCare(page, code);
            }
          }
        );
        $(document).on(
          "click",
          "#autotable-deal a.m-datatable__pager-link",
          function (event) {
            event.preventDefault();
            var page = $(this).attr("data-page");
            if (page) {
              var code = $("#customer_lead_code").val();
              listLead.getDataDeal(page, code);
            }
          }
        );
    $('#call_center_journey').select2({
            placeholder: callCenter.jsontranslate['Chọn hành trình'] 
    });
    $('#call_center_pipeline').select2({
            placeholder: callCenter.jsontranslate['Chọn pipeline']
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
</script>