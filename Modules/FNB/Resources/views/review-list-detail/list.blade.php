<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="ss--font-size-th ss--text-center ">#</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Cấp độ đánh giá') }}</th>
            <th class="ss--font-size-th ss--text-center">{{ __('Tên đánh giá') }}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--text-center">{{$list->perpage()*($list->currentpage()-1)+($key+1)}}</td>
                    <td class="ss--text-center">{{$item['review_list_name']}}</td>
                    <td class="ss--text-center">{{$item['review_list_detail_name']}}</td>
                    <td>
                        <button type="button" onclick="requestListDetail.removeReviewListDetail(this,'{{$item["review_list_detail_id"]}}')"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Xóa')}}"><i class="la la-trash"></i>
                        </button>
                        <button type="button" onclick="requestListDetail.showPopup('{{$item["review_list_detail_id"]}}')"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Chỉnh sửa')}}"><i class="la la-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}
