<div id="add-group" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa flaticon-plus m--margin-right-5"></i>
                    {{__('Thêm Nhóm thẻ')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>
                        {{__('Tên nhóm')}}: <span class="required">*</span>
                    </label>
                    {{--{!! Form::text('branch_name',null,['class' => 'form-control m-input','id'=>'name']) !!}--}}
                    <input type="text" name="name-group" class="form-control m-input" id="name-group"
                           autocomplete="off">
                    <span class="err error-name"></span>
                    @if ($errors->has('branch_name'))
                        <span class="form-control-feedback">
                                     {{ $errors->first('branch_name') }}
                                </span>
                        <br>
                    @endif

                </div>
                <div class="form-group">
                    <label>
                        {{__('Ghi chú')}}:
                    </label>
                    <input type="text" name="description-group" autocomplete="off" class="form-control m-input"
                           id="description-group">
                    @if ($errors->has('address'))
                        <span class="form-control-feedback">
                                     {{ $errors->first('address') }}
                                </span>
                        <br>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-secondary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('Hủy')}}</span>
						</span>
                        </button>

                        <div class="btn-group">
                            <button onclick="ServiceCard.addGroupService(this)"
                                    class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Lưu lại')}}</span>
							</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

