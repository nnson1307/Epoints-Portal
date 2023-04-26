<div id="add" class="modal fade" role="dialog">
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
                    <i class="fa fa-plus-circle"></i> {{__('TẠO PHIẾU CHI')}}
                </h4>
            </div>
            <form id="form">
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <labe>
                                        {{__('Nhóm người nhận')}}:<b class="text-danger">*</b>
                                    </labe>
                                    <div class="input-group">
                                        <select name="object_accounting_type_code" id="object_accounting_type_code" class="form-control m-input select"
                                                style="width: 100%">
                                            <option value="">{{__('Chọn nhóm người nhận')}}</option>
                                            @foreach($OBJECT_ACCOUNTING_TYPE as $v)
                                                <option value="{{$v['object_accounting_type_code']}}">{{__($v['object_accounting_type_name_vi'])}}</option>
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
                                    <div class="form-group" id="object_render">
                                        <input type="text" class="form-control m-input btn-sm"
                                               name="accounting_name" id="accounting_name" placeholder="{{__('Nhập tên người nhận')}}">
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Loại phiếu chi')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <select name="payment_type" id="payment_type" class="form-control m-input" style="width: 100%">
                                            <option></option>
                                            @foreach($PAYMENT_TYPE as $v)
                                                <option value="{{$v['payment_type_id']}}">{{__($v['payment_type_name_vi'])}}</option>
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
                                        <input type="text" name="document_code" class="form-control m-input btn-sm" id="document_code"
                                               placeholder="{{__('Nhập mã tham chiếu')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Số tiền')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="form-group">
                                        <input aria-describedby="basic-addon1" name="total_amount" class="format-money form-control m-input btn-sm" id="total_amount"
                                               placeholder="{{__('Nhập số tiền')}}">
                                    </div>
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
                                            @foreach($PAYMENT_METHOD as $v)
                                                @if($v["payment_method_code"] != "MEMBER_MONEY" && $v["payment_method_code"] != "MEMBER_CARD" )
                                                    <option value="{{__($v['payment_method_code'])}}">{{__($v['payment_method_name_vi'])}}</option>
                                                @endif
                                            @endforeach
                                        </select>
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
                                            <select name="branch_code" id="branch_code" class="form-control m-input select"
                                                    style="width: 100%">
                                                <option value="">{{__('Chọn chi nhánh')}}</option>
                                                @foreach($BRANCH as $v)
                                                    <option value="{{$v['branch_code']}}">{{__($v['branch_name'])}}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="branch_code" id="branch_code" disabled class="form-control m-input select"
                                                    style="width: 100%">
                                                @foreach($BRANCH as $v)
                                                    @if($v['branch_id'] == Auth::user()->branch_id)
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
                                        <input type="text" name="note" class="form-control m-input btn-sm" id="note"
                                               placeholder="{{__('Nhập mô tả')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="type_add" id="type_add" value="0">

                    </div>
                    <div class="modal-footer">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m--align-right">
                                <a href="{{route('payment')}}"
                                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                                </a>
                                <button type="button" onclick="payment.add()"
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
            </form>
        </div>
    </div>
</div>

