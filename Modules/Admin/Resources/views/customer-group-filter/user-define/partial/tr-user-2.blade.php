<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th style="width: 20px; padding: 0.5rem">
                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success m--margin-bottom-15">
                    <input type="checkbox" onclick="userGroupDefine.selectAll2(this)">
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
                            <input class="check-box-choose-user" type="checkbox"
                                   onclick="userGroupDefine.chooseUser2(this)">
                            <input type="hidden" value="{{$item['phone1']}}" class="phone-2">
                            <input type="hidden" value="{{$item['customer_code']}}" class="customer-code-2">
                            <input type="hidden" value="{{$item['customer_id']}}" class="customer-id-2">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        <span title="{{ $item['full_name'] }}">
                            {{ ($item['full_name']) }}
                        </span>
                    </td>
                    <td>
                        <span title="{{ $item['phone1'] }}">
                            {{ ($item['phone1']) }}
                        </span>
                    </td>
                    <td>
                        <span title="{{ $item['customer_code'] }}">
                            {{ ($item['customer_code']) }}
                        </span>
                    </td>
                    <td>{{$item['is_actived'] == 1 ? 'Hoạt động' : 'Không hoạt động'}}</td>
                </tr>
            @endforeach
        @else
        @endif
        </tbody>
    </table>
    @if(isset($list))
        {{$list->links('admin::customer-group-filter.user-define.helper.paging-ajax-2')}}
    @endif
</div>

