<div class="row">
    <div class="col-lg-3">
        <label>
            {{ __('Nội dung') }}: <b class="text-danger">*</b>
        </label>
    </div>
    <div class="col-lg-9">
        <div class="form-group m-form__group">
                        <textarea name="preview" rows="5" cols="40"
                                  class="form-control m-input preview-class" maxlength="480">{{isset($item->preview)?$item->preview:''}}</textarea>
            <i class="pull-right">{{ __('Số ký tự') }}: <i
                        class="count-character">0</i>{{ __('/480 ký tự') }}</i>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <label>
            {{ __('Danh sách tham số') }}:
        </label>
    </div>
    <div class="col-lg-9">
        <div class="d-flex">
            @foreach ($param_list as $key => $value)
                <button type="button" class="mr-3 p-3 text-black-50 bg-secondary text-params-coppy" data-value="<{{$key}}>"><i
                            class="fa fa-clone mr-2"></i>{{ $value }}</button>
            @endforeach
        </div>
    </div>
</div>