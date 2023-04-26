<tr class="{{isset($data['manage_parent_comment_id']) ? 'tr_child_'.$detail['manage_comment_id'] : 'tr_'.$detail['manage_comment_id']}}">
    <td>
        <img tabindex="-1" style="height: 40px;border-radius: 50%" src="{{$detail['staff_avatar']}}"
             onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($detail['staff_name']),0,1))}}';">
    </td>
    <td>
        <p>{{$detail['staff_name']}}</p>
        <div class="message_work_detail">
            {!! $detail['message'] !!}
            @if(isset($detail['path']))
                <p class="message_work_path">
                    {{$detail['path']}}
                </p>
            @endif
        </div>
        <p class="mb-0">
            @if(!isset($data['manage_parent_comment_id']))
                <a href="javascript:void(0)" class="reply_message" onclick="Comment.showFormChat({{$detail['manage_comment_id']}})">{{ __('managerwork::managerwork.answer') }} </a>
            @endif
            {{\Carbon\Carbon::parse($detail['created_at'])->diffForHumans(\Carbon\Carbon::now()) }}
        </p>
    </td>
</tr>
@if(!isset($data['manage_parent_comment_id']))
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
                <tbody class="tr_child_{{$detail['manage_comment_id']}}">
                </tbody>
            </table>
        </td>
    </tr>
@endif