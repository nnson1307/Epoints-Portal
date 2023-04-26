
<script type="text/template" id="button-tpl">
    <a href="javascript:void(0)" onclick="order.customer_haunt('1',this)"
       class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary cus_haunt"><i
                class="la la-user"></i>{{__('Khách hàng vãng lai')}}</a>
</script>
<script type="text/template" id="customer-haunt-tpl">
    <div class="m-widget4__item ">
        <div class="m-widget4__img m-widget4__img--pic">
            <img src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
                 alt="">
        </div>
        <div class="m-widget4__info">
							<span class="m-widget4__title ">
							{{__('Khách vãng lai')}}
							</span><br>

            <span class="m-widget4__sub">

							</span>

        </div>

    </div>
</script>
<script type="text/template" id="tab-card-tpl">
    <li class="nav-item m-tabs__item type">
        <a href="javascript:void(0)" onclick="order.click('member_card')"
           class="nav-link m-tabs__link tab_member_card" data-toggle="tab" role="tab"
           aria-selected="false" data-name="member_card">
            <i class="la la-shopping-cart"></i>{{__('Thẻ dịch vụ đã mua')}}
        </a>
    </li>
</script>
<script type="text/template" id="list-tpl">
    <div class="info-box col-lg-3 m--margin-bottom-10"
         onclick="order.append_table({id},'{price_hidden}','{type}','{name}','{code}')">
        <div class="info-box-content ss--text-center">
            <span class="info-box-number ss--text-center">{price}@lang('đ')</span>
            <div class="info-box-number ss--text-center">
                <img src="{img}" class="ss--image-pos">
                <input type="hidden" class="type_hidden" name="type_hidden" value="{type}">
            </div>
            <span class="info-box-text ss--text-center">{name}</span>
        </div>
    </div>
</script>

<script type="text/template" id="table-tpl">
    <tr class="tr_table">
        <td class="td_vtc stt_length" style="width: 30px;"></td>
        <td class="td_vtc" style="color: #000; font-weight: 500;width: 100px;">
            {name}
            <input type="hidden" name="number_tr" value="{stt}">
            <input type="hidden" name="id" value="{id}">
            <input type="hidden" name="name" value="{name}">
            <input type="hidden" name="object_type" value="{type_hidden}">
            <input type="hidden" name="object_code" value="{code}">
        </td>
        <td class="td_vtc" style="width: 130px;">
            <input class="form-control price" name="price" value="{price}" id="price_{stt}">
        </td>
        <td class="td_vtc" style="width: 90px;">
            <input style="text-align: center; height: 31px;font-size: 13px;" type="text" name="quantity"
                   class="quantity quantity_{id} form-control btn-ct-input" data-id="{stt}" value="1"
                   {{$customPrice == 1 ? 'disabled' : ''}} {isSurcharge}>
            <input type="hidden" name="quantity_hid" value="{quantity_hid}">
        </td>
        <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center; width: 130px;">
            <input type="hidden" name="discount" class="form-control discount" value="0">
            <input type="hidden" name="voucher_code" value="">
            @if (!isset($customPrice) || $customPrice == 0)
                <a href="javascript:void(0)" id="discount_{stt}"
                   class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                   onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}','{stt}')">
                    <i class="la la-plus icon-sz"></i>
                </a>
            @endif
        </td>
        <td class="amount-tr td_vtc" style="text-align: center; width: 130px;">
            @if (isset($customPrice) && $customPrice == 1)
                <input name="amount" style="text-align: center;" class="form-control amount"
                       id="amount_{stt}"
                       value="{amount_hidden}">
            @else
                <div id="amount_surcharge_{stt}">
                    <input name="amount" style="text-align: center;" class="form-control amount"
                           id="amount_{stt}"
                           value="{amount_hidden}">
                </div>
                <div id="amount_not_surcharge_{stt}">
                    {amount}{{__('đ')}}
                    <input type="hidden" style="text-align: center;" name="amount"
                           class="form-control amount" id="amount_{stt}" value="{amount_hidden}">
                </div>
            @endif
        </td>
        <td class="td_vtc" style="width: 50px;">
            <input type="hidden" name="is_change_price" value="{is_change_price}">
            <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">
            <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i
                        class='la la-trash'></i></a>
        </td>

        <input type="hidden" id="numberRow" value="{numberRow}">
        <input type="hidden" name="note" id="note_{stt}">
    </tr>

    <tr class="tr_note_child_{stt}">
        <td></td>
        <td colspan="2">
            <span id="note_text_{stt}">@lang('Ghi chú/Dịch vụ thêm')...</span>
            <a href="javascript:void(0)"
               onclick="order.showPopupAttach('{stt}', '{type_hidden}', '{id}', '{name}', '{price}')"
               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                <i class="la la-pencil"></i>
            </a>
        </td>
        <td></td>
        <td colspan="2" class="td_staff">
            <select class="form-control staff staff_{stt}" name="staff_id" multiple="multiple"
                    style="width: 80%">
                <option></option>
                @foreach($optionStaff as $key => $value)
                    <option value="{{$value['staff_id']}}">{{$value['full_name']}}</option>
                @endforeach
            </select>
        </td>
    </tr>
