
<tr>
    <td>
        {{ $salary_bonus_minus_text }}
        <input type="hidden" value="{{ $salary_bonus_minus }}" id="salary_bonus_minus">
    </td>
    <td>
        @if(isset($salaryBonusMinus))
            @if($salaryBonusMinus['salary_bonus_minus_type'] == 'bonus')
                {{ $salary_bonus_minus_num }} <span class="salary-unit-name">@lang("VNĐ")</span>
            @else
                - {{ $salary_bonus_minus_num }} <span class="salary-unit-name">@lang("VNĐ")</span>
            @endif
        @endif
        <input type="hidden" value="{{ $salary_bonus_minus_num }}" id="salary_bonus_minus_num">
    </td>
    <td nowrap="">

        <a onclick="salaryTempalte.removeCell(this);" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
            <i class="la la-trash"></i>
        </a>
    </td>
</tr>

