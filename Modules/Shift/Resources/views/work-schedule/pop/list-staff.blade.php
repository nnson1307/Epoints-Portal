<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_all" name="check_all" type="checkbox" onclick="view.chooseAllStaff(this)">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">{{__('TÊN NHÂN VIÊN')}}</th>
            <th class="tr_thead_list">{{__('CHI NHÁNH')}}</th>
            <th class="tr_thead_list">{{__('PHÒNG BAN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($list) > 0)
            @foreach($list as $k => $v)
                <tr>
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_one" name="check_one" type="checkbox"
                                   {{isset($arrChooseStaff) && in_array($v['staff_id'], $arrChooseStaff) ? 'checked' : ''}}
                                onclick="view.chooseStaff(this)">
                            <span></span>
                            <input type="hidden" class="staff_id" value="{{$v['staff_id']}}">
                        </label>
                    </td>
                    <td>{{$v['full_name']}}</td>
                    <td>{{$v['branch_name']}}</td>
                    <td>{{$v['department_name']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

