<div id="modal-add-banner" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM BANNER')}}
                </h4>
            </div>
            <form id="form-add-banner">
                <div class="modal-body">
                    {!! csrf_field() !!}

                    <div class="form-group m-form__group ">
                        <div class="row">
                            <div class="col-lg-3 ">
                                <a href="javascript:void(0)"
                                   onclick="document.getElementById('getFileBanner').click()"
                                   class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm hình ảnh')}}
                                                </span>
                                            </span>
                                </a>
                            </div>
                            <div class="col-lg-9  w-col-mb-banner div_avatar">
                                <div class="wrap-img avatar_add ">
                                    {{--<img class="m--bg-metal m-image img-sd" id="blah_banner"--}}
                                         {{--src="http://archwayarete.greatheartsacademies.org/wp-content/uploads/sites/11/2016/11/default-placeholder.png"--}}
                                         {{--alt="{{__('Hình ảnh')}}" width="100px" height="100px">--}}
                                    {{--<span class="delete-img">--}}
                                                    {{--<a href="javascript:void(0)" onclick="banner.remove_img()">--}}
                                                        {{--<i class="la la-close"></i>--}}
                                                    {{--</a>--}}
                                                {{--</span>--}}
                                    {{--<input type="hidden" id="banner_img" name="banner_img" value="">--}}
                                </div>
                                <span class="error_img" style="color:red"></span>
                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                       data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                       id="getFileBanner" type="file"
                                       onchange="uploadBanner(this);" class="form-control"
                                       style="display:none">

                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="black_title">
                                    {{__('Link liên kết')}}:<b class="text-danger">*</b>
                                </label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="link" name="link"
                                       placeholder="{{__('Hãy nhập link liên kết')}}...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-3">
                                <label class="black_title">
                                    {{__('Vị trí')}}:<b class="text-danger">*</b>
                                </label>
                            </div>
                            <div class="col-lg-9">
                                <input type="number" class="form-control" id="position" name="position"
                                       placeholder="{{__('Hãy nhập vị trí')}}...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>

                            <button type="button" id="luu1" onclick="banner.submit_add()"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>


                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
