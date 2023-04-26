<div class="div-event">
    <div class="m-widget4">
        @foreach($pointRewardRule as $item)
            @if(!in_array($item['point_reward_rule_id'],[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]))
                <div class="m-widget4__item record_rule {{$item['point_reward_rule_id']%2 == 1 ? '' : 'ss--background-config-sms'}}">
                    <div class="m-widget4__checkbox m--margin-left-15">
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                            <input type="hidden" value="{{$item['point_reward_rule_id']}}" class="point_reward_rule_id">
                            <input {{$item['point_reward_rule_id'] == 1 ? 'disabled' : ''}} type="checkbox"
                                   class="is_actived" {{$item['is_actived'] == 1 ? 'checked' : ''}}>
                            <span></span>
                        </label>
                    </div>
                    <div class="m-widget4__info">
                        <div class="row">
                            <div class="col-lg-3">
                                    <span class="m-widget4__title sz_dt">
                                            {{__($item['rule_name'])}}
                                    </span>
                            </div>
                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Phép tính')}}</label>
                                <input readonly class="form-control" style="text-align: center; " value="+">
                            </div>

                            <div class="col-lg-2">
                                <label class="sz_sms">{{__('Giá trị')}}</label>
                                <input type="text" class="form-control point_value numeric"
                                       value="{{$item['point_value']}}" min="0" onkeyup="pointRewardRule.defaultInput(this)">
                                <span class="text-danger error_point_value"></span>
                            </div>
                            <div class="col-lg-6">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
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
                    <button type="button" onclick="pointRewardRule.saveEvent(this)"
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
