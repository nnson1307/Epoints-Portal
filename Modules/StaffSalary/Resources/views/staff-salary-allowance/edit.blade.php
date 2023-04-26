<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modalAllowanceEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 40% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #1365C6!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA PHỤ CẤP')
                </h5>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>
                        @lang('Tên phụ cấp'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" id="salary_allowance_name" value="{{ $data['salary_allowance_name'] }}" class="form-control m-input" placeholder="{{ __('Tên phụ cấp') }}">
                    <span class="error-salary-allowance-name"></span>
                    <input type="hidden" id="salary_allowance_id" value="{{ $data['salary_allowance_id'] }}">
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
                                m--margin-left-10" onclick="allowance.edit();">
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
