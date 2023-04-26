<div class="modal fade" id="modal-create-recompense" role="dialog" style="z-index: 200;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i>
                    @if ($type == "R")
                        {{__('THÊM HÌNH THỨC THƯỞNG')}}
                    @else
                        {{__('THÊM HÌNH THỨC PHẠT')}}
                    @endif
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create-recompense">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @if ($type == "R")
                                @lang('Loại thưởng'):
                            @else
                                @lang('Loại phạt'):
                            @endif

                            <b class="text-danger">*</b>
                        </label>
                        <div class="input-group m-input-group">
                            <select class="form-control" id="recompense_id" name="recompense_id">
                                <option></option>
                                @foreach($optionRecompense as $v)
                                    <option value="{{$v['recompense_id']}}">{{$v['recompense_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Mức áp dụng'):<b class="text-danger">*</b>
                        </label>
                        <div class="input_min_time_work">
                            <input type="text" class="form-control m-input phone" id="money" name="money">
                        </div>
                    </div>
                </form>
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
                    <button type="button"
                            onclick="index.submitCreateRecompense('{{$time_working_staff_id}}', '{{$type}}')"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                    							<span>
                    							<i class="la la-check"></i>
                    							<span>{{__('LƯU')}}</span>
                    							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>