</script>

<script type="text/template" id="bill-tpl">
    <span class="total_bill">
                                   {total_bill_label}
                 <input type="hidden" name="total_bill" id="total_bill" class="form-control total_bill"
                        value="{total_bill}">

                    </span>
</script>
<script type="text/template" id="customer-tpl">
    <div class="m-widget4__img m-widget4__img--pic">
        <img src="{img}" alt="" width="50px" height="50px">
    </div>
    <div class="m-widget4__info">
							<span class="m-widget4__title ">
							{full_name}
							</span><br>
        <span class="m-widget4__sub">
							<i class="la la-phone"></i>{phone}<br>
                             {{__('Điểm')}}: 0<br>
                             {{__('Tiền còn lại')}}: {money} đ
							</span>
    </div>
</script>
<script type="text/template" id="type-receipt-tpl">
    <div class="row">
        <label class="col-lg-6 font-13">{label}:<span
                    style="color:red;font-weight:400">{money}</span></label>
        <div class="input-group input-group-sm col-lg-6">
            <input onkeyup="order.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input"
                   placeholder="Nhập giá tiền"
                   aria-describedby="basic-addon1"
                   name="{name_cash}" id="{id_cash}" value="0">
            <div class="input-group-append"><span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}</span>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="active-card-tpl">
    <a href="javascript:void(0)" onclick="order.modal_card({id})"
       class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary active-a"><i
                class="la la-plus"></i>{{__('Thẻ dịch vụ đã mua')}}</a>
</script>
<script type="text/template" id="list-card-tpl">
    <div class="info-box col-lg-3 m--margin-bottom-10"
         onclick="order.append_table_card({id_card},'0','member_card','{card_name}','{quantity_app}','{card_code}',this)">
        <div class="info-box-content ss--text-center">
            <div class="m-widget4__item card_check_{id_card}">
                    <span class="m-widget4__sub m--font-bolder m--font-success quantity">
                        {quantity}(lần)
                    </span>
                <div class="m-widget4__img m-widget4__img--pic">
                    <img src="{img}" class="ss--image-pos">
                </div>
                <div class="m-widget4__info">
                    <span class="m-widget4__title"> {card_name} </span>
                </div>
                <input type="hidden" class="card_hide" value="{card_code}">
                <input type="hidden" class="quantity_card" value="{quantity_app}">
            </div>
        </div>
    </div>


    <!-- <div class="m-widget4__item card_check_{id_card}">
            <div class="m-widget4__img m-widget4__img--pic">
                <img src="{{asset('{img}')}}" alt="" width="52px" height="52px">
            </div>
            <div class="m-widget4__info">
                <span class="m-widget4__title"> {card_name} </span><br>
                <span class="m-widget4__sub m--font-bolder m--font-success quantity">{quantity}(lần)</span><br>
                <span class="m-widget4__sub m--font-bolder m--font-success">{card_code}</span><br>
            </div>
            <div class="m-widget4__ext">
                <input type="hidden" class="card_hide" value="{card_code}">
                <input type="hidden" class="quantity_card" value="{quantity_app}">
                <a href="javascript:void(0)"
                   onclick="order.append_table_card({id_card},'0','member_card','{card_name}','{quantity_app}','{card_code}',this)"
                   class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">
                    Chọn
                </a>
            </div>
        </div> -->

