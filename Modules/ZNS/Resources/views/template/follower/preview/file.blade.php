<style>
    .card.card--file {
        max-width: 100%;
        width: 376px;
    }

    .card.card--file, .card.card--link, .card.card--location, .card.card--sound, .card.card--text, .card.card--undo, .shadow-bubble {
        -webkit-box-shadow: 0 1px 0 0 var(--box-popover);
        -moz-box-shadow: 0 1px 0 0 var(--box-popover);
        box-shadow: 0 1px 0 0 var(--box-popover);
    }

    .file-message {
        position: relative;
        display: block;
        padding: 12px;
        border-radius: 8px;
        background-color: var(--white-300);
        margin-bottom: 4px;
        max-width: calc(100% - 160px);
        width: 376px;
        cursor: pointer;
    }

    .card {
        position: relative;
        display: block;
        padding: 12px;
        border-radius: 8px;
        background: var(--white-300);
        margin-bottom: 4px;
    }

    .file-message__container {
        cursor: pointer;
        position: relative;
    }

    .file-message {
        position: relative;
        display: block;
        padding: 12px;
        border-radius: 8px;
        background-color: var(--white-300);
        margin-bottom: 4px;
        max-width: calc(100% - 160px);
        width: 376px;
        cursor: pointer;
    }

    .file-message__content-container {
        display: flex;
        width: 100%;
        font-size: 13px;
        color: var(--neutral-300);
    }

    .file-message-icon {
        margin-right: 10px;
        flex: 0 0 auto;
    }

    .file-tit-box--size-large {
        width: 3.5rem;
        height: 3.5rem;
    }

    .file-tit-box {
        position: relative;
        display: inline-block;
    }

    .file-icon--size-large.svg-icon {
        width: 3.5rem;
        height: 3.5rem;
    }

    .file-icon.svg-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .file-icon--size-extra-large.svg-icon, .file-icon--size-large.svg-icon, .file-icon--size-medium.svg-icon, .file-icon--size-small.svg-icon {
        object-fit: contain;
    }

    .file-tit-box__icon {
        z-index: 1;
        visibility: visible;
        opacity: 1;
    }

    .file-tit-box__icon, .file-tit-box__thumb {
        max-width: 100%;
        max-height: 100%;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .file-icon--size-large.svg-icon {
        width: 3.5rem;
        height: 3.5rem;
    }

    .file-icon.svg-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .file-icon--size-extra-large.svg-icon, .file-icon--size-large.svg-icon, .file-icon--size-medium.svg-icon, .file-icon--size-small.svg-icon {
        object-fit: contain;
    }

    .file-tit-box__icon {
        z-index: 1;
        visibility: visible;
        opacity: 1;
    }

    .file-tit-box__icon, .file-tit-box__thumb {
        max-width: 100%;
        max-height: 100%;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .svg-icon--size-large {
        width: 2rem;
        height: 2rem;
        flex-basis: 2rem;
    }

    .svg-icon {
        display: inline-block;
        background-repeat: no-repeat;
        background-size: contain;
        background-position: 50%;
        vertical-align: middle;
    }

    .file-message__content-title {
        margin-bottom: 5px;
        overflow: hidden;
        font-size: 14px;
        font-weight: 500;
        display: flex;
    }

    #group-creator .create-group__item__name, .cb-info-file-item__error-msg, .cb-info-file-item__file-size, .cb-info-file-item__send-date, .cb-info-file-item__suggest-preview-file, .cb-info-file-item__suggest-preview-folder, .chat-box-member__info__name, .chat-info-link__subtitle, .chat-info-link__title, .conv-item-title__name, .conv-message, .conv-title-name, .file-banner-content__full-name, .file-banner-content__sender-name, .file-message__content-error, .file-message__content-title, .file-progress-row__end-text, .file-progress-row__start-text, .file-star-msg-content__size, .file-star-msg-content__title, .file-suggest-item__conversation, .file-suggest-item__name, .file-suggest-item__size, .filter-preview-v2 > div, .fmsg-send-time__error-msg, .item-message, .item-title-name, .mn-noti-undo-del > .mn-noti-undo-del__counting > .mn-noti-undo-del__mess > span, .mn-noti-undo-del > .mn-noti-undo-del__failed > .mn-noti-undo-del__mess > span, .mn-noti-undo-del > .mn-noti-undo-del__success > .mn-noti-undo-del__mess > span, .quote-file__name, .quote-file__text, .search-result-file__file-name, .search-result-file__file-name-seg, .search-result-file__file-size, .search-result-file__send-date, .search-result-file__suggest-preview-file, .search-result-file__suggest-preview-folder, .star-msgs__group__item__title > .text-name.user-name, .text-simple-dropdown > div, .truncate, .user-single-filter-preview > div {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        word-wrap: break-word;
        max-width: 250px;
    }

    .file-message__content-title {
        margin-bottom: 5px;
        overflow: hidden;
        font-size: 14px;
        font-weight: 500;
        display: flex;
    }

    .file-message__content-info {
        flex: 1 1 75%;
        display: flex;
        overflow: hidden;
    }

    .file-message__content-container {
        display: flex;
        width: 100%;
        font-size: 13px;
        color: var(--neutral-300);
    }

    .file-message__content-actions {
        display: flex;
        flex: 1 1 25%;
    }

    .flx-e, .qm-mrc {
        justify-content: flex-end;
    }
</style>
<div class="preview-template">
    <div class="file-message card--file card  pin-react " data-id="div_ReceivedMsg_File">
        <div class="file-message__container">
            <div class="file-message__content-container" style="min-height: 49px;">
                <div class="file-tit-box file-tit-box--size-large file-message-icon file-message-icon--none">
                    <div class="svg-icon svg-icon--size-large file-icon file-icon--size-large file-tit-box__icon file-message-icon__icon"
                         style="background-image: url('https://chat.zalo.me/assets/icon-word.d7db8ecee5824ba530a5b74c5dd69110.svg');"></div>
                    <div class="file-tick file-tick--status-none file-tick--size-large file-tit-box__tick file-message-icon__tick"
                         style="background-image: url('https://chat.zalo.me/assets/icon-word.d7db8ecee5824ba530a5b74c5dd69110.svg');"></div>
                </div>
                <div class="file-message__content">
                    <div class="file-message__content-title file-ticket" title="d5jor1164892531303042022.docx">
                        <div class="truncate">{{$item->file}}</div>
                    </div>
                    <div class="file-message__content-info-container">
                        <div class="file-message__content-info file-message__content-info-preview"><span
                                        class="file-message__content-info-size" title="11.65 KB">11.65 KB</span>
                            <span class="file-message__content-actions flx-e none"><a
                                        class="clickable file-message__actions download"
                                        data-translate-title="STR_DOWNLOAD_FILE" title="Lưu về máy">
                                    <i class="fa fa-download" aria-hidden="true"></i>

                                </a>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>