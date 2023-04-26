<div class="modal fade show" id="edit-commission" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM HOA HỒNG')}}
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label class="black-title">
                        {{__('Hoa hồng người giới thiệu')}}:
                    </label>
                    <div class="input-group m-input-group m-input-group--solid refer">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="{{$item['type_refer_commission'] == 'money' || $item['type_refer_commission'] == null ? 'btn btn-info color_button active':'btn btn-default'}}"
                                   id="refer_money"
                                   onclick="service.refer_commission('money')">
                                <input type="radio" name="type_refer_commission" value="money" checked>{{__('Tiền mặt')}}
                            </label>
                            <label class="{{$item['type_refer_commission'] == 'percent' ? 'btn btn-info color_button active':'btn btn-default'}}" id="refer_percent"
                                   onclick="service.refer_commission('percent')">
                                <input type="radio" name="type_refer_commission" value="percent">{{__('Phần trăm')}}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="input-group m-input-group">
{{--                        <input class="form-control m-input" id="refer_commission_value" name="refer_commission_value"--}}
{{--                               placeholder="{{__('Nhập hoa hồng người giới thiệu')}}" value="{{number_format($item['refer_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">--}}
                        <input class="form-control m-input w-100 {{$item['type_refer_commission'] == 'money' || $item['type_refer_commission'] == null ? '':'d-none'}}"
                               id="refer_commission_value" name="refer_commission_value"
                               placeholder="{{__('Nhập hoa hồng người giới thiệu')}}" value="{{$item['type_refer_commission'] == 'money' || $item['type_refer_commission'] == null ? number_format($item['refer_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : number_format(0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                        <input class="form-control m-input w-100 ml-0 {{$item['type_refer_commission'] == 'percent' ? '':'d-none'}}"
                               id="refer_commission_percent" name="refer_commission_value"
                               placeholder="{{__('Nhập hoa hồng người giới thiệu')}}" value="{{$item['type_refer_commission'] == 'percent' ? number_format($item['refer_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : number_format(0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    </div>
                    <span class="error_refer_commission"></span>
                </div>
                <div class="form-group m-form__group">
                    <label class="black-title">
                        {{__('Hoa hồng nhân viên phục vụ')}}:
                    </label>
                    <div class="input-group m-input-group m-input-group--solid staff">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="{{$item['type_staff_commission'] == 'money' || $item['type_staff_commission'] == null ? 'btn btn-info color_button active':'btn btn-default'}}" id="staff_money"
                                   onclick="service.staff_commission('money')">
                                <input type="radio" name="type_staff_commission" value="money" checked>Tiền mặt
                            </label>
                            <label class="{{$item['type_staff_commission'] == 'percent' ? 'btn btn-info color_button active':'btn btn-default'}}" id="staff_percent"
                                   onclick="service.staff_commission('percent')">
                                <input type="radio" name="type_staff_commission" value="percent">Phần trăm
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="input-group m-input-group">
{{--                        <input class="form-control m-input" id="staff_commission_value" name="staff_commission_value"--}}
{{--                               placeholder="{{__('Nhập hoa hồng nhân viên phục vụ')}}" value="{{number_format($item['staff_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">--}}
                        <input class="form-control m-input w-100 {{$item['type_staff_commission'] == 'money' || $item['type_staff_commission'] == null ? '':'d-none'}}"
                               id="staff_commission_value" name="staff_commission_value"
                               placeholder="{{__('Nhập hoa hồng nhân viên phục vụ')}}" value="{{$item['type_staff_commission'] == 'money' || $item['type_staff_commission'] == null ? number_format($item['staff_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : number_format(0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                        <input class="form-control m-input ml-0 w-100 {{$item['type_staff_commission'] == 'percent' ? '':'d-none'}}"
                               id="staff_commission_percent" name="staff_commission_value"
                               placeholder="{{__('Nhập hoa hồng nhân viên phục vụ')}}" value="{{$item['type_staff_commission'] == 'percent' ? number_format($item['staff_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : number_format(0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    </div>
                    <span class="error_staff_commission"></span>
                </div>
                <div class="form-group m-form__group">
                    <label class="black-title">
                        {{__('Hoa hồng cho deal')}}:
                    </label>
                    <div class="input-group m-input-group m-input-group--solid deal">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="{{$item['type_deal_commission'] == 'money' || $item['type_deal_commission'] == null ? 'btn btn-info color_button active':'btn btn-default'}}" id="deal_money"
                                   onclick="service.deal_commission('money')">
                                <input type="radio" name="type_deal_commission" value="money" checked>{{__('Tiền mặt')}}
                            </label>
                            <label class="{{$item['type_deal_commission'] == 'percent' ? 'btn btn-info color_button active':'btn btn-default'}}" id="deal_percent"
                                   onclick="service.deal_commission('percent')">
                                <input type="radio" name="type_deal_commission" value="percent">{{__('Phần trăm')}}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="input-group m-input-group">
{{--                        <input class="form-control m-input" id="deal_commission_value" name="deal_commission_value"--}}
{{--                               placeholder="{{__('Nhập hoa hồng cho deal')}}" value="{{number_format($item['deal_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">--}}
                        <input class="form-control m-input w-100 {{$item['type_deal_commission'] == 'money' || $item['type_deal_commission'] == null ? '':'d-none'}}"
                               id="deal_commission_value" name="deal_commission_value"
                               placeholder="{{__('Nhập hoa hồng cho deal')}}" value="{{$item['type_deal_commission'] == 'money' || $item['type_deal_commission'] == null ? number_format($item['deal_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : number_format(0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                        <input class="form-control m-input ml-0 w-100 {{$item['type_deal_commission'] == 'percent' ? '':'d-none'}}"
                               id="deal_commission_percent" name="deal_commission_value"
                               placeholder="{{__('Nhập hoa hồng cho deal')}}" value="{{$item['type_deal_commission'] == 'percent' ? number_format($item['deal_commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : number_format(0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    </div>
                    <span class="error_deal_commission"></span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>

                        <button data-dismiss="modal" class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md btn-save-image m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
