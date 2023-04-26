<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th ss--text-center ">#</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Hành động') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Khách hàng') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Bàn') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Thao tác') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Phương thức thanh toán') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Trạng thái') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Ngày tạo') }}</th>

        </tr>
        </thead>
        <tbody>
        @if(isset($listRequest) && count($listRequest) != 0)
            @foreach ($listRequest as $key => $item)
                <tr>
                    <td class="ss--text-center">{{$listRequest->perpage()*($listRequest->currentpage()-1)+($key+1)}}</td>

                    <td class="ss--text-center">
                        <button onclick="request.showPopupEdit({{$item}})" type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                            title="{{__('Chỉnh sửa')}}" >
                            <i class="la la-edit"></i>
                        </button>
                        <button onclick="request.deleteRequest('{{$item['customer_request_id']}}','{{$item['payment']}}')" type="button"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Xóa')}}"><i class="la la-trash"></i>
                        </button>
                    </td>

                    <td class="ss--text-center">{{isset($item['customer_name']) ? $item['customer_name'] : ''}}</td>

                    <td class="ss--text-center">{{isset($item['table_name']) ? $item['table_name'] : ''}}</td>

                    @if(isset($item['action']))
                        @if($item['action'] == 'payment')
                            <td class="ss--text-center">{{__('Thanh toán')}}</td>
                        @elseif($item['action'] == 'clean_table')
                            <td class="ss--text-center">{{__('Dọn bàn')}}</td>
                        @elseif($item['action'] == 'change_table')
                            <td class="ss--text-center">{{__('Đổi bàn')}}</td>
                        @else
                            <td class="ss--text-center">{{__('Khác')}}</td>
                        @endif
                    @else
                        <td class="ss--text-center">{{__('Khác')}}</td>
                    @endif
                    <td class="ss--text-center">{{isset($item['method_name_vi']) ? $item['method_name_vi'] : ''}}</td>
                    @if($item['status'] == 'new')
                        <td class="ss--text-center">{{__('Mới')}}</td>
                    @elseif($item['status'] == 'processing')
                        <td class="ss--text-center">{{__('Đang thực hiện')}}</td>
                    @elseif($item['status'] == 'done')
                        <td class="ss--text-center">{{__('Hoàn thành')}}</td>
                    @else
                        <td class="ss--text-center"></td>
                    @endif

                    <td class="ss--text-center">{{isset($item['created_at']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s',$item['created_at'])->format('d/m/Y H:i') : ''}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $listRequest->links('helpers.paging') }}
</div>
