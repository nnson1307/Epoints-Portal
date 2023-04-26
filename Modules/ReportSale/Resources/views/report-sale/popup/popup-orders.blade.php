<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modalListOrders" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 80% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #008990!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('DANH SÁCH ĐƠN HÀNG')
                </h5>
            </div>

            <div class="modal-body">
                <div id="autotable-list-pop">
                    
                    <form class="frmFilter bg">
                        <input type="hidden" name="time_search" id="time_search" value="{{$time}}">
                        <input type="hidden" name="branch_search" id="branch_search" value="{{$branch}}">
                        <input type="hidden" name="order_type" id="order_type" value="{{$orderType}}">
                        <div class="row padding_row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="search" placeholder=" {{__('Nhập thông tin tìm kiếm')}}">
                                </div>
                            </div>
                            <div class="col-lg-2 form-group">
                                <button class="btn btn-primary color_button btn-search">
                                    @lang("TÌM KIẾM") <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-content m--padding-top-30">
                        {{-- @include('report-sale::report-sale.popup.list-orders') --}}
                    </div>
                </div>
                
            </div>
            {{-- <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </button>

                        <button class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md
                                m--margin-left-10" onclick="holiday.add();">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('ĐỒNG Ý')</span>
                            </span>
                        </button>

                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
