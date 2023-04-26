@if(isset($LIST))
    @foreach ($LIST as $key => $item)
        <div class="form-group m-form__group">
            <div class="row">
                <div class="col-lg-3">
                    <label>Logo:</label>
                </div>
                <div class="col-lg-9">
                    @if ($item['logo'])
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="config_email_template.changeStatusLogo(this, '{!! $item['id'] !!}')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                    @else
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="config_email_template.changeStatusLogo(this, '{!! $item['id'] !!}')"
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
                    <label>{{__('Website')}}:</label>
                </div>
                <div class="col-lg-9">
                    @if ($item['website'])
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="config_email_template.changeStatusWebsite(this, '{!! $item['id'] !!}')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                    @else
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="config_email_template.changeStatusWebsite(this, '{!! $item['id'] !!}')"
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
                    <label>{{__('Màu nền (Header)')}}:</label>
                </div>
                <div class="col-lg-6">
                    <input class="form-control jscolor" id="background_header" name="background_header" value="{{$item['background_header']}}">
                </div>
            </div>
        </div>
        <div class="form-group m-form__group">
            <div class="row">
                <div class="col-lg-3">
                    <label>{{__('Màu chữ (Header)')}}:</label>
                </div>
                <div class="col-lg-6">
                    <input class="form-control jscolor" id="color_header" name="color_header" value="{{$item['color_header']}}">
                </div>
            </div>
        </div>
        <div class="form-group m-form__group">
            <div class="row">
                <div class="col-lg-3">
                    <label>{{__('Màu nền (Body)')}}:</label>
                </div>
                <div class="col-lg-6">
                    <input class="form-control jscolor" id="background_body" name="background_body" value="{{$item['background_body']}}">
                </div>
            </div>
        </div>
        <div class="form-group m-form__group">
            <div class="row">
                <div class="col-lg-3">
                    <label>{{__('Màu chữ (Body)')}}:</label>
                </div>
                <div class="col-lg-6">
                    <input class="form-control jscolor" id="color_body" name="color_body" value="{{$item['color_body']}}">
                </div>
            </div>
        </div>
        <div class="form-group m-form__group">
            <div class="row">
                <div class="col-lg-3">
                    <label>{{__('Màu nền (Footer)')}}:</label>
                </div>
                <div class="col-lg-6">
                    <input class="form-control jscolor" id="background_footer" name="background_footer" value="{{$item['background_footer']}}">
                </div>
            </div>
        </div>
        <div class="form-group m-form__group">
            <div class="row">
                <div class="col-lg-3">
                    <label>{{__('Màu chữ (Footer)')}}:</label>
                </div>
                <div class="col-lg-6">
                    <input class="form-control jscolor" id="color_footer" name="color_footer" value="{{$item['color_footer']}}">
                </div>
            </div>
        </div>
        <div class="form-group m-form__group">
            <div class="row">
                <div class="col-lg-3">
                    <a href="javascript:void(0)"
                       onclick="document.getElementById('getFileImage').click()"
                       class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm hình minh họa')}}
                                                </span>
                                            </span>
                    </a>
                </div>
                <div class="col-lg-6 w-col-mb-100 div_image">
                    <div class="wrap-img append_image">
                        @if($item['image']!= null)
                            <img class="m--bg-metal m-image img-sd" id="img"
                                 src="/{{$item['image']}}"
                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                            <span class="delete-img cl_image" style="display: block">
                                                    <a href="javascript:void(0)"
                                                       onclick="config_email_template.remove_image('{{$item['id']}}')">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                 </span>
                        @else
                            <img class="m--bg-metal m-image img-sd" id="img"
                                 src="{{asset('static/backend/images/default-placeholder.png')}}"
                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                            <span class="delete-img cl_image">
                                                    <a href="javascript:void(0)"
                                                       onclick="config_email_template.remove_image('{{$item['id']}}')">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                    </span>
                        @endif
                        <input type="hidden" id="image" name="image" value="">
                    </div>
                </div>
                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                       data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                       id="getFileImage"
                       type="file"
                       onchange="uploadImage(this,'{{$item['id']}}');" class="form-control"
                       style="display:none">
            </div>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <button type="submit" onclick="config_email_template.modal_view('{{$item['id']}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-eye"></i>
							<span>{{__('XEM TRƯỚC')}}</span>
							</span>
                    </button>
                    <button type="submit" onclick="config_email_template.submit_edit('{{$item['id']}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                    </button>
                </div>
            </div>

        </div>
    @endforeach
@endif


