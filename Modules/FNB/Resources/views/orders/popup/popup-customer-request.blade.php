<!-- Modal -->
<div class="modal fade" id="popup-customer-request" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog " role="document" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="title">{{__('DANH SÁCH YÊU CẦU')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">#</th>
                                <th class="tr_thead_list">{{__('Tên khách hàng')}}</th>
                                <th class="tr_thead_list">{{__('Loại yêu cầu')}}</th>
                                <th class="tr_thead_list">{{__('Nội dung yêu cầu')}}</th>
                                <th class="tr_thead_list">{{__('Trạng thái')}}</th>
                                <th class="tr_thead_list">{{__('Người xử lý')}}</th>
                                <th class="tr_thead_list">{{__('Thời gian xử lý')}}</th>
                                <th class="tr_thead_list"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $key => $item)
                                    <tr class="tr_{{$item['customer_request_id']}}">
                                        <td>{{$list->perpage()*($list->currentpage()-1)+($key+1)}}</td>
                                        <td>{{$item['full_name']}}</td>
                                        <td>
                                            @switch($item['action'])
                                                @case('clean_table')
                                                    {{__('Dọn bàn')}}
                                                    @break
                                                @case('change_table')
                                                    {{__('Dọn bàn')}}
                                                    @break
                                                @case('payment')
                                                    {{__('Dọn bàn')}}
                                                    @break
                                                @case('other')
                                                    {{__('Dọn bàn')}}
                                                    @break
                                                @default
                                                    @break
                                            @endswitch
                                        </td>
                                        <td><span>{{$item['note']}}</span></td>
                                        <td class="append-tr"><span class="{{$item['status'] == 'new' ? 'text-warning' : ($item['status'] == 'processing' ? 'text-primary' : 'text-success')}}">{{$item['status'] == 'new' ? __('Chưa xử lý') : ($item['status'] == 'processing' ? __('Đang xử lý') : __('Hoàn thành'))}}</span></td>
                                        <td class="append-tr">{{$item['process_name']}}</td>
                                        <td class="append-tr">{{$item['process_at'] != '' ? \Carbon\Carbon::parse($item['process_at'])->format('H:i d/m/Y') : ''}}</td>
                                        <td class="block_status append-tr">
                                            @if(in_array($item['status'],['new','processing']))
                                                <button type="button" class="btn btn-info" onclick="PopupAction.confirmCustomerRequest('{{$item['table_id']}}','{{$item['customer_request_id']}}','{{$item['status'] == 'new' ? 'processing' : 'done'}}')">
                                                    {{$item['status'] == 'new' ? __('Đang xử lý') : __('Hoàn thành')}}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <span class="la 	la-arrow-left"></span>
                        HỦY
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>