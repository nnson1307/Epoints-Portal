<div class="ss--background">
    <div class="row ss--bao-filter pb-3">
        <div class="col-lg-12 mb-3">
            <button href="javascript:void(0)" onclick="productChild.addConditionSuggest()"
                class="btn ss--btn-search ss--float-right">
                        <span class="m--margin-right-10 m--margin-left-15 m--margin-right-15">
                            <i class="fa fa-plus ss--icon-plus pr-3"></i>
                            {{__('Thêm điều kiện')}}
                        </span>
            </button>
        </div>
        <div class="col-12">
            <div class="row product_suggest_condition">
                @if(count($getListProductSuggestConfig['listConfig']) != 0)
                    @foreach($getListProductSuggestConfig['listConfig'] as $key => $itemConfig)
                        <div class="col-12 mb-3 block-suggest block-suggest-{{$key}}">
                            <div class="row">
                                <input type="hidden" class="suggest_id" name="suggest[{{$key}}][id]" id="suggest_id_{{$key}}" value="{{$key}}">
                                <div class="col-2">
                                    <select class="form-control select2-suggest suggest_type" name="suggest[{{$key}}][type]" id="suggest_type_{{$key}}">
                                        <option value="product">{{__('Sản phẩm')}}</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control select2-suggest suggest_is_condition" name="suggest[{{$key}}][is_condition]" id="suggest_is_condition_{{$key}}">
                                        <option value="1" {{$itemConfig['is_condition'] == 1 ? 'selected' : ''}}>{{__('Là')}}</option>
                                        <option value="0" {{$itemConfig['is_condition'] == 0 ? 'selected' : ''}}>{{__('Không là')}}</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control select2-suggest suggest_product_condition_id" name="suggest[{{$key}}][product_condition_id]" id="suggest_product_condition_id_{{$key}}" onchange="productChild.changeCondition({{$key}})">
                                        @foreach($getListCondition as $item)
                                            <option value="{{$item['product_condition_id']}}" {{$itemConfig['product_condition_id'] == $item['product_condition_id'] ? 'selected' : ''}} data-option="{{$item['type']}}" data-key="{{$item['key']}}">{{$item['product_condition_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-6 block-number {{!in_array($itemConfig['type_condition'],['number','number_date']) ? 'd-none' : ''}}">
                                            <input type="text" class="form-control suggest_quantity" value="{{$itemConfig['quantity']}}" placeholder="{{__('Nhập số lượng')}}" name="suggest[{{$key}}][quantity]" id="suggest_quantity_{{$key}}">
                                        </div>
                                        <div class="col-6 block-date {{!in_array($itemConfig['type_condition'],['number_date']) ? 'd-none' : ''}}">
                                            <input type="text" class="form-control daterange-picker suggest_date_range" value="{{$itemConfig['start_date'] != null ? \Carbon\Carbon::parse($itemConfig['start_date'])->format('d/m/Y').' - '.\Carbon\Carbon::parse($itemConfig['end_date'])->format('d/m/Y'): ''}}" name="suggest[{{$key}}][date_range]" id="suggest_date_range_{{$key}}" placeholder="{{__('Chọn khoảng thời gian')}}">
                                        </div>
                                        <div class="col-12 block-tags {{!in_array($itemConfig['type_condition'],['tags']) ? 'd-none' : ''}}">
                                            <select class="form-control select2-suggest suggest_tags" multiple="multiple" name="suggest[{{$key}}][tags]" id="suggest_tags_{{$key}}" data-placeholder="Chọn tags sản phẩm">
                                                @foreach($getListTags as $item)
                                                    <option value="{{$item['product_tag_id']}}" {{isset($getListProductSuggestConfig['listConfigMap'][$itemConfig['product_suggest_config_id']]) && in_array($item['product_tag_id'],$getListProductSuggestConfig['listConfigMap'][$itemConfig['product_suggest_config_id']]) ? 'selected' : ''}}>{{$item['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <button href="javascript:void(0)" onclick="productChild.removeCondition({{$key}})"
                                            class="btn btn-danger ss--float-left">
                                <span class="m--margin-right-10 m--margin-left-15 m--margin-right-15">
                                    <i class="fa fa-times ss--icon-plus pr-3"></i>
                                    {{__('Xoá')}}
                                </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 mb-3 block-suggest block-suggest-0">
                        <div class="row">
                            <input type="hidden" class="suggest_id" name="suggest[0][id]" id="suggest_id_0" value="0">
                            <div class="col-2">
                                <select class="form-control select2-suggest suggest_type" name="suggest[0][type]" id="suggest_type_0">
                                    <option value="product">{{__('Sản phẩm')}}</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <select class="form-control select2-suggest suggest_is_condition" name="suggest[0][is_condition]" id="suggest_is_condition_0">
                                    <option value="1">{{__('Là')}}</option>
                                    <option value="0">{{__('Không là')}}</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <select class="form-control select2-suggest suggest_product_condition_id" name="suggest[0][product_condition_id]" id="suggest_product_condition_id_0" onchange="productChild.changeCondition(0)">
                                    @foreach($getListCondition as $item)
                                        <option value="{{$item['product_condition_id']}}" data-option="{{$item['type']}}" data-key="{{$item['key']}}">{{$item['product_condition_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 block-number">
                                <input type="text" class="form-control suggest_quantity" placeholder="{{__('Nhập số lượng')}}" name="suggest[0][quantity]" id="suggest_quantity_0">
                            </div>
                            <div class="col-2 block-date">
                                <input type="text" class="form-control daterange-picker suggest_date_range" name="suggest[0][date_range]" id="suggest_date_range_0" placeholder="{{__('Chọn khoảng thời gian')}}">
                            </div>
                            <div class="col-4 block-tags d-none">
                                <select class="form-control select2-suggest suggest_tags" multiple="multiple" name="suggest[0][tags]" id="suggest_tags_0" data-placeholder="Chọn tags sản phẩm">
                                    @foreach($getListTags as $item)
                                        <option value="{{$item['product_tag_id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <button href="javascript:void(0)" onclick="productChild.removeCondition(0)"
                                        class="btn btn-danger ss--float-left">
                                <span class="m--margin-right-10 m--margin-left-15 m--margin-right-15">
                                    <i class="fa fa-times ss--icon-plus pr-3"></i>
                                    {{__('Xoá')}}
                                </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal-footer save-attribute pr-0">
    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
        <div class="m-form__actions m--align-right">
            <a href="{{route('admin.product')}}"
               class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                <span class="ss--text-btn-mobi">
                    <i class="la la-arrow-left"></i>
                    <span>{{__('HỦY')}}</span>
                </span>
            </a>
            <button onclick="productChild.addConditionSuggestConfig()" type="button"
                    class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                <span class="ss--text-btn-mobi">
                    <i class="la la-check"></i>
                    <span>{{__('LƯU THÔNG TIN')}}</span>
                </span>
            </button>
        </div>
    </div>
</div>