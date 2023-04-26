<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_all" name="check_all" type="checkbox" onclick="view.chooseAll(this, 'service')">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">{{__('Dịch vụ')}}</th>
            <th class="tr_thead_list">{{__('Nhóm')}}</th>
            <th class="tr_thead_list">{{__('Thời gian')}}</th>
            <th class="tr_thead_list">{{__('GIÁ BÁN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_one" name="check_one" type="checkbox" {{isset($arrServiceTemp[$item['service_code']]) ? 'checked' : ''}}
                                   onclick="view.choose(this, 'service')">
                            <span></span>
                            <input type="hidden" class="service_id" value="{{$item['service_id']}}">
                            <input type="hidden" class="service_code" value="{{$item['service_code']}}">
                            <input type="hidden" class="service_name" value="{{$item['service_name']}}">
                            <input type="hidden" class="base_price" value="{{$item['price_standard']}}">
                        </label>
                    </td>
                    <td>
                        @if($item['number']>0)
                            <i class="la la-check"></i>
                        @endif
                        <a class="m-link" style="color:#464646" title="{{__('Xem chi tiết')}}"
                           href='{{route("admin.service.detail",$item['service_id'])}}'>
                            {{$item['service_name']}}
                        </a>

                    </td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['time']}} {{__('phút')}}</td>
                    <td>
                        {{number_format($item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

