<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-users"></i> @lang("THÊM NHÓM KHÁCH HÀNG")
                </h4>
                {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
            </div>
            <div class="modal-body">
                <form id="form-customer-group">
                    <div class="form-group">
                        <label>
                            @lang("Tên nhóm khách hàng"):
                        </label>
                        <div class="{{ $errors->has('group_name') ? ' has-danger' : '' }}">
                            <input type="text" id="group_name" name="group_name" class="form-control  m-input"
                                   placeholder="@lang("Nhập tên nhóm khách hàng")">
                            <span class="error-group-name"></span>
                        </div>
                    </div>
                    {{--<div class="modal-footer">--}}
                    {{--<button onclick="customer.add_customer_group(0)" title="Thêm và tạo mới" class="btn btn-success">Lưu và tạo mới--}}
                    {{--</button>--}}
                    {{--<button onclick="customer.add_customer_group(1)" title="Thêm và đóng" class="btn btn-primary">Lưu và đóng--}}
                    {{--</button>--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>--}}
                    {{--</div>--}}
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button type="button" onclick="$('#add').modal('hide');"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>


                            <button type="button" onclick="customer.add_customer_group(1)"
                                    class="btn btn-success  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>

							<span>@lang("THÊM")</span>
							</span>
                            </button>
                            {{--<button type="button" class="btn btn-success  dropdown-toggle dropdown-toggle-split m-btn m-btn--md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                            {{--</button>--}}
                            {{--<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">--}}
                            {{--<button type="submit" class="dropdown-item" href="javascript:void(0)" onclick="customer.add_customer_group(0)"><i class="la la-plus"></i> Lưu &amp; Tạo mới--}}
                            {{--</button>--}}
                            {{--<button type="submit" class="dropdown-item" href="javascript:void(0)" onclick="customer.add_customer_group(1)"><i class="la la-undo"></i> Lưu &amp; Đóng--}}
                            {{--</button>--}}
                            {{--</div>--}}

                        </div>
                    </div>
                    <input type="hidden" name="type_add" id="type_add" value="0">
                </form>
            </div>

        </div>

    </div>
</div>
