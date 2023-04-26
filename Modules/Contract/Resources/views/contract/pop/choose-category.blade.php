<div class="modal fade show" id="modal-category" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỌN LOẠI HỢP ĐỒNG')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-category">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Loại hợp đồng'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class="form-control" id="category_choose" name="category_choose" style="width:100%;">
                                <option></option>
                                @foreach($optionCategory as $item)
                                    @if($item['type'] == 'sell')
                                        <option value="{{$item['contract_category_id']}}" selected>{{$item['contract_category_name']}}</option>
                                    @else
                                        @if($type != 'from_deal')
                                        <option value="{{$item['contract_category_id']}}">{{$item['contract_category_name']}}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                            <input type="hidden" id="type" name="type" value="{{$type}}">
                            <input type="hidden" id="deal_code" name="deal_code" value="{{$dealCode}}">
                            <input type="hidden" id="customer_id" name="customer_id" value="{{$dataCustomer != null ? $dataCustomer['customer_id'] : ''}}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <a href="{{route('contract.contract')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="view.chooseCategory()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('CHỌN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
