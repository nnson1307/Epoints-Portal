<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_all" name="check_all" type="checkbox" onclick="view.chooseAll(this, 'service_card')">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">{{__('TÊN THẺ')}}</th>
            <th class="tr_thead_list">{{__('MÃ THẺ')}}</th>
            <th class="tr_thead_list">{{__('NHÓM THẺ')}}</th>
            <th class="tr_thead_list">{{__('SỐ NGÀY SỬ DỤNG')}}</th>
            <th class="tr_thead_list">{{__('SỐ LẦN SỬ DỤNG')}}</th>
            <th class="tr_thead_list">{{__('GIÁ BÁN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_one" name="check_one" type="checkbox" {{isset($arrServiceCardTemp[$item['code']]) ? 'checked' : ''}}
                                   onclick="view.choose(this, 'service_card')">
                            <span></span>
                            <input type="hidden" class="service_card_id" value="{{$item['service_card_id']}}">
                            <input type="hidden" class="service_card_code" value="{{$item['code']}}">
                            <input type="hidden" class="service_card_name" value="{{$item['card_name']}}">
                            <input type="hidden" class="base_price" value="{{$item['price']}}">
                        </label>
                    </td>
                    <td>{{$item['card_name']}}</td>
                    <td>{{$item['code']}}</td>
                    <td>{{$item['group_name']}}</td>
                    <td>{{$item['date_using']}}</td>
                    <td>{{$item['number_using']}}</td>
                    <td>
                        {{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

