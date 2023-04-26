<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">{{__('MÃ HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('TÊN HỢP ĐỒNG')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI HIỆN TẠI')}}</th>
            <th class="tr_thead_list">{{__('TRẠNG THÁI CẬP NHẬT')}}</th>
            <th class="tr_thead_list">{{__('TÌNH TRẠNG')}}</th>
            <th class="tr_thead_list">{{__('NGƯỜI YÊU CẦU')}}</th>
            <th class="tr_thead_list">{{__('NGÀY YÊU CẦU')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if($item['can_browse'] == 1)
                            @if ($item['status'] == 'new')

                                @if(in_array('contract.contract-browse.confirm', session()->get('routeList')))
                                <a href="javascript:void(0)" onclick="listBrowse.confirm({{$item['contract_browse_id']}})"
                                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                    <i class="la la-check"></i>
                                </a>
                                @endif
                                @if(in_array('contract.contract-browse.refuse', session()->get('routeList')))
                                <button href="javascript:void(0)"
                                        onclick="listBrowse.showModalRefuse({{$item['contract_browse_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill">
                                    <i class="la la-close"></i>
                                </button>
                                @endif
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(in_array('contract.contract.show', session()->get('routeList')))
                            <a href="{{route("contract.contract.show",[ 'id' => $item['contract_id']])}}" target="_blank">
                                {{$item['contract_code']}}
                            </a>
                        @else
                            {{$item['contract_code']}}
                        @endif
                    </td>
                    <td>{{$item['contract_name']}}</td>
                    <td>{{$item['status_name_now']}}</td>
                    <td>{{$item['status_name_new']}}</td>
                    <td>
                        @switch($item['status'])
                            @case('new')
                            @lang('Đợi duyệt')
                            @break

                            @case('confirm')
                            @lang('Đã duyệt')
                            @break

                            @case('refuse')
                            @lang('Từ chối')
                            @break
                        @endswitch
                    </td>
                    <td>{{$item['request_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