</script>
<script type="text/template" id="table-card-tpl">
    <tr class="tr_table">
        <td></td>
        <td>{name}
            <input type="hidden" name="id" value="{id}">
            <input type="hidden" name="name" value="{name}">
            <input type="hidden" name="object_type" value="{type_hidden}">
            <input type="hidden" name="object_code" value="{code}">
        </td>
        <td>
            {price}đ
            <input type="hidden" name="price" value="{price_hidden}">
        </td>
        <td>
            <input style="text-align: center;" name="quantity" class="quantity_c form-control btn-ct-input">
            <input type="hidden" name="quantity_hid" value="{quantity_hid}">
        </td>
        <td class="discount-tr-{type_hidden}-{id} text-center">
            <input type="hidden" name="discount" class="form-control discount" value="0">
            <input type="hidden" name="voucher_code" value="">
            <a class="{class} m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary"
               href="javascript:void(0)" onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}')">
                <i class="la la-plus"></i>
            </a>
        </td>
        <td class="amount-tr text-center">
            {amount}đ
            <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                   value="{amount_hidden}">
        </td>
        <td>
            <select class="form-control staff" name="staff_id" style="width:100%;">
                <option></option>
                @foreach($optionStaff as $key => $value)
                    <option value="{{$value['staff_id']}}">{{$value['full_name']}}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="hidden" name="number_ran">
            <a class='remove_card' href="javascript:void(0)" style="color: #a1a1a1"><i
                        class='la la-trash'></i></a>
        </td>
    </tr>
</script>
<script type="text/template" id="active-tpl">
    <div class="m-checkbox-list">
        <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success sz_dt">
            <input type="checkbox" name="check_active" id="check_active" value="0"> {{__('Kích hoạt thẻ dịch vụ')}}
            <span></span>
        </label>
    </div>
</script>
<script type="text/template" id="sv-card-print-tpl">
    <div class="col-lg-6 m--padding-bottom-10 card_print">
        <label class="m-option">
                            <span class="m-option__label">
																					<span class="m-option__head">
																						<span class="m-option__title"
                                                                                              style="font-weight: bold;">
																							{card_name}
																						</span>
																						<span class="m-option__focus">
																							{money}
																						</span>
																					</span>
																					<span class="m-option__body">
																						{{__('Số lần sử dụng')}}: {number_using}
																					</span>
                                <span class="m-option__body">
																						{{__('Thời hạn sử dụng')}}: {date_using}
																					</span>
                                 <span class="m-option__body">
																						{{__('Chưa sử dụng')}}
																					</span>
                                <span class="m-option__body">
																						{{__('Mã thẻ')}}: {card_code}
																					</span>
																				</span>
        </label>
        <div class="form-group m-form__group" align="center">
            <a href="javascript:void(0)" onclick="order.print('{card_code}')"
               class="btn btn-outline-primary btn-sm 	m-btn m-btn--icon">
															<span>
																<i class="la la-print"></i>
																<span>
																	{{__('In')}}
																</span>
															</span>
            </a>
            <a href="javascript:void(0)" onclick="ORDERGENERAL.sendEachSmsServiceCard('{card_code}')"
               class="btn btn-outline-primary btn-sm m-btn m-btn--icon btn-send-sms">
															<span>
																<i class="la la-mobile-phone"></i>
																<span>
																	{{__('SMS')}}
																</span>
															</span>
            </a>
        </div>
    </div>
</script>
<script type="text/template" id="button-discount-add-tpl">
    <div class="m-form__actions m--align-right w-100">
        <button data-dismiss="modal"
                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{(__('HUỶ'))}}</span>
                                </span>
        </button>
        <button type="button" onclick="order.discount('{id}','{id_type}','{numb_ran}')"
                class="btn btn-primary  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-print m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
        </button>
    </div>
</script>
<script type="text/template" id="button-discount-bill-tpl">
    <div class="m-form__actions m--align-right w-100">
        <button data-dismiss="modal"
                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{(__('HUỶ'))}}</span>
                                </span>
        </button>
        <button type="button" onclick="order.modal_discount_bill_click()"
                class="btn btn-primary  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-print m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
        </button>
    </div>
