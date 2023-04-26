
<div class="form-group m-form__group row text-center">
    <div class="col-12">
        <object type="image/svg+xml" style="pointer-events: none;display: block; margin: auto;" data="{{asset('static/backend/images/icon-no-result.svg')}}"></object>
    </div>
    
    
    <label for="example-password-input" class="col-12 col-form-label">
        @lang('Không tìm thấy kết quả của từ khóa') <b>"{{ $keyWork}}"</b>
    </label>
</div>
<div class="form-group m-form__group row text-center">
    <div class="col-12">
        <button type="button" onclick="callCenter.showModalCustomerInfo('','','{{ $keyWork}}');"
                        class="btn color_button m-btn--icon m--margin-left-10">
                        <span>
                        <span>{{__('NHẬP THÔNG TIN')}}</span>
                        <i class="fa fa-plus-circle" style="padding-left: 5px;"></i>
                        </span>
                </button>
    </div>
</div>