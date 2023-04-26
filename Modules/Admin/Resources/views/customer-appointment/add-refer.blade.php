<div id="refer" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Thêm người giới thiệu')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form_refer">
                    {{--<div class="form-group m-form__group">--}}
                        {{--<label>Chon người giới thiệu</label>--}}
                        {{--<div>--}}
                           {{--<select id="search_refer" name="search_refer" style="width: 100%">--}}

                           {{--</select>--}}
                        {{--</div>--}}
                        {{--<input type="hidden" name="refer_hidden" id="refer_hidden">--}}
                        {{--<input type="hidden" name="refer_text" id="refer_text">--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label>
                            {{__('Tên người giới thiệu')}}:
                        </label>
                        <div>
                            <input type="text" id="full_name_refer" name="full_name_refer" class="form-control m-input"
                                   placeholder="{{__('Họ và tên')}}">

                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Số điện thoại')}}:
                        </label>
                        <div>
                            <input type="text" onkeydown="onKeyDownInput(this)" id="phone1_refer" name="phone1_refer" class="form-control m-input"
                                   placeholder="{{__('Số điện thoại')}}">
                            <span class="error-phone" style="color: #ff0000"></span>
                        </div>
                    </div>
                    {{--<div class="modal-footer">--}}
                        {{--<button id="btn_refer" onclick="customer_appointment.add_refer()" title="Thêm và đóng" class="btn btn-primary">--}}
                            {{--Lưu lại--}}
                        {{--</button>--}}
                        {{--<button type="button" class="btn btn-default" data-dismiss="modal">{{__('Hủy')}}</button>--}}
                    {{--</div>--}}
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal" class="btn btn-danger m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('Thoát')}}</span>
						</span>
                            </button>

                            <div class="btn-group">
                                <button type="submit" onclick="customer_appointment.add_refer(0)" class="btn btn-success  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Lưu lại')}}</span>
							</span>
                                </button>
                                <button type="button" class="btn btn-success  dropdown-toggle dropdown-toggle-split m-btn m-btn--md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">
                                    <button type="submit" class="dropdown-item" href="javascript:void(0)" onclick="customer_appointment.add_refer(0)"><i class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}
                                    </button>
                                    <button type="submit" class="dropdown-item" href="javascript:void(0)" onclick="customer_appointment.add_refer(1)"><i class="la la-undo"></i> {{__('Lưu')}} &amp; {{__('Đóng')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="type_add" id="type_add" value="0">
                </form>
            </div>

        </div>

    </div>
</div>
