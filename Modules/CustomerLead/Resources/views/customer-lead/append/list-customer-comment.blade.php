<div class="col-12 mt-3 ml-2">
    <div style=" min-height: 50px !important; max-height: 400px !important; overflow-y: scroll; margin-bottom: 20px;">
        <table class="table table-message table-message-main">
            <thead>
            <tr>
                <th width="2%" style="padding:0 !important;"></th>
                <th width="90%" style="padding:0 !important;"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($listComment as $item)
                <tr class="tr_{{$item['customer_lead_comment_id']}}">
                    <td>
                        <img tabindex="-1" style="height: 40px; width: 40px;border-radius: 50%" src="{{$item['staff_avatar']}}"
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
                        <p class="mb-0"><a href="javascript:void(0)" class="reply_message" onclick="CustomerComment.showFormChat({{$item['customer_lead_comment_id']}})">{{ __('managerwork::managerwork.answer') }} </a> {{\Carbon\Carbon::parse($item['created_at'])->diffForHumans(\Carbon\Carbon::now()) }}</p>
                    </td>
                </tr>
                @if(count($item['child_comment']) != 0)
                <tr>
                    <td style="border-top: none;"></td>
                    <td style="border-top: none;">
                        <table class="table-message">
                            <tbody class="tr_child_{{$item['customer_lead_comment_id']}}">
                            @foreach($item['child_comment'] as $itemChild)
                                <tr>
                                    <td style="border-top: none;">
                                        <img tabindex="-1" style="height: 40px; width: 40px; border-radius: 50%" src="{{$itemChild['staff_avatar']}}"
                                             onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($itemChild['staff_name']),0,1))}}';">
                                    </td>
                                    <td style="border-top: none;">
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
                @endif
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
    <button type="button" onclick="CustomerComment.addComment()" class=" mt-3 ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
        {{ __('managerwork::managerwork.sent') }}
    </button>
</div>
