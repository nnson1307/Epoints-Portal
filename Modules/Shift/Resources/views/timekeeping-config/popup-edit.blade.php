<div class="modal fade show" id="modal-edit" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-edit"></i> @lang('CHỈNH SỬA CẤU HÌNH CHẤM CÔNG')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-edit">

                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Chi nhánh làm việc'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class="form-control" id="branch_id" name="branch_id" style="width:100%;">
                                <option></option>
                                @foreach($optionBranch as $v)
                                    <option value="{{$v['branch_id']}}" {{$item['branch_id'] == $v['branch_id'] ? 'selected': ''}}>{{$v['branch_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="input-group row">
                            <div class="input-group col-sm-6 d-flex align-items-center">
                                <input type="radio" id="css" name="timekeeping_type" onchange="onTimeKeepingTypeChange(this)" value="wifi" @if($item['timekeeping_type'] == 'wifi') checked @endif>
                                <span class="ml-2" for="wifi">@lang("Chấm công bằng WIFI")</span>
                            </div>
                            <div class="input-group col-sm-6 d-flex align-items-center">
                                <input type="radio" id="css" name="timekeeping_type" onchange="onTimeKeepingTypeChange(this)" value="gps" @if($item['timekeeping_type'] == 'gps') checked @endif>
                                <span class="ml-2" for="gps">@lang("Chấm công bằng GPS")</span>
                            </div>
                        </div>
                    </div>

                    <div id="timekeeping-content">

                        <div class="group-wifi @if($item['timekeeping_type'] == 'gps') d-none @endif">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên wifi'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="wifi_name" name="wifi_name" value="{{$item['wifi_name'] ?? ' '}}"
                                       placeholder="@lang('Tên wifi')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Địa chỉ IP'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="wifi_ip" name="wifi_ip"
                                       placeholder="@lang('Vd: 42.114.204.250')" value="{{$item['wifi_ip'] ?? ' '}}">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Ghi chú'):
                                </label>
                                <textarea class="form-control m-input" id="note" name="note" placeholder="@lang('Ghi chú')" rows="3">{{$item['note']}}</textarea>
                            </div>

                            <div class="row">
                                <div class="form-group m-form__group col-lg-6">
                                    <a target="_blank" href="https://whatismyipaddress.com/" class="btn btn-sm m-btn--icon color w-100">
                                <span>
                                    <i class="la 	la-wifi"></i>
                                    <span>@lang('Lấy địa chỉ IP từ wifi hiện tại')</span>
                                </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="group-gps @if($item['timekeeping_type'] == 'wifi') d-none @endif">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Kinh độ'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="latitude" name="latitude"
                                       value="{{$item['latitude'] ?? ' '}}"
                                       placeholder="@lang('Kinh độ')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Vĩ độ'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="longitude" name="longitude"
                                       value="{{$item['longitude'] ?? ' '}}"
                                       placeholder="@lang('Vĩ độ')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Bán kính cho phép') (m):
                                </label>
                                <input type="number" class="form-control m-input" id="allowable_radius" name="allowable_radius"
                                       value="{{$item['allowable_radius'] ?? ' '}}"
                                       placeholder="@lang('Bán kính cho phép')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Ghi chú'):
                                </label>
                                <textarea class="form-control m-input" id="note_gps" name="note_gps" placeholder="@lang('Ghi chú')" rows="3">{{$item['note']}}</textarea>
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
                    <button type="button" onclick="edit.save({{$item['timekeeping_config_id']}})"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
