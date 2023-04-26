@if($type_button == 1)
    <div class="button_item">
        <h5 class="bold heading-button-item">
            <a href="javascript:void(0);" class="remove-button-item">
                <i class="fa fa-minus-circle ss--icon-title fz-20 text-danger m--margin-right-5"></i>
            </a>
            {{__('Nút thứ ')}} <span class="number_count_button">{{$stt}}</span>
        </h5>
        <div class="content-button-item container">
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Loại nút')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                    <select name="type_button[{{$stt}}]" class="form-control">
                        @foreach($list_type_button as $key => $value)
                            <option value="{{$key}}"{{$type_button == $key ?' selected':''}}>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Tiêu đề')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                <textarea name="title[{{$stt}}]" rows="1" cols="40"
                          class="form-control m-input" maxlength="100">{{isset($button_item->title)?$button_item->title:''}}</textarea>
                    <i class="pull-right">{{ __('Số ký tự') }}: <i
                                class="count-character">0</i>{{ __('/100 ký tự') }}</i>

                </div>
            </div>
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Đường dẫn liên kết')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                    <input class="form-control" name="link[{{$stt}}]" value="{{isset($button_item->link)?$button_item->link:''}}">
                </div>
            </div>
            <div class="row form-group">
                <div class="form-group m-form__group ">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <a href="javascript:void(0)" class="btn  btn-sm m-btn--icon color add-icon-button">
                            <span>
                                <i class="la la-plus"></i>
                                <span>
                                    {{ __('Thêm icon') }}
                                </span>
                            </span>
                            </a>
                        </div>
                        <div class="col-lg-12 div_avatar">
                            <input type="hidden" name="icon[{{$stt}}]" class="icon-value" value="{{isset($button_item->icon)?$button_item->icon:''}}">
                            <div class="wrap-img avatar float-left">
                                <img class="m--bg-metal m-image img-sd icon-preview"
                                     src="{{isset($button_item->icon)?$button_item->icon:asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                     alt="{{ __('Hình ảnh') }}" width="100px" height="100px">
                            </div>
                            <div
                                    class="form-group m-form__group float-left m--margin-left-20 warning_img">
                                <label for="">{{ __('Định dạng ảnh hỗ trợ') }}: <b
                                            class="image-info image-format"></b> jpg,png</label>
                                <br>
                                <label for="">{{ __('Dung lượng tối đa') }}: <b
                                            class="image-info image-capacity">1MB</b>
                                </label><br>
                            </div>

                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                   data-msg-accept="{{ __('Hình ảnh không đúng định dạng') }}" type="file"
                                   class="form-control icon-file" style="display:none">
                            <div class="show_image"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($type_button == 2)
    <div class="button_item">
        <h5 class="bold heading-button-item">
            <a href="javascript:void(0);" class="remove-button-item">
                <i class="fa fa-minus-circle ss--icon-title fz-20 text-danger m--margin-right-5"></i>
            </a>
            {{__('Nút thứ ')}} <span class="number_count_button">{{$stt}}</span>
        </h5>
        <div class="content-button-item container">
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Loại nút')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                    <select name="type_button[{{$stt}}]" class="form-control">
                        @foreach($list_type_button as $key => $value)
                            <option value="{{$key}}"{{$type_button == $key ?' selected':''}}>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Tiêu đề')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                <textarea name="title[{{$stt}}]" rows="1" cols="40"
                          class="form-control m-input" maxlength="100">{{isset($button_item->title)?$button_item->title:''}}</textarea>
                    <i class="pull-right">{{ __('Số ký tự') }}: <i
                                class="count-character">0</i>{{ __('/100 ký tự') }}</i>

                </div>
            </div>
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Số điện thoại')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                    <input class="form-control" name="phone_number[{{$stt}}]" value="{{isset($button_item->phone_number)?$button_item->phone_number:''}}">
                </div>
            </div>
            <div class="row form-group">
                <div class="form-group m-form__group ">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <a href="javascript:void(0)" class="btn  btn-sm m-btn--icon color add-icon-button">
                            <span>
                                <i class="la la-plus"></i>
                                <span>
                                    {{ __('Thêm icon') }}
                                </span>
                            </span>
                            </a>
                        </div>
                        <div class="col-lg-12 div_avatar">
                            <input type="hidden" name="icon[{{$stt}}]" class="icon-value" value="{{isset($button_item->icon)?$button_item->icon:''}}">
                            <div class="wrap-img avatar float-left">
                                <img class="m--bg-metal m-image img-sd icon-preview"
                                     src="{{isset($button_item->icon)?$button_item->icon:asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                     alt="{{ __('Hình ảnh') }}" width="100px" height="100px">
                            </div>
                            <div
                                    class="form-group m-form__group float-left m--margin-left-20 warning_img">
                                <label for="">{{ __('Định dạng ảnh hỗ trợ') }}: <b
                                            class="image-info image-format"></b> jpg,png</label>
                                <br>
                                <label for="">{{ __('Dung lượng tối đa') }}: <b
                                            class="image-info image-capacity">1MB</b>
                                </label><br>
                            </div>

                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                   data-msg-accept="{{ __('Hình ảnh không đúng định dạng') }}" type="file"
                                   class="form-control icon-file" style="display:none">
                            <div class="show_image"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($type_button == 3)
    <div class="button_item">
        <h5 class="bold heading-button-item">
            <a href="javascript:void(0);" class="remove-button-item">
                <i class="fa fa-minus-circle ss--icon-title fz-20 text-danger m--margin-right-5"></i>
            </a>
            {{__('Nút thứ ')}} <span class="number_count_button">{{$stt}}</span>
        </h5>
        <div class="content-button-item container">
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Loại nút')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                    <select name="type_button[{{$stt}}]" class="form-control">
                        @foreach($list_type_button as $key => $value)
                            <option value="{{$key}}"{{$type_button == $key ?' selected':''}}>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Tiêu đề')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                <textarea name="title[{{$stt}}]" rows="1" cols="40"
                          class="form-control m-input" maxlength="100">{{isset($button_item->title)?$button_item->title:''}}</textarea>
                    <i class="pull-right">{{ __('Số ký tự') }}: <i
                                class="count-character">0</i>{{ __('/100 ký tự') }}</i>

                </div>
            </div>
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Nội dung tin nhắn')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                    <input class="form-control" name="content[{{$stt}}]" value="{{isset($button_item->content)?$button_item->content:''}}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 align-items-center">
                    <h6 class="d-flex">
                        {{__('Số điện thoại')}}
                    </h6>
                </div>
                <div class="col-md-9 form-group">
                    <input class="form-control" name="phone_number[{{$stt}}]" value="{{isset($button_item->phone_number)?$button_item->phone_number:''}}">
                </div>
            </div>
            <div class="row">
                <div class="form-group m-form__group ">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <a href="javascript:void(0)" class="btn  btn-sm m-btn--icon color add-icon-button">
                            <span>
                                <i class="la la-plus"></i>
                                <span>
                                    {{ __('Thêm icon') }}
                                </span>
                            </span>
                            </a>
                        </div>
                        <div class="col-lg-12 div_avatar">
                            <input type="hidden" name="icon[{{$stt}}]" class="icon-value" value="{{isset($button_item->icon)?$button_item->icon:''}}">
                            <div class="wrap-img avatar float-left">
                                <img class="m--bg-metal m-image img-sd icon-preview"
                                     src="{{isset($button_item->icon)?$button_item->icon:asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                     alt="{{ __('Hình ảnh') }}" width="100px" height="100px">
                            </div>
                            <div
                                    class="form-group m-form__group float-left m--margin-left-20 warning_img">
                                <label for="">{{ __('Định dạng ảnh hỗ trợ') }}: <b
                                            class="image-info image-format"></b> jpg,png</label>
                                <br>
                                <label for="">{{ __('Dung lượng tối đa') }}: <b
                                            class="image-info image-capacity">1MB</b>
                                </label><br>
                            </div>

                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                   data-msg-accept="{{ __('Hình ảnh không đúng định dạng') }}" type="file"
                                   class="form-control icon-file" style="display:none">
                            <div class="show_image"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
