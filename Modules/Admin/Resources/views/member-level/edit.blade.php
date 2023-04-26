<div id="editForm" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA CẤP ĐỘ THÀNH VIÊN')}}
                </h4>
            </div>
            <form id="formEdit">
                <div class="modal-body">
                    <input type="hidden" id="hhidden">
                    <div class="form-group">
                        <label>
                            {{__('Cấp độ')}}:<b class="text-danger">*</b>
                        </label>
                        {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                        <input disabled type="text" name="name" class="form-control m-input btn-sm" id="h_name">
                        <span class="error-name"></span>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Điểm quy đổi')}}:<b class="text-danger">*</b>
                        </label>
                        {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                        <input type="text" name="point" class="form-control m-input btn-sm" id="h_point">
                        @if ($errors->has('seat'))
                            <span class="form-control-feedback">
                                     {{ $errors->first('point') }}
                                </span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Giảm')}}:<b class="text-danger">*</b> (%)
                        </label>
                        {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                        <input type="text" name="discount" class="form-control m-input btn-sm" id="discount">
                        @if ($errors->has('seat'))
                            <span class="form-control-feedback">
                                     {{ $errors->first('point') }}
                                </span>
                            <br>
                        @endif
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-2">
                            <label>
                                {{__('Trạng thái')}}:
                            </label>
                        </div>
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-lg-1">
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label>
                                                <input id="h_is_actived" name="is_actived" type="checkbox">
                                                <span></span>
                                            </label>
                                        </span>
                                </div>
                                <div class="col-lg-4 m--margin-top-5">
                                    <i>{{__('Select to activate status')}}</i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            {{__('Quyền lợi')}}:<b class="text-danger">*</b>
                        </label>
                        <textarea class="form-control description" cols="50" rows="10" id="description"
                                  name="description"></textarea>
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

                            <button type="button" id="btnLuu"
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
