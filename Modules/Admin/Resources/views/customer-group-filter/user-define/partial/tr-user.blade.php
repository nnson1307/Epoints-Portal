<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>
                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success m--margin-bottom-15">
                    <input type="checkbox" onclick="userGroupDefine.selectAll1(this)">
                    <span></span>
                </label>
            </th>
            <th>{{__('HỌ VÀ TÊN')}}</th>
            <th>{{__('SỐ ĐIỆN THOẠI')}}</th>
            <th>{{__('MÃ KHÁCH HÀNG')}}</th>
            <th>{{__('TRẠNG THÁI')}}</th>
        </tr>
        </thead>
        <tbody id="tbody-add-user">
        @if(isset($list))
            @foreach($list as $item)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success m--margin-bottom-15">
                            <input type="checkbox" checked class="check-box-choose-user1"
                                   onclick="userGroupDefine.chooseUser1(this)">
                            <span></span>
                            <input type="hidden" value="{{$item['phone1']}}" class="phone-1">
                            <input type="hidden" value="{{$item['customer_code']}}" class="customer-code-1">
                            <input type="hidden" value="{{$item['customer_id']}}" class="customer-id-1">

                        </label>
                    </td>
                    <td>
                        <span title="{{ $item['full_name'] }}">
                            {{ ($item['full_name']) }}
                        </span>
                    </td>
                    <td>
                        <span title="{{$item['phone1']}}">
                            {{ ($item['phone1']) }}
                        </span>
                    </td>
                    <td>
                        <span title="{{ $item['customer_code'] }}">
                            {{ ($item['customer_code']) }}
                        </span>
                    </td>
                    <td>{{$item['is_actived']==1?'Hoạt động':'Không hoạt động'}}</td>
                </tr>
            @endforeach
        @else
        @endif
        </tbody>
    </table>
    @if(isset($list))
        {{$list->links('admin::customer-group-filter.user-define.helper.paging-ajax-1')}}
    @endif
</div>
