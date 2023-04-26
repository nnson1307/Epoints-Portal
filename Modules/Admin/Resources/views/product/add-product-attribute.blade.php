<div class="form-group m-form__group m--margin-bottom-20">
    <div class="input-group row new-attribute-version" id="new-attribute-version">
        <div class="col-lg-3">
            <select style="width: 100%" name="selectAttrGr[]" class="form-control">
                <option value="">{{__('Nhóm thuộc tính')}}</option>
                @foreach($productAttributeGroup as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
        {{-- <div class="col-lg-3">
            <div class="input-group">
                <input id="product_sku" name="product_sku" type="text" class="form-control m-input class" value="{{ $sku }}"
                       placeholder="{{__('Sku sản phẩm')}}"
                       aria-describedby="basic-addon1">
            </div>
            <span class="errs error-product-sku" style="color: rgb(255, 0, 0);"></span>
        </div> --}}
        <div class="col-lg-9">
            <div class="class-procuct-attibute">
                <select style="width: 100%" class="form-control" disabled name="sProducAttribute[]" multiple="multiple">
                </select>
            </div>
        </div>
    </div>
</div>
<span style="color: red;" class="errs-attribute"></span>
</div>


{{--<div class="row m--margin-bottom-20 new-attribute-version" id="new-attribute-version">--}}
{{--<div class="col-lg-2">--}}
{{--<select name="selectAttrGr[]" class="form-control">--}}
{{--<option value="">Chọn nhóm thuộc tính</option>--}}
{{--@foreach($productAttributeGroup as $key=>$value)--}}
{{--<option value="{{$key}}">{{$value}}</option>--}}
{{--@endforeach--}}
{{--</select>--}}
{{--</div>--}}
{{--<div class="col-lg-8 class-procuct-attibute">--}}
{{--<select class="form-control col-lg-12" name="sProducAttribute[]" multiple="multiple">--}}
{{--</select>--}}
{{--</div>--}}
{{--</div>--}}

<script src="{{asset('static/backend/js/admin/product/add-version.js?v='.time())}}" type="text/javascript"></script>