</script>
<script type="text/template" id="table-gift-tpl">
    <tr class="tr_table promotion_gift">
        <td></td>
        <td class="td_vtc">
            {name}
            <input type="hidden" name="id" value="{id}">
            <input type="hidden" name="name" value="{name}">
            <input type="hidden" name="object_type" value="{type_hidden}">
            <input type="hidden" name="object_code" value="{code}">
        </td>
        <td class="td_vtc">
            {price} {{__('đ')}}
            <input type="hidden" name="price" value="{price_hidden}">
        </td>
        <td class="td_vtc">
            <input style="text-align: center;" type="text" name="quantity" class="form-control btn-ct-input"
                   data-id="{stt}" value="{quantity}" disabled>
            <input type="hidden" name="quantity_hid" value="{quantity_hid}">
        </td>
        <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center">
            <input type="hidden" name="discount" class="form-control discount" value="0">
            <input type="hidden" name="voucher_code" value="">
        </td>
        <td class="amount-tr td_vtc" style="text-align: center">
            {amount} @lang('đ')
            <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                   value="{amount_hidden}">
        </td>
        <td>
            <select class="form-control staff" name="staff_id" style="width:80%;" disabled multiple="multiple">
                <option></option>
                @foreach($optionStaff as $key => $value)
                    <option value="{{$value['staff_id']}}">{{$value['full_name']}}</option>
                @endforeach
            </select>
        </td>
        <td class="td_vtc">
            <input type="hidden" name="is_change_price" value="0">
            <input type="hidden" name="is_check_promotion" value="0">
            <a class='remove' href="javascript:void(0)" onclick="order.removeGift(this)" style="color: #a1a1a1"><i
                        class='la la-trash'></i></a>
        </td>
    </tr>
</script>
<script type="text/template" id="list-promotion-tpl">
    <div class="info-box col-lg-3 m--margin-bottom-10"
         onclick="order.append_table({id},'{price_hidden}','{type}','{name}','{code}')">
        <div class="info-box-content ss--text-center">
                <span class="info-box-number ss--text-center"
                      style="text-decoration: line-through;">{price}{{__('đ')}}</span>
            <span class="info-box-number ss--text-center">{price_hidden}{{__('đ')}}</span>
            <div class="info-box-number ss--text-center">
                <img src="{img}" class="ss--image-pos">
                <input type="hidden" class="type_hidden" name="type_hidden" value="{type}">
            </div>
            <span class="info-box-text ss--text-center">{name}</span>
        </div>
    </div>
