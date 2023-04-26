<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table-discount">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">{{__('TÊN')}}</th>
            <th class="tr_thead_list">{{__('GIÁ GỐC')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (count($list) > 0)
            @foreach($list as $item)
                <tr>
                    <td>
                        {{$item['object_name']}}
                        <input type="hidden" class="object_type" value="{{$item['object_type']}}">
                        <input type="hidden" class="object_code" value="{{$item['object_code']}}">
                    </td>
                    <td>{{number_format($item['base_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>
                        <a href="javascript:void(0)" onclick="view.removeTr(this, '{{$item['object_code']}}', 'discount', '{{$item['object_type']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
                            <i class="la la-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

