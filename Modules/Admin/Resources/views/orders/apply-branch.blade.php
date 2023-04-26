<div id="modal-apply-branch" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <form id="form-apply-branch">
                <div class="modal-header">
                    <h4 class="modal-title title_index">
                        <i class="la la-bank"></i> {{__('CHUYỂN TIẾP CHI NHÁNH')}}
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{__('Chi nhánh')}}:<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class="form-control" style="width: 100%" id="branch_id" name="branch_id">
                                <option></option>
                                @foreach($optionBranch as $k => $v)
                                    <option value="{{$k}}" {{$item['branch_id'] == $k ? 'selected' : ''}}>{{$v}}</option>
                                @endforeach
                            </select>
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
                            <button type="button" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-active m--margin-left-10"
                                    onclick="list.submit_apply_branch({{$order_id}})">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHUYỂN TIẾP')}}</span>
							</span>
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
