<div class="modal fade show" id="modal-image-before" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM HÌNH ẢNH')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-img">
                    <div class="form-group m-form__group ">
                        <label>{{__('Ảnh kèm theo')}}:</label>
                        <div class="m-dropzone dropzone dz-clickable" action="{{route('admin.upload-image')}}" id="dropzoneBefore">
                            <div class="m-dropzone__msg dz-message needsclick">
                                <h3 href="" class="m-dropzone__msg-title">
                                    {{__('Hình ảnh')}}
                                </h3>
                                <span>{{__('Chọn hình ảnh')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display:flex;justify-content:center;" id="up-image-before-temp">
                        @if(count($orderImage) > 0)
                            @foreach($orderImage as $v)
                                @if ($v['type'] == 'before')
                                    <div class="wrap-img image-show-child">
                                        <input type="hidden" name="img-order" value="{{$v['link']}}">
                                        <img class="m--bg-metal m-image img-sd " src="{{$v['link']}}" alt="Hình ảnh" width="100px" height="100px">
                                        <span class="delete-img-sv" style="display: block;">
                                            <a href="javascript:void(0)" onclick="detail.removeImage(this)">
                                                <i class="la la-close"></i>
                                            </a>
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>

                        <button class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md
                            m--margin-left-10" onclick="detail.saveImage('{{$order['order_code']}}', 'before')">
							<span>
							<i class="la la-check"></i>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>