</script>
<script type="text/template" id="payment_method_tpl">
    <div class="row mt-3 method payment_method_{id}">
        <label class="col-lg-6 font-13">{label}:<span
                    style="color:red;font-weight:400">{money}</span></label>
        <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
            <input onkeyup="order.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input"
                   placeholder="{{__('Nhập giá tiền')}}"
                   aria-describedby="basic-addon1"
                   name="payment_method" id="payment_method_{id}" value="0">
            <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
            </div>
            <div class="input-group-append">
                <button type="button" style="display: {style-display}" onclick="vnpay.createQrCode(this)" class="btn btn-primary m-btn m-btn--custom color_button">
                    @lang('TẠO QR')
                </button>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="quick_appointment_tpl">
    <div class="form-group m-form__group row">
        <div class="form-group col-lg-6">
            <label class="black-title">{{__('Ngày hẹn')}}:<b
                        class="text-danger">*</b></label>
            <div class="input-group date_edit">
                <div class="m-input-icon m-input-icon--right">
                    <input class="form-control m-input" name="date" id="date"
                           readonly placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                           onchange="customerAppointment.changeNumberTime()">
                    <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                    class="la la-calendar"></i></span></span>
                </div>
            </div>
            <span class="error_date_appointment" style="color: #ff0000"></span>
        </div>
        <div class="form-group col-lg-6">
            <label class="black-title">{{__('Giờ hẹn')}}:<b
                        class="text-danger">*</b></label>
            <div class="input-group m-input-group time_edit">
                <input class="form-control" id="time" name="time" placeholder="{{__('Chọn giờ hẹn')}}"
                       onchange="customerAppointment.changeNumberTime()">
            </div>
            <span class="error_time_appointment" style="color: #ff0000"></span>
        </div>
    </div>
    <div class="m-section__content">
        <div class="m-scrollable m-scroller" data-scrollable="true"
             style="height: 200px; overflow: auto;">
            <div class="table-responsive">
                <table class="table m-table m-table--head-separator-metal" id="table_quantity">
                    <thead>
                    <tr>
                        <th class="th_modal_app" style="width: 10%">{{__('HÌNH THỨC')}}</th>
                        <th class="th_modal_app" style="width: 50%">{{__('DỊCH VỤ')}}</th>
                        <th class="th_modal_app"
                            style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('NHÂN VIÊN')}}</th>
                        <th class="th_modal_app"
                            style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('PHÒNG')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="tr_quantity tr_service">
                        <td>
                            @lang('Dịch vụ')
                            <input type="hidden" name="customer_order" id="customer_order_1"
                                   value="1">
                            <input type="hidden" name="object_type" id="object_type"
                                   value="service">
                        </td>
                        <td>
                            <select class="form-control service_id" name="service_id"
                                    id="service_id_1"
                                    style="width: 100%" multiple="multiple">
                                <option></option>
                                @foreach($optionService as $k => $v)
                                    <option value="{{$v['service_id']}}">{{$v['service_name']}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                            <select class="form-control staff_id" name="staff_id" id="staff_id_1"
                                    title="{{__('Chọn nhân viên phục vụ')}}" style="width: 100%">
                                <option></option>
                                @foreach($optionStaff as $k => $v)
                                    <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                            <select class="form-control room_id" name="room_id" id="room_id_1"
                                    title="{{__('Chọn phòng')}}" style="width: 100%">
                                <option></option>
                                @foreach($optionRoom as $k => $v)
                                    <option value="{{$v['room_id']}}">{{$v['name']}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="tr_quantity tr_card">
                        <td>
                            {{__('Thẻ liệu trình')}}
                            <input type="hidden" name="customer_order" id="customer_order_2" value="2">
                            <input type="hidden" name="object_type" id="object_type" value="member_card">
                        </td>
                        <td>
                            <select class="form-control customer_svc" name="service_id" id="service_id_2"
                                    style="width: 100%"
                                    multiple="multiple">
                                <option></option>
                            </select>
                        </td>
                        <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                            <select class="form-control staff_id" name="staff_id" id="staff_id_2"
                                    title="{{__('Chọn nhân viên phục vụ')}}"
                                    style="width: 100%">
                                <option></option>
                                @foreach($optionStaff as $k => $v)
                                    <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                            <select class="form-control room_id" name="room_id" id="room_id_2"
                                    title="{{__('Chọn phòng')}}" style="width: 100%">
                                <option></option>
                                @foreach($optionRoom as $k => $v)
                                    <option value="{{$v['room_id']}}">{{$v['name']}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <input type="hidden" id="time_type" value="R">
    <input type="hidden" id="type_number" name="type_number" value="1">
    <div class="m-separator m-separator--dashed m--margin-top-5"></div>
</script>

<script type="text/template" id="table-child-tpl">
    <tr class="tr_child_{stt}">
        <td></td>
        <td style="color: #000; vertical-align: middle;">
            {object_name}

            <input type="hidden" class="object_type" name="object_type" value="{object_type}">
            <input type="hidden" class="object_id" name="object_id" value="{object_id}">
            <input type="hidden" class="object_code" name="object_code" value="{object_code}">
            <input type="hidden" class="object_name" name="object_name" value="{object_name}">
        </td>
        <td>
            <input class="form-control price_attach" name="price_attach" value="{price}"
                   onchange="order.changePriceAttach()">
        </td>
        <td class="td_vtc" style="width: 80px !important;text-align: center;">
            {quantity}
            <input type="hidden" class="quantity_child" name="quantity" value="{quantity}">
        </td>
        <td colspan="3"></td>
    </tr>
</script>

<script type="text/template" id="close-discount-bill">
    <a href="javascript:void(0)" onclick="order.close_discount_bill('{close_discount_hidden}')"
       class="tag_a">
        <i class="la la-close cl_amount_bill"></i>
    </a>
    {discount}{{__('đ')}}
    <input type="hidden" id="discount_bill" name="discount_bill" value="{discount_hidden}">
    <input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="{code_bill}">
</script>

<script type="text/template" id="button-discount-tpl">
    <div class="m-form__actions m--align-right w-100">
        <button data-dismiss="modal"
                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
        </button>
        <button type="button" onclick="order.discount('{id}','{id_type}','{stt}')"
                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
        </button>
    </div>
</script>

<script type="text/template" id="button-discount-bill-tpl">
    <div class="m-form__actions m--align-right w-100">
        <button data-dismiss="modal"
                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
        </button>
        <button type="button" onclick="order.modal_discount_bill_click()"
                class="btn btn-primary  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
        </button>
    </div>
</script>