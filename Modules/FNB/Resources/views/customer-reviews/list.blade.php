<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th ss--text-center ">#</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Hành động') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Khách hàng') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Bàn') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Loại đánh giá') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Chi tiết đánh giá') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Ghi chú') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Ngày tạo') }}</th>

        </tr>
        </thead>
        <tbody>
        @if(isset($listCustomerReview) && count($listCustomerReview) != 0)
            @foreach ($listCustomerReview as $key => $item)
                <tr>
                    <td class="ss--text-center">{{$listCustomerReview->perpage()*($listCustomerReview->currentpage()-1)+($key+1)}}</td>

                    <td class="ss--text-center">
                        <button onclick="customerReviews.showPopupEdit({{$item}})" type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                            title="{{__('Chỉnh sửa')}}" >
                            <i class="la la-edit"></i>
                        </button>
                        <button onclick="customerReviews.deleteRequest('{{$item['customer_request_id']}}','{{$item['payment']}}')" type="button"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Xóa')}}"><i class="la la-trash"></i>
                        </button>
                    </td>

                    <td class="ss--text-center">{{isset($item['customer_name']) ? $item['customer_name'] : ''}}</td>

                    <td class="ss--text-center">{{isset($item['table_name']) ? $item['table_name'] : ''}}</td>

                    <td class="ss--text-center">{{isset($item['review_list_name']) ? $item['review_list_name'] : ''}}</td>

                    <td class="ss--text-center">
                        @if(isset($item['review_list_detail_name']) && $item['review_list_detail_name'] != []  && $item['review_list_detail_name'] != null)
                        @foreach($item['review_list_detail_name'] as $k => $v)
                            <p>{{$v}}</p>
                            @endforeach
                        @endif
                    </td>

                    <td class="ss--text-center">{{isset($item['note']) ? $item['note'] : ''}}</td>

                    <td class="ss--text-center">{{isset($item['created_at']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s',$item['created_at'])->format('d/m/Y H:i') : ''}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $listCustomerReview->links('helpers.paging') }}
</div>
