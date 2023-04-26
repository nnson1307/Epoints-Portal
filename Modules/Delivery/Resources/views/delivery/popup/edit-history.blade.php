<div class="modal fade show" id="modal-edit" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    @lang('Chỉnh sửa phiếu giao hàng')
                </h5>
            </div>
            <form id="form-edit">
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Thời gian giao hàng dự kiến'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group date">
                            <input type="text" class="form-control m-input" readonly=""
                                   placeholder="@lang('Thời gian giao hàng dự kiến')" id="time_ship" name="time_ship"
                                   value="{{\Carbon\Carbon::parse($item['time_ship'])->format('d/m/Y H:i')}}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nhân viên'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class="form-control" style="width: 100%" id="delivery_staff" name="delivery_staff">
                                <option></option>
                                @foreach($optionCarrier as $v)
                                    <option value="{{$v['user_carrier_id']}}"
                                            {{$v['user_carrier_id'] == $item['delivery_staff'] ? 'selected' : ''}}>{{$v['full_name']}}</option>
                                @endforeach
                            </select>
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
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="detail.submitEditHistory({{$item['delivery_history_id']}})"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>