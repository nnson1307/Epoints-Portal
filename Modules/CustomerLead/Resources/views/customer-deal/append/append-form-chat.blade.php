<tr class="form-chat-message">
    <td></td>
    <td>
        <div class="col-12 mb-1">
            <textarea id="description-en" name="description" type="text" class="form-control m-input class description_{{$deal_comment_id}}"
              placeholder="{{ __('managerwork::managerwork.enter_comment') }}"
              aria-describedby="basic-addon1"></textarea>
        </div>
        <div class="col-12 text-right">
            <button type="button" onclick="DealComment.addCommentChild({{$deal_comment_id}})" class=" mt-3 ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">{{ __('managerwork::managerwork.sent') }}</button>
        </div>
    </td>
</tr>
