<div class="modal fade show" id="editImage" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title  ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('THÊM HÌNH ẢNH SẢN PHẨM')}}
                </h4>
                <button onclick="cancelAddImage()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{--<div class="form-group m-form__group">--}}
                    {{--<label>--}}
                        {{--Hình đại diện:--}}
                    {{--</label>--}}
                    {{--<div class="col-lg-4 col-md-4 ss--padding-left-0">--}}
                        {{--<div class="form-group m-form__group m-widget19">--}}
                            {{--<div class="m-widget19__pic">--}}
                                {{--@if($product->avatar!=null)--}}
                                    {{--<img class="m--bg-metal m--padding-5 m-image" id="blah-edit"--}}
                                         {{--src="{{asset($product->avatar)}}"--}}
                                         {{--alt="Hình ảnh">--}}
                                {{--@else--}}
                                    {{--<img class="m--bg-metal m-image" id="blah-edit"--}}
                                         {{--src="{{asset('uploads/admin/service_card/default/hinhanh-default3.png')}}"--}}
                                         {{--alt="Hình ảnh">--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<input id="getFile" accept=".png, .jpg, .jpeg" type="file" onchange="uploadImage(this);" class="form-control"--}}
                                   {{--style="display:none">--}}
                            {{--<div class="m-widget19__action">--}}
                                {{--<a href="javascript:void(0)" onclick="document.getElementById('getFile').click()"--}}
                                   {{--class="btn m-btn--square ss--button-cms-piospa btn-sm m-btn--icon">--}}
                                    {{--<span>--}}
                                    {{--<i class="la la-image"></i>--}}
                                    {{--<span>--}}
                                    {{--Chọn hình ảnh--}}
                                    {{--</span>--}}
                                    {{--</span>--}}
                                {{--</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="form-group m-form__group">
                    <label>
                        {{__('Hình ảnh')}}:
                    </label>
                    {{csrf_field()}}
                    <div id="hiddenn"></div>
                    <div class="input-group" id="dropzone">
                        <div class="col-lg-12 col-md-12 col-sm-12 ss--padding-left-0">
                            <div class="m-dropzone dropzone m-dropzone--primary dz-clickable"
                                 action="{{route('admin.upload-image')}}" id="dropzoneImageProductEdit">
                                <div class="m-dropzone__msg dz-message needsclick">
                                    <h3 class="m-dropzone__msg-title">{{__('Hình sản phẩm')}}</h3>
                                    <span class="m-dropzone__msg-desc">{{__('Vui lòng chọn hình ảnh')}}.</span>
                                </div>
                                <input type="hidden" id="file_image" name="product_image" value="file_name">
                                <div id="temp">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <button onclick="cancelAddImage()" data-dismiss="modal"
                                class="btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>

                        <div class="btn-group">
                            <button type="button"
                                    class="btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md btn-save-image m--margin-left-10 m--margin-bottom-5">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>