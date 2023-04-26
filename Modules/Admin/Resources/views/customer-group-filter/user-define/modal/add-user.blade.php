<div class="modal fade" id="modal-add-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{__('Thêm khách hàng')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row kt-margin-b-10">
                    <div class="col-lg-12">
                        <button class="btn ss--btn-search"
                                data-toggle="modal" onclick="userGroupDefine.showModalImportExcel()">
                        {{__('THÊM KHÁCH HÀNG BẰNG EXCEL')}}
                        </button>
                    </div>
                </div>
                <div class="form-group row kt-margin-b-10">
                    <div class="col-lg-3 form-group">
                        <input type="text" name="define_full_name_2" id="define_full_name_2" class="form-control"
                               placeholder="{{__('Họ tên khách hàng')}}"
                               value="">
                    </div>
                    <div class="col-lg-2 form-group">
                        <input type="text" name="define_phone_2" id="define_phone_2" class="form-control"
                               placeholder="{{__('Số điện thoại')}}"
                               value="">
                    </div>
                    <div class="col-lg-2 form-group">
                        <select type="text" name="define_is_actived_2" id="define_is_actived_2" style="width: 100%"
                                class="form-control select-2 ss--select-2 ss-width-100pt">
                            <option value="">{{__('Chọn trạng thái')}}</option>
                            <option value="1">{{__('Hoạt động')}}</option>
                            <option value="0">{{__('Tạm ngưng')}}</option>
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn ss--btn-search kt-margin-r-10" id="search-trigger"  onclick="userGroupDefine.searchPopup1()"
                                style="">{{__('TÌM KIẾM')}}
                        </button>
                    </div>
                    <div class="col-lg-3 form-group">
                        <button class="btn ss--btn-search"
                                style="" onclick="userGroupDefine.showModalAddUser2()">
                            {{__('THÊM KHÁCH HÀNG')}}
                        </button>
                    </div>
                </div>
                <div class="form-group row kt-margin-b-0">
                    <div class="col-lg-12">
                        <div class="ss--height-modal-customer table-responsive" id="table-list-user">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success m--margin-bottom-15">
                                            <input type="checkbox"
                                                   onclick="userGroupDefine.selectAll1(this, 'table-popup-user')">
                                            <span></span>
                                        </label>
                                    </th>
                                    <th class="ss--nowrap">{{__('HỌ VÀ TÊN')}}</th>
                                    <th class="ss--nowrap">{{__('SỐ ĐIỆN THOẠI')}}</th>
                                    <th class="ss--nowrap">{{__('TRẠNG THÁI')}}</th>
                                </tr>
                                </thead>
                                <tbody id="tbody-add-user">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button type="button" onclick="userGroupDefine.addUserGroupDefine()"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('THÊM KHÁCH HÀNG')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>