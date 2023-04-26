<style>
    :root {
        --blue-700: #e5efff;
        --blue-650: #c8deff;
        --blue-600: #abcdff;
        --blue-500: #72abff;
        --blue-400: #3989ff;
        --blue-300: #0068ff;
        --blue-base: #0068ff;
        --dark-blue-200: #004bb9;
        --dark-blue-base: #004bb9;
        --grey-700: #fbfbfd;
        --grey-600: #f4f5f7;
        --grey-500: #eeeff2;
        --grey-400: #e8eaef;
        --grey-300: #e1e4ea;
        --grey-base: #e1e4ea;
        --dark-grey-700: #060707;
        --dark-grey-600: #121415;
        --dark-grey-500: #363b3e;
        --dark-grey-400: #666f76;
        --dark-grey-300: #96a3ad;
        --dark-grey-base: #96a3ad;
        --red-700: #fbebe9;
        --red-600: #f3bcbb;
        --red-500: #eb8e8b;
        --red-400: #e2615d;
        --red-300: #db342e;
        --red-base: #db342e;
        --orange-700: #fdf3e9;
        --orange-600: #fbd6bb;
        --orange-500: #f9ba8c;
        --orange-400: #f79e5d;
        --orange-300: #f5832f;
        --orange-base: #f5832f;
        --neutral-700: #e5e7eb;
        --neutral-600: #abb4bc;
        --neutral-500: #72808e;
        --neutral-400: #394e60;
        --neutral-300: #001a33;
        --neutral-200: #081020;
        --neutral-100: #050a19;
        --neutral-base: #001a33;
        --green-700: #e7f5ef;
        --green-600: #b2e2cb;
        --green-500: #7ecea7;
        --green-400: #49bb82;
        --green-300: #15a85f;
        --green-base: #15a85f;
        --yellow-700: #fff9e5;
        --yellow-600: #ffefab;
        --yellow-500: #ffe472;
    }
    .preview-template {
        width: 500px;
    }

    .card.card--oa>.oa-msg-child, .card.card--oa>.oa-msg-header {
        background-color: var(--white-300);
    }
    .oa-msg-header {
        width: 360px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .card.card--oa, .card.card--video {
        width: auto;
        cursor: pointer;
    }
    .oa-msg-header__img {
        width: calc(100% - 20px);
        height: 232px;
        object-fit: cover;
        background-color: var(--grey-600);
        background-repeat: no-repeat;
        background-size: cover;
        border-radius: 8px;
    }
    .oa-msg-header__title {
        font-weight: 500;
        margin-top: 10px;
        margin-bottom: 12px;
        padding: 0 10px;
    }
    .oa-msg-header__desc {
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 10px;
        white-space: pre-line;
    }
    .card.card--oa>.oa-msg-child, .card.card--oa>.oa-msg-header {
        background-color: var(--white-300);
    }
    .oa-msg-child {
        padding: 10px;
        border-top: 1px solid var(--grey-300);
        background-color: transparent;
    }
    .oa-msg-child__img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        margin-right: 10px;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<div class="preview-template">
    <div class="" style="display: flex; width: 72.5%;">
        <div class="card  last-msg card--oa">
            <div class="oa-msg-header">
                <div class="oa-msg-header__img"
                     style="background-image: url('{{isset($item->image)?$item->image:''}}'); background-position: center center; border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;"></div>
                <div class="oa-msg-header__title">{{isset($item->image_title)?$item->image_title:''}}</div>
                <div class="oa-msg-header__desc">{{isset($item->preview)?$item->preview:''}}</div>
            </div>
            @foreach($item->template_button() as $key => $button_item)
            <div class="oa-msg-child flx">
                <div class="oa-msg-child__img flx-fix no-bg"
                     style="background-image: url('{{isset($button_item->icon)?$button_item->icon:asset('uploads/admin/service_card/default/hinhanh-default3.png')}}');"></div>
                <div class="oa-msg-child__title flx flx-al-c">{{isset($button_item->title)?$button_item->title:''}}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>