<div id="editForm" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA CÔNG THỨC QUY ĐỔI')}}
                </h4>
            </div>
            <form id="formEdit">
                <div class="modal-body">
                    <input type="hidden" id="hhidden">
                    <div class="form-group">
                        <label>
                            {{__('Đơn vị cần quy đổi')}}:<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select style="width: 100%" name="unit_id" id="b_unit_id" class="form-control m-input ">
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
                            <select style="width: 100%" name="unit_standard" class="form-control " id="unit_standard">
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
                        <input type="number" name="conversion_rate" class="form-control m-input"
                               id="conversion_rate">
                        @if ($errors->has('conversion_rate'))
                            <span class="form-control-feedback">
                                     {{ $errors->first('conversion_rate') }}
                                </span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                            </button>

                            <button type="button    " id="btnLuu"
                                    class="btn btn-primary color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                    <span>
                                    <i class="la la-edit"></i>
                                    <span>{{__('CẬP NHẬT')}}</span>
                                    </span>
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
