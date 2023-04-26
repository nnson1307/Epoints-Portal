@if(isset($LIST_SETTING_OTHER))
    @foreach($LIST_SETTING_OTHER as $item)
        @if($item['name']!='Thời gian đặt lịch')
            <div class="form-group m-form__group">
                <div class="row">
                    <div class="col-lg-3">
                    <span>
                        {{__($item['name'])}}
                    </span>
                    </div>
                    <div class="col-lg-9">
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="other_extra.change_status_setting_other(this, '{!! $item['id'] !!}')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                    <input type="checkbox"
                           onclick="other_extra.change_status_setting_other(this, '{!! $item['id'] !!}')"
                           class="manager-btn">
                    <span></span>
                    </label>
                    </span>
                        @endif

                    </div>
                </div>
            </div>
        @else
            <form id="form-other-day">
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3">
                            <span>{{__($item['name'])}}</span>
                        </div>
                        <div class="col-lg-9">
                            <span>{{__('Khách hàng được phép đặt lịch hẹn xa nhất bao lâu?')}}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3">

                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" name="day"
                                               value="{{$item['day']}}" placeholder="{{__('Hãy nhập số ngày')}}...">
                                        <div class="input-group-append">
                                         <span class="input-group-text" id="basic-addon2">
                                             {{__('Ngày')}}
                                         </span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-3">
                                    <button type="button" onclick="other_extra.edit_day('{{$item['id']}}')"
                                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<span>{{__('LƯU')}}</span>
							</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    @endforeach
@endif