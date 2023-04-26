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
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA PHÒNG PHỤC VỤ')}}
                </h4>
            </div>
            <form id="formEdit">
                <div class="modal-body">
                    <input type="hidden" id="hhidden">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Tên phòng')}}:<b class="text-danger">*</b>
                                </label>
                                {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                                <input placeholder="{{__('Nhập tên phòng')}}" type="text" name="name" class="form-control m-input btn-sm"
                                       id="h_name">

                                <span class="h_error-name" id="baoloi"></span>
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Trạng thái')}}:
                                </label>
                                <div class="row">
                                    <div class="col-lg-2">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="h_is_actived" name="is_actived" type="checkbox">
                        <span></span>
                    </label>
                </span>
                                    </div>
                                    <div class="col-lg-8 m--margin-top-5">
                                        <i>{{__('Chọn để kích hoạt trạng thái')}}</i>
                                    </div>
                                </div>
                                {{--<div>--}}
                                {{--<label class="m-checkbox">--}}
                                {{--<input type="checkbox" name="is_actived" id="h_is_actived" value="1"> Hoạt động--}}
                                {{--<span></span>--}}
                                {{--</label>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Số ghế phục vụ')}}:<b class="text-danger">*</b>
                                </label>
                                {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                                <input placeholder="Nhập số ghế" type="number" name="seat" class="form-control m-input btn-sm"
                                       id="h_seat">
                            </div>
                        </div>
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


                            <button id="btnLuu"
                                    class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
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
