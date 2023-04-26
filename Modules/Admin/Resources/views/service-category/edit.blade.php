<div id="editForm" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA NHÓM DỊCH VỤ')}}
                </h4>

            </div>
            <form id="formEdit">
                <div class="modal-body">
                    <input type="hidden" id="hhidden">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Nhóm dịch vụ')}}:<b class="text-danger">*</b>
                                </label>
                                <input placeholder="{{__('Nhập tên nhóm dịch vụ')}}" type="text" name="name"
                                       class="form-control m-input"
                                       id="h_name">
                                <span class="error-name"></span>
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Trạng thái')}} :
                                </label>
                                {{--<div class="input-group">--}}
                                {{--<label class="m-checkbox m-checkbox--air">--}}
                                {{--<input id="h_is_actived" class="is_actived" type="checkbox">Hoạt động--}}
                                {{--<span></span>--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                <div class="row">
                                    <div class="col-lg-2">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="h_is_actived" name="is_actived" type="checkbox" class="is_actived">
                        <span></span>
                    </label>
                </span>
                                    </div>
                                    <div class="col-lg-8 m--margin-top-5">
                                        <i>{{__('Chọn để kích hoạt trạng thái')}}</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Mô tả')}}:
                                </label>
                                <div class="input-group">
                                <textarea placeholder="{{__('Nhập mô tả')}}" rows="5" cols="50" name="description"
                                          id="h_description" class="form-control"></textarea>
                                </div>
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

                            <button type="submit" id="btnLuu"
                                    class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
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
