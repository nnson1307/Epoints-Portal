<div class="modal fade" id="modal-add-user-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{__('Thêm khách hàng')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body"  style="
    max-height: calc(100vh - 210px);
    overflow-y: auto;">
                <div class="form-group row kt-margin-b-10">
                    <div class="col-lg-3">
                        <input type="text" name="define_full_name_3" id="define_full_name_3" class="form-control"
                               placeholder="{{__('Họ tên khách hàng')}}"
                               value="">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="define_phone_3" id="define_phone_3" class="form-control"
                               placeholder="{{__('Số điện thoại')}}"
                               value="">
                    </div>
                    <div class="col-lg-2">
                        <select type="text" name="define_is_actived_3" id="define_is_actived_3" style="width: 100%"
                                class="form-control select-2 ss--select-2 ss-width-100pt">
                            <option value="">{{__('Chọn trạng thái')}}</option>
                            <option value="1">{{__('Hoạt động')}}</option>
                            <option value="0">{{__('Tạm ngưng')}}</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <button class="btn ss--btn-search kt-margin-r-10" onclick="userGroupDefine.searchAddUser()"
                                style="">{{__('TÌM KIẾM')}}
                        </button>
                    </div>
                </div>
                <div class="form-group kt-margin-b-0">
                    <div class="kt-section__content"
                         id="table-list-user">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                <span class="ss--text-btn-mobi">
                <i class="la la-arrow-left"></i>
                <span>{{__('HỦY')}}</span>
                </span>
                </button>
                <button type="button" onclick="userGroupDefine.addUser2()"
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