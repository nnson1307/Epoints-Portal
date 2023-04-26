<style>
    .modal-custom {
        max-width: 80%;
        margin: 0 auto;
    }
</style>
<div class="modal fade" id="modal_add" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{__('Thêm sản phẩm')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <select style="width: 100%"
                            name="1"
                            id=""
                            class="form-control ss--select-2 col-lg-6"
                            onchange="productChild.selectedProductChild('{{$type_tab}}', this)">
                        <option value="">{{__('Chọn sản phẩm')}}</option>
                        @foreach($option as $item)
                            <option value="{{$item['product_child_id']}}">
                                {{$item['product_child_name']}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <div class="table-responsive">
                        <table id="table-product" class="table table-striped m-table ss--header-table">
                            <thead>
                            <tr>
                                <th class="ss--font-size-th ss--text-center">#</th>
                                <th class="ss--font-size-th ss--nowrap">{{__('SẢN PHẨM')}}</th>
                                <th class="ss--font-size-th ss--nowrap ss--text-center">{{__('GIÁ')}}</th>
                                <th class="ss--font-size-th ss--nowrap ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
                                <th class="ss--font-size-th ss--nowrap ss--text-center">{{__('GIÁ NHẬP')}}</th>
                                @if($type_tab == 'sale')
                                    <th class="ss--font-size-th ss--nowrap ss--text-center">% {{__('GIẢM GIÁ')}}</th>
                                @endif
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="tbody-table-product">
                            {{--<tr>--}}
                            {{--<td class="ss--text-center">	1</td>--}}
                            {{--<td>	Face mụn/200ml 	Face mụn/200ml</td>--}}
                            {{--<td class="ss--text-center">100,000,000</td>--}}
                            {{--<td class="ss--text-center">Chai</td>--}}
                            {{--<td class="ss--text-center">100,000,000</td>--}}
                            {{--<td>--}}
                            {{--<button onclick="productChild.removeTr(this)"--}}
                            {{--class="ss--margin-top--8px m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"--}}
                            {{--title="Xóa">--}}
                            {{--<i class="la la-trash"></i>--}}
                            {{--</button>--}}
                            {{--</td>--}}
                            {{--</tr>--}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
                <button onclick="productChild.submitAdd('{{$type_tab}}')"
                        class="btn btn-info color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
                </button>
            </div>
        </div>
    </div>
</div>