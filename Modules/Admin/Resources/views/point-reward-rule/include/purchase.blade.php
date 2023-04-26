<div class="div-purchase">
    <div class="m-widget4">
        <div class="m--margin-bottom-5 ss--border-group-rule">
            @if(isset($pointRewardRule[0]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[0]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[0]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[0]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" checked>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[0]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Điểm')}}</label>
                                <input readonly style="background-color: #ffffff" type="text"
                                       class="form-control"
                                       value="1">
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Số tiền')}}</label>
                                <input type="text" class="form-control point_value input-mask "
                                       value="{{number_format(1/$pointRewardRule[0]['point_value'],0)}}">
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="m--margin-bottom-5 ss--border-group-rule">
            @if(isset($pointRewardRule[4]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[4]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[4]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[4]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[4]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[4]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" onchange="pointRewardRule.isNumber(this)" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[4]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                    <option value="*" {{$pointRewardRule[4]['point_maths'] == '*' ? 'selected' : ''}}>
                                        x
                                    </option>
                                    <option value="+" {{$pointRewardRule[4]['point_maths'] == '+' ? 'selected' : ''}}>
                                        +
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <div class="div-input">
                                    <input type="text" class="form-control point_value input-mask"
                                           value="{{$pointRewardRule[4]['point_value']}}">
                                </div>
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($pointRewardRule[5]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[5]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{__($pointRewardRule[5]['point_reward_rule_id'])}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[5]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[5]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[5]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" onchange="pointRewardRule.isNumber(this)" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[5]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                        <option value="*" {{$pointRewardRule[5]['point_maths'] == '*' ? 'selected' : ''}}>
                                            x
                                        </option>
                                    <option value="+" {{$pointRewardRule[5]['point_maths'] == '+' ? 'selected' : ''}}>
                                        +
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <div class="div-input">
                                    <input type="text" class="form-control point_value input-mask"
                                       value="{{$pointRewardRule[5]['point_value']}}">
                                </div>
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($pointRewardRule[6]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[6]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[6]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[6]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[6]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[6]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" onchange="pointRewardRule.isNumber(this)" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[6]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                        <option value="*" {{$pointRewardRule[6]['point_maths'] == '*' ? 'selected' : ''}}>
                                            x
                                        </option>
                                    <option value="+" {{$pointRewardRule[6]['point_maths'] == '+' ? 'selected' : ''}}>
                                        +
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <div class="div-input">
                                    <input type="text" class="form-control point_value input-mask "
                                       value="{{$pointRewardRule[6]['point_value']}}">
                                </div>
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($pointRewardRule[7]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[7]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[7]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[7]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[7]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[7]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" onchange="pointRewardRule.isNumber(this)" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[7]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                        <option value="*" {{$pointRewardRule[7]['point_maths'] == '*' ? 'selected' : ''}}>
                                            x
                                        </option>
                                    <option value="+" {{$pointRewardRule[7]['point_maths'] == '+' ? 'selected' : ''}}>+
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <div class="div-input">
                                    <input type="text" class="form-control point_value input-mask "
                                       value="{{$pointRewardRule[7]['point_value']}}">
                                </div>
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="m--margin-bottom-5 ss--border-group-rule">
            @if(isset($pointRewardRule[1]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[1]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[1]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[1]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[1]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[1]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[1]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                        <option value="*" {{$pointRewardRule[1]['point_maths'] == '*' ? 'selected' : ''}}>
                                            x
                                        </option>
                                    {{--<option value="+" {{$pointRewardRule[1]['point_maths'] == '+' ? 'selected' : ''}}>+--}}
                                    {{--</option>--}}
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <input type="text" class="form-control point_value input-mask "
                                       value="{{$pointRewardRule[1]['point_value']}}">
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($pointRewardRule[2]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[2]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[2]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[2]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[2]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[2]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[2]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                        <option value="*" {{$pointRewardRule[2]['point_maths'] == '*' ? 'selected' : ''}}>
                                            x
                                        </option>
                                    {{--<option value="+" {{$pointRewardRule[2]['point_maths'] == '+' ? 'selected' : ''}}>+--}}
                                    {{--</option>--}}
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <input type="text" class="form-control point_value input-mask "
                                       value="{{$pointRewardRule[2]['point_value']}}">
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($pointRewardRule[3]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[3]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[3]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[3]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[3]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[3]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[3]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                    {{--@if(!in_array($pointRewardRule[3]['point_reward_rule_id'],[5, 6, 7, 8, 12, 13, 14, 15]))--}}
                                        <option value="*" {{$pointRewardRule[3]['point_maths'] == '*' ? 'selected' : ''}}>
                                            x
                                        </option>
                                    {{--@endif--}}
                                    {{--<option value="+" {{$pointRewardRule[3]['point_maths'] == '+' ? 'selected' : ''}}>+--}}
                                    {{--</option>--}}
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <input type="text" class="form-control point_value input-mask "
                                       value="{{$pointRewardRule[3]['point_value']}}">
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="m--margin-bottom-5 ss--border-group-rule">
            @if(isset($pointRewardRule[9]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[9]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[9]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[9]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[9]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[9]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[9]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                    {{--@if(!in_array($pointRewardRule[9]['point_reward_rule_id'],[5, 6, 7, 8, 12, 13, 14, 15]))--}}
                                        {{--<option value="*" {{$pointRewardRule[9]['point_maths'] == '*' ? 'selected' : ''}}>--}}
                                            {{--x--}}
                                        {{--</option>--}}
                                    {{--@endif--}}
                                    <option value="+" {{$pointRewardRule[9]['point_maths'] == '+' ? 'selected' : ''}}>+
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <input type="text" class="form-control point_value input-mask "
                                       value="{{$pointRewardRule[9]['point_value']}}">
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">
                                @if($pointRewardRule[9]['point_reward_rule_id'] == 9)
                                    <label class="sz_sms">{{__('Dịch vụ')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($service as $key => $value)
                                            <option {{in_array($key, explode(",",$pointRewardRule[9]['hagtag_id'])) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($pointRewardRule[9]['point_reward_rule_id'] == 10)
                                    <label class="sz_sms">{{__('Sản phẩm')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($productChild as $key => $value)
                                            <option {{in_array($key, explode(",",($pointRewardRule[9]['hagtag_id']))) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($pointRewardRule[9]['point_reward_rule_id'] == 11)
                                    <label class="sz_sms">{{__('Thẻ dịch vụ')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($serviceCard as $key => $value)
                                            <option {{in_array($key, explode(",",($pointRewardRule[9]['hagtag_id']))) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($pointRewardRule[8]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[8]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[8]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[8]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[8]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[8]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[8]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                    {{--@if(!in_array($pointRewardRule[8]['point_reward_rule_id'],[5, 6, 7, 8, 12, 13, 14, 15]))--}}
                                        {{--<option value="*" {{$pointRewardRule[8]['point_maths'] == '*' ? 'selected' : ''}}>--}}
                                            {{--x--}}
                                        {{--</option>--}}
                                    {{--@endif--}}
                                    <option value="+" {{$pointRewardRule[8]['point_maths'] == '+' ? 'selected' : ''}}>+
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <input type="text" class="form-control point_value input-mask "
                                       value="{{$pointRewardRule[8]['point_value']}}">
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">
                                @if($pointRewardRule[8]['point_reward_rule_id'] == 9)
                                    <label class="sz_sms">{{__('Dịch vụ')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($service as $key => $value)
                                            <option {{in_array($key, explode(",",$pointRewardRule[8]['hagtag_id'])) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($pointRewardRule[8]['point_reward_rule_id'] == 10)
                                    <label class="sz_sms">{{__('Sản phẩm')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($productChild as $key => $value)
                                            <option {{in_array($key, explode(",",($pointRewardRule[8]['hagtag_id']))) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($pointRewardRule[8]['point_reward_rule_id'] == 11)
                                    <label class="sz_sms">{{__('Thẻ dịch vụ')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($serviceCard as $key => $value)
                                            <option {{in_array($key, explode(",",($pointRewardRule[8]['hagtag_id']))) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($pointRewardRule[10]))
                <div class="m-widget4__item record_rule {{$pointRewardRule[10]['point_reward_rule_id']%2 == 0 ? '' : ''}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$pointRewardRule[10]['point_reward_rule_id']}}"
                                   class="point_reward_rule_id">
                            <input {{$pointRewardRule[10]['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$pointRewardRule[10]['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($pointRewardRule[10]['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <select name="" id="" style="width: 100%"
                                        class="form-control m-input m_selectpicker point_maths" {{$pointRewardRule[10]['point_reward_rule_id'] == 1 ? 'disabled' : ''}}>
                                    {{--@if(!in_array($pointRewardRule[10]['point_reward_rule_id'],[5, 6, 7, 8, 12, 13, 14, 15]))--}}
                                        {{--<option value="*" {{$pointRewardRule[10]['point_maths'] == '*' ? 'selected' : ''}}>--}}
                                            {{--x--}}
                                        {{--</option>--}}
                                    {{--@endif--}}
                                    <option value="+" {{$pointRewardRule[10]['point_maths'] == '+' ? 'selected' : ''}}>+
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <input type="text" class="form-control point_value input-mask "
                                       value="{{$pointRewardRule[10]['point_value']}}">
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-5">
                                @if($pointRewardRule[10]['point_reward_rule_id'] == 9)
                                    <label class="sz_sms">{{__('Dịch vụ')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($service as $key => $value)
                                            <option {{in_array($key, explode(",",$pointRewardRule[10]['hagtag_id'])) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($pointRewardRule[10]['point_reward_rule_id'] == 10)
                                    <label class="sz_sms">{{__('Sản phẩm')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($productChild as $key => $value)
                                            <option {{in_array($key, explode(",",($pointRewardRule[10]['hagtag_id']))) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($pointRewardRule[10]['point_reward_rule_id'] == 11)
                                    <label class="sz_sms">{{__('Thẻ dịch vụ')}}</label>
                                    <select name="" id="" class="form-control ss-select-2 hagtag_id"
                                            multiple="multiple" style="width: 100%">
                                        @foreach($serviceCard as $key => $value)
                                            <option {{in_array($key, explode(",",($pointRewardRule[10]['hagtag_id']))) ? 'selected' : ''}}  value="{{$key}}">
                                                {{$value}}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <div class="form-group m-form__group m--margin-top-10">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
{{--                    <a href="{{ \Illuminate\Support\Facades\URL::previous() }}"--}}
{{--                       class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">--}}
{{--                                            <span class="ss--text-btn-mobi">--}}
{{--                                            <i class="la la-arrow-left"></i>--}}
{{--                                            <span>HỦY</span>--}}
{{--                                            </span>--}}
{{--                    </a>--}}
                    <button type="button" onclick="pointRewardRule.savePurchase(this)"
                            class="ss--btn-mobiles save-change btn-save m--margin-left-10 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 color_button">
                                                <span class="ss--text-btn-mobi">
                                            <i class="la la-check"></i>
                                            <span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
                                            </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
