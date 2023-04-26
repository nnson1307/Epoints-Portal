<div id="modal-edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA NGÀNH NGHỀ')}}
                </h4>

            </div>
            <form id="form-edit">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" id="bussiness_id" name="bussiness_id">
                            <div class="form-group">
                                <label>
                                    {{__('Tên ngành nghề')}}:<b class="text-danger">*</b>
                                </label>
                                <input placeholder="{{__('Nhập tên ngành nghề')}}..." type="text" name="name_edit"
                                       class="form-control m-input btn-sm"
                                       id="name_edit">
                                <span class="error_name_edit" style="color:red"></span>
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Trạng thái')}}:
                                </label>
                                {{--<div>--}}
                                {{--<label class="m-checkbox">--}}
                                {{--<input type="checkbox" name="is_actived" id="h_is_actived" value="1">Hoạt động--}}
                                {{--<span></span>--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                <div class="row">
                                    <div class="col-lg-2">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_actived" name="is_actived" type="checkbox">
                        <span></span>
                    </label>
                </span>
                                    </div>
                                    <div class="col-lg-7 m--margin-top-5">
                                        <i>{{__('Select to activate status')}}</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Mô tả')}}:
                                </label>
                                <textarea class="form-control" rows="4" id="description_edit" name="description_edit"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>

                            <button type="submit" id="luu" onclick="bussiness.submit_edit()"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
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
