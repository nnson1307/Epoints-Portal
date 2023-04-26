<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modalSalaryBonusMinusAdd" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 40% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #1365C6!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('Thêm điều kiện tính thưởng / phạt')
                </h5>
            </div>

            <div class="modal-body">
                <div class="row padding_row">
                    <div class="col-lg-12 form-group">
                        <label>
                            @lang('Loại thưởng / phạt')
                        </label>
                        <select class="form-control m-input width-select" name="salaryBonusMinus" id="salaryBonusMinus" style="width : 100%;">
                            <option value="" selected="selected">@lang('Chọn loại thưởng / phạt')</option>
                            @if(isset($salaryBonusMinus))
                                @foreach ($salaryBonusMinus as $key => $item)
                                    <option value="{{ $item['salary_bonus_minus_id'] }}" >{{ $item['salary_bonus_minus_name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        <input type="hidden" value="" id="salary_bonus_minus_type">
                        <span class="error-salaryBonusMinus"></span>
                    </div>
                    <div class="col-lg-12 form-group">
                        <label>
                           @lang('Áp dụng')
                        </label>
                        <div class="input-group">
                            <input type="text" name="salary_bonus_minus_num" class="form-control m-input" id="salary_bonus_minus_num"
                                   placeholder="{{__('Hãy nhập lương cứng')}}"
                                   value="{{number_format(0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                            <div class="input-group-append">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-secondary active">
                                        <input type="radio" name="options" id="option1" autocomplete="off" checked=""> @lang('VNĐ')
                                    </label>
{{--                                    <label class="btn btn-secondary">--}}
{{--                                        <input type="radio" name="options" id="option2" autocomplete="off"> %--}}
{{--                                    </label>--}}
                                </div>
                            </div>
                        </div>
                        <span class="error-bonus-minus-num"></span>
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

                        <button class="btn btn-success color_button m-btn m-btn--icon m-btn--wide m-btn--md
                                m--margin-left-10" onclick="salaryTempalte.addSalaryBonusMinus()">
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

    $(document).ready(function () {
        $('#salaryBonusMinus').select2();
        new AutoNumeric.multiple('#salary_bonus_minus_num', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });
    });
</script>