<div class="m-portlet__body">
    <span class="chart-name">{{$info['project_name']}}</span>
</div>
<div class="m-portlet__body height-main" style="    font-weight: 400;">
    <table class="table-hover">
        <tr>
            <th>{{__('Ngày tạo')}}</th>
            <td>{{isset($info['created_at']) && $info['created_at']!= null ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $info['created_at'])->format('d/m/Y') : ''}}</td>

        </tr>
        <tr>
            <th>{{__('Người tạo')}}</th>
            <td style="color:#5CACEE">
                <a target="_blank" href="{{route('admin.staff.show',['id' => $info['created_by']])}}">{{isset($info['created_by_name']) ? $info['created_by_name'] : ''}}</a>
            </td>
        </tr>
        <tr>
            <th>{{__('Ngày hoàn thành')}}</th>
            <td>{{isset($info['date_finish']) && $info['date_finish']!= null ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $info['date_finish'])->format('d/m/Y') : ''}}</td>
        </tr>
        <tr>
            <th>{{__('Hợp đồng')}}</th>
            <td>
                @if(isset($info['contract_id']) && $info['contract_id'] != null  && $info['contract_id'] != [] )
                    <a target="_blank" href="{{route('contract.contract.show',['id' => $info['contract_id']])}}">{{$info['contract_code']}}</a>
                    
                @endif
            </td>
        </tr>
        <tr>
            <th>{{__('Khách hàng')}}</th>
            <td style="color:#5CACEE">
                @if(isset($info['customer']) && $info['customer'] != [])
                    <a target="_blank" href="{{route('admin.customer.detail',['id' => $info['customer'][0]['customer_id']])}}">{{$info['customer'][0]['customer_name']}}</a>
                @endif
        </tr>
        <tr>
            <th>{{__('Người liên hệ')}}</th>
            <td>{{isset($info['contact_name']) && $info['contact_name'] != [] ? $info['contact_name'].(isset($info['contact_phone'])  && $info['contact_phone'] != null ? ' ('. $info['contact_phone'] . ')': ''): ''}}</td>
        </tr>
        <tr>
            <th>{{__('Tiền tố dự án')}}</th>
            <td>{{isset($info['prefix_code']) ? $info['prefix_code'] : ''}}</td>
        </tr>
    </table>
</div>