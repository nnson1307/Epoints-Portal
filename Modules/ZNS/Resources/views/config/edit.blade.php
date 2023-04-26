<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title ss--title m--font-bold">
                <i class="la la-edit ss--icon-title m--margin-right-5"></i>
                {{ __('CHI TIẾT ZNS BỊ ĐỘNG') }}
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-group m-input">
                <label class="black_title">
                    @lang('Tên mẫu'):<b class="text-danger">*</b>
                </label>
                <input type="hidden" name="id" value="{{$item->id}}">
                <input class="form-control" name="name" autocomplete="off" placeholder="{{ __('Tên mẫu') }}"
                       value="{{ $item->name }}">
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Tên mẫu ZNS'):<b class="text-danger">*</b>
                </label>
                <div class="input-group">
                    <select name="zns_template_id" class="form-control select2 select2-active" id="zns_template_id">
                        <option value="">@lang('Chọn tên mẫu ZNS')</option>
                        @foreach ($option as $key => $value)
                            <option value="{{$key}}"{{$item->zns_template_id == $key ? ' selected':''}}>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title d-block">
                    @lang('Nội dung mẫu tin ZNS'):<b class="text-danger">*</b>
                </label>
                {{--                <textarea class="form-control" name="description" rows="6" cols="5"--}}
                {{--                    placeholder="@lang('Nội dung mẫu tin ZNS')..." disabled>{{ '21321' }}</textarea>--}}
                <iframe class="innerIframe d-block border-0" src="{{ $item->preview }}" name="innerIframe"
                        id="content_zns" width="500px" height="400px"></iframe>
            </div>
            <div class="form-group m-input">
                <label class="black_title">
                    @lang('Ghi chú'):
                </label>
                <input class="form-control" name="hint" autocomplete="off" placeholder="{{ __('Ghi chú') }}"
                       value="{{ $item->hint }}">
            </div>
            @if ($param_trigger)
                <div class="form-group m-input">
                    <label class="black_title">
                        @lang('Các tham số có thể sử dụng'):
                    </label>
                    <div class="text-break">
                        @foreach ($param_trigger as $value)
                            <div class="mr-3 coppy_button text-break text-black-50 d-block"><i
                                        class="fa fa-clone mr-2"></i>{{ $value->value }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(in_array($item->key,['birthday']))
                <div class="form-group m-form__group choose-time">
                    <label for="">{{__('Thời gian gửi')}}:</label>
                    <input id="send-time" name="time_sent" class="form-control col-lg-4"
                           readonly="" placeholder="{{__('Chọn giờ')}}" type="text">
                </div>
            @elseif (in_array($item->key,['service_card_nearly_expired']))
                <div class="form-group m-form__group choose-day">
                    <label for="">{{__('Gửi trước số ngày')}}:</label>
                    <select name="value" id="number-day" class="form-control col-lg-3">
                        @for($i=1;$i<4;$i++)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
            @elseif (in_array($item->key,['remind_appointment']))
                <div class="form-group m-form__group choose-day">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label class="m-radio cus mr-3">
                                    <input type="checkbox" name="check_send[before]"
                                           value="before"{{$item->check_send == "before"?" checked":""}}>{{__('Gửi trước số giờ :')}}
                                    <span></span>
                                </label>
                                <select name="value[before]" class="form-control col-lg-3">
                                    @for($i=1;$i<4;$i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label class="m-radio cus mr-3">
                                    <input type="checkbox" name="check_send[after]" value="after"{{$item->check_send == "after"?" checked":""}}>{{__('Gửi sau số giờ :')}}
                                    <span></span>
                                </label>
                                <select name="value[after]" class="form-control col-lg-3">
                                    @for($i=1;$i<4;$i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                    </button>
                    <button type="submit"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-check"></i>
                            <span>{{ __('CẬP NHẬT THÔNG TIN') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
