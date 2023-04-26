<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM CÔNG THỨC QUY ĐỔI')}}
                </h4>
            </div>
            <form id="form">
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            {{__('Đơn vị cần quy đổi')}}:<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select style="width: 100%" name="unit_id" id="h_unit_id" class="form-control  m-input">
                                <option></option>
                                @foreach($unit as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Đơn vị gốc')}}:
                        </label>
                        <div class="input-group">
                            <select style="width: 100%" name="unit_standard" class="form-control " id="h_unit_standard">
                                <option></option>
                                @foreach($unit as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Tỉ lệ chuyển đổi')}}:<b class="text-danger">*</b>
                        </label>
                        <input onkeydown="onKeyDownInput(this)" type="number" name="conversion_rate"
                               class="form-control m-input" id="h_conversion_rate">
                    </div>
                </div>
                <input type="hidden" name="type_add" id="type_add" value="0">
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>


                            <button type="button" onclick="unit_conversion.add(1)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>

                            <button type="button" onclick="unit_conversion.add(0)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="fa fa-plus-circle"></i>
							<span>{{__('Lưu & TẠO MỚI')}}</span>
							</span>
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
