@if(isset($LIST))
    @foreach ($LIST as $key => $item)
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group m-form__group">
            <span style="font-size: 1.3rem;font-weight: 400;color: #008990;">
                @if($item['card_type']=='service_card')
                    {{__('Cấu hình thẻ dịch vụ')}}
                @elseif($item['card_type']=='money_card')
                    {{__('Cấu hình thẻ tiền')}}
                @elseif($item['card_type']=='voucher')
                    {{__('Cấu hình voucher')}}
                @endif
            </span>
                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3">
                            <a href="javascript:void(0)"
                               onclick="document.getElementById('getFileLogo_{{$item['id']}}').click()"
                               class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm logo')}}
                                                </span>
                                            </span>
                            </a>
                        </div>
                        <div class="col-lg-9 w-col-mb-100 div_logo">
                            <div class="wrap-img append_logo_{{$item['id']}}">
                                @if($item['logo']!=null)
                                    <img class="m--bg-metal m-image img-sd" id="logo_img_{{$item['id']}}"
                                         src="{{$item['logo']}}"
                                         alt="Hình ảnh" width="100px" height="100px">
                                    <span class="delete-img cl_logo_{{$item['id']}}" style="display: block">
                                                    <a href="javascript:void(0)"
                                                       onclick="config_service_card.remove_logo('{{$item['id']}}')">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                 </span>
                                @else
                                    <img class="m--bg-metal m-image img-sd" id="logo_img_{{$item['id']}}"
                                         src="{{asset('static/backend/images/default-placeholder.png')}}"
                                         alt="Hình ảnh" width="100px" height="100px">
                                    <span class="delete-img cl_logo_{{$item['id']}}">
                                                    <a href="javascript:void(0)"
                                                       onclick="config_service_card.remove_logo('{{$item['id']}}')">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                    </span>
                                @endif
                                <input type="hidden" id="logo_{{$item['id']}}" name="logo" value="{{$item['logo']}}">
                            </div>


                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                   data-msg-accept="Hình ảnh không đúng định dạng"
                                   id="getFileLogo_{{$item['id']}}"
                                   type="file"
                                   onchange="uploadLogo(this,'{{$item['id']}}');" class="form-control"
                                   style="display:none">
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3">
                            <label>{{__('Tên spa')}}</label>
                        </div>
                        <div class="col-lg-9">
                            @if($item['name_spa']!=null)
                                <input class="form-control" id="name_spa_{{$item['id']}}" name="name_spa" value="{{$item['name_spa']}}">
                            @else
                                <input class="form-control" id="name_spa_{{$item['id']}}" name="name_spa" value="{{$spa_info[0]['name']}}">
                            @endif
                        </div>
                    </div>

                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3">
                            <label>{{__('Qr code (Mã thẻ)')}}</label>
                        </div>
                        <div class="col-lg-9">
                            @if ($item['qr_code'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="config_service_card.changeStatusQrCode(this, '{!! $item['id'] !!}')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="config_service_card.changeStatusQrCode(this, '{!! $item['id'] !!}')"
                                           class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3">
                            <label>{{__('Màu nền (Background)')}}</label>
                        </div>
                        <div class="col-lg-9">
                            <input class="form-control jscolor" id="background_{{$item['id']}}" name="background"
                                   value="{{$item['background']}}">
                        </div>
                    </div>

                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3">
                            <label>{{__('Màu chữ')}}</label>
                        </div>
                        <div class="col-lg-9">
                            <input class="form-control jscolor" id="color_{{$item['id']}}" name="color"
                                   value="{{$item['color']}}">
                        </div>
                    </div>

                </div>
                <div class="form-group m-form__group">
                    <div class="row">
                        <div class="col-lg-3">
                            <a href="javascript:void(0)"
                               onclick="document.getElementById('getFileBackground_{{$item['id']}}').click()"
                               class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm hình nền')}}
                                                </span>
                                            </span>
                            </a>
                        </div>
                        <div class="col-lg-9 w-col-mb-100 div_background">
                            <div class="wrap-img append_background_{{$item['id']}}">
                                @if($item['background_image']!= null)
                                    <img class="m--bg-metal m-image img-sd" id="background_img_{{$item['id']}}"
                                         src="{{$item['background_image']}}"
                                         alt="Hình ảnh" width="100px" height="100px">
                                    <span class="delete-img cl_background_{{$item['id']}}" style="display: block">
                                                    <a href="javascript:void(0)"
                                                       onclick="config_service_card.remove_background('{{$item['id']}}')">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                 </span>
                                @else
                                    <img class="m--bg-metal m-image img-sd" id="background_img_{{$item['id']}}"
                                         src="{{asset('static/backend/images/default-placeholder.png')}}"
                                         alt="Hình ảnh" width="100px" height="100px">
                                    <span class="delete-img cl_background_{{$item['id']}}">
                                                    <a href="javascript:void(0)"
                                                       onclick="config_service_card.remove_background('{{$item['id']}}')">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                    </span>
                                @endif
                                <input type="hidden" id="background_image_{{$item['id']}}" name="background_image" value="{{$item['background_image']}}">
                            </div>
                        </div>
                        <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                               data-msg-accept="Hình ảnh không đúng định dạng"
                               id="getFileBackground_{{$item['id']}}"
                               type="file"
                               onchange="uploadBackground(this,'{{$item['id']}}');" class="form-control"
                               style="display:none">
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label><strong>{{__('Lưu ý')}}:</strong> {{__('Hình nền và màu nền chọn 1 trong 2, trường hợp có cã 2 thì ưu tiên hình nền')}}</label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group m-form__group" id="modal_view_render_{{$item['id']}}">
                    @include('admin::config-print-service-card.view-before')
                </div>
            </div>
        </div>


        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
            <div class="m-form__actions m--align-right">
                <button type="submit" onclick="config_service_card.view_after('{{$item['id']}}')"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-eye"></i>
							<span>{{__('XEM TRƯỚC')}}</span>
							</span>
                </button>
                <button type="submit" onclick="config_service_card.submit_edit('{{$item['id']}}')"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                </button>
            </div>
        </div>
        <div class="form-group m-form__group border_bot_config_email">

        </div>

    @endforeach
@endif


