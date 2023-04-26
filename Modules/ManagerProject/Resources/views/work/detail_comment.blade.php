<nav class="nav">
    <a class="hover-cursor nav-link active" onclick="ChangeTab.tabComment('comment')">{{ __('managerwork::managerwork.comment') }}</a>
    <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('document')">{{ __('managerwork::managerwork.document') }}</a>
    <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('remind')">{{ __('managerwork::managerwork.remind') }}</a>

    @if($detail['parent_id'] == null)
        <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('sub_task')">{{ __('managerwork::managerwork.child_task') }}</a>
    @endif
    <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('history')">{{ __('managerwork::managerwork.history') }}</a>
</nav>
<div class="col-12 mt-3 ml-2">
    <div class="row scroll-chat">
        <table class="table table-message table-message-main">
            <thead>
            <tr>
                <th width="2%" style="padding:0 !important;"></th>
                <th width="90%" style="padding:0 !important;"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($listComment as $item)
                <tr class="tr_{{$item['manage_comment_id']}}">
                    <td>
                        <img tabindex="-1" style="height: 40px;border-radius: 50%" src="{{$item['staff_avatar']}}"
                             onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($item['staff_name']),0,1))}}';">
                    </td>
                    <td>
                        <p>{{$item['staff_name']}}</p>
                        <div class="message_work_detail">
                            {!! $item['message'] !!}
                            @if(isset($item['path']))
                                <p class="message_work_path">
                                    <img src="{{$item['path']}}" style="width:200px">
                                </p>
                            @endif
                        </div>
                        <p class="mb-0"><a href="javascript:void(0)" class="reply_message" onclick="Comment.showFormChat({{$item['manage_comment_id']}})">{{ __('managerwork::managerwork.answer') }} </a> {{\Carbon\Carbon::parse($item['created_at'])->diffForHumans(\Carbon\Carbon::now()) }}</p>
                    </td>
                </tr>
                {{--                            @if(count($item['child_comment']) != 0)--}}
                <tr>
                    <td></td>
                    <td>
                        <table class="table-message">
                            <thead>
                            <tr>
                                <th width="3%" style="padding:0 !important;"></th>
                                <th width="90%" style="padding:0 !important;"></th>
                            </tr>
                            </thead>
                            <tbody class="tr_child_{{$item['manage_comment_id']}}">
                            @foreach($item['child_comment'] as $itemChild)
                                <tr>
                                    <td>
                                        <img tabindex="-1" style="height: 40px;border-radius: 50%" src="{{$itemChild['staff_avatar']}}"
                                             onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($itemChild['staff_name']),0,1))}}';">
                                    </td>
                                    <td>
                                        <p>{{$itemChild['staff_name']}}</p>
                                        <div class="message_work_detail">
                                            {!! $itemChild['message'] !!}
                                            @if(isset($itemChild['path']))
                                                <p class="message_work_path">
                                                    <img src="{{$itemChild['path']}}" style="width:200px">
                                                </p>
                                            @endif
                                        </div>

                                        <p class="mb-0">{{\Carbon\Carbon::parse($itemChild['created_at'])->diffForHumans(\Carbon\Carbon::now()) }}</p>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                {{--                            @endif--}}
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="col-12 mb-1">
                    <textarea id="description-en" name="description" type="text" class="form-control m-input class description"
                              placeholder="{{ __('managerwork::managerwork.enter_comment') }}"
                              aria-describedby="basic-addon1"></textarea>
</div>
<div class="col-12 mb-5 text-right">
    <button type="button" onclick="Comment.addComment()" class=" mt-3 ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">{{ __('managerwork::managerwork.sent') }}</button>
</div>

<script src="{{asset('static/backend/js/manager-project/managerWork/detail-work-comment.js')}}"></script>
<script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
<script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work.js?v=' . time()) }}" type="text/javascript"></script>
<script>
    function registerSummernote(element, placeholder, max, callbackMax) {
        $('.description').summernote({
            placeholder: '',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname', 'fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
            ],
            callbacks: {
                onImageUpload: function (files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadImgCk(files[i]);
                    }
                },
                onKeydown: function (e) {
                    var t = e.currentTarget.innerText;
                    if (t.length >= max) {
                        //delete key
                        if (e.keyCode != 8)
                            e.preventDefault();
                        // add other keys ...
                    }
                },
                onKeyup: function (e) {
                    var t = e.currentTarget.innerText;
                    if (typeof callbackMax == 'function') {
                        callbackMax(max - t.length);
                    }
                },
                onPaste: function (e) {
                    var t = e.currentTarget.innerText;
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    // var all = t + bufferText;
                    var all = bufferText;
                    document.execCommand('insertText', false, all.trim().substring(0, max - t.length));
                    // document.execCommand('insertText', false, bufferText);
                    if (typeof callbackMax == 'function') {
                        callbackMax(max - t.length);
                    }
                }
            },
        });
    }

    $(function(){
        registerSummernote('.description', 'Leave a comment', 1000, function(max) {
            $('.description').text(max)
        });
    });
</script>
