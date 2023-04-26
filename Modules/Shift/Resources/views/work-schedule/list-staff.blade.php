<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('TÊn NHÂN VIÊN')}}</th>
            <th class="tr_thead_list">{{__('CHI NHÁNH')}}</th>
            <th class="tr_thead_list">{{__('PHÒNG BAN')}}</th>
            <th class="tr_thead_list">{{__('HÀNH ĐỘNG')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($list) > 0)
            @foreach($list as $k => $v)
                <tr class="tr_staff">
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>{{$v['full_name']}}</td>
                    <td>{{$v['branch_name']}}</td>
                    <td>{{$v['department_name']}}</td>
                    <td>
                        <a href="javascript:void(0)" onclick="view.removeStaffTr(this, '{{$v['staff_id']}}')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Xoá')">
                            <i class="la la-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

