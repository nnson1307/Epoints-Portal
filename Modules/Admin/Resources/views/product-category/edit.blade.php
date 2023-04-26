<div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="la la-edit ss--icon-title m--margin-right-5">
                    </i>{{__('CHỈNH SỬA DANH MỤC SẢN PHẨM')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idHidden" value="{{$categoryId}}">
                <div class="form-group">
                    <label>
                        {{__('Tên danh mục')}}:<b class="text-danger">*</b>
                    </label>
                    <input type="text" id="category-name" class="form-control m-input"
                           placeholder="{{__('Nhập tên danh mục')}}" value="{{$categoryName}}">
                    <span class="error-category-name"></span>
                </div>
                <div class="form-group">
                    <label>
                        {{__('Mã danh mục')}}:
                    </label>
                    <input type="text" id="category-code" class="form-control m-input" value="{{$categoryCode}}"
                           placeholder="{{__('Nhập mã danh mục')}}">
                    <span class="error-category-code"></span>
                </div>
                <div class="form-group">
                    <label>
                        {{__('Trạng thái')}} :
                    </label>
                    <div class="input-group row">
                        <div class="col-lg-1">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_actived" type="checkbox" class="is_actived" name="" {{$isActived == 1 ? 'checked': ''}}>
                        <span></span>
                    </label>
                </span>
                        </div>
                        <div class="col-lg-4 m--margin-top-5">
                            <i>{{__('Chọn để kích hoạt trạng thái')}}</i>
                        </div>
                    </div>
                </div>
                <div class="m-form__group form-group row">
                    <label class="col-lg-3 col-form-label">@lang('Ảnh đại diện'):</label>
                    <div class="col-lg-10">
                        <div class="form-group m-form__group m-widget19">
                            <div class="m-widget19__pic">
                                <img class="m--bg-metal m-image img-sd" id="blah" height="150px"
                                     src="{{$iconImage != null ? $iconImage: 'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}"
                                     alt="Hình ảnh"/>
                            </div>
                            <input type="hidden" id="icon_image" name="icon_image" value="{{$iconImage}}">
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                   data-msg-accept="Hình ảnh không đúng định dạng"
                                   id="getFile" type='file'
                                   onchange="uploadAvatar2(this);"
                                   class="form-control"
                                   style="display:none"/>
                            <div class="m-widget19__action" style="max-width: 25%">
                                <a href="javascript:void(0)"
                                   onclick="document.getElementById('getFile').click()"
                                   class="btn  btn-sm m-btn--icon color w-100">
                                            <span class="m--margin-left-20">
                                                <i class="fa fa-camera"></i>
                                                <span>
                                                    @lang('Tải ảnh lên')
                                                </span>
                                            </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        {{__('Mô tả')}}:
                    </label>
                    <textarea class="form-control" rows="5" id="description" placeholder="{{__('Mô tả')}}">{{$description}}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 ss--btn">
                    <span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>
                        <button type="button" onclick="productCategory.submitEdit()"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 m--margin-left-10">
                    <span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>