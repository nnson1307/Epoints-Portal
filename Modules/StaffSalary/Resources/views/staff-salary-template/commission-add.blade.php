<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modalSalaryCommissionAdd" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 40% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #1365C6!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('THÊM ĐIỀU KIỆN TÍNH HOA HỒNG')
                </h5>
            </div>

            <div class="modal-body">
                <div class="row padding_row">
                    <div class="col-lg-12 form-group">
                        <label>
                            <b>Hoa Hồng</b>
                        </label>
                        <select class="form-control m-input" name="commission" id="commission" style="width : 100%">
                            <option value="" selected="selected">Chọn loại doanh thu</option>
                            <option value="" >Theo doanh thu cá nhân</option>
                            <option value="" >Theo tổng doanh thu</option>
                        </select>
                    </div>
                    <div class="col-lg-12 form-group">
                        <label>
                           <b>Doanh thu trên</b>
                        </label>
                        <input type="text" class="form-control" placeholder="100,000">
                    </div>
                    <div class="col-lg-12 form-group">
                        <label>
                            <b>Áp dụng</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="350,000">
                            <div class="input-group-append">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-secondary active">
                                        <input type="radio" name="options" id="option1" autocomplete="off" checked=""> VNĐ
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="options" id="option2" autocomplete="off"> %
                                    </label>
                                </div>
                            </div>
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
        $('#commission').select2();
</script>