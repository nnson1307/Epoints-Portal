@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services.png')}}" alt=""
                style="height: 20px;"> {{__('CẤU HÌNH')}}</span>
@endsection
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-cog"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CẤU HÌNH IN HÓA ĐƠN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        {!! Form::open(["route"=>"admin.config-print-bill.submitEdit","method"=>"POST","id"=>"form", 'class' => 'm-form--group-seperator-dashed ']) !!}
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group m-form__group">
                        <label for="">{{__('Kích thước')}}</label>
                        <div class="">
                            <select name="template" id="template" class="form-control class-select2"
                                    style="width: 100%">
                                <option {{$configPrintBill->template=='k80'?'selected':''}} value="k80">K80</option>
                                <option {{$configPrintBill->template=='k58'?'selected':''}} value="k58">K58</option>
                                <option {{$configPrintBill->template=='A5'?'selected':''}} value="A5">A5 ({{ __('Dọc') }})</option>
                                <option {{$configPrintBill->template=='A5-landscape'?'selected':''}} value="A5-landscape">A5 ({{ __('Ngang') }})</option>
                                <option {{$configPrintBill->template=='A4'?'selected':''}} value="A4">A4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Số liên in')}}</label>
                        <div class="">
                            <select name="printed_sheet" id="printed_sheet" class="form-control class-select2"
                                    style="width: 100%">
                                <option {{$configPrintBill->printed_sheet==1?'selected':''}} value="1">1</option>
                                <option {{$configPrintBill->printed_sheet==2?'selected':''}} value="2">2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('In lại')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input id="is_print_reply"
                                               {{$configPrintBill->is_print_reply==1?'checked':''}} type="checkbox"
                                               class="manager-btn" name="is_print_reply">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Số lần in tối đa')}}</label>
                        <input value="{{$configPrintBill->print_time}}" name="print_time" id="print_time" type="text"
                               class="class-number form-control">
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện logo')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_logo==1?'checked':''}} id="is_show_logo"
                                               type="checkbox" class="manager-btn" name="is_show_logo">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện tên đơn vị/ cty')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_unit==1?'checked':''}} id="is_show_unit"
                                               type="checkbox" class="manager-btn" name="is_show_unit">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện địa chỉ đơn vị/ cty')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_address==1?'checked':''}} id="is_show_address"
                                               type="checkbox" class="manager-btn" name="is_show_address">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện SĐT đơn vị/cty')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_phone==1?'checked':''}} id="is_show_phone"
                                               type="checkbox" class="manager-btn" name="is_show_phone">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện mã hóa đơn')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_order_code==1?'checked':''}} id="is_show_order_code"
                                               type="checkbox" class="manager-btn" name="is_show_order_code">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Ký hiệu')}}:</label>
                        <input value="{{$configPrintBill->symbol}}" name="symbol" id="symbol" type="text"
                               class="class-number form-control">
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện mã số thuế')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_company_tax_code==1?'checked':''}} id="is_company_tax_code"
                                               type="checkbox" class="manager-btn" name="is_company_tax_code">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Mã số thuế')}}:</label>
                        <input value="{{$spaInfo->tax_code}}" name="tax_code" id="tax_code" type="number"
                               class="class-number form-control" onkeyup="Config.onKeyDownInputNumber(this)">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện nhân viên thu ngân')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_cashier==1?'checked':''}} id="is_show_cashier"
                                               type="checkbox" class="manager-btn" name="is_show_cashier">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện khách hàng')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_customer==1?'checked':''}} id="is_show_customer"
                                               type="checkbox" class="manager-btn" name="is_show_customer">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện mã khách hàng')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_customer_code==1?'checked':''}} id="is_customer_code"
                                               type="checkbox" class="manager-btn" name="is_customer_code">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện thời gian in')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_datetime==1?'checked':''}} id="is_show_datetime"
                                               type="checkbox" class="manager-btn" name="is_show_datetime">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                   
                   
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện mã hồ sơ')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_profile_code==1?'checked':''}} id="is_profile_code"
                                               type="checkbox" class="manager-btn" name="is_profile_code">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện footer')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_show_footer==1?'checked':''}} id="is_show_footer"
                                               type="checkbox" class="manager-btn" name="is_show_footer">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện QRCode')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_qrcode_order==1?'checked':''}} id="is_qrcode_order"
                                               type="checkbox" class="manager-btn" name="is_qrcode_order">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện chỗ ký tên')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_sign==1?'checked':''}} id="is_sign"
                                               type="checkbox" class="manager-btn" name="is_sign">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện tổng tiền')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_total_bill==1?'checked':''}} id="is_total_bill"
                                               type="checkbox" class="manager-btn" name="is_total_bill">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện tổng giảm giá')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_total_discount==1?'checked':''}} id="is_total_discount"
                                               type="checkbox" class="manager-btn" name="is_total_discount">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện tổng tiền phải trả')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_total_amount==1?'checked':''}} id="is_total_amount"
                                               type="checkbox" class="manager-btn" name="is_total_amount">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện tổng tiền khách trả')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_total_receipt==1?'checked':''}} id="is_total_receipt"
                                               type="checkbox" class="manager-btn" name="is_total_receipt">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện phương thức thanh toán')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_payment_method==1?'checked':''}} id="is_payment_method"
                                               type="checkbox" class="manager-btn" name="is_payment_method">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện tiền trả lại')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_amount_return==1?'checked':''}} id="is_amount_return"
                                               type="checkbox" class="manager-btn" name="is_amount_return">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Hiện tiền khách nợ')}}:</label>
                        <div class="input-group row">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input {{$configPrintBill->is_dept_customer==1?'checked':''}} id="is_dept_customer"
                                               type="checkbox" class="manager-btn" name="is_dept_customer">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10">
                                <i>{{__('Chọn để kích hoạt')}}</i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">{{__('Thông tin thêm')}}:</label>
                        <textarea name="note_footer" id="note_footer" rows="5"
                                  class="form-control">{{$configPrintBill->note_footer}}</textarea>
                    </div>
                </div>
               
            </div>
        </div>
        <div class="modal-footer m--margin-right-20">
            <div class="form-group m-form__group m--margin-top-10">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a data-toggle="modal"
                           data-target="#modalAdd"
                           href="javascript:void(0)"
                           class="ss--btn-mobiles btn-save btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-eye"></i>
                                <span>{{__('XEM TRƯỚC')}}</span>
                            </span>
                        </a>
                        <button type="submit"
                                class="ss--btn-mobiles save-change btn-save btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span class="ss--text-btn-mobi">
                                            <i class="la la-check"></i>
                                            <span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
                                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::config-print-bill.templatea5')
        </div>
    </div>
@endsection

@section('after_script')
    <script src="{{asset('static/backend/js/admin/general/jquery.printPage.js?v='.time())}}"
            type="text/javascript"></script>

    <script src="{{asset('static/backend/js/admin/config-print-bill/config-print-bill.js')}}"
            type="text/javascript"></script>

    @if(Session::has("statusss"))
        <script>
            $.getJSON(laroute.route('translate'), function (json) {
                swal(json["Cấu hình in hóa đơn thành công"], "", "success");
            });
        </script>
    @endif
@endsection
