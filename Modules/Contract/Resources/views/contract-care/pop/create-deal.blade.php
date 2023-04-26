<div class="modal fade show" id="modal-create" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('Bạn có muốn tạo deal cho') {{$amount}} @lang('hợp đồng đã chọn')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên deal'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="pop_deal_name" name="deal_name"
                                           placeholder="@lang('Nhập tên deal')">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Người sở hữu deal'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    @if(Auth::user()->is_admin)
                                        <select class="form-control" id="pop_staff" name="staff"
                                                style="width:100%;">
                                            <option></option>
                                            @foreach($optionStaff as $v)
                                                @if($v['staff_id'] == Auth()->id())
                                                    <option value="{{Auth()->id()}}" selected>{{Auth()->user()->full_name}}</option>
                                                @else
                                                    <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="form-control" id="pop_staff" name="staff"
                                                style="width:100%;">
                                            <option value="{{Auth()->id()}}" selected>{{Auth()->user()->full_name}}</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Pipeline'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="pop_pipeline_code" name="pipeline_code"
                                            style="width:100%;">
                                        <option></option>
                                        @foreach($optionPipeline as $v)
                                            <option value="{{$v['pipeline_code']}}">{{$v['pipeline_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Hành trình'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control journey" id="pop_journey_code" name="journey_code"
                                            style="width:100%;">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Ngày kết thúc dự kiến'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="pop_end_date_expected" name="end_date_expected"
                                           placeholder="@lang('Chọn ngày kết thúc dự kiến')">
                                </div>
                            </div>
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
                    <button type="button" onclick="expireContract.createDeal('{{$type}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('THỰC HIỆN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

