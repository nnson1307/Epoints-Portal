<div class="modal fade" id="modal-add-shift" role="dialog" style="z-index: 100;">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('DANH SÁCH CA')}}
                </h5>
            </div>
            <div class="modal-body" id="autotable-shift-pop">
                <div class="padding_row bg">
                    <form class="frmFilter">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <input type="text" class="form-control" name="search"
                                       placeholder="@lang("Nhập tên ca")">
                                <input type="hidden" name="day_name" value="{{$day_name}}">
                                <input type="hidden" name="focus_shift_id" value="{{$focus_shift_id}}">
                                <input type="hidden" name="staff_salary_type_code" value="{{$staff_salary_type_code}}">
                            </div>
                            <div class="col-lg-2 form-group">
                                <button class="btn btn-primary color_button btn-search" style="display: block">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-content m--margin-top-30">
                    @include('shift::time-working-staff.pop.list-shift')
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
                    <button type="button" onclick="index.submitAddShift('{{$staff_id}}', '{{$working_day}}', '{{$view}}')"
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


