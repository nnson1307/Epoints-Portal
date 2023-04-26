<div class="modal fade" id="modal-my-shift" role="dialog" style="z-index: 50;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                   {{__('CA LÀM')}}
                </h5>
            </div>
            <div class="modal-body">
                <div class="div_my_shift">
                    @include('shift::time-working-staff.pop.list-my-shift')
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
                </div>
            </div>
        </div>
    </div>
</div>


