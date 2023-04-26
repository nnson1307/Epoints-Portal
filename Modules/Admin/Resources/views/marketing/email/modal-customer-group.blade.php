<div class="modal fade show" id="add-customer-group">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM NHÓM KHÁCH HÀNG')}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body">
                <div class="ss--background">
                    <div class="ss--bao-filter">
                        <div class="row">
                            <div class="form-group m-form__group col-lg-4">
                                <select name="filter_type_group" id="filter_type_group" onchange="edit.searchCusGroupFilter();" class="form-control" style="width: 100%">
                                    <option value="">{{__('Chọn loại nhóm khách hàng')}}</option>
                                    <option value="user_define">{{__('Nhóm khách hàng tự định nghĩa')}}</option>
                                    <option value="auto">{{__('Nhóm khách hàng tự động')}}</option>
                                </select>
                            </div>
                            <div class="form-group m-form__group col-lg-4">
                                <select name="customer_group_filter" id="customer_group_filter" class="form-control" style="width: 100%">
                                    <option value="">{{__('Chọn nhóm khách hàng')}}</option>
                                    {{--                            append option--}}
                                </select>
                            </div>
                            <div class="form-group m-form__group col-lg-4">
                                <button onclick="edit.search_group()" class="btn ss--button-cms-piospa ss--btn">
                                    {{__('TÌM KIẾM')}}<i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive m--margin-top-30">
                    <div class="m-scrollable m-scroller ps ps--active-y w-100 pr-0" data-scrollable="true"
                         style="height: 300px; overflow: hidden;">
                        <table class="table table-striped m-table m-table--head-bg-default customer_group_list">
                            <thead class="bg">
                            <tr>
                                <th width="2%" class="tr_thead_list">#</th>
                                <th width="50%" class="tr_thead_list">{{__('KHÁCH HÀNG')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('EMAIL')}}</th>
                                <th width="8%" class="tr_thead_list">{{__('NGÀY SINH')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('GIỚI TÍNH')}}</th>
                                <th width="10%" class="tr_thead_list">{{__('CHI NHÁNH')}}</th>
                                <th width="10%" class="tr_thead_list">
                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                                        <input class="check_all_group" name="check_all_group" type="checkbox">
                                        <span></span>
                                    </label> {{__('TẤT CẢ')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody class="customer_group_list_body" style="font-size: 13px">

                            </tbody>
                        </table>

                    </div>
                    <span class="error_append" style="color: red"></span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="submit" onclick="edit.click_append_group()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHỌN')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>