@if (!empty($itemGroup))
    <div id="itemGroupCustomerSeleted">
        <input type="text" disabled value="" placeholder="{{ $itemGroup->name }}">
        <input type="text" id="itemGroupChecked" hidden value="{{ $itemGroup->id }}">
        <i class="la la-close" onclick="branch.removeCustomerAuto(this)"></i>
    </div>
@else
    <input type="text" id="itemGroupChecked" value="" placeholder="">
@endif
