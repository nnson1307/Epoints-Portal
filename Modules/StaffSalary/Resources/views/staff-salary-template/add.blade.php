<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modalSalaryTemplateAdd" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 70% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #1365C6!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('THÊM MỚI MẪU ÁP DỤNG')
                </h5>
            </div>

            <div class="modal-body">
                <div class="row padding_row border">
                    <div class="col-lg-8 form-group">
                        <label>
                           <b> Mẫu  áp dụng</b>
                        </label>
                        <input type="text" class="form-control" placeholder="Mẫu áp dụng">
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>
                            <b>Kỳ hạn trả lương</b>
                        </label>
                        <select class="form-control m-input" name="department_id" id="department_id">
                            <option value="" selected="selected">Chọn kỳ hạn trả lương</option>
                            <option value="week">Theo tuần</option>
                            <option value="month">Theo tháng</option>
                        </select>
                    </div>
                    <div class="col-lg-8 form-group">
                        <label>
                           <b>Chi nhánh</b>
                        </label>
                        <select class="form-control m-input" name="department_id" id="department_id">
                            <option value="" selected="selected">Chi nhánh</option>
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>
                            <b>Thời gian trả lương</b>
                        </label>
                        <select class="form-control m-input" name="department_id" id="department_id">
                            <option value="" selected="selected">Chọn thời gian trả lương</option>
                            <option value="" >Ngày thứ 2</option>
                            <option value="" >Ngày thứ 3</option>
                            <option value="" >Ngày thứ 4</option>
                            <option value="" >Ngày thứ 5</option>
                            <option value="" >Ngày thứ 6</option>
                        </select>
                    </div>
                </div>
                <br>
               <div class="row padding_row border">
                    <div class="col-lg-6 form-group">
                        <label>
                            <b>Hoa Hồng</b>
                        </label>
                        <select class="form-control m-input" name="department_id" id="department_id">
                            <option value="" selected="selected">Chọn loại doanh thu</option>
                            <option value="" >Theo doanh thu cá nhân</option>
                            <option value="" >Theo tổng doanh thu</option>
                        </select>
                    </div>
                    <div class="table-responsive" style="display: none;">
                        <table class="table table-striped m-table m-table--head-bg-default">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">Doanh thu</th>
                                <th class="tr_thead_list">Hoa hồng thụ hưởng</th>
                                <th class="tr_thead_list"></th>
                            </tr>
                            </thead>
                            <tbody>
                                <td>
                                    > 5,000,000
                                </td>
                                <td>
                                    5% 
                                </td>
                                <td nowrap="">
                                    <a href="javascript:void(0)" onclick="" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <button onclick="" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
                                        <i class="la la-trash"></i>
                                    </button>
                                </td>
                            </tbody>
                        </table>
                        <a href="javascript:void(0)" onclick="salaryTempalte.showModalCommissionAdd()" class="btn btn-outline-success m-btn m-btn--icon m-btn--outline-2x">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span>Thêm điều kiện</span>
                            </span>
                        </a>
                    </div>
               </div>
               <br>
               <div class="row padding_row border">
                    <div class="col-lg-4 form-group">
                        <label>
                            <h4>Phụ cấp</h4>
                        </label>
                    </div>
                    <div class="col-lg-8 form-group text-right">
                        <span class="m-switch m-switch--success">
                            <label>
                                <input type="checkbox" name="ckbAllowances" onclick="salaryTempalte.checkAllowances();">
                                <span></span>
                            </label>
                        </span>
                    </div>
                    <div class="col-lg-12 form-group">
                        <div class="table-responsive" id="tblAllowances" style="display: none;">
                            <table class="table table-striped m-table m-table--head-bg-default">
                                <thead class="bg">
                                <tr>
                                    <th class="tr_thead_list">Loại phụ cấp</th>
                                    <th class="tr_thead_list">Phụ cấp thụ hưởng</th>
                                    <th class="tr_thead_list"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <td>
                                        Mỗi ngày làm việc
                                    </td>
                                    <td>
                                       5%
                                    </td>
                                    <td nowrap="">
                                        <a href="javascript:void(0)" onclick="" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <button onclick="" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </td>
                                </tbody>
                            </table>
                            <a href="javascript:void(0)" onclick="salaryTempalte.showModalAllowancesAdd()" class="btn btn-outline-success m-btn m-btn--icon m-btn--outline-2x">
                                <span>
                                    <i class="fa fa-plus-circle"></i>
                                    <span>Thêm điều kiện</span>
                                </span>
                            </a>
                        </div>
                    </div>
               </div> 
            </div>
            <div class="modal-footer">
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
            </div>
        </div>
    </div>
</div>
<script>
    $('#staff_holiday_start_date, #staff_holiday_end_date').datepicker({
            rtl: mUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            format: 'dd/mm/yyyy',
        });
</script>