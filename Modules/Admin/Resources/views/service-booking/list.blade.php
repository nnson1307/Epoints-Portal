<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('TÊN DỊCH VỤ')}}</th>
            <th class="tr_thead_list">{{__('THỜI GIAN ĐẶT')}}</th>
            <th class="tr_thead_list">{{__('NHÂN VIÊN TẠO')}}</th>
            <th class="tr_thead_list">{{__('NGÀY TẠO')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
            <th class="tr_thead_list"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td>{{$item['object_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['date'] . ' '. $item['time'])->format('d/m/Y H:i')}}</td>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @switch($item['status'])
                            @case('new')
                            {{__('MỚI')}}
                            @break

                            @case('confirm')
                            {{__('XÁC NHẬN')}}
                            @break

                            @case('wait')
                            {{__('CHỜ PHỤC VỤ')}}
                            @break

                            @case('finish')
                            {{__('ĐÃ HOÀN THÀNH')}}
                            @break

                            @case('processing')
                            {{__('ĐANG THỰC HIỆN')}}
                            @break
                        @endswitch
                    </td>
                    <td>
                        @if(in_array('admin.customer_appointment.submitModalEdit',session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="customer_appointment.click_modal_edit('{{$item['customer_appointment_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Cập nhật')}}">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
