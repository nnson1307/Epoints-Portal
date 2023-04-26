<div class="modal fade show" id="modal-customer-branch" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM CHI NHÁNH ĐƯỢC XEM')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-customer-branch">
                    <div class="form-group m-form__group ">
                        <label>{{__('Chi nhánh')}}:</label>
                        <div class="input-group m-input-group m-input-group--solid">
                            <select class="form-control" id="customer_branch_id" name="customer_branch_id" style="width:100%;" multiple>
                                <option></option>
                                @foreach($optionBranch as $v)
                                    <option value="{{$v['branch_id']}}"
                                            {{in_array($v['branch_id'], $arrBranchCustomer) ? 'selected' : ''}}>{{$v['branch_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </button>

                        <button class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md
                            m--margin-left-10" onclick="customer.saveCustomerBranch({{$customer_id}})">
							<span>
							<i class="la la-check"></i>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>