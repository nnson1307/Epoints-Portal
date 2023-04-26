<div class="modal fade" id="edit_message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12">
                    <h4 class="modal-title text-center mb-5" id="exampleModalLabel">{{ __('managerwork::managerwork.update_notification') }}</h4>
                </div>
                <div class="col-12 form-group">
                    <label>{{ __('managerwork::managerwork.notice_items') }}</label>
                    <input type="text" class="form-control" id="manage_config_notification_title_popup" name="manage_config_notification_title_popup" value="{{$detail['manage_config_notification_title']}}">
                </div>
                <div class="col-12 form-group">
                    <label>{{__('managerwork::managerwork.content_notification')}}</label>
                    <textarea class="form-control" rows="10" id="manage_config_notification_message_popup" name="manage_config_notification_message_popup">{!! $detail['manage_config_notification_message'] !!}</textarea>
                </div>
                <div class="col-12 mt-3">
                    @if($detail['manage_config_notification_key'] == 'work_finish')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_work_title]')">
                            {{ __('managerwork::managerwork.work_name') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[updated_name]')">
                            {{ __('managerwork::managerwork.staff_update') }}
                        </a>
                    @elseif($detail['manage_config_notification_key'] == 'work_assign')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[created_name]')">
                            {{ __('managerwork::managerwork.staff_created') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_work_title]')">
                            {{ __('managerwork::managerwork.work_name') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[processor_name]')">
                            {{ __('managerwork::managerwork.staff_processor') }}
                        </a>
                    @elseif($detail['manage_config_notification_key'] == 'work_update_status')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_work_title]')">
                            {{ __('managerwork::managerwork.work_name') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[updated_name]')">
                            {{ __('managerwork::managerwork.staff_update') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_status_name]')">
                            {{ __('managerwork::managerwork.status') }}
                        </a>
                    @elseif($detail['manage_config_notification_key'] == 'comment_new')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_work_title]')">
                            {{ __('managerwork::managerwork.work_name') }}
                        </a>
                    @elseif($detail['manage_config_notification_key'] == 'comment_tag')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[created_name]')">
                            {{ __('managerwork::managerwork.staff_created') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_work_title]')">
                            {{ __('managerwork::managerwork.work_name') }}
                        </a>
                    @elseif($detail['manage_config_notification_key'] == 'file_new')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_work_title]')">
                            {{ __('managerwork::managerwork.work_name') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[updated_name]')">
                            {{ __('managerwork::managerwork.staff_update') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[file_name]')">
                            {{ __('managerwork::managerwork.file_name') }}
                        </a>
                    @elseif($detail['manage_config_notification_key'] == 'work_expire')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_work_title]')">
                            {{ __('managerwork::managerwork.work_name') }}
                        </a>
                    @elseif($detail['manage_config_notification_key'] == 'work_update_description')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[manage_work_title]')">
                            {{ __('managerwork::managerwork.work_name') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[updated_name]')">
                            {{ __('managerwork::managerwork.staff_update') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[description]')">
                            {{ __('managerwork::managerwork.content') }}
                        </a>
                    @elseif(in_array($detail['manage_config_notification_key'],['total_work_assign','total_work_overdue']))
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[staff_name]')">
                            {{ __('managerwork::managerwork.staff') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[total_work]')">
                            {{ __('managerwork::managerwork.quantity') }}
                        </a>
                    @elseif($detail['manage_config_notification_key'] == 'work_remind')
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[created_name]')">
                            {{ __('managerwork::managerwork.staff_created') }}
                        </a>
                        <a href="javascript:void(0)"
                           class="btn m--margin-right-10 btn-sm ss--btn-parameter ss--font-weight-200"
                           style="color: black;"
                           onclick="Notification.append_para_txa('[description]')">
                            {{ __('managerwork::managerwork.content_remind') }}
                        </a>
                    @endif
                </div>
            </div>
            <input type="hidden" id="text_error_popup" value="{{ __('managerwork::managerwork.enter_content_notification') }}">
            <input type="hidden" id="title_error_popup" value="{{ __('managerwork::managerwork.enter_title_notification') }}">
            <input type="hidden" id="title_191_error_popup" value="{{ __('managerwork::managerwork.enter_title_notification_191') }}">
            <input type="hidden" id="manage_config_notification_id_popup" value="{{$detail['manage_config_notification_id']}}">
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('managerwork::managerwork.cancel') }}</button>
                <button type="button" class="btn ss--btn-search" onclick="Notification.changeMessage()">{{ __('managerwork::managerwork.save') }}</button>
            </div>
        </div>
    </div>
</div>