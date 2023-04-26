<div class="m-form m-form--label-align-right">
    <div class="row">
        <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
            <label for="">
                {{__('Chọn chi nhánh')}}
            </label>
            <select name="branch" style="width: 100%" id="branch"
                    class="form-control">
                @foreach($optionBranch as $key => $value)
                    <option value="{{$value['branch_id']}}"
                            {{($value['branch_id'] == $getConfig['value']) ? 'selected': ''}}>
                        {{$value['branch_name']}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-group m-form__group m--margin-top-10">
    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
        <div class="m-form__actions m--align-right">
            <button type="button"
                    class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_save m--margin-left-10"
                    onclick="GetList.saveInventoryConfig()">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT')}}</span>
							</span>
            </button>
        </div>
    </div>
</div>

