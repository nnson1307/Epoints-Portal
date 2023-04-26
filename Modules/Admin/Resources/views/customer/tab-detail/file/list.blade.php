<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th></th>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Tập tin')}}</th>
            <th class="tr_thead_list">{{__('Nội dung')}}</th>
            <th class="tr_thead_list text-center">{{__('Người tạo / Người cập nhật')}}</th>
            <th class="tr_thead_list text-center">{{__('Ngày tạo / Ngày cập nhật')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <a href="javascript:void(0)" onclick="detail.popEditFile('{{$item['customer_file_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('Cập nhật')}}">
                            <i class="la la-edit"></i>
                        </a>
                    </td>
                    <td> {{isset($page) ? ($page-1)*10 + $key+1 : $key+1}}</td>
                    <td>
                        <a href="{{$item['link']}}" target="_blank">{{$item['file_name']}}</a>
                    </td>
                    <td>{{$item['note']}}</td>
                    <td class="text-center">{{$item['staff_name_create']}} <br> {{$item['staff_name_update']}}</td>
                    <td class="text-center">{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s')}} <br>  {{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y H:i:s')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}