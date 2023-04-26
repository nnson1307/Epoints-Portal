<div class="modal fade" id="modal-config-sms" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ss--title m--font-bold" id="exampleModalLabel">
                    <i class="la la-edit ss--icon-title m--margin-right-5"></i> {{__('CHI TIẾT TIN NHẮN SMS')}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="m-portlet__body">
                    <h7 class="modal-title m--font-boldest title-setting"></h7>
                    <div class="row m--margin-top-10">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">{{__('Nội dung tin nhắn')}}:</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <i class="pull-right"><i class="count-character">0</i>{{__('/480 ký tự')}}</i>
                                    </div>
                                </div>
                                <textarea onkeyup="ConfigSms.contentMessageChange()" placeholder=" {{__('Nội dung tin nhắn')}}"
                                          rows="4" cols="30"
                                          id="message-content" class="form-control
                                 m-input"></textarea>
                                <span class="text-danger error-count-character"></span>
                            </div>
                            <div class="form-group m-form__group choose-time">
                                <label for="">{{__('Thời gian gửi')}}:</label>
                                <input id="send-time" class="form-control col-lg-4"
                                       readonly="" placeholder="{{__('Chọn giờ')}}" type="text">
                            </div>
                            <div class="form-group m-form__group choose-day">
                                <label for="">{{__('Số ngày')}}:</label>
                                <br>
                                <select name="" id="number-day" class="form-control col-lg-3">
                                    @for($i=1;$i<4;$i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group m-form__group choose-hour">
                                <label for="">{{__('Số giờ')}}:</label>
                                <br>
                                <select name="" id="hour" class="form-control col-lg-3">
                                    @for($i=1;$i<4;$i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label>
                                    {{__('Tham số')}}:
                                </label>
                                <div class="parameter">

                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <div class="parameter2">

                                </div>
                            </div>
                        </div>
                    </div>


                    {{--<div class="form-group m-form__group choose-day">--}}
                    {{--<label for="">Số ngày:</label>--}}
                    {{--<input onkeydown="ConfigSms.onKeyDownInput(this)" id="number-day" class="form-control col-lg-3"--}}
                    {{--placeholder="Số ngày" min="0" value="1" type="number">--}}
                    {{--<select name="number-day" id="number-day" class="form-control col-lg-3">--}}
                    {{--@for($i=1;$i<4;$i++)--}}
                    {{--<option value="{{$i}}">{{$i}}</option>--}}
                    {{--@endfor--}}
                    {{--</select>--}}
                    {{--</div>--}}

                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit  ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10 m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>
                        <button onclick="ConfigSms.saveConfigTypeSms()" type="button"
                                class="ss--btn-mobiles btn btn-success ss--btn color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>