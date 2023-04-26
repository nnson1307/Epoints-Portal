
    <div class="table-responsive">
        <table class="table table-striped m-table m-table--head-bg-default">
            <thead class="bg">
            <tr>
                <th>{{__('CHI NHÁNH')}}</th>
                <th>@lang("SỐ TIỀN")</th>
                <th>{{__('NGÀY TẠO')}}</th>
                <th>{{__('TRẠNG THÁI')}}</th>
            </tr>
            </thead>
            @if (count($data) > 0)
            <tbody>
            @foreach($data as $log)
                <tr>
                    <td>
                        {{$log['branch_name']}}
                    </td>
                    <td>{{number_format($log['money'])}}</td>
                    <td>{{date("d/m/Y",strtotime($log['created_at']))}}</td>
                    <td>
                        @if($log['type']=='tranfer_money')
                            @lang("Cộng tiền vào tài khoản")
                        @elseif($log['type']=='cash_out')
                            @lang("Tạo phiếu chi")
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            @endif
        </table>
    </div>
