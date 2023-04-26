<!-- Modal -->
<div class="modal fade" id="select-topping" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-select-attribute">
                <div class="modal-header">
                    <h2 class="modal-title" id="title">{{__('GHI CHÚ / MÓN THÊM')}}</h2>
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
                </div>
                <div class="modal-body pb-2">
                    <div class="col-12 scroll-topping">
                        <div class="row">
                            @foreach($listAttribute as $key => $item)
                                <div class="col-12 mb-3">
                                    <span class="note-font">{{__('Chọn')}} {{$item[0][getValueByLang('product_attribute_group_name_')]}}:</span>
                                </div>
                                <div class="col-12">
                                    @foreach($item as $keyValue => $value)
                                        <label class="row" for="product_attribute_{{$value['product_attribute_id']}}">
                                            <div class="col-6">
                                                <label for="product_attribute_{{$value['product_attribute_id']}}"><span class="text-capitalize">{{$item[0][getValueByLang('product_attribute_group_name_')]}}</span> {{$value[getValueByLang('product_attribute_label_')]}}</label>
                                            </div>
                                            <div class="col-3">
                                                <div class="price_size">
                                                    <?php $total = 0 ?>

                                                    <input type="hidden" name="price_attribute[{{$value['product_attribute_id']}}]" value="{{$total}}">
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="choose-size">
                                                    <input type="radio" id="product_attribute_{{$value['product_attribute_id']}}" name="product_attribute_id[{{$key}}]" {{in_array($value['product_attribute_id'],$arrSelectAttribute) ? 'checked' : ''}} value="{{$value['product_attribute_id']}}" onchange="order.changeToppingSelect(`{{$product['product_child_id']}}`,`{{$row}}`)"><label for="product_attribute_{{$value['product_attribute_id']}}"></label><br>
                                                    <input type="hidden" name="product_attribute_name[{{$value['product_attribute_id']}}]" value="{{$item[0][getValueByLang('product_attribute_group_name_')].' '.$value[getValueByLang('product_attribute_label_')]}}">
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        @if(count($listTopping) != 0)
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <span class="note-font">Topping:</span>
                                    </div>
                                    <div class="topping">
                                        @foreach($listTopping as $item)
                                            <label for="topp_{{$item['product_child_id']}}" class="col-12">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="topp_{{$item['product_child_id']}}">{{$item[getValueByLang('product_child_name_')]}}</label>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="price_size">
                                                            <label for="topp_{{$item['product_child_id']}}">
                                                                @if($item['price'] == 0)
                                                                    {{__('Miễn phí')}}
                                                                @else
                                                                    +{{number_format($item['price'])}}
                                                                @endif
                                                            </label>
                                                            <input type="hidden" name="price_topping[{{$item['product_child_id']}}]" value="{{$item['price']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="choose-size">
                                                            <input type="checkbox" id="topp_{{$item['product_child_id']}}" {{in_array($item['product_child_id'],$topping) ? 'checked' : ''}} name="topping[]" onchange="order.changeToppingSelect(`{{$product['product_child_id']}}`,`{{$row}}`)" value="{{$item['product_child_id']}}"><label for="topp_{{$item['product_child_id']}}"></label><br>
                                                            <input type="hidden"  name="topping_name[{{$item['product_child_id']}}]" value="{{$item[getValueByLang('product_child_name_')]}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <span class="note-font">Ghi chú:</span>
                                </div>
                                <div class="note-plus">
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-12">
                                            <div class="form-group m-form__group" style="margin-bottom: 5px">
                                                <div class="input-group">
                                                    <input id="note_topping" name="note_topping" type="text"
                                                           class="form-control m-input class"
                                                           placeholder='Nhập nội dung ghi chú'
                                                           value="{{$note}}"
                                                           aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between mt-3">
                            <div class="mb-1">
                                <span class="note-font">{{__('Tổng tiền')}}:</span>
                            </div>
                            <div class="note-plus">
                                <span class="total-money-tmp">0</span>đ
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="product_topping_select" value="{{$product['product_id']}}">
                <input type="hidden" name="product_child_topping_select" value="{{$product['product_child_id']}}">
                <div class="modal-footer">
                    @if($removeProduct == 'true')
                        <button type="button" class="btn btn-secondary btn-close-popup-topping" data-dismiss="modal" onclick="order.closePopupTopping(`{{$product['product_child_id']}}`,`{{$row}}`)">
                            <span class="la la-arrow-left"></span>
                            {{__('HỦY')}}
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <span class="la la-arrow-left"></span>
                            {{__('HỦY')}}
                        </button>
                    @endif

                    <button type="button" class="btn btn-primary btn-save-topping-select" onclick="order.saveToppingSelect(`{{$product['product_child_id']}}`,`{{$row}}`)">
                        <span class="la la-check"></span>
                        {{__('LƯU THÔNG TIN')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>