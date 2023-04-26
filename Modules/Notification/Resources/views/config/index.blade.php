@extends('layout')
@section('title_header')

@endsection
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

        /*.modal-lg {*/
        /*max-width: 65% !important;*/
        /*}*/
        .nav-tabs .nav-item:hover , .fa-plus-circle:hover , .kt-checkbox input:hover{
            cursor: pointer;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link.active {
            color:#6f727d !important;;
            border-bottom: #6f727d !important;;
            background: #EEF3F9 !important;;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link {
            padding: 15px;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-institution"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CẤU HÌNH THÔNG BÁO TỰ ĐỘNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body p-0">
            <ul class="nav nav-tabs nav-pills mb-3" role="tablist" style="margin-bottom: 0;">
                <li class="nav-item">
                    <a href="javascript:void(0)" onclick="changeTabNoti('common')" class="nav-link common-tab tab-noti {{ $tab != 'contract' ? 'active' : '' }}">{{__('Cấu hình chung')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('manager-work.manage-config.notification')}}">{{__('Công việc')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('config-noti')}}">{{__('Chấm công')}}</a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" onclick="changeTabNoti('contract')" class="nav-link contract-tab tab-noti {{ $tab == 'contract' ? 'active' : '' }}">{{__('Hợp đồng')}}</a>
                </li>
            </ul>

            <div class="col-12 p-3" id="notify-common" {{$tab == 'contract' ? 'hidden' : ''}}>
                @if(count($dataGroup) > 0)
                    @foreach($dataGroup as $item)
                        <h3 class="m--font-success">{{$item['config_notification_group_name']}}</h3>
                        @if(isset($dataConfig[$item['config_notification_group_id']]) && $dataConfig[$item['config_notification_group_id']] > 0)
                            @foreach($dataConfig[$item['config_notification_group_id']] as $v)

                                <div class="m-widget4 form-group">
                                    <div class="m-widget4__item ss--background-config-sms">
                                        <div class="m-widget4__checkbox  m--margin-left-15">
                                            <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success">
                                                <input type="checkbox" {{$v['is_active'] == 1 ? 'checked' : ''}}
                                                onchange="index.changeStatus('{{$v['key']}}', this)">
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="m-widget4__info">
                                            <div class="row">
                                                <div class="col-lg-4">
                                <span class="m-widget4__title sz_dt">
                                        {{$v['name']}}
                                </span><br>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="row">
                                                        <div class="col-lg-11">
                                                            <label class="sz_sms">{{__('Nội dung thông báo')}}</label>

                                                            <textarea placeholder="{{__('Nội dung tin nhắn')}}" readonly rows="3"
                                                                      name="message-new-calendar"
                                                                      id="message-new-calendar"
                                                                      class="form-control m-input ss--background-color">{{$v['message']}}</textarea>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            {{--                                                        @if(in_array('admin.sms.get-config',session('routeList')))--}}
                                                            <a href="{{route('config.edit', $v['key'])}}"
                                                               style="color: #a1a1a1;float: right" title="Chỉnh sửa"><i
                                                                        class="la la-edit"></i></a>
                                                            {{--                                                        @endif--}}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="tab-pane" id="notify-contract" {{$tab != 'contract' ? 'hidden' : ''}} role="tabpanel">
                <button onclick="saveConfig()"
                        class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc float-right m-3">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('LƯU CẤU HÌNH')</span>
                                    </span>
                </button>
                <div class="table-content m--padding-top-15">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('THÔNG BÁO CÁC HẠN MỤC BÊN DƯỚI CHO')</th>
                                <th class="tr_thead_list text-center">@lang('HÌNH THỨC THÔNG BÁO')</th>
                                <th class="tr_thead_list text-center">@lang('NỘI DUNG THÔNG BÁO')</th>
                                <th class="tr_thead_list text-center">@lang('NGƯỜI TẠO')</th>
                                <th class="tr_thead_list text-center">@lang('NGƯỜI THỰC HIỆN')</th>
                                <th class="tr_thead_list text-center">@lang('NGƯỜI THEO DÕI')</th>
                                <th class="tr_thead_list text-center">@lang('NGƯỜI KÝ')</th>
                            </tr>
                            </thead>
                            <tbody style="font-size: 13px" id="notify-contract">
                            @foreach($lstNotifyContract as $key => $value)
                                <tr>
                                    <td hidden class="check_{{$value['contract_notify_config_id']}}"></td>
                                    <td hidden class="contract_notify_config_id">{{$value['contract_notify_config_id']}}</td>
                                    <td class="contract_notify_config_name">{{$value['contract_notify_config_name']}}</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="email" {{$value['email'] == '1' ? 'checked' : ''}}>
                                        <span><i class="far fa-envelope"></i></span>
                                        <span class="pl-3"></span>
                                        <input type="checkbox" name="notify" {{$value['notify'] == '1' ? 'checked' : ''}}>
                                        <span><i class="fas fa-bell"></i></span>
                                    </td>
                                    <td class="row">
                                            <textarea id="contract_notify_config_content"
                                                      name="contract_notify_config_content"
                                                      class="col-lg-10 form-control m-input ss--background-color"
                                                      rows="3">{{$value['contract_notify_config_content']}}</textarea>
                                        <label class="col-lg-2" onclick="editContent('{{$value['contract_notify_config_id']}}', '{{$value['contract_notify_config_content']}}')">
                                                <span>
                                                    <i class="la la-edit"></i>
                                                </span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="is_created_by" {{$value['is_created_by'] == '1' ? 'checked' : ''}}
                                                {{$value['contract_notify_config_code'] == 'need_approved' ? 'disabled': ''}}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="is_performer_by" {{$value['is_performer_by'] == '1' ? 'checked' : ''}}
                                                {{$value['contract_notify_config_code'] == 'need_approved' ? 'disabled': ''}}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="is_follow_by" {{$value['is_follow_by'] == '1' ? 'checked' : ''}}
                                                {{$value['contract_notify_config_code'] == 'need_approved' ? 'disabled': ''}}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="is_signer_by" {{$value['is_signer_by'] == '1' ? 'checked' : ''}}
                                                {{$value['contract_notify_config_code'] == 'need_approved' ? 'disabled': ''}}>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- end table-content -->
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/notification/config/script.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        function changeTabNoti(tab) {
            if(tab == 'contract'){
                $('#notify-contract').removeAttr('hidden');
                $('#notify-common').attr('hidden', true);
            }
            else{
                $('#notify-common').removeAttr('hidden');
                $('#notify-contract').attr('hidden', true);
            }

            $('.tab-noti').removeClass('active');
            $('.'+tab+'-tab').addClass('active');
        }
        function saveConfig() {
            $.getJSON(laroute.route('translate'), function (json) {
                var listConfig = [];
                $('tbody#notify-contract').find('tr').each(function(k, v){
                    let itemConfig = {
                        'contract_notify_config_id': $(this).find('.contract_notify_config_id').text(),
                        'email': $(this).find('input[name="email"]').is(":checked") ? 1 : 0,
                        'notify': $(this).find('input[name="notify"]').is(":checked") ? 1 : 0,
                        'contract_notify_config_content': $(this).find('textarea[name="contract_notify_config_content"]').val(),
                        'is_created_by': $(this).find('input[name="is_created_by"]').is(":checked") ? 1 : 0,
                        'is_performer_by': $(this).find('input[name="is_performer_by"]').is(":checked") ? 1 : 0,
                        'is_signer_by': $(this).find('input[name="is_signer_by"]').is(":checked") ? 1 : 0,
                        'is_follow_by': $(this).find('input[name="is_follow_by"]').is(":checked") ? 1 : 0,
                    };
                    listConfig.push(itemConfig);
                    itemConfig = {};
                });
                $.ajax({
                    url: laroute.route('config.submit-notify-contract'),
                    data: {
                        'listConfig': listConfig
                    },
                    method: 'POST',
                    dataType: "JSON",
                    success: function (res) {
                        if (res.error == false) {
                            swal.fire(res.message, "", "success");
                        } else {
                            swal.fire(res.message, '', "error");
                        }
                        $('a[href="#notify-contract"]').trigger('click');
                    }
                });

            });
        }
        function editContent(id, content) {
            $.getJSON(laroute.route('translate'), function (json) {
                var tpl = $('#tpl-modal-content').html();
                tpl = tpl.replace(/{content}/g, content.trim());
                tpl = tpl.replace(/{id}/g, id);
                $('#my-modal').html(tpl);
                $('#edit-contract-content').modal('show');
                $('#pop_parameter_for_content').select2({
                    placeholder: json['Chọn nội dụng mẫu']
                });
            });
        }
        function appendContent() {
            var currentContent = $('#pop_content').val();
            var newSelected = $('#pop_parameter_for_content').val();
            newSelected.forEach(e => {
                if(currentContent.indexOf(`${e}`) === -1){
                    currentContent = currentContent + ' ' +  e ;
                }
            });
            $('#pop_content').val(currentContent);
        }
        function saveContent(id) {
            var content = $('#pop_content').val();
            $(`.check_${id}`).parent('tr').find('textarea[name="contract_notify_config_content"]').val(content);
            $('#edit-contract-content').modal('hide');
        }
    </script>

    <script type="text/template" id="tpl-modal-content">
        <div class="modal fade show" id="edit-contract-content" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title color_title" id="exampleModalLabel">
                            <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA NỘI DUNG')
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        @lang('Nội dung'):
                                    </label>
                                    <div class="input-group-prepend row" style="margin-left: -3px;">
                                        <div class="m-input-icon m-input-icon--right col-lg-12">
                                            <select class="form-control" id="pop_parameter_for_content" name="pop_parameter_for_content" style="width:100%;"
                                                    multiple
                                                    onchange="appendContent();">
                                                <option value="{creator}">@lang('Người tạo')</option>
                                                <option value="{receiver}">@lang('Người nhận')</option>
                                                <option value="{contract_code}_{contract_title}">@lang('Mã hợp đồng_Tiêu đề hợp đồng')</option>
                                                <option value="{updater}">@lang('Người cập nhật')</option>
                                                <option value="{approve_by}">@lang('Người thực hiện duyệt')</option>
                                                <option value="{reason_deny}">@lang('Lý do từ chối')</option>
                                                <option value="{performer}">@lang('Người thực hiện')</option>
                                                <option value="{follower}">@lang('Người theo dõi')</option>
                                                <option value="{signed_by}">@lang('Người ký')</option>
                                                <option value="{tab_contract}">@lang('Thẻ hợp đồng')</option>
                                            </select>
                                        </div>
                                        <textarea class="form-control col-lg-12" placeholder="{{__('Nội dung')}}"
                                                  id="pop_content"
                                                  name="pop_content" style="height: 75px">{content}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="m-form__actions m--align-right w-100">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>
                            <button type="button" onclick="saveContent({id})"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
@stop
