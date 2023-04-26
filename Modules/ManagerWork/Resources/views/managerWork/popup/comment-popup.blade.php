<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-comment ss--icon-title m--margin-right-5"></i>
            {{ __('Bình luận') }}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body" id="form-repeat-modal">
        <div class="max-height-400px mb-3">
            @if(isset($comment) && $comment)
                @foreach($comment as $comment_item)
                    <div class="d-flex mt-1 mb-1">
                        <div>
                            <img tabindex="-1" style="height: 40px;border-radius: 50%" src="{{$comment_item->staff_avatar}}"
                                 alt="{{$comment_item->full_name}}"
                                 onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name=d';">
                        </div>
                        <div>
                            <b class="ml-3">{{$comment_item->full_name}}</b>
                            <div class="message_work_detai ml-3">
                                {!! $comment_item->message !!}
                            </div>
                            <p class="mb-0 fz-10">{{\Carbon\Carbon::parse($comment_item->created_at)->diffForHumans(\Carbon\Carbon::now()) }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <form class="container" id="send_comment">
            <div class="row justify-content-center">
                {{--                <p class="lead emoji-picker-container">--}}
                {{--                    <textarea type="textbox" data-emoji-input="unicode" class="form-control" placeholder="Input field" data-emojiable="true"></textarea>--}}
                {{--                </p>--}}
                <div class="col-12 mb-1">
                    <input type="hidden" name="manage_work_id" id="manage_work_id_comment" value="{{$manage_work_id}}">
                    <textarea name="description" id="description_comment" type="text"
                              class="form-control m-input class description"
                              placeholder="{{ __('managerwork::managerwork.enter_comment') }}"
                              aria-describedby="basic-addon1"></textarea>
                </div>
                <div class="col-12 mb-5 text-right">
                    <button type="submit"
                            class=" mt-3 ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">{{ __('managerwork::managerwork.sent') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-arrow-left"></i>
                        <span>{{ __('HỦY') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>