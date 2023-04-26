<div class="modal fade ajax collection-edit-modal" method="POST" action="{{route('admin.collection.ajax-edit')}}" role="dialog">
    <div class="modal-dialog modal-dialog-centered hu-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Sửa Collection')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="m-form__group form-group col-6">
                        <div class="form-group m-form__group m-widget19 image_web">
                            <label>Hình web</label>

                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd image_web" height="150px" id="image_web" src="{{$item['image_web']??'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}" alt="Hình ảnh">
                            </div>
                            <input type="hidden" id="image_web" name="image_web" class="image_web" value="{{$item['image_web']??''}}">
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" data-msg-accept="Hình ảnh không đúng định dạng" type="file" onchange="uploadImage3(this,'.image_web');" class="form-control getFile" style="display:none">
                            <div class="m-widget19__action" style="max-width: 170px">
                                <a href="javascript:void(0)" onclick="$('.image_web .getFile').click()" class="btn  btn-sm m-btn--icon color w-100">
                                <span class="">
                                    <i class="fa fa-camera"></i>
                                    <span>
                                        Tải ảnh lên</span>
                                </span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="m-form__group form-group col-6">
                        <div class="form-group m-form__group m-widget19 image_app">
                            <label>Hình app</label>

                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd image_app" height="150px" id="image_app" src="{{$item['image_app']??'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}" alt="Hình ảnh">
                            </div>
                            <input type="hidden" id="image_app" name="image_app" class="image_app" value="{{$item['image_app']??''}}">
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" data-msg-accept="Hình ảnh không đúng định dạng" type="file" onchange="uploadImage3(this,'.image_app');" class="form-control getFile" style="display:none">
                            <div class="m-widget19__action" style="max-width: 170px">
                                <a href="javascript:void(0)" onclick="$('.image_app .getFile').click()" class="btn  btn-sm m-btn--icon color w-100">
                                <span class="">
                                    <i class="fa fa-camera"></i>
                                    <span>
                                        Tải ảnh lên</span>
                                </span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 form-group m-form__group">
                        <label class="black_title">
                            Link:<b class="text-danger"></b>
                        </label>
                        <div class="">
                            <input type="text" class="form-control m-input" name="link" placeholder="Nhập link" value="{{$item['link']}}">
                        </div>
                    </div>

                    <div class="col-12 form-group m-form__group">
                        <label class="black_title">
                            Nguồn:<b class="text-danger"></b>
                        </label>
                        <div class="">
                            <input type="text" class="form-control m-input" name="source" placeholder="Nhập nguồn" value="{{$item['source']}}">
                        </div>
                    </div>



            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{__('HỦY')}}</span>
                            </span>
                        </button>
                        <button type="button" data-checkin_collection_id="{{$item['checkin_collection_id']??''}}"
                            class="submit ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{__('Chỉnh sửa')}}</span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $( '.datepicker' ).datepicker({
            format: "dd/mm/yyyy",
            viewMode: "years"
        });
    </script>
</div>