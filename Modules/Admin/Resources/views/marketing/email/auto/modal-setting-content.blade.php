<div class="modal fade show" id="setting-content" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-auto" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title">
                    <i class="la la-edit"></i> @lang('CHỈNH SỬA EMAIL')
                </h5>
                {{--<a href="javascript:void(0)" onclick="auto.template()"--}}
                {{--class="btn btn-primary m-btn m-btn--icon m-btn--pill">--}}
                {{--<span>--}}
                {{--<i class="la la-eye"></i>--}}
                {{--<span> Xem trước</span>--}}
                {{--</span>--}}
                {{--</a>--}}

            </div>


            <form id="form-content">
                <div class="modal-body">
                    <input type="hidden" id="id_content" name="id_content">
                    <input type="hidden" id="type_content" name="type_content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label>{{__('Tiêu đề')}}:</label>
                                <input type="text" class="form-control" id="title" name="title"
                                       placeholder="{{__('Hãy nhập tiêu đề')}}...">
                            </div>
                            <div class="form-group m-form__group parameters">
                                <label>{{__('Tham số')}}:</label>
                                <div class="row" id="tb_para">

                                </div>
                            </div>
                            <div class="append_type">


                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label>{{__('Nội dung')}}:</label>
                                <textarea class="form-control content" cols="50" rows="10" id="content"
                                          name="content"></textarea>
                                <span class="error_content" style="color: #ff0000"></span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="m-form__actions m--align-right w-100">
                        <a href="javascript:void(0)" data-dismiss="modal"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                           <span>
                            <i class="la la-arrow-left"></i>
                               <span>{{__('HỦY')}}</span>
                           </span>
                        </a>
                        <button type="button" onclick="auto.submit_content()"
                                class="btn btn-info color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHỈNH SỬA')}}</span>
							</span>
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>