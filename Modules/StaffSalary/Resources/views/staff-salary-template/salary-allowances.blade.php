
<tr>
    <td>
        {{ $salary_allowance_text }}
        <input type="hidden" value="{{ $salary_allowance }}" id="salary_allowance_id">
    </td>
    <td>
        {{ $salary_allowance_num }} <span class="salary-unit-name">{{$unitText}}</span>
        <input type="hidden" value="{{ $salary_allowance_num }}" id="salary_allowance_num">
    </td>
    <td nowrap="">

        <a onclick="salaryTempalte.removeCell(this);" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
            <i class="la la-trash"></i>
        </a>
    </td>
</tr>

