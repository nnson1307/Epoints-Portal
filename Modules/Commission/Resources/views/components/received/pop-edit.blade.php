<div class="modal fade" id="modal-edit" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA')}}
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group" style="text-align: right">
                    <a href="{{ route('admin.commission.add') }}" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle m--margin-right-5"></i>
                        <span> THÊM HOA HỒNG</span>
                    </span>
                    </a>
                </div>
                <div class="form-group" id="div-allocation">
                    @include('commission::components.received.list-allocation')
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
                    @if (isset($listAllocation) && count($listAllocation) > 0)
                        <button type="button"
                                onclick="listStaff.submitEdit('{{$staff_id}}')"
                                class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                    							<span>
                    							<i class="la la-check"></i>
                    							<span>{{__('LƯU')}}</span>
                    							</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


