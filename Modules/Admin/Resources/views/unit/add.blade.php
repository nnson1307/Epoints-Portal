<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM ĐƠN VỊ TÍNH')}}
                </h4>

            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Đơn vị tính')}}:<b class="text-danger">*</b>
                                </label>
                                <input placeholder="{{__('Nhập đơn vị tính')}}" type="text" name="name"
                                       class="form-control m-input"
                                       id="name">
                                <span class="error-name"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Đơn vị chuẩn')}}:
                                </label>
                                <div class="">
                                    <label class="m-checkbox">
                                        <input type="checkbox" name="is_standard" id="is_standard" value="1">{{__('Chọn')}}
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row" style="display: none">
                        <div class="form-group row col-12">
                            <label class="col-sm-4">
                                {{__('Trạng thái')}}:
                            </label>
                            <div class="col-lg-8">
                                <label class="m-checkbox">
                                    <input type="checkbox" checked name="is_actived" id="is_actived" value="1">Hoạt động
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="type_add" id="type_add" value="0">
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

                            <button type="submit" id="luu" onclick="unit.add(1)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>

                            <button type="submit" id="luu" onclick="unit.add(0)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="fa fa-plus-circle"></i>
							<span>{{__('LƯU & TẠO MỚI')}}</span>
							</span>
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
