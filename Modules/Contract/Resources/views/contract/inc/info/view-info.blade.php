<h4>@lang('Thông tin hợp đồng')</h4>

<div id="group-general">
    <div class="row">
        @if (count($tabGeneral) > 0)
            @foreach($tabGeneral as $v)
                <div class="form-group m-form__group col-lg-{{$v['number_col']}}">
                    <label class="black_title">
                        {{$v['key_name']}}:
                        <b class="text-danger">{{$v['is_validate'] == 1 ? '*': ''}}</b>
                    </label>
                    <div class="input-group">
                        @switch($v['type'])
                            @case('text')
                                <input type="text" class="form-control m-input" id="{{$v['key']}}"
                                       name="{{'tab_general_'. $v['key']}}"
                                       isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                       keyType="{{$v['type']}}"
                                       value="{{$v['key'] == 'contract_no' && $infoCategory != null ? $infoCategory['contract_code_format'] : ''}}"
                                       value="{{$v['key'] == 'contract_name' && $infoOrder != null ? __('Hợp đồng') . ' ' . $infoOrder['order_code'] : ''}}">
                                @break

                            @case('text_area')
                                <textarea class="form-control m-input" id="{{$v['key']}}"
                                          name="{{'tab_general_'. $v['key']}}" rows="3"
                                          cols="5" isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                          keyType="{{$v['type']}}"></textarea>
                                @break

                            @case('int')
                                <input type="number" class="form-control m-input input_int" id="{{$v['key']}}"
                                       name="{{'tab_general_'. $v['key']}}"
                                       isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                       keyType="{{$v['type']}}">
                                @break

                            @case('float')
                                <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                                       name="{{'tab_general_'. $v['key']}}"
                                       isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                       keyType="{{$v['type']}}">
                                @break

                            @case('date')
                                <input type="text" class="form-control m-input date_picker" readonly=""
                                       id="{{$v['key']}}"
                                       name="{{'tab_general_'. $v['key']}}" isValidate="{{$v['is_validate']}}"
                                       keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                                @break

                            @case('select')
                                @if ($v['key'] == 'contract_category_id')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_general_'. $v['key']}}"
                                            style="width:100%;" onchange="view.changeCategory(this)"
                                            isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                            keyType="{{$v['type']}}">
                                        @foreach($optionCategory as $v1)
                                            @if($v1['type'] == 'sell')
                                                <option value="{{$v1['contract_category_id']}}"
                                                        {{$categoryId ==  $v1['contract_category_id'] ? 'selected' : ''}}>{{$v1['contract_category_name']}}</option>
                                            @else
                                                @if($type != 'from_deal')
                                                    <option value="{{$v1['contract_category_id']}}"
                                                            {{$categoryId ==  $v1['contract_category_id'] ? 'selected' : ''}}>{{$v1['contract_category_name']}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                @elseif($v['key'] == 'performer_by')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_general_'. $v['key']}}"
                                            style="width:100%;" isValidate="{{$v['is_validate']}}"
                                            keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                        <option value="">@lang('Chọn người thực hiện')</option>
                                        @foreach($optionStaff as $v1)
                                            <option value="{{$v1['staff_id']}}">
                                                {{$v1['staff_name'].'_'.$v1['department_name'].'_'.$v1['staff_title_name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @break

                            @case('select_multiple')
                                @if ($v['key'] == 'sign_by')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_general_'. $v['key']}}"
                                            style="width:100%;" multiple isValidate="{{$v['is_validate']}}"
                                            keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                        {{--<option value="">@lang('Chọn người ký')</option>--}}
                                        @foreach($optionStaff as $v1)
                                            <option value="{{$v1['staff_id']}}">
                                                {{$v1['staff_name'].'_'.$v1['department_name'].'_'.$v1['staff_title_name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($v['key'] == 'follow_by')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_general_'. $v['key']}}"
                                            style="width:100%;" multiple isValidate="{{$v['is_validate']}}"
                                            keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                        {{--<option value="">@lang('Chọn người theo dõi')</option>--}}
                                        @foreach($optionStaff as $v1)
                                            <option value="{{$v1['staff_id']}}">
                                                {{$v1['staff_name'].'_'.$v1['department_name'].'_'.$v1['staff_title_name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @break

                            @case('select_insert')
                                @if ($v['key'] == 'tag')
                                    <select class="form-control" id="{{$v['key']}}" name="{{'tab_general_'. $v['key']}}"
                                            multiple style="width:100%;" isValidate="{{$v['is_validate']}}"
                                            keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                        @foreach($optionTag as $v1)
                                            <option value="{{$v1['contract_tag_id']}}">{{$v1['name']}}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @break
                        @endswitch

                    </div>
                </div>
            @endforeach
        @endif

        <div class="form-group m-form__group col-lg-4">
            <label class="black_title">
                @lang('Trạng thái'):<b class="text-danger">*</b>
            </label>
            <div class="input-group">
                <select class="form-control" id="status_code" name="status_code" style="width:100%;">
                    @foreach($optionStatus as $v1)
                        <option value="{{$v1['status_code']}}">{{$v1['status_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<h4>
    @lang('Thông tin đối tác')

    <a class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10"
       href="javascript:void(0)" onclick="addQuickly.showPopupAddQuicklyCustomer()">
        @lang('THÊM KHÁCH HÀNG')
    </a>
</h4>


<div id="group-partner">
    <div class="row">
        @if (count($tabPartner) > 0)
            @foreach($tabPartner as $v)
                <div class="form-group m-form__group col-lg-{{$v['number_col']}}">
                    <label class="black_title">
                        {{$v['key_name']}}:
                        <b class="text-danger">{{$v['is_validate'] == 1 ? '*': ''}}</b>
                    </label>
                    <div class="input-group">
                        @switch($v['type'])
                            @case('text')
                                <input type="text" class="form-control m-input" id="{{$v['key']}}"
                                       name="{{'tab_partner_'. $v['key']}}"
                                       isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                       keyType="{{$v['type']}}"
                                        {{in_array($v['key'], ['representative', 'hotline', 'staff_title']) ? 'disabled': ''}}>
                                @break

                            @case('text_area')
                                <textarea class="form-control m-input" id="{{$v['key']}}"
                                          name="{{'tab_partner_'. $v['key']}}" rows="3"
                                          cols="5" isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                          keyType="{{$v['type']}}"></textarea>
                                @break

                            @case('int')
                                <input type="number" class="form-control m-input input_int" id="{{$v['key']}}"
                                       name="{{'tab_partner_'. $v['key']}}"
                                       isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                       keyType="{{$v['type']}}">
                                @break

                            @case('float')
                                <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                                       name="{{'tab_partner_'. $v['key']}}"
                                       isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                       keyType="{{$v['type']}}">
                                @break

                            @case('date')
                                <input type="text" class="form-control m-input date_picker" readonly=""
                                       id="{{$v['key']}}"
                                       name="{{'tab_partner_'. $v['key']}}" isValidate="{{$v['is_validate']}}"
                                       keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                                @break

                            @case('select')
                                @if ($v['key'] == 'partner_object_type')
                                    @if ($infoOrder != null)
                                        <select class="form-control select" id="{{$v['key']}}"
                                                name="{{'tab_partner_'. $v['key']}}"
                                                style="width:100%;"
                                                onchange="view.choosePartnerType(this, {{$infoOrder != null ? $infoOrder['customer_id'] : ''}})"
                                                isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                                keyType="{{$v['type']}}">
                                            <option value="">@lang('Chọn loại đối tác')</option>
                                            <option value="personal"
                                                    {{isset($type) != '' && $type == 'from_deal' ? 'selected' : ''}}
                                                    {{$infoOrder != null && $infoOrder['customer_type'] == 'personal' ? 'selected': ''}}>@lang('Cá nhân')</option>
                                            <option value="business" {{$infoOrder != null && $infoOrder['customer_type'] == 'business' ? 'selected': ''}}>@lang('Doanh nghiệp')</option>
                                            @if ($infoCategory['type'] == 'buy')
                                                <option value="supplier">@lang('Nhà cung cấp')</option>
                                            @endif
                                        </select>
                                    @else
                                        <select class="form-control select" id="{{$v['key']}}"
                                                name="{{'tab_partner_'. $v['key']}}"
                                                style="width:100%;"
                                                onchange="view.choosePartnerType(this, {{$dataCustomer != null ? $dataCustomer['customer_id'] : ''}})"
                                                isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                                keyType="{{$v['type']}}">
                                            <option value="">@lang('Chọn loại đối tác')</option>
                                            <option value="personal"
                                                    {{isset($type) != '' && $type == 'from_deal' ? 'selected' : ''}}
                                                    {{$infoOrder != null && $infoOrder['customer_type'] == 'personal' ? 'selected': ''}}>@lang('Cá nhân')</option>
                                            <option value="business" {{$infoOrder != null && $infoOrder['customer_type'] == 'business' ? 'selected': ''}}>@lang('Doanh nghiệp')</option>
                                            @if ($infoCategory['type'] == 'buy')
                                                <option value="supplier">@lang('Nhà cung cấp')</option>
                                            @endif
                                        </select>
                                    @endif
                                @elseif($v['key'] == 'partner_object_id')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_partner_'. $v['key']}}"
                                            style="width:100%;" onchange="view.choosePartner(this)"
                                            isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                            keyType="{{$v['type']}}" {{$dataCustomer != null ? 'disabled': ''}}>
                                        <option value="">@lang('Chọn đối tác')</option>
                                    </select>
                                @elseif($v['key'] == 'partner_object_form')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_partner_'. $v['key']}}"
                                            style="width:100%;"
                                            isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                            keyType="{{$v['type']}}">
                                        <option value="internal">@lang('Nội bộ')</option>
                                        <option value="external">@lang('Bên ngoài')</option>
                                        <option value="partner">@lang('Đại lý')</option>
                                    </select>
                                @endif
                                @break
                        @endswitch

                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<h4 {{$type == 'from_deal' ? 'hidden' : ''}}>
    @lang('Thông tin thanh toán')

    <a href="javascript:void(0)" onclick="viewVat.showPopCreate()"
       class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill">
                            <span>
                                <span> {{__('THÊM VAT')}}</span>
                            </span>
    </a>
</h4>

@if ($infoCategory['type'] == 'buy')
    <div class="form-group" style="display: none;">
         <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_value_goods" name="is_value_goods" type="checkbox"
                               onchange="view.changeValueGoods(this)">@lang('Lấy theo giá trị hàng hoá')
                        <span></span>
                    </label>
                </span>
    </div>
@endif

<div id="group-payment" {{$type == 'from_deal' ? 'hidden' : ''}} class="div-input-payment">
    <div class="row">
        @if (count($tabPayment) > 0)
            @foreach($tabPayment as $v)
                <div class="form-group m-form__group col-lg-{{$v['number_col']}}">
                    <label class="black_title">
                        {{$v['key_name']}}:
                        <b class="text-danger">
                            @if ($infoCategory['type'] == 'sell' && in_array($v['key'], ['total_amount', 'tax', 'discount', 'last_total_amount', 'total_amount_after_discount', 'vat_id']))
                                {{$v['is_validate'] == 1 ? '': ''}}
                            @else
                                {{$v['is_validate'] == 1 ? '*': ''}}
                            @endif
                        </b>
                    </label>
                    <div class="input-group">
                        @switch($v['type'])
                            @case('text')
                                <input type="text" class="form-control m-input" id="{{$v['key']}}"
                                       name="{{'tab_payment_'. $v['key']}}"
                                       isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                       keyType="{{$v['type']}}"
                                        {{in_array($v['key'], ['representative', 'hotline', 'staff_title']) ? 'disabled': ''}}
                                        {{$infoCategory['type'] == 'sell' && in_array($v['key'], ['reason_discount']) ? 'disabled': ''}}>
                                @break

                            @case('text_area')
                                <textarea class="form-control m-input" id="{{$v['key']}}"
                                          name="{{'tab_payment_'. $v['key']}}" rows="3"
                                          cols="5" isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                          keyType="{{$v['type']}}"></textarea>
                                @break

                            @case('int')
                                <input type="number" class="form-control m-input input_int" id="{{$v['key']}}"
                                       name="{{'tab_payment_'. $v['key']}}"
                                       isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                       keyType="{{$v['type']}}">
                                @break

                            @case('float')
                                @if ($v['key'] == 'total_amount')
                                    <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                                           name="{{'tab_payment_'. $v['key']}}"
                                           isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                           keyType="{{$v['type']}}" onchange="view.changePrice()"
                                           value="{{$infoOrder != null ? number_format($infoOrder['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : ''}}">
                                @elseif($v['key'] == 'tax')
                                    <div class="tax" style="width: 100%;">
                                        <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                                               name="{{'tab_payment_'. $v['key']}}"
                                               isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                               keyType="{{$v['type']}}" onchange="view.changePrice()">
                                    </div>
                                @elseif($v['key'] == 'discount')
                                    <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                                           name="{{'tab_payment_'. $v['key']}}"
                                           isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                           keyType="{{$v['type']}}" onchange="view.changePrice()"
                                           value="{{$infoOrder != null ? number_format($infoOrder['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : ''}}">
                                @elseif($v['key'] == 'last_total_amount')
                                    <div class="last_total_amount" style="width: 100%;">
                                        <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                                               name="{{'tab_payment_'. $v['key']}}"
                                               isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                               keyType="{{$v['type']}}"
                                               value="{{$infoOrder != null ? number_format($infoOrder['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : ''}}">
                                    </div>
                                @elseif($v['key'] == 'total_amount_after_discount')
                                    <div class="total_amount_after_discount" style="width: 100%;">
                                        <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                                               name="{{'tab_payment_'. $v['key']}}"
                                               isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                               keyType="{{$v['type']}}" onchange="view.changePrice()"
                                               value="{{$infoOrder != null ? number_format($infoOrder['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : ''}}">
                                    </div>
                                @else
                                    <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                                           name="{{'tab_payment_'. $v['key']}}"
                                           isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                           keyType="{{$v['type']}}">
                                @endif
                                @break

                            @case('date')
                                <input type="text" class="form-control m-input date_picker" readonly=""
                                       id="{{$v['key']}}"
                                       name="{{'tab_payment_'. $v['key']}}" isValidate="{{$v['is_validate']}}"
                                       keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                                @break

                            @case('select')
                                @if($v['key'] == 'vat_id')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_payment_'. $v['key']}}"
                                            style="width:100%;" onchange="view.chooseVAT()"
                                            isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                                            keyType="{{$v['type']}}">
                                        <option value="">@lang('Chọn VAT')</option>
                                        @foreach($optionVat as $v1)
                                            <option value="{{$v1['vat_id']}}">{{floatval($v1['vat'])}}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @break

                            @case('select_insert')
                                @if ($v['key'] == 'payment_method_id')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_payment_'. $v['key']}}"
                                            style="width:100%;" isValidate="{{$v['is_validate']}}"
                                            keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                        <option value="">@lang('Chọn phương thức thanh toán')</option>
                                        @foreach($optionPaymentMethod as $v1)
                                            <option value="{{$v1['payment_method_id']}}">{{$v1['payment_method_name']}}</option>
                                        @endforeach
                                    </select>
                                @elseif($v['key'] == 'payment_unit_id')
                                    <select class="form-control select" id="{{$v['key']}}"
                                            name="{{'tab_payment_'. $v['key']}}"
                                            style="width:100%;" isValidate="{{$v['is_validate']}}"
                                            keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                                        <option value="">@lang('Chọn đơn vị thanh toán')</option>
                                        @foreach($optionPaymentUnit as $v1)
                                            <option value="{{$v1['payment_unit_id']}}">{{$v1['name']}}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @break
                        @endswitch
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<h4>@lang('Thiết lập khác')</h4>

<div class="form-group row">
    <div class="col-lg-4">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_renew" name="is_renew" type="checkbox"
                               checked="">@lang('Hợp đồng cần gia hạn trước')
                        <span></span>
                    </label>
                </span>

    </div>
    <div class="col-lg-8">
        <div class="input-group">
            <input type="text" class="form-control m-input input_int" id="number_day_renew" name="number_day_renew">
            <div class="input-group-append">
                <span class="input-group-text">@lang('Ngày hết hiệu lực')</span>
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_created_ticket" name="is_created_ticket" type="checkbox"
                               checked="">@lang('Tạo ticket khi hợp đồng ở trạng thái')
                        <span></span>
                    </label>
                </span>

    </div>
    <div class="col-lg-3">
        <select class="form-control" id="status_code_created_ticket" name="status_code_created_ticket"
                style="width:100%;">
            @foreach($optionStatus as $v1)
                @if($v1['default_system'] == 'processing')
                    <option value="{{$v1['status_code']}}" selected>{{$v1['status_name']}}</option>
                @else
                    <option value="{{$v1['status_code']}}">{{$v1['status_name']}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<input type="hidden" id="category_type" name="category_type" value="{{$infoCategory['type']}}">
<input type="hidden" id="category_id" name="category_id" value="{{$infoCategory['contract_category_id']}}">

