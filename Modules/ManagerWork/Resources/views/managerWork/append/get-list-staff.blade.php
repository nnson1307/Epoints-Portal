<table class="table table-striped m-table m-table--head-bg-default">
    <thead class="bg">
    <th class="tr_thead_list">{{__('Tài khoản')}}</th>
    <th class="tr_thead_list">{{__('Vai trò')}}</th>
    </thead>
    <tbody>
    @foreach($listStaff as $item)
        <tr>
            <td>{{$item['full_name']}}</td>
            <td>{{$item['staff_id'] == $processor_id ? __('Người thực hiện') : __('Người hỗ trợ')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $listStaff->links('manager-work::managerWork.helpers.paging-list-staff') }}