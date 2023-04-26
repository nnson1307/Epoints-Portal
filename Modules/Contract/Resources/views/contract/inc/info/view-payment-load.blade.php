<div class="row">
    @if (count($tabPayment) > 0)
        @foreach($tabPayment as $v)
            <div class="form-group m-form__group col-lg-4">
                <label class="black_title">
                    {{$v['key_name']}}:
                    <b class="text-danger">{{$v['is_validate'] == 1 ? '*': ''}}</b>
                </label>
                <div class="input-group">
                    @switch($v['type'])
                        @case('text')
                        <input type="text" class="form-control m-input" id="{{$v['key']}}"
                               name="{{'tab_payment_'. $v['key']}}"
                               isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                               keyType="{{$v['type']}}"
                                {{in_array($v['key'], ['representative', 'hotline', 'staff_title']) ? 'disabled': ''}}
                                {{$is_value_goods == 1 && in_array($v['key'], ['reason_discount']) ? 'disabled': ''}}>
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
                        <input type="text" class="form-control m-input input_float" id="{{$v['key']}}"
                               name="{{'tab_payment_'. $v['key']}}"
                               isValidate="{{$v['is_validate']}}" keyName="{{$v['key_name']}}"
                               keyType="{{$v['type']}}" {{$is_value_goods == 1 && in_array($v['key'], ['total_amount', 'tax', 'discount', 'last_total_amount']) ? 'disabled': ''}}
                                value="{{$is_value_goods == 1 && in_array($v['key'], ['total_amount', 'tax', 'discount', 'last_total_amount']) ? '0': ''}}">

                        @break

                        @case('date')
                        <input type="text" class="form-control m-input date_picker" readonly="" id="{{$v['key']}}"
                               name="{{'tab_payment_'. $v['key']}}" isValidate="{{$v['is_validate']}}"
                               keyName="{{$v['key_name']}}" keyType="{{$v['type']}}">
                        <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                        </div>
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