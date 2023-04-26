<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table_branch">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Chi nhánh')}}</th>
            <th class="tr_thead_list">{{__('Giá dịch vụ')}}</th>
            <th class="tr_thead_list">{{__('Giá chi nhánh')}}</th>
            @if(session()->get('brand_code') == 'giakhang')
                <th class="tr_thead_list width-250">{{__('Giá tuần')}}</th>
                <th class="tr_thead_list width-250">{{__('Giá tháng')}}</th>
                <th class="tr_thead_list width-250">{{__('Giá năm')}}</th>
            @else
                <th hidden class="tr_thead_list width-250">{{__('Chi tuần')}}</th>
                <th hidden class="tr_thead_list width-250">{{__('Giá tháng')}}</th>
                <th hidden class="tr_thead_list width-250">{{__('Giá năm')}}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($itemBranch as $key=>$value)
            <tr>
                <td>
                @if(isset($page))
                    {{ ($page-1)*$display + $key+1}}
                @else
                    {{$key+1}}
                @endif
                <input type="hidden" id="service_branch_price_id"
                       name="service_branch_price_id"
                       value="{{$value['service_branch_price_id']}}">
                </td>
                <td>{{$value['branch_name']}}<input type="hidden"
                                                    value="{{$value['branch_id']}}"></td>
                <td>
                    {{number_format($value['old_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    <input type="hidden" value="{{$value['old_price']}}">
                </td>
                <td>{{number_format($value['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>

                @if(session()->get('brand_code') == 'giakhang')
                    <td>{{number_format($value['price_week'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>

                    <td>{{number_format($value['price_month'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>

                    <td>{{number_format($value['price_year'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                @else
                    <td hidden>{{number_format($value['price_week'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>

                    <td hidden>{{number_format($value['price_month'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>

                    <td hidden>{{number_format($value['price_year'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{$itemBranch->links('helpers.paging') }}
