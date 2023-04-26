<div id="add-receipt" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }

            .modal-lg {
                max-width: 60%;
            }
        </style>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM PHIẾU THU')}}
                </h4>
            </div>
            <form id="add-receipt-form">
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Loại phiếu thu')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
{{--                                        <select name="payment_type" id="payment_type" class="form-control m-input" style="width: 100%">--}}
                                        <select name="receipt_type_code" id="receipt_type_code" class="form-control m-input" style="width: 100%">
                                            <option></option>
                                            @foreach($data['optionReceiptType'] as $v)
                                                <option value="{{$v['receipt_type_code']}}">{{$v['receipt_type_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="black_title">
                                        @lang('Thông tin người trả tiền'):<b class="text-danger"> *</b>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="object_accounting_type_code"
                                                name="object_accounting_type_code"
                                                style="width:100%;" onchange="projectInfo.changeType(this)">
                                            <option></option>
                                            @foreach($data['optionObjAccType'] as $v)
                                                <option value="{{$v['object_accounting_type_code']}}">{{$v['object_accounting_type_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group m-form__group div_add_name" style="display:none;">
                                    <label class="black_title">
                                        @lang('Nhập tên người trả tiền'):<b class="text-danger"> *</b>
                                    </label>
                                    <input type="text" class="form-control m-input"
                                           id="object_accounting_name" name="object_accounting_name"
                                           placeholder="@lang('Nhập tên người trả tiền')">
                                    <span class="err error-obj-acc-name"></span>
                                </div>
                                <div class="form-group m-form__group div_add_id" style="display:none;">
                                    <label class="black_title">
                                        @lang('Chọn người trả tiền'):<b class="text-danger"> *</b>
                                    </label>
                                    <select class="form-control" id="object_accounting_id" name="object_accounting_id"
                                            style="width:100%;">
                                        <option></option>
                                    </select>
                                    <span class="err error-obj-acc-id"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Số tiền'):<b class="text-danger"> *</b>
                                    </label>
{{--                                    <input type="text" class="form-control m-input format-money numeric_child"--}}
{{--                                           id="money" name="money" placeholder="@lang('Nhập số tiền')">--}}
                                    <input type="text" id="money" name="money" class="format-money form-control m-input btn-sm numeric_child money_receipt"
                                           placeholder="{{__('Nhập số tiền')}}">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Hình thức thanh toán')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select name="payment_method" id="payment_method" class="form-control m-input select"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn hình thức thanh toán')}}</option>
                                            @foreach($data['optionPaymentMethod'] as $v)
                                                <option value="{{$v['payment_method_code']}}">
                                                    {{$v['payment_method_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Nội dung thu')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="form-group">
                                        <input type="text" name="note" class="form-control m-input btn-sm" id="note"
                                               placeholder="{{__('Nhập nội dung thu')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m--align-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <span class="la 	la-arrow-left"></span>
                                    {{__('HỦY')}}
                                </button>
                                <button type="button" onclick="projectInfo.addReceipt()"
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
                <input type="hidden" id="manage_project_id" name="manage_project_id" value="{{$data['manage_project_id']}}"></input>
            </form>
        </div>
    </div>
</div>


<script>
    // import Input from "../../../../../ManagerWork/Resources/vue/components/filters/Input";
    // export default {
    //     components: {Input}
    // }
</script>