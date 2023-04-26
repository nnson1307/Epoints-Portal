<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                <h2 class="m-portlet__head-text">
                    {{__("THÊM KHÁCH HÀNG")}}
                </h2>
            </div>
        </div>
        @if(!isset($params['view_type']))
        <div class="m-portlet__head-tools">
            <div onmouseover="onmouseoverAddNew()" onmouseout="onmouseoutAddNew()"
                 class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--open btn-hover-add-new"
                 m-dropdown-toggle="hover" aria-expanded="true">
                <a href="#"
                   class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--icon m-btn--icon-only m-dropdown__toggle">
                    <i class="la la-plus m--hide"></i>
                    <i class="la la-ellipsis-h"></i>
                </a>
                <div class="m-dropdown__wrapper dropdow-add-new" style="z-index: 101;display: none">
                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"
                                  style="left: auto; right: 21.5px;"></span>
                    <div class="m-dropdown__inner">
                        <div class="m-dropdown__body">
                            <div class="m-dropdown__content">
                                <ul class="m-nav">
                                    <li class="m-nav__item">
                                        <a data-toggle="modal"
                                           data-target="#add" href="" class="m-nav__link">
                                            <i class="m-nav__link-icon la la-users"></i>
                                            <span class="m-nav__link-text">{{__("Thêm nhóm khách hàng") }}</span>
                                        </a>
                                    </li>
                                    <li class="m-nav__item">
                                        <a data-toggle="modal"
                                           data-target="#add_customer_refer" href="" class="m-nav__link">
                                            <i class="m-nav__link-icon fa fa-user-plus"></i>
                                            <span class="m-nav__link-text">{{__("Thêm người giới thiệu")}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif
    </div>
    <form id="form-add">
        <div class="m-portlet__body">
            {!! csrf_field() !!}
            <div class="m-widget4  m-section__content" id="m_blockui_1_content">
                {{--<div class="col-lg-3">--}}
                {{--</div>--}}
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group m-form__group m-widget19">
                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd" id="blah" width="200px"
                                     height="220px"
                                     src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
                                     alt="{{__('Hình ảnh')}}"/>
                            </div>
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                   data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                   id="getFile" type='file'
                                   onchange="uploadImage(this);"
                                   class="form-control"
                                   style="display:none"/>
                            <div class="m-widget19__action" style="max-width: 155px">
                                <a href="javascript:void(0)"
                                   onclick="document.getElementById('getFile').click()"
                                   class="btn  btn-sm m-btn--icon color w-100">
                                            <span class="m--margin-left-20">
                                                <i class="fa fa-camera"></i>
                                                <span>
                                                    {{__("Tải ảnh lên")}}
                                                </span>
                                            </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="row clearfix">
                            <div class="col-lg-6">
                                <input type="hidden" id="customer_avatar" name="customer_avatar" value="">

                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        @lang("Tên khách hàng"):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="text" id="full_name" name="full_name"
                                                   class="form-control m-input "
                                                   value="{{$params['full_name'] ?? null}}"
                                                   placeholder="{{__("Tên khách hàng")}}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                            class="la la-user"></i></span></span>
                                        </div>
                                        @if ($errors->has('full_name'))
                                            <span class="form-control-feedback">
                                     {{ $errors->first('full_name') }}
                                         </span>
                                            <br>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        @lang("Mã hồ sơ"):
                                    </label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="text" id="profile_code" name="profile_code"
                                                   class="form-control m-input "
                                                   placeholder="{{__("Mã hồ sơ")}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        {{__("Ngày sinh")}}:
                                    </label>
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-4">
                                            <select class="form-control op_day" style="width: 100%"
                                                    title="{{__('Ngày')}}"
                                                    id="day"
                                                    name="day">
                                                <option></option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 m">
                                            <select class="form-control width-select" style="width: 100%"
                                                    title="@lang("Tháng")"
                                                    id="month" name="month">
                                                <option></option>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 y">
                                            <select class="form-control width-select" style="width: 100%"
                                                    title="@lang("Năm")"
                                                    id="year" name="year">
                                                <option></option>
                                                @for($i=1940;$i<= date("Y");$i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <span class="error_birthday" style="color: #ff0000"></span>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        @lang("Giới tính"):
                                    </label>
                                    <div class="m-form__group form-group ">

                                        <div class="m-radio-inline">
                                            <label class="m-radio cus">
                                                <input type="radio" name="gender" value="male"> @lang("Nam")
                                                <span class="span"></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="gender" value="female"> @lang("Nữ")
                                                <span class="span"></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" name="gender" value="other"> @lang("Khác")
                                                <span class="span"></span>
                                            </label>
                                        </div>
                                        @if ($errors->has('gender'))
                                            <span class="form-control-feedback">
                                     {{ $errors->first('gender') }}
                                         </span>
                                            <br>
                                        @endif

                                    </div>

                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Số điện thoại')}}:<b
                                                class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="number" id="phone1" name="phone1"
                                                   class="form-control m-input "
                                                   placeholder="@lang("Thêm số điện thoại") 1"
                                                   onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                            class="la la-phone"></i></span></span>
                                        </div>
                                    </div>
                                    <span class="error_phone1" style="color: #ff0000"></span>
                                </div>
                                <div class="m-form__group form-group phone2" style="display: none">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="phone2" name="phone2"
                                               placeholder="@lang("Thêm số điện thoại") 2"
                                               onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                        <div class="input-group-append">
                                            <a href="javascript:void(0)"
                                               class="btn btn-danger color_button m-btn m-btn--custom m-btn--icon delete-phone">
									<span>
										<span class="sp-rm-sdt2">@lang("XÓA")</span>
									</span>
                                            </a>
                                        </div>
                                    </div>
                                    <span class="error_phone2" style="color: #ff0000"></span>
                                </div>
                                <div class="m-form__group form-group">
                                    <a href="javascript:void(0)"
                                       class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
									<span>
										<i class="fa fa-plus-circle"></i>
										<span>@lang("Thêm số điện thoại")</span>
									</span>
                                    </a>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        {{__('Địa chỉ')}}:
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <select name="province_id" id="province_id" class="form-control" onchange='addressCustomer.changeProvince()'
                                                    style="width: 100%">
                                                <option></option>
                                                @foreach($optionProvince as $key=>$value)
                                                    {{--                                                        @if($key==79)--}}
                                                    {{--                                                            <option value="{{$key}}" selected>{{$value}}</option>--}}
                                                    {{--                                                        @else--}}
                                                    <option value="{{$key}}">{{$value}}</option>
                                                    {{--                                                        @endif--}}
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 d form-group">
                                            <select name="district_id" id="district_id" onchange='addressCustomer.changeDistrict()'
                                                    class="form-control district" style="width: 100%">
                                                <option></option>
                                            </select>
                                        </div>

                                        <div class="col-lg-6 d">
                                            <select name="ward_id" id="ward_id"
                                                    class="form-control ward_id" style="width: 100%">
                                                <option></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group m-form__group">
                                                <div class="input-group">
                                                    <div class="m-input-icon m-input-icon--right">
                                                        <input id="address" name="address"
                                                               class="form-control autosizeme"
                                                               placeholder="@lang("Nhập địa chỉ khách hàng")"
                                                               data-autosize-on="true"
                                                               style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-map-marker"></i></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                {{--                                    <div class="form-group m-form__group">--}}
                                {{--                                        <div class="input-group">--}}
                                {{--                                            <div class="m-input-icon m-input-icon--right">--}}
                                {{--                                                <input id="address" name="address"--}}
                                {{--                                                       class="form-control autosizeme"--}}
                                {{--                                                       placeholder="@lang("Nhập địa chỉ khách hàng")"--}}
                                {{--                                                       data-autosize-on="true"--}}
                                {{--                                                       style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">--}}
                                {{--                                                <span class="m-input-icon__icon m-input-icon__icon--right">--}}
                                {{--                                <span><i class="la la-map-marker"></i></span></span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input id="postcode" name="postcode" class="form-control"
                                                   placeholder="@lang("Nhập post code")"
                                                   data-autosize-on="true"
                                                   style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-map-marker"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        {{__('Email')}}:
                                    </label>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text" id="email" name="email"
                                               class="form-control m-input"
                                               placeholder="Vd: piospa@gmail.com">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                                <span><i class="la la-at"></i></span></span>
                                    </div>
                                    <span class="error_email" style="color: #ff0000"></span>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        {{__('Loại khách hàng')}}:
                                    </label>
                                    <div class="m-input-icon m-input-icon--right">
                                        <select id="customer_type" name="customer_type" onchange="changeCustomerType(this)"
                                                title="@lang("Chọn loại khách hàng")"
                                                class="form-control m-input" style="width: 100%">
                                            <option value="personal" selected>@lang('Cá nhân')</option>
                                            <option value="business">@lang('Doanh nghiệp')</option>
                                        </select>
                                    </div>
                                    <span class="error_type_customer" style="color: #ff0000"></span>
                                </div>
                                <div class="open-business-input form-group m-form__group" hidden>
                                    <label class="black-title">@lang("Mã số thuế"):</label>
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text" id="tax_code" name="tax_code" class="form-control m-input" minlength="11" maxlength="13">
                                    </div>
                                </div>
                                <div class="open-business-input form-group m-form__group" hidden>
                                    <label class="black-title">
                                        @lang("Người đại diện"):
                                    </label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="text" id="representative" name="representative"
                                                   class="form-control m-input " maxlength="191"
                                                   placeholder="{{__("Người đại diện")}}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                            class="la la-user"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="open-business-input form-group m-form__group" hidden>
                                    <label class="black-title">{{__('Hotline')}}:</label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="number" id="hotline" name="hotline"
                                                   class="form-control m-input " maxlength="15" minlength="10"
                                                   placeholder="@lang("Nhập hotline")"
                                                   onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                            class="la la-phone"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black-title">@lang("Nhóm khách hàng"):<b
                                                class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <select id="customer_group_id" name="customer_group_id"
                                                title="@lang("Chọn nhóm khách hàng")"
                                                class="form-control m-input" style="width: 100%">
                                            <option></option>
                                            @foreach($optionGroup as $key=>$value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="add-info">
                                    <div class="form-group">
                                        <label>
                                            @lang("Nguồn khách hàng"):
                                        </label>
                                        <select class="form-control m-input width-select" id="customer_source_id"
                                                name="customer_source_id" title="@lang("Chọn nguồn khách hàng")"
                                                style="width: 100%">
                                            <option></option>
                                            @foreach($optionSource as $key=>$value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('customer_source_id'))
                                            <span class="form-control-feedback">
                        {{ $errors->first('customer_source_id') }}
                        </span>
                                            <br>
                                        @endif
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label>
                                            @lang("Người giới thiệu"):
                                        </label>
                                        <div class="input-group m-input-group">
                                            <select class="form-control width-select" name="customer_refer_id"
                                                    id="customer_refer_id" style="width: 100%">

                                            </select>
                                        </div>
                                        @if ($errors->has('customer_refer_id'))
                                            <span class="form-control-feedback">
                        {{ $errors->first('customer_refer_id') }}
                        </span>
                                            <br>
                                        @endif
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label>
                                            Facebook:
                                        </label>
                                        <div class="m-input-icon m-input-icon--right">
                                            <input id="facebook" name="facebook" class="form-control m-input"
                                                   type="text"
                                                   placeholder="@lang("Nhập link facebook")">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                        <span><i class="la la-facebook"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="more_info">
                                    @if(count($customDefine) > 0)
                                        @foreach($customDefine as $v)
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{$v['title']}}:
                                                </label>
                                                <div class="m-input-icon m-input-icon--right">
                                                    @switch($v['type'])
                                                        @case('text')
                                                        <input type="text" id="{{$v['key']}}" name="{{$v['key']}}"
                                                               class="form-control m-input" maxlength="190">
                                                        @break;
                                                        @case('boolean')
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox" class="manager-btn" checked
                                                                           id="{{$v['key']}}" name="{{$v['key']}}"
                                                                           value="1"
                                                                           onchange="customer.changeBoolean(this)">
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                        @break;
                                                    @endswitch
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="form-group m-form__group">
                                    <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                       onclick="customer.modalImage()">
                                        <i class="fa fa-plus-circle"></i> @lang('Ảnh kèm theo')
                                    </a>
                                </div>
                                <div class="div_image_customer image-show row"></div>

                                <div class="form-group m-form__group">
                                    <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                       onclick="customer.modalFile()">
                                        <i class="fa fa-plus-circle"></i> @lang('File kèm theo')
                                    </a>
                                </div>
                                <div class="div_file_customer"></div>
                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Ghi chú')}}:
                                    </label>
                                    <div class="input-group m-input-group ">
                                            <textarea id="note" name="note" class="form-control autosizeme" rows="8"
                                                      placeholder="@lang("Nhập thông tin ghi chú")"
                                                      data-autosize-on="true"
                                                      style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;"></textarea>
                                    </div>
                                </div>
                                @if (count($getParameter) > 0)
                                    <div class="form-group m-form__group parameter">
                                        @foreach($getParameter as $v)
                                            <a href="javascript:void(0)"
                                               class="btn btn-sm ss--btn-parameter ss--font-weight-200"
                                               style="color: black;"
                                               onclick="customer.append_parameter('{{$v['content']}}')">
                                                {{$v['parameter_name']}}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group m-form__group">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    @if(!isset($params['view_type']))

                        <a href="{{route('admin.customer')}}"
                           class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button"
                                class="btn  btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                        <button type="button"
                                class="btn  btn-success color_button son-mb
                                        m-btn m-btn--icon m-btn--wide m-btn--md btn-add m--margin-left-10">
                                <span>
                                <i class="fa fa-plus-circle"></i>
                                <span>{{__('LƯU & TẠO MỚI')}}</span>
                                </span>
                        </button>
                    @else

                        <a href="javascript:void(0)" data-dismiss= modal
                           class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button"
                                class="btn  btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </form>
</div>
<input type="hidden" id="view_type" name="view_type" value="{{$params['view_type'] ?? null}}">
<input type="hidden" class="hidden-add-info" value="0">
