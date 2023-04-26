<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th></th>
            <th class="tr_thead_list">@lang('Mã hợp đồng')</th>
            <th class="tr_thead_list">@lang('Tên hợp đồng')</th>
            <th class="tr_thead_list">@lang('Loại hợp đồng')</th>
            <th class="tr_thead_list">@lang('Trạng thái')</th>
            <th class="tr_thead_list">@lang('Giá trị hợp đồng')</th>
            <th class="tr_thead_list">@lang('Giá trị đã thanh toán')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST_CONTRACT))
            @foreach ($LIST_CONTRACT as $key => $value)
                <tr>
                    <td>{{$key + 1}}</td>
                    <td>
                        @if(in_array('contract.contract.show', session()->get('routeList')))
                            <a class="m-link" style="color:#464646"  href="{{route("contract.contract.show",[ 'id' => $value['contract_id']])}}"  target="_blank">
                                {{$value['contract_code']}}
                            </a>
                        @else
                            {{$value['contract_code']}}
                        @endif
                    </td>
                    <td>
                        {{$value['contract_name']}}
                    </td>
                    <td>{{$value['contract_category_name']}}</td>
                    <td>{{$value['status_name']}}</td>
                    <td>{{number_format($value['last_total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{number_format($value['total_receipt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{--{{ $LIST->links('helpers.paging') }}--}}
