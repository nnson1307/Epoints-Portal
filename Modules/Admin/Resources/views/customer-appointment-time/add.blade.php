<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Thêm giờ hẹn')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <form id="form">
                <div class="modal-body">
                    {{--{!! csrf_field() !!}--}}
                    <div class="form-group">
                        <label>
                            {{__('Khung giờ hẹn')}}:<b class="text-danger">*</b>
                        </label>
                        {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                        <input type="text" readonly name="time" class="form-control m-input" id="time"
                               placeholder="{{__('Hãy nhập khung giờ hẹn')}}">
                        <span class="error-time" style="color: #ff0000"></span>
                    </div>
                </div>
                <input type="hidden" name="type_add" id="type_add" value="0">
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('Hủy')}}</span>
						</span>
                            </button>

                            <div class="btn-group">
                                <button type="submit" id="luu" onclick="customer_appointment_time.add(1)"
                                        class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Lưu lại')}}</span>
							</span>
                                </button>
                                <button type="button"
                                        class="btn btn-primary  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                     style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">
                                    <button type="submit" class="dropdown-item" id="luu1"
                                            onclick="customer_appointment_time.add(0)">
                                        <i class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}
                                    </button>
                                    <button type="submit" class="dropdown-item" id="luu"
                                            onclick="customer_appointment_time.add(1)">z
                                        <i class="la la-undo"></i> {{__('Lưu')}} &amp; {{__('Đóng')}}
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item" data-dismiss="modal"><i class="la la-close"></i> {{__('Hủy')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
