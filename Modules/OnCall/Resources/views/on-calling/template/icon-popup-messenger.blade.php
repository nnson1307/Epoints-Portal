
<script type="text/template" id="oncall-icon-popup-messenger-tpl">
    <li class="oncall-li" id="oncall-{phone}" onclick="layout.getModalFromIcon('{{Auth()->id()}}', '{history_id}', '{id}', '{type}', '{phone}', '{{session()->get('brand_code')}}')">
        <span class="icon-css-hover">{phone}</span>
        <img class="oncall-icon-css"
             src="{avatar}">
    </li>
</script>