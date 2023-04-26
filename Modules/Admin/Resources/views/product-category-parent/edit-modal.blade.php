<div class="modal fade ajax product-category-parent-edit-modal" method="POST" action="{{route('admin.product-category-parent.ajax-edit')}}" role="dialog">
    <div class="modal-dialog modal-dialog-centered hu-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Sửa danh mục sản phẩm cha')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">


                    <div class="col-12 form-group m-form__group">
                        <label class="black_title">
                            Tên danh mục:<b class="text-danger"></b>
                        </label>
                        <div class="">
                            <input type="text" class="form-control m-input" name="product_category_parent_name" placeholder="Nhập tên danh mục" value="{{$item['product_category_parent_name']??''}}">
                        </div>
                    </div>
                    <div class="m-form__group form-group col-12">
                        <div class="form-group m-form__group m-widget19 icon_image">
                            <label>Hình ảnh</label>

                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd icon_image" height="150px" id="icon_image" src="{{$item['icon_image']??'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}" alt="Hình ảnh">
                            </div>
                            <input type="hidden" id="icon_image" name="icon_image" class="icon_image" value="{{$item['icon_image']??''}}">
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" data-msg-accept="Hình ảnh không đúng định dạng" type="file" onchange="uploadImage3(this,'.icon_image');" class="form-control getFile" style="display:none">
                            <div class="m-widget19__action" style="max-width: 170px">
                                <a href="javascript:void(0)" onclick="$('.icon_image .getFile').click()" class="btn  btn-sm m-btn--icon color w-100">
                                <span class="">
                                    <i class="fa fa-camera"></i>
                                    <span>
                                        Tải ảnh lên</span>
                                </span>
                                </a>
                            </div>
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
                        <button type="button" data-product_category_parent_id="{{$item['product_category_parent_id']??''}}"
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