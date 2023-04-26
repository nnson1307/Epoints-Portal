<div class="div-config">
    <div class="m-portlet__body">
        <div class="form-group row">
            <div class="col-lg-3">
                <label for="">{{__('Thời gian thiết lập lại thứ hạng thành viên')}}:</label>
            </div>
            <div class="col-lg-2">
                <select name="reset_member_ranking" id="reset_member_ranking" class="form-control ss-select-2 hagtag_id"
                       style="width: 100%">
                    <option value="0" {{$config[0]['value'] == 0 ? 'selected' : ''}}>
                        {{__('Không thời hạn')}}
                    </option>
                    @for($i = 1; $i < 13; $i++)
                        @if(in_array($i, [1, 2, 3, 4, 6, 12]))
                            <option value="{{$i}}" {{$config[0]['value'] == $i ? 'selected' : ''}}>
                                {{$i}} {{__('tháng')}}
                            </option>
                        @endif
                    @endfor
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-2">
                <label for="">{{__('Trạng thái')}}:</label>
            </div>
            <div class="col-lg-10">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input {{$config[1]['value'] == 1 ? 'checked' : ''}} type="checkbox" class="" name="actived_loyalty" id="actived_loyalty">
                        <span></span>
                    </label>
                </span>
            </div>
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
                    <button type="button" onclick="pointRewardRule.saveConfig()"
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
