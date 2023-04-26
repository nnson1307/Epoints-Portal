<div id="modal-detail" class="modal fade show" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
            .modal-lg {
                max-width: 60%;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('CHI TIẾT PHIẾU CHI')}}
                </h4>
            </div>
            <form id="formdetail">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Nhóm người nhận')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select disabled name="detail_object_accounting_type_code" class="form-control m-input select2"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn nhóm người nhận')}}</option>
                                            @foreach($OBJECT_ACCOUNTING_TYPE as $v)
                                                @if($v['object_accounting_type_code'] == $item['object_accounting_type_code'])
                                                    <option value="{{$v['object_accounting_type_code']}}" selected>{{__($v['object_accounting_type_name_vi'])}}</option>
                                                @else
                                                    <option value="{{$v['object_accounting_type_code']}}">{{__($v['object_accounting_type_name_vi'])}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Người nhận')}}:<b class="text-danger">*</b>
                                    </label>
                                    @if($item['object_accounting_type_code'] == 'OAT_CUSTOMER')
                                        <select disabled name="detail_accounting_id" class="form-control m-input select2"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn khách hàng')}}</option>
                                        @foreach($CUSTOMER as $v)
                                            @if($v['customer_id'] == $item['accounting_id'])
                                                <option value="{{$v['customer_id']}}" selected>{{__($v['full_name'])}}</option>
                                            @else
                                                <option value="{{$v['customer_id']}}">{{__($v['full_name'])}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    @elseif($item['object_accounting_type_code'] == 'OAT_SUPPLIER')
                                        <select disabled name="detail_accounting_id" class="form-control m-input select2"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn nhà cung cấp')}}</option>
                                        @foreach($SUPPLIER as $v)
                                            @if($v['supplier_id'] == $item['accounting_id'])
                                                <option value="{{$v['supplier_id']}}" selected>{{__($v['supplier_name'])}}</option>
                                            @else
                                                <option value="{{$v['supplier_id']}}">{{__($v['supplier_name'])}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    @elseif($item['object_accounting_type_code'] == 'OAT_EMPLOYEE')
                                        <select disabled name="detail_accounting_id" class="form-control m-input select2"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn nhân viên')}}</option>
                                        @foreach($STAFF as $v)
                                            @if($v['staff_id'] == $item['accounting_id'])
                                                <option value="{{$v['staff_id']}}" selected>{{__($v['full_name'])}}</option>
                                            @else
                                                <option value="{{$v['staff_id']}}">{{__($v['full_name'])}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    @else
                                        <div class="form-group" id="detail_object_render">
                                            <input disabled type="text" class="form-control m-input btn-sm"
                                                value="{{$item['accounting_name']}}"  name="detail_accounting_name" placeholder="{{__('Người nhận')}}">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Loại phiếu chi')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select disabled name="detail_payment_type" class="form-control m-input select2"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn loại phiếu chi')}}</option>
                                            @foreach($PAYMENT_TYPE as $v)
                                                @if($v['payment_type_id'] == $item['payment_type'])
                                                    <option value="{{$v['payment_type_id']}}" selected>{{__($v['payment_type_name_vi'])}}</option>
                                                @else
                                                    <option value="{{$v['payment_type_id']}}">{{__($v['payment_type_name_vi'])}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Mã tham chiếu')}}:
                                    </label>
                                    <div class="form-group">
                                        <input disabled type="text" name="detail_document_code" class="form-control m-input btn-sm" value="{{$item['document_code']}}"
                                               placeholder="{{__('Nhập mã tham chiếu')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Trạng thái')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select disabled name="detail_status" class="form-control m-input select2"
                                                style="width: 100%">
                                            <option value="new" {{$item['status'] == 'new' ? 'selected' : ''}}>{{__('Mới')}}</option>
                                            <option value="approved" {{$item['status'] == 'approved' ? 'selected' : ''}}>{{__('Đã xác nhận')}}</option>
                                            <option value="paid" {{$item['status'] == 'paid' ? 'selected' : ''}}>{{__('Đã chi')}}</option>
                                            <option value="unpaid" {{$item['status'] == 'unpaid' ? 'selected' : ''}}>{{__('Đã huỷ chi')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if($item['status'] == 'approved')
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>
                                            {{__('Người xác nhận')}}:<b class="text-danger">*</b>
                                        </label>
                                        <div class="form-group">
                                            @foreach($STAFF as $v)
                                                @if($item['approved_by'] == $v['staff_id'])
                                                    <input disabled type="text" class="form-control m-input btn-sm"
                                                          value="{{$v['full_name']}}" placeholder="{{__('Tên người xác nhận')}}">
                                                @break
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @elseif($item['status'] == 'paid')
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>
                                            {{__('Người chi')}}:<b class="text-danger">*</b>
                                        </label>
                                        <div class="form-group">
                                            @foreach($STAFF as $v)
                                                @if($item['staff_id'] == $v['staff_id'])
                                                    <input disabled type="text" class="form-control m-input btn-sm"
                                                           value="{{$v['full_name']}}" placeholder="{{__('Tên người thanh toán')}}">
                                                    @break
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Số tiền')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="form-group">
                                        <input disabled aria-describedby="basic-addon1" name="detail_total_amount" id="detail_total_amount" class="format-money form-control m-input btn-sm"
                                              value="{{$item['total_amount']}}" placeholder="{{__('Nhập số tiền')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Hình thức thanh toán')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select disabled name="detail_payment_method" class="form-control m-input select2"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn hình thức thanh toán')}}</option>
                                            @foreach($PAYMENT_METHOD as $v)
                                                @if($v['payment_method_code'] == $item['payment_method'])
                                                    <option value="{{$v['payment_method_code']}}" selected>{{__($v['payment_method_name_vi'])}}</option>
                                                @else
                                                    <option value="{{$v['payment_method_code']}}">{{__($v['payment_method_name_vi'])}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Ngày ghi nhận')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            {!! Form::text("payment_date",\Carbon\Carbon::parse($item['payment_date'])->format('d/m/Y'),["disabled"=>"disabled","class"=>"form-control date-picker","name"=>"detail_payment_date","placeholder"=>"Chọn ngày thanh toán"]); !!}
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Chi nhánh')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        @if(Auth::user()->is_admin==1)
                                            <select disabled name="detail_branch_code" class="form-control m-input select2"
                                                    style="width: 100%">
                                                <option value="">{{__('Chọn chi nhánh')}}</option>
                                                @foreach($BRANCH as $v)
                                                    @if($v['branch_code'] == $item['branch_code'])
                                                        <option value="{{$v['branch_code']}}" selected>{{__($v['branch_name'])}}</option>
                                                    @else
                                                        <option value="{{$v['branch_code']}}">{{__($v['branch_name'])}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @else
                                            <select disabled name="detail_branch_code" class="form-control m-input select2"
                                                    style="width: 100%">
                                                @foreach($BRANCH as $v)
                                                    @if($v['branch_code'] == $item['branch_code'])
                                                        <option value="{{$v['branch_code']}}" selected>{{__($v['branch_name'])}}</option>
                                                        @break;
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Mô tả')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="form-group">
                                        <input disabled type="text" name="detail_note" class="form-control m-input btn-sm"
                                              value="{{$item['note']}}" placeholder="{{__('Nhập mô tả')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
