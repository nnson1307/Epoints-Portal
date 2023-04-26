<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Người giới thiệu')}}</th>
            <th class="tr_thead_list text-center">{{__('Tổng người giới thiệu trong nhánh')}}</th>
            <th class="tr_thead_list">{{__('Số cấp')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
        </tr>
        </thead>
        <tbody style="font-size: 13px">
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr>
                    <td>{{($list->currentPage() - 1)*$list->perPage() + $key+1 }}</td>
                    <td class="">
                        <a href="{{route('referral.referral-member.detailCommissionReferral',['id' => $item['referral_member_id']])}}">
                            {{$item['customer_full_name']}}
                        </a>
                    </td>
                    <td class="text-center">{{isset($item['total_referral']) ? $item['total_referral'] : 0}}</td>
                    <td>1</td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" disabled {{$item['status'] == 'active' ? 'checked' : ''}}>
                                <span></span>
                            </label>
                        </span>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10" class="text-center">{{__('Không có dữ liệu')}}</td>
            </tr>
        @endif
        </tbody>

    </table>
</div>
{{ $list->links('referral::helpers.paging-referral') }}
