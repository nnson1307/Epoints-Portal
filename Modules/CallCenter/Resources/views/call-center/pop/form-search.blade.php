<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modal-search" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 80% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" style="font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('TRA CỨU THÔNG TIN TIẾP NHẬN')
                </h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>
                                @lang('Nhập số điện thoại (tên khách hàng) tiếp nhận'):
                            </label>
                            <input type="text" class="form-control" name="keyWordCustomer" id="keyWordCustomer"
                            placeholder="@lang("Nhập từ khóa tìm kiếm")">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="lstCustomerSearch">
                     
                    </div>
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
                    <button type="button" onclick="callCenter.SearchCustomerCallCenter()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('TRA CỨU')}}</span>
                            <i class="fa fa-search m--margin-left-5" style="padding-left: 5px;"></i> 
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>