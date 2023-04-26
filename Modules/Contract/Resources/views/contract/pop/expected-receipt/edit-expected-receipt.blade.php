<div class="modal fade show" id="modal-edit-revenue" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i>

                    @if ($type == 'receipt')
                        @lang('CHỈNH SỬA ĐỢT DỰ KIẾN THU')
                    @else
                        @lang('CHỈNH SỬA ĐỢT DỰ KIẾN CHI')
                    @endif
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-edit">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tiêu đề'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="title" name="title"
                                   value="{{$infoRevenue['title']}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nội dung nhắc nhở'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class="form-control select" id="contract_category_remind_id"
                                    name="contract_category_remind_id"
                                    style="width:100%;">
                                <option></option>
                                @foreach($optionRemind as $v1)
                                    <option value="{{$v1['contract_category_remind_id']}}"
                                            {{$infoRevenue['contract_category_remind_id'] == $v1['contract_category_remind_id'] ? 'selected': ''}}>{{$v1['title']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Giá trị thanh toán'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input input_float" id="amount" name="amount"
                                   value="{{number_format($infoRevenue['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-4">
                            <label class="black_title">
                                @if ($type == 'receipt')
                                    @lang('Thời gian dự kiến thu'):
                                @else
                                    @lang('Thời gian dự kiến chi'):
                                @endif
                                <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control select" id="send_type" name="send_type"
                                        style="width:100%;" onchange="expectedRevenue.changeType(this)">
                                    <option></option>
                                    <option value="after" {{$infoRevenue['send_type'] == "after"? 'selected': ''}}>@lang('Sau ngày ký hợp đồng')</option>
                                    <option value="hard" {{$infoRevenue['send_type'] == "hard"? 'selected': ''}}>@lang('Cố định')</option>
                                    <option value="custom" {{$infoRevenue['send_type'] == "custom"? 'selected': ''}}>@lang('Tuỳ chọn')</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-8 div_send_type">
                            @switch($infoRevenue['send_type'] )
                                @case('after')
                                <label class="black_title">
                                    @lang('Giá trị')
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input input_int" id="send_value"
                                           name="send_value" value="{{$infoRevenue['send_value']}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">@lang('Ngày')</span>
                                    </div>
                                </div>
                                @break
                                @case('hard')
                                <label class="black_title"></label>
                                <div class="row">
                                    <div class="col-lg-3">
                                        @lang('Định kỳ ngày')
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control m-input input_int" id="send_value"
                                               name="send_value" value="{{$infoRevenue['send_value']}}">
                                    </div>
                                    <div class="col-lg-2">
                                        @lang('mỗi')
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control m-input input_int"
                                                   id="send_value_child"
                                                   name="send_value_child" value="{{$infoRevenue['send_value_child']}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">@lang('Tháng')</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break
                                @case('custom')
                                <label class="black_title">
                                    @lang('Giá trị')':
                                </label>
                                <div class="div_add_date">
                                    @if (count($revenueLog) > 0)
                                        @foreach($revenueLog as $v)
                                            <div class="form-group input-group">
                                                <input type="text" class="form-control m-input date_picker" readonly=""
                                                       name="date_custom"
                                                       value="{{\Carbon\Carbon::parse($v['date_send'])->format('d/m/Y')}}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary" type="button"
                                                            onclick="expectedRevenue.removeDate(this)">
                                                        <i class="la la-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <a href="javascript:void(0)" onclick="expectedRevenue.addDate()"
                                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('Thêm ngày')</span>
                                    </span>
                                </a>
                                @break
                            @endswitch
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Ghi chú'):
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" cols="3" id="note_revenue"
                                      name="note_revenue">{{$infoRevenue['note']}}</textarea>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div id="contract_revenue_files" class="row">
                            @if (count($revenueFile) > 0)
                                @foreach($revenueFile as $v)
                                    <div class="col-lg-12">
                                        <a href="{{$v['link']}}" value="{{$v['$v']}}" name="contract_revenue_files[]" class="ss--text-black" download="{{$v['file_name']}}">{{$v['file_name']}}</a>
                                        <a href="javascript:void(0)" onclick="expectedRevenue.removeFile(this)"><i class="la la-trash"></i></a>
                                        <br>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="m-widget19__action">
                            <button type="button" onclick="document.getElementById('upload_file_revenue').click()"
                                    class="btn btn-primary btn-sm color_button m-btn text-center">
                                {{ __('Tải hồ sơ') }}
                            </button>
                        </div>
                    </div>
                </form>
                <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .docx"
                       id="upload_file_revenue" type="file"
                       class="btn btn-primary btn-sm color_button m-btn text-center"
                       style="display: none" oninvalid="setCustomValidity('Please, blah, blah, blah ')"
                       onchange="expectedRevenue.uploadFile(this)"
                >
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
                    <button type="button" onclick="expectedRevenue.edit('{{$infoRevenue['type']}}', '{{$infoRevenue['contract_expected_revenue_id']}}')"
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