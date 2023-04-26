<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th></th>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Người liên hệ')}}</th>
            <th class="tr_thead_list">{{__('Số điện thoại')}}</th>
            <th class="tr_thead_list">{{__('Email')}}</th>
            <th class="tr_thead_list">{{__('Chức vụ')}}</th>
            <th class="tr_thead_list">{{__('Ngày tạo')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <a href="javascript:void(0)" onclick="detail.popEditPersonContact('{{$item['customer_person_contact_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Cập nhật')}}">
                            <i class="la la-edit"></i>
                        </a>
                    </td>
                    <td> {{isset($page) ? ($page-1)*10 + $key+1 : $key+1}}</td>
                    <td>{{$item['person_name']}}</td>
                    <td>{{$item['person_phone']}}</td>
                    <td>{{$item['person_email']}}</td>
                    <td>{{$item['staff_title_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}