<div id="add" class="modal fade" role="dialog">
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
                    <i class="fa fa-plus-circle"></i> {{__('THÊM PHÒNG PHỤC VỤ')}}
                </h4>

            </div>
            <form id="form">
                <div class="modal-body">

                    {{--{!! csrf_field() !!}--}}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Tên phòng')}}:<b class="text-danger">*</b>
                                </label>
                                {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                                <input type="text" name="name" class="form-control m-input btn-sm" id="name"
                                       placeholder="{{__('Nhập tên phòng')}}">
                                <span class="error-name"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Số ghế phục vụ')}}:<b class="text-danger">*</b>
                                </label>
                                {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                                <input onkeydown="onKeyDownInput(this)" type="text" name="seat"
                                       class="form-control m-input btn-sm" id="seat"
                                       placeholder="{{__('Nhập số ghế')}}">
                                <span class="error-seat"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: none;">
                        <div class="form-group row col-12">
                            <label class="col-sm-4">
                                {{__('Trạng thái')}}
                            </label>
                            <div class="col-lg-8">
                                {{--<select name="is_actived" class="form-control" id="is_actived">--}}
                                {{--<option value="1">Hoạt động</option>--}}
                                {{--<option value="0">Tạm ngưng</option>--}}
                                {{--</select>--}}
                                <label class="m-checkbox">
                                    <input type="checkbox" checked name="is_actived" id="is_actived" value="1"> 
                                    {{__('Hoạt động')}}
                                    <span></span>
                                </label>
                            </div>
                        </div>
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

                            <button type="submit" id="luu" onclick="room.add(1)"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                            </button>

                            <button type="submit" id="luu" onclick="room.add(0)"
                                    class="btn btn-success color_button son-mb   m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="fa fa-plus-circle"></i>
							<span>{{__('LƯU & TẠO MỚI')}}</span>
							</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
