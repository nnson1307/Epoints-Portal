<div id="modal-edit-banner" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA BANNER')}}
                </h4>
            </div>
            <form id="form-edit-banner">
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <input type="hidden" id="banner_id" name="banner_id">
                    <div class="form-group m-form__group ">
                        <div class="row">
                            <div class="col-lg-3 ">
                                <a href="javascript:void(0)"
                                   onclick="document.getElementById('getFileEdit').click()"
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
                                <input type="hidden" id="banner_img_hidden" name="banner_img_hidden">
                                <div class="wrap-img avatar_edit">

                                </div>
                                <span class="error_img" style="color:red"></span>
                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                       data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                       id="getFileEdit" type="file"
                                       onchange="uploadBannerEdit(this);" class="form-control"
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
                                <input type="text" class="form-control" id="link_edit" name="link_edit"
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
                                <input type="number" class="form-control" id="position_edit" name="position_edit"
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

                            <button type="button" id="luu1" onclick="banner.submit_edit()"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-edit"></i>
							<span>{{__('CẬP NHẬT')}}</span>
							</span>
                            </button>


                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
