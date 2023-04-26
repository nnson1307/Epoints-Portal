<div class="modal fade show" id="modal-create" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('Thêm máy in')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-register">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên máy in'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="printer_name" name="printer_name"
                                       placeholder="@lang('Tên máy in')">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Địa chỉ IP'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="printer_ip" name="printer_ip"
                                       placeholder="@lang('Địa chỉ IP')">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Chi nhánh'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group m-input-group">
                                    <select class="form-control" id="branch_id" name="branch_id">
                                        @foreach($optionBranch as $v)
                                            <option value="{{$v['branch_id']}}">{{$v['branch_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label for="">{{__('Kích thước')}}</label>
                            <div class="black_title">
                                <select name="template" id="template" class="form-control class-select2"
                                        style="width: 100%">
                                    <option {{$configPrintBill->template=='k80'?'selected':''}} value="k80">K80</option>
                                    <option {{$configPrintBill->template=='k58'?'selected':''}} value="k58">K58</option>
                                    <option {{$configPrintBill->template=='A5'?'selected':''}} value="A5">A5</option>
                                    <option {{$configPrintBill->template=='A4'?'selected':''}} value="A4">A4</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Cổng'):<b class="text-danger">*</b>
                                </label>
                                <input type="number" class="class-number form-control m-input" id="printer_port" name="printer_port"
                                       placeholder="@lang('Cổng')">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Chiều rộng giấy in'):<b class="text-danger">*</b>
                                </label>
                                <input type="number" class="class-number form-control m-input" id="template_width" name="template_width"
                                       placeholder="@lang('Chiều rộng giấy in')">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label for="">{{__('Đặt làm mặc định')}}:</label>
                                <div class="input-group row">
                                <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_default"
                                                   type="checkbox" class="manager-btn" name="is_default">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                    <div class="col-lg-5 m--margin-top-5 m--margin-left-10">
                                        <i>{{__('Chọn để kích hoạt')}}</i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label for="">{{__('Trạng thái')}}:</label>
                                <div class="input-group row">
                                    <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_actived"
                                                   checked
                                                   type="checkbox" class="manager-btn" name="is_actived">
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                    <div class="col-lg-5 m--margin-top-5 m--margin-left-10">
                                        <i>{{__('Chọn để kích hoạt')}}</i>
                                    </div>
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
                    <button type="button" onclick="create.save()"
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