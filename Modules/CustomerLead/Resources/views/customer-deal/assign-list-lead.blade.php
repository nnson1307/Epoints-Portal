<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_all" name="check_all" type="checkbox" onclick="assign.chooseAll(this)">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">{{__('TÊN KHÁCH HÀNG')}}</th>
            <th class="tr_thead_list">{{__('TÊN DEAL')}}</th>
            <th class="tr_thead_list">{{__('MÃ DEAL')}}</th>
            <th class="tr_thead_list">{{__('HÀNH TRÌNH HIỆN TẠI')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_one" name="check_one" type="checkbox" {{isset($arrLeadTemp[$item['deal_code']]) ? 'checked' : ''}}
                            onclick="assign.choose(this)">
                            <span></span>
                            <input type="hidden" class="deal_id" value="{{$item['deal_id']}}">
                            <input type="hidden" class="deal_code" value="{{$item['deal_code']}}">
                            <input type="hidden" class="time_revoke_lead" value="{{$item['time_revoke_lead']}}">
                        </label>
                    </td>
                    <td>{{$item['full_name']}}</td>
                    <td>{{$item['deal_name']}}</td>
                    <td>{{$item['deal_code']}}</td>
                    <td>{{$item['pipeline_name']}} - {{$item['journey_name']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

