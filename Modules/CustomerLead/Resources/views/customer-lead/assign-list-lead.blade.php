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
            <th class="tr_thead_list">{{__('LOẠI KHÁCH HÀNG')}}</th>
            <th class="tr_thead_list">{{__('NGUỒN KHÁCH HÀNG')}}</th>
            <th class="tr_thead_list">{{__('HÀNH TRÌNH HIỆN TẠI')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_one" name="check_one" type="checkbox" {{isset($arrLeadTemp[$item['customer_lead_code']]) ? 'checked' : ''}}
                            onclick="assign.choose(this)">
                            <span></span>
                            <input type="hidden" class="customer_lead_id" value="{{$item['customer_lead_id']}}">
                            <input type="hidden" class="customer_lead_code" value="{{$item['customer_lead_code']}}">
                            <input type="hidden" class="time_revoke_lead" value="{{$item['time_revoke_lead']}}">
                        </label>
                    </td>
                    <td>
                        {{$item['full_name']}}
                    </td>
                    <td>
                        @if($item['customer_type'] == 'personal')
                            @lang('Cá nhân')
                        @elseif($item['customer_type'] == 'business')
                            @lang('Doanh nghiệp')
                        @endif
                    </td>
                    <td>{{$item['customer_source_name']}}</td>
                    <td>{{$item['pipeline_name']}} - {{$item['journey_name']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

