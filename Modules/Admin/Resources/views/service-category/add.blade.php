<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM NHÓM DỊCH VỤ')}}
                </h4>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            {{__('Nhóm dịch vụ')}}:<b class="text-danger">*</b>
                        </label>
                        <div>
                            <input placeholder="{{__('Nhập tên nhóm dịch vụ')}}..." type="text" name="name"
                                   class="form-control m-input" id="name">
                            <span class="error-name"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Mô tả')}}:
                        </label>
                        <div class="input-group">
                                <textarea placeholder="{{__('Nhập thông tin mô tả')}}" rows="3" cols="50" name="description"
                                          id="description" class="form-control"></textarea>
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

                            <button type="button" id="luu" onclick="service_category.add(1)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>

                            <button type="button" id="luu" onclick="service_category.add(0)"
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
