<style>
    .preview-template{
        width: 500px;
    }
    .card.card--text {
        user-select: text;
        min-width: 32px;
        max-width: calc(100% - 38px);
    }
    .card.card--file, .card.card--link, .card.card--location, .card.card--sound, .card.card--text, .card.card--undo, .shadow-bubble {
        -webkit-box-shadow: 0 1px 0 0 var(--box-popover);
        -moz-box-shadow: 0 1px 0 0 var(--box-popover);
        box-shadow: 0 1px 0 0 var(--box-popover);
    }
    .card {
        position: relative;
        display: block;
        padding: 12px;
        border-radius: 8px;
        background: var(--white-300);
        margin-bottom: 4px;
    }
</style>
<div class="preview-template">
    <div class="" style="display: flex; width: 100%;">
        <div class="card  last-msg card--text" data-id="div_ReceivedMsg_Text">
            <div><span id="mtc-3311315588312"><span class="text">{{isset($item->preview)?$item->preview:''}}</span></span>
            </div>
        </div>
    </div>
</div>