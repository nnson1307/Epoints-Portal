<style>
    .preview-template {
        width: 500px;
    }

    .card.card--text {
        user-select: text;
        min-width: 32px;
        max-width: 100%;
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
    <div class="img-msg-v2 -caption -bg-v-1" data-id="div_ReceivedMsg_Photo"
         style="--thumb-cw:498px; --min-threshold:undefinedpx;">
        <div class="img-msg-v2__dn"></div>
        <div class="img-msg-v2__bub">
            <div maxloss="0" class="ci-th -fit-scale-down fade-th-v2 msg-select-overlay img-msg-v2__th"
                 style="width: 500px;max-width: 500px">
                <div class="or-bx -ort-0 ci-th__thb ci-th-thumb-tr-enter-done"
                     style="--or-bx-cw:498px; --or-bx-ch:350px; --or-bx-bw:498px; --or-bx-bh:350px;">
                    <img class="ci-th__thumb rounded" src="{{isset($item->image)?$item->image:asset('uploads/admin/service_card/default/hinhanh-default3.png')}}"
                            data-drag-src="{{isset($item->image)?$item->image:asset('uploads/admin/service_card/default/hinhanh-default3.png')}}">
                </div>
            </div>
            <div class="card last-msg card--text" data-id="div_ReceivedMsg_Text">
                <div><span id="mtc-3311315588312"><span class="text">{{isset($item->image_title)?$item->image_title:''}}</span></span>
                </div>
            </div>
        </div>
    </div>
</div>