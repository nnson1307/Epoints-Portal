<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">@lang('KHÁCH HÀNG TIỀM NĂNG')</th>
            <th class="tr_thead_list">@lang('NỘI DUNG')</th>
            <th class="tr_thead_list">@lang('NGÀY THAY ĐỔI')</th>
            <th class="tr_thead_list">@lang('NGƯỜI THAY ĐỔI')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['title']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s')}}</td>
                    <td>{{$item['staff_name']}}</td>
                    <td>
                        <a href="javascript:void(0)"
                           onclick="customerLog.popupDetailHistory({{$item['customer_log_id']}})"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chi tiết cập nhật')">
                            <i class="la  la-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@if(isset($LIST) && count($LIST) > 0)
    {{ $LIST->links('helpers.paging') }}
@endif
