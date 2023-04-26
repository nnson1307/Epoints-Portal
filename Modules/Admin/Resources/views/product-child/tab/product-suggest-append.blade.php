<div class="col-12 mb-3 block-suggest block-suggest-{{$data['number']}}">
    <div class="row">
        <input type="hidden" class="suggest_id" name="suggest[{{$data['number']}}][id]" id="suggest_id_{{$data['number']}}" value="{{$data['number']}}">
        <div class="col-2">
            <select class="form-control select2-suggest suggest_type" name="suggest[{{$data['number']}}][type]" id="suggest_type_{{$data['number']}}">
                <option value="product">{{__('Sản phẩm')}}</option>
            </select>
        </div>
        <div class="col-2">
            <select class="form-control select2-suggest suggest_is_condition" name="suggest[{{$data['number']}}][is_condition]" id="suggest_is_condition_{{$data['number']}}">
                <option value="1">{{__('Là')}}</option>
                <option value="0">{{__('Không là')}}</option>
            </select>
        </div>
        <div class="col-2">
            <select class="form-control select2-suggest suggest_product_condition_id" name="suggest[{{$data['number']}}][product_condition_id]" id="suggest_product_condition_id_{{$data['number']}}" onchange="productChild.changeCondition({{$data['number']}})">
                @foreach($getListCondition as $item)
                    <option value="{{$item['product_condition_id']}}" data-option="{{$item['type']}}" data-key="{{$item['key']}}">{{$item['product_condition_name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-4">
            <div class="row">
                <div class="col-6 block-number">
                    <input type="text" class="form-control suggest_quantity" placeholder="{{__('Nhập số lượng')}}" name="suggest[{{$data['number']}}][quantity]" id="suggest_quantity_{{$data['number']}}">
                </div>
                <div class="col-6 block-date">
                    <input type="text" class="form-control daterange-picker suggest_date_range" name="suggest[{{$data['number']}}][date_range]" id="suggest_date_range_{{$data['number']}}" placeholder="{{__('Chọn khoảng thời gian')}}">
                </div>
                <div class="col-12 block-tags d-none">
                    <select class="form-control select2-suggest suggest_tags" multiple="multiple" name="suggest[{{$data['number']}}][tags]" id="suggest_tags_{{$data['number']}}" data-placeholder="Chọn tags sản phẩm">
                        @foreach($getListTags as $item)
                            <option value="{{$item['product_tag_id']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-2">
            <button href="javascript:void(0)" onclick="productChild.removeCondition({{$data['number']}})"
                class="btn btn-danger ss--float-left">
                <span class="m--margin-right-10 m--margin-left-15 m--margin-right-15">
                    <i class="fa fa-times ss--icon-plus pr-3"></i>
                    {{__('Xoá')}}
                </span>
            </button>
        </div>
    </div>
</div>