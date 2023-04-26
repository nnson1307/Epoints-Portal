<div class="modal fade" role="dialog" id="modal-address-contact">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-user-plus"></i> {{__('Danh sách địa chỉ')}}
                </h4>
                <a onclick="create.add_contact({{$customer_id}})"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                   title="{{__('Thêm địa chỉ')}}">
                    <i class="la la-plus"></i>
                </a>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group" id="autotable-contact">
                    <form class="frmFilter bg">
                        <input type="hidden" name="customer_id" value="{{$customer_id}}">

                        <div class="form-group m-form__group" style="display:none;">
                            <button class="btn btn-primary color_button btn-search">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </form>
                    <div class="table-content">
                        @include('admin::orders.list-contact')
                    </div>
                </div>
                <input type="hidden" id="customer_id_popup" value="{{$customer_id}}">
                <form id="form-address-contact">
                    <div class="append_address_contact">

                    </div>
                </form>
                <input type="hidden" id="customer_contact_id_default">
                <input type="hidden" id="flag_default">
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn ">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                    </button>
                    <button type="button" onclick="create.save({{$customer_id}})" id="submit_contact"
                            class="btn btn-primary color_button  m-btn m-btn--icon m-btn--wide m-btn--md btn-print m--margin-left-10 son-mb">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                    </button>
                </div>
            </div
        </div>
    </div>
</div>
