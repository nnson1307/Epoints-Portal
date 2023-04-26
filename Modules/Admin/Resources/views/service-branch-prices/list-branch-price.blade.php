<div class="table-responsive">
    <div class="m-scrollable m-scroller ps ps--active-y ss--table-scroll-vertical" data-scrollable="true" style="height: 400px">
        <table class="table table-striped m-table ss--header-table ss--nowrap" id="table_branch">
            <thead>
            <tr class="ss--font-size-th">
                <th class="ss--width-50">#</th>
                <th>{{__('DỊCH VỤ')}}</th>
                <th class="ss--text-center">{{__('GIÁ CHUẨN')}}</th>
                <th></th>
                <th></th>
                <th class="ss--text-center">{{__('GIÁ CHI NHÁNH')}}</th>
                <th></th>
                <th></th>
                @if(session()->get('brand_code') == 'giakhang')
                    <th>{{__('GIÁ TUẦN')}}</th>
                    <th>{{__('GIÁ THÁNG')}}</th>
                    <th>{{__('GIÁ NĂM')}}</th>
                @else
                    <th hidden>{{__('GIÁ TUẦN')}}</th>
                    <th hidden>{{__('GIÁ THÁNG')}}</th>
                    <th hidden>{{__('GIÁ NĂM')}}</th>
                @endif
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
                        <td>{{$value['service_name']}}<input type="hidden" name="id_service[]"
                                                             value="{{$value['service_id']}}">
                        </td>
                        <td class="ss--text-center">
                            {{--{{$value['price_standard']}}--}}
                            {{number_format($value['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            <input type="hidden" value="{{$value['price_standard']}}">
                        </td>
                        <td></td>
                        <td></td>
                        <td class="ss--text-center ss--width-150">
                            <input class="new form-control m-input ss--btn-ct ss--text-center" name="new_price"
                                   id="{{$value['service_id']}}" value="{{isset($PRICE_LIST) && $PRICE_LIST->where('service_id',$value['service_id'])->first() != null ? $PRICE_LIST->where('service_id',$value['service_id'])->first()->toArray()['new_price'] : 0}}">
                        </td>
                        <td></td>
                        <td></td>

                        @if(session()->get('brand_code') == 'giakhang')
                            <td class="ss--text-center ss--width-150">
                                <input class="new form-control m-input ss--btn-ct ss--text-center" name="new_price"
                                       id="price_week_{{$value['service_id']}}" value="{{isset($PRICE_LIST) && $PRICE_LIST->where('service_id',$value['service_id'])->first() != null ? $PRICE_LIST->where('service_id',$value['service_id'])->first()->toArray()['price_week'] : 0}}">
                            </td>
                            <td class="ss--text-center ss--width-150">
                                <input class="new form-control m-input ss--btn-ct ss--text-center" name="new_price"
                                       id="price_month_{{$value['service_id']}}" value="{{isset($PRICE_LIST) && $PRICE_LIST->where('service_id',$value['service_id'])->first() != null ? $PRICE_LIST->where('service_id',$value['service_id'])->first()->toArray()['price_month'] : 0}}">
                            </td>
                            <td class="ss--text-center ss--width-150">
                                <input class="new form-control m-input ss--btn-ct ss--text-center" name="new_price"
                                       id="price_year_{{$value['service_id']}}" value="{{isset($PRICE_LIST) && $PRICE_LIST->where('service_id',$value['service_id'])->first() != null ? $PRICE_LIST->where('service_id',$value['service_id'])->first()->toArray()['price_year'] : 0}}">
                            </td>
                        @else
                            <td class="ss--text-center ss--width-150" hidden>
                                <input class="new form-control m-input ss--btn-ct ss--text-center" name="new_price"
                                       id="price_week_{{$value['service_id']}}" value="{{isset($PRICE_LIST) && $PRICE_LIST->where('service_id',$value['service_id'])->first() != null ? $PRICE_LIST->where('service_id',$value['service_id'])->first()->toArray()['price_week'] : 0}}">
                            </td>
                            <td class="ss--text-center ss--width-150" hidden>
                                <input class="new form-control m-input ss--btn-ct ss--text-center" name="new_price"
                                       id="price_month_{{$value['service_id']}}" value="{{isset($PRICE_LIST) && $PRICE_LIST->where('service_id',$value['service_id'])->first() != null ? $PRICE_LIST->where('service_id',$value['service_id'])->first()->toArray()['price_month'] : 0}}">
                            </td>
                            <td class="ss--text-center ss--width-150" hidden>
                                <input class="new form-control m-input ss--btn-ct ss--text-center" name="new_price"
                                       id="price_year_{{$value['service_id']}}" value="{{isset($PRICE_LIST) && $PRICE_LIST->where('service_id',$value['service_id'])->first() != null ? $PRICE_LIST->where('service_id',$value['service_id'])->first()->toArray()['price_year'] : 0}}">
                            </td>
                        @endif
                        <td>
                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success m-checkbox--solid pull-right m--margin-top-5">
                                @if(isset($PRICE_LIST) && $PRICE_LIST->where('service_id',$value['service_id'])->first() != null)
                                    @if($PRICE_LIST->where('service_id',$value['service_id'])->first()->toArray()['is_actived'] == 1)
                                        <input class="check" checked
                                               {{ ($value['is_actived'] == 1) ? 'checked' : '' }} id="check_branch_{{ $value['service_id'] }}"
                                               name="check_branch[]"
                                               type="checkbox">
                                        <span></span>
                                    @else
                                        <input class="check"
                                               {{ ($value['is_actived'] == 1) ? 'checked' : '' }} id="check_branch_{{ $value['service_id'] }}"
                                               name="check_branch[]"
                                               type="checkbox">
                                        <span></span>
                                    @endif
                                @else
                                    <input class="check"
                                           {{ ($value['is_actived'] == 1) ? 'checked' : '' }} id="check_branch_{{ $value['service_id'] }}"
                                           name="check_branch[]"
                                           type="checkbox">
                                    <span></span>
                                @endif
                            </label>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="branch_tb">
                    <td align="center" colspan="8">{{__('Tạm thời chưa có dữ liệu.')}}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>