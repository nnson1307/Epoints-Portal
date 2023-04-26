@if(isset($LIST_BOOKING_EXTRA))
    @foreach($LIST_BOOKING_EXTRA as $item)
        <div class="item_extra">
            <div class="form-group m-form__group">
                <div class="row">
                    <div class="col-lg-3">
                    <span>
                        {{$item['name']}}
                    </span>
                    </div>
                    <div class="col-lg-9">
                        <textarea class="form-control value" rows="4" name="value">{{$item['value']}}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group" style="text-align: right">
                <button type="submit" onclick="other_extra.edit_extra(this,'{{$item['id']}}')"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<span>{{__('LƯU')}}</span>
							</span>
                </button>
            </div>
        </div>
        @if($item['id']==3)
            <div class="form-group m-form__group">
                <div class="row">
                    <div class="col-lg-3">
                    <span>
                        {{__('Hình share facebook')}}
                    </span>
                    </div>
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-2 div_avatar">
                                {{--<input type="hidden" id="avarta_fb_hidden" name="avarta_fb_hidden" value="{{$item['image']}}">--}}
                                <div class="wrap-img avatar_share_fb">
                                    @if($item['image']!=null)
                                        <img class="m--bg-metal m-image img-sd" id="blah_share_fb"
                                             src="/{{$item['image']}}"
                                             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                        <span class="delete-img" style="display: block">
                                                    <a href="javascript:void(0)" onclick="other_extra.remove_img('{{$item['id']}}')">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                    @else
                                        <img class="m--bg-metal m-image img-sd" id="blah_share_fb"
                                             src="{{asset('static/backend/images/default-placeholder.png')}}"
                                             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                        <span class="delete-img">
                                                    <a href="javascript:void(0)" onclick="other_extra.remove_img('{{$item['id']}}')">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                    @endif
                                    <input type="hidden" id="blah_share_fb_hidden" name="blah_share_fb_hidden">
                                </div>
                                <span class="error_img" style="color:red"></span>
                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                       data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                       id="getFileShareFB" type="file"
                                       onchange="uploadImageFB(this,'{{$item['id']}}');" class="form-control"
                                       style="display:none">

                            </div>
                            <div class="col-lg-3 w-col-mb-avatar">
                                <a href="javascript:void(0)"
                                   onclick="document.getElementById('getFileShareFB').click()"
                                   class="btn  btn-sm m-btn--icon color son-mb" style="vertical-align: bottom">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Tải lên')}}
                                                </span>
                                            </span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif