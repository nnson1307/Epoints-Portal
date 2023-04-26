<?php
$index = 1;
?>
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('Tên phụ cấp')</th>
            <th class="tr_thead_list">@lang('Người tạo')</th>
            <th class="tr_thead_list">@lang('Ngày tạo')</th>
            <th class="tr_thead_list"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))

            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        {{ $index++ }}
                    </td>
                    <td>
                        {{ $item['salary_allowance_name'] }}
                    </td>
                    <td>
                        {{ $item['staff_name'] }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $item['created_at'])->format('d/m/Y H:s:i') }}
                    </td>
                    <td nowrap="">
                        @if(in_array('staff-salary-allowance.edit',session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="allowance.showModalEdit({{ $item['salary_allowance_id'] }})"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="View">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

</div>
{{--{{ $LIST->links('helpers.paging') }}--}}