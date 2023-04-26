<div class="table-responsive">
    <div class="m-scrollable m-scroller ps ps--active-y ss--table-scroll-vertical" data-scrollable="true"
         style="height: 250px">
        <table class="table table-striped m-table ss--header-table" id="table_branch">
            <thead>
            <tr class="ss--font-size-th ss--nowrap">
                <th># </th>
                <th>{{__('PHIÊN BẢN')}}</th>
                <th class="ss--text-center">{{__('GIÁ CHUẨN')}}</th>
                <th class="ss--text-center">{{__('GIÁ CHI NHÁNH')}}</th>
                <th>
                    <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success pull-right m--margin-bottom-20">
                        <input id="check_all_branch" name="check_all_branch" checked
                               type="checkbox">
                        <span></span>
                    </label>
                </th>
            </tr>
            </thead>
            <tbody>
            @if (isset($LIST) && $LIST->count())
                @foreach($LIST as $key => $value)
                    <tr class="branch_tb">
                        <td>{{$key+1}}</td>
                        <td class="ss--width-150">{{$value['product_child_name']}}<input type="hidden" name="id_product[]"
                                                                   value="{{$value['product_id']}}">
                        </td>
                        <td class="ss--text-center ss--nowrap">
                            {{$value['price']}}
                            <input type="hidden" value="{{$value['price']}}">
                        </td>
                        <td class="ss--text-center ss--width-max-width-200">
                            <input class="new form-control m-input price_branch_{{$value['product_id']}}
                                    ss--btn-ct ss--text-center"
                                   name="new_price"
                                   value="0">
                        </td>
                        <td>
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid">
                                <input class="check check_branch_{{ $value['product_id'] }}"
                                       {{ ($value['is_actived'] == 1) ? 'checked' : '' }} name="check_branch[]"
                                       type="checkbox">
                                <span></span>
                            </label>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="branch_tb">
                    <td align="center" colspan="8">{{__('Chưa có dữ liệu')}}.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>