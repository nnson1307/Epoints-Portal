
@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">ChatHub</span>
@stop
@section("after_style")

    <link href="{{asset('static/backend/css/chathub/message/message.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('static/backend/css/chathub/message/scroll.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('static/backend/css/chathub/message/custom.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('static/backend/css/chathub/message/perpect-croll.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('static/backend/css/chathub/message/style.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>


    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('content')
<style>
    .form-control-feedback {
        width: 100%;
        position:relative;
        text-align:left;
    }
    /*.modal-backdrop {*/
    /*    position: relative !important;*/
    /*}*/
    /* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
{{-- <div class="alert alert-info"></div> --}}
<!--begin::Portlet-->
<div class="d-flex">
    <div class="m-portlet m-portlet--head-sm col-4 mr-2 mb-0">
        <div class="m-portlet__body s-height-mes mt-3">
            <div class="table-content m--padding-top-10 s-height-mes">
                <div class="kt-searchbar row">
                    <div class="col-12 m-input-icon m-input-icon--left">
                        <form action="{{route('message')}}" method="GET" class="m-form m-form--fit m--margin-bottom-20 d-flex">
                            <input autocomplete="off" type="text" id="search_message" name="search_message" class="form-control m-input--pill m-input" value="{{ isset($_GET['search_message']) ? $_GET['search_message'] : '' }}" placeholder="@lang('chathub::message.index.SEARCH')">
                            <input type="hidden" id="channelSelect" name="channelSelect" value="{{@$channelSelect}}">
                            <input type="hidden" id="reading_type" name="reading_type" value="">
                            <button type="submit" class="btn m-btn m-btn--icon" id="m_search">
                                <span>
                                    <i class="la la-search"></i>
                                </span>
                            </button>
                        </form>
                    </div>
                    <div class="input-group col-6">
                        <select class="form-control" id="type_reading" name="type_reading" onchange="message.selectChannel()">
                                <option value="">Tất cả</option>
                                <option value="read">Đã đọc</option>
                                <option value="unread">Chưa đọc</option>
                                <option value="sent">Đã trả lời</option>
                        </select>
                    </div>
                    <div class="input-group col-6" id="channel-list">
                        <select class="form-control" id="selectChannel" onchange="message.selectChannel()">
                            @if($listChannel)
                                <option id="channel-null" value="null" @if($channelSelect==null)selected @endif>Tất cả</option>
                            @foreach($listChannel as $channel)
                                <option id="channel-{{$channel['channel_id']}}" value="{{$channel['channel_id']}}" @if($channelSelect==$channel['channel_id'])selected @endif>{{$channel['name']}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="kt-widget kt-widget--users kt-mt-20">
                    <div class="kt-scroll kt-scroll--pull scroll-chat s-scroll" id="scroll-customer" data-spy="scroll" data-offset="50" style="height: 520px; overflow: hidden;">
                        <div class="kt-widget__items" id="conversation-list">
                            @if($listCustomer)
                            <?php $isFirst = true?>
                            @foreach($listCustomer as $customer)
                                <div class="kt-widget__item @if($isFirst) bg-secondary @endif @if(!$isFirst && $customer['is_read']>0) new-message  @endif" id="customer_{{$customer['customer_id']}}" data-converid="1" onclick="message.handleClickConversation('{{$customer['customer_register_id']}}', '{{$customer['channel_id']}}')">
                                    <span class="kt-userpic kt-userpic--circle">
                                        <img src="{{$customer['avatar']}}" alt="image">
                                    </span>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__section">
                                            <a href="javascript:void(0);" class="kt-widget__username" data-convername="{{$customer['full_name']}}">{{$customer['full_name']}}</a>

                                            <span class="ml-3" id="{{$customer['customer_id']}}_{{$customer['channel_id']}}">@if($customer['is_read']>0)({{$customer['is_read']}})@endif</span>

                                        </div>
                                        <span class="kt-widget__desc" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color: {{$customer['is_read']>0 ? 'blue' : ''}};">
                                            @if($customer['content_type'] == 'image')
                                                <i class="fas fa-file-image"></i> Đã gửi một ảnh
                                            @elseif($customer['content_type'] == 'template')
                                                <?php
                                                $data = json_decode($customer['last_message'], true);
                                                echo $data[0]['title'];
                                                ?>
                                            @else
                                                {{$customer['last_message']}}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="kt-widget__action">
                                        {{-- $customer['is_read'] --}}
                                        @if($customer['is_read'] > 0)
                                            <i class="fas fa-circle" style="color:blue"></i>
                                        @elseif($customer['last_message_send'] == $customer['last_message'])

                                        @else
                                            <i class="fas fa-check-circle"></i>
                                        @endif
                                        {{$customer['channel_name']}}
                                        <span class="kt-widget__date">{{timeAgo($customer['last_time'])}}</span>
                                    </div>
                                </div>
                                <?php $isFirst = false ?>
                            @endforeach
                            @endif
                        </div>
                    <div class="ps__rail-x" style="left: 0px; bottom: -102px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 102px; right: -2px; height: 249px;"><div class="ps__thumb-y" tabindex="0" style="top: 73px; height: 176px;"></div></div></div>
                </div>
            </div><!-- end table-content -->
        </div>
    </div>
    <div class="m-portlet m-portlet--head-sm col-8 mb-0">
        <div class="kt-grid__item kt-grid__item--fluid kt-app__content s-height-mes" id="kt_chat_content">
            <div class="kt-chat">
                <div class="kt-portlet kt-portlet--head-lg kt-portlet--last ">
                    <div class="kt-portlet__head">
                        <div class="kt-chat__head ">
                            <div class="kt-chat__center">
                                <div class="kt-chat__label">
                                    <a href="javascript:void(0)" class="kt-chat__title"></a>
                                    <span class="kt-chat__status">

                                    </span>
                                </div>
                            </div>
                            <div class="kt-chat__right">
                                <div id="detail-button" class="hidden replace-channel">
{{--                                    <button onclick="message.getEditForm('{channel_id}')" type="button" class="btn btn-success" id="getEditForm" data-toggle="modal" data-target="#kt_modal_4"><i class="fas fa-user-cog"></i>@lang('chathub::message.index.DETAIL')</button>--}}
                                    <button onclick="message.getFormLead()" type="button" class="btn btn-success" id="getEditForm" data-toggle="modal" data-target="#kt_modal_4"><i class="fas fa-user-cog"></i>@lang('Tạo khách hàng tiềm năng')</button>
                                    <button onclick="message.getFormDeal()" type="button" class="btn btn-success"><i class="fas fa-user-cog"></i>@lang('Tạo cơ hội bán hàng')</button>

                                    <a href="javascript:void(0)" onclick="customer_appointment.click_modal()"
                                       class="btn btn-success m-btn m-btn--icon m-btn--pill">
                                        <span>
                                            <i class="la la-calendar-plus-o"></i>
                                            <span>{{__('Thêm lịch hẹn')}}</span>
                                        </span>
                                    </a>
                                </div>
                                <!--begin:: Aside Mobile Toggle -->
                                {{-- <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md kt-hidden-desktop" id="kt_chat_aside_mobile_toggle">

                                </button> --}}
                                <!--end:: Aside Mobile Toggle-->
                            </div>
                            <div class="kt-chat__right">
                                {{-- <div id="option-status-button">
                                    <button type="button" class="btn btn-success hidden" onclick="Chat.changeOptionStatus(7)"><i class="flaticon2-check"></i>Hoàn thành</button>
                                </div> --}}
                            </div>
                        </div>
                        <div data-container="body" data-toggle="kt-popover" data-placement="left" data-html="true" data-content="" data-original-title="">
                        </div>
                    </div>
                    <div class="kt-portlet__body p-3">

                        <div class="kt-scroll kt-scroll--pull scroll-chat s-scroll"  data-spy="scroll" data-mobile-height="300" id="chatBody" style="height: 420px; overflow: hidden;">
                            {{-- đoạn chat --}}
                            <div id="scroll" class="kt-chat__messages">
{{--                                @if($listMessage)--}}
{{--                                    @foreach($listMessage as $message)--}}
{{--                                        @if($message['type'] == 'send')--}}
{{--                                            <div class="kt-chat__message chat__message_id_{{$message['message_id']}} kt-chat__message--right">--}}
{{--                                                <div class="kt-chat__user">--}}
{{--                                                    <span class="kt-chat__datetime">{{$message['time']}}</span>--}}
{{--                                                    <a href="javascript:void(0)" class="kt-chat__username"><span>{{$message['channel_name']}}</span></a>--}}
{{--                                                    <span class="kt-userpic kt-userpic--circle kt-userpic--sm">--}}
{{--                                                        <img src="{{$message['channel_avatar']}}" alt="image">--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                <div class="kt-chat__text kt-bg-light-brand">--}}
{{--                                                    {{$message['content']}}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @else--}}
{{--                                            <div class="kt-chat__message chat__message_id_{{$message['message_id']}}">--}}
{{--                                                <div class="kt-chat__user">--}}
{{--                                                    <span class="kt-chat__datetime">{{$message['time']}}</span>--}}
{{--                                                    <a href="javascript:void(0)" class="kt-chat__username"><span>{{$message['full_name']}}</span></a>--}}
{{--                                                    <span class="kt-userpic kt-userpic--circle kt-userpic--sm">--}}
{{--                                                    <img src="{{$message['avatar']}}" alt="image">--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                <div class="kt-chat__text kt-bg-light-brand">--}}
{{--                                                    {{$message['content']}}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}

                            </div>
                            <div class="ps__rail-x" style="left: 0px; bottom: -55px;">
                                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;">
                                </div>
                            </div>
                            <div class="ps__rail-y" style="top: 55px; right: -2px; height: 444px;">
                                <div class="ps__thumb-y" tabindex="0" style="top: 144px; height: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot p-3" id="chatFoot" >
                        <div class="kt-chat__input">
                            <div id="image-file" class="d-flex"></div>
                            <div class="kt-chat__editor" style="height: 40px">
                                <textarea id="sent-message" class="hidden"  placeholder="@lang('chathub::message.index.TYPE')"></textarea>
                            </div>
                            <div class="kt-chat__toolbar">
                                <div id="tool" class="kt_chat__tools hidden">
                                    <a onclick="message.popupImage()"  href="javascript:void(0);" class="btn m-btn--square btn-outline-successsss m-btn m-btn--icon"><i class="fas fa-images"></i>
                                    </a>
                                    <a onclick="message.popupFile()"  href="javascript:void(0);" class="btn m-btn--square btn-outline-successsss m-btn m-btn--icon"><i class="flaticon-tool-1"></i>
                                    </a>
                                    {{-- <a href="javascript:void(0);" onclick="Message.popupFile()"><i class="flaticon-tool-1"></i>
                                    </a> --}}

                                    {{-- <a href="javascript:void(0);" onclick="Chat.writeText()"><i class="flaticon2-edit"></i></a> --}}
                                </div>
                                <div class="kt_chat__actions" style="height: 50px">
                                    {{-- check last message 24h -> hidden button by class hidden-send-button --}}
                                    <button id="submit-sent" onclick="message.sentMessage()" type="button" class="hidden btn btn-brand btn-md btn-upper btn-bold kt-chat__reply hidden-send-button">@lang('chathub::message.index.SEND')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden" id="list-customer">@if($listCustomer){{count($listCustomer)}}@else 0 @endif</div>
<div class="hidden" id="message_id"></div>

<div id="show-form"></div>
<div id="form-deal"></div>
<div id="show-modal"></div>
@include('chathub::message.add-image')
@include('chathub::message.add-file')
<div id="array-file-hidden"></div>
<div id="array-image-hidden"></div>
@stop

@section('after_script')
    <script src="{{asset('static/backend/js/chathub/message/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/chathub/message/dropzone-edit.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/chathub/message/add-file.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/chathub/message/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
    <script src="{{asset('static/backend/js/chathub/message/message.js?time='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
        message.channel_social_id = '{{@$currentChannel['channel_social_id']}}';
        message.channel_id = '{{@$currentChannel['channel_id']}}';
        message.customer_id = '{{@$currentCustomer['customer_id']}}';
        message.customer_social_id = '{{@$currentCustomer['register_object_id']}}';
        message.last_message_id = '{{@$lastMessage['message_id']}}';
        message.image_message_default = '{{asset('static/backend/images/trans-list-post.png')}}';
        message.init();

        @if(@$currentCustomer['customer_id'])
        message.handleClickConversation('{{$currentCustomer['customer_id']}}', '{{$currentChannel['channel_id']}}');
        @endif

        $('#scroll').scrollTop($('#scroll').height());
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>

    <script src="{{asset('static/backend/js/admin/customer-appointment/list-calendar.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text-template" id="tpl-phone">
        <div class="form-group m-form__group div_phone_attach">
            <div class="input-group">
                <input type="hidden" class="number_phone" value="{number}">
                <input type="text" class="form-control phone phone_attach" placeholder="@lang('Số điện thoại')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removePhone(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_phone_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text-template" id="tpl-email">
        <div class="form-group m-form__group div_email_attach">
            <div class="input-group">
                <input type="hidden" class="number_email" value="{number}">
                <input type="text" class="form-control email_attach" placeholder="@lang('Email')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removeEmail(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_email_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text-template" id="tpl-fanpage">
        <div class="form-group m-form__group div_fanpage_attach">
            <div class="input-group">
                <input type="hidden" class="number_fanpage" value="{number}">
                <input type="text" class="form-control fanpage_attach" placeholder="@lang('Fan page')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removeFanpage(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_fanpage_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text/template" id="tpl-type">
        <div class="form-group m-form__group">
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Mã số thuế'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="tax_code" name="tax_code"
                       placeholder="@lang('Mã số thuế')">
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Người đại diện'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="representative" name="representative"
                       placeholder="@lang('Người đại diện')">
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Hot line'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="hotline" name="hotline"
                       placeholder="@lang('Hot line')">
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-contact">
        <tr class="tr_contact">
            <td>
                <input type="hidden" class="number_contact" value="{number}">
                <input type="text" class="form-control m-input full_name_contact" placeholder="@lang('Họ và tên')">
                <span class="error_full_name_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input phone phone_contact" placeholder="@lang('Số điện thoại')">
                <span class="error_phone_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control email_contact" placeholder="@lang('Email')">
                <span class="error_email_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input address_contact" placeholder="@lang('Địa chỉ')">
                <span class="error_address_contact_{number} color_red"></span>
            </td>
            <td>
                <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" href="javascript:void(0)" onclick="view.removeContact(this)">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="dealMessage.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25%;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="dealMessage.changeObject(this)">
                    <option></option>
                </select>
                <span class="error_object color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input object_price" name="object_price" style="background-color: white;"
                       id="object_price_{stt}" value="" readonly>
                <input type="hidden" class="object_id" name="object_id">
            </td>
            <td style="width: 9%">
                <input type="text" class="form-control m-input btn-ct-input object_quantity" name="object_quantity"
                       id="object_quantity_{stt}" style="text-align: center" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_discount" name="object_discount"
                       id="object_discount_{stt}" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_amount" name="object_amount" style="background-color: white;"
                       id="object_amount_{stt}" value="" readonly>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="dealMessage.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="template_right">
        <div class="card" style="width: 18rem;float:left; margin-left: 10px">
            <img class="card-img-top" alt="Card image cap" src="{image_url}">
            <div class="card-body">
                <h5 class="card-title">{title}</h5>
                <p class="card-text">{subtitle}</p>
            </div>

            <div class="card-body">
                <a href="{buttons_url}" class="card-link">{buttons_title}</a>
            </div>
        </div>
    </script>
    <script type="text/template" id="right">
        <div class="kt-chat__message chat__message_id_{message_id} kt-chat__message--right">
            <div class="kt-chat__user">
                <span class="kt-chat__datetime">{time}</span>
                <a href="javascript:void(0)" class="kt-chat__username"><span>{full_name}</span></a>
                <span class="kt-userpic kt-userpic--circle kt-userpic--sm">
                    <img src="{avatar}" alt="image">
                </span>
            </div>
            <div class="kt-chat__text kt-bg-light-brand {col12}">
                {content}
            </div>
        </div>
    </script>
    <script type="text/template" id="left">
        <div class="kt-chat__message chat__message_id_{message_id}">
            <div class="kt-chat__user">
                <span class="kt-userpic kt-userpic--circle kt-userpic--sm">
                    <img src="{avatar}" alt="image">
                </span>
                <a href="javascript:void(0)" class="kt-chat__username"><span>{full_name}</span></a>
                <span class="kt-chat__datetime">{time}</span>
            </div>

            <div class="kt-chat__text {bg} {col12}">
                {content}
            </div>
        </div>
    </script>
    <script type="text/template" id="customer-add">
        <div class="kt-widget__item conversation {bg}" id="customer_{customer_id}" data-converid="1" onclick="message.handleClickConversation({register_object_id},{channel_id})">
            <span class="kt-userpic kt-userpic--circle">
                <img src="{avatar}" alt="image">
            </span>
            <div class="kt-widget__info">
                <div class="kt-widget__section">
                    <a href="javascript:void(0);" class="kt-widget__username" data-convername="{full_name_con}">{full_name}</a>
                    <span class="ml-3" id="{id}">{is_read}</span>
                </div>
                <span class="kt-widget__desc" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color: {text-color};">
                    {last_message}
                </span>
            </div>
            <div class="kt-widget__action">
                {icon_message}
                {channel_name}
                <span class="kt-widget__date">{last_time}</span>
            </div>
        </div>
    </script>
    <script  type="text/template" id="add-image">
        <div class="message-image ml-2">
            <div class="kt-avatar__holder bao-image">
                <img class="img-appp" src="{link}" style="height:40px">

            </div>
            <label class="ss--kt-avatar__uploads">
                <input type="hidden" name="image[]" class="image_center_path" value="{link}">
            </label>
            <label onclick="Upload.removeImage('{name}',this)" class="">
                <i class="la la-close"></i>
            </label>
            <a href="">file</a>
        </div>
    </script>
    <script  type="text/template" id="add-file">
        <div class="message-file ml-2">
            <div class="kt-avatar__holder bao-image">
                <img class="file-appp" src="{{asset('static/backend/images/file.png')}}" style="height:40px">
                <span>{name}</span>
            </div>
            <label class="ss--kt-avatar__uploads">
                <input type="hidden" name="file[]" class="file_center_path" value="{link}">
            </label>
            <label onclick="Upload.removeFile('{name}',this)" class="">
                <i class="la la-close"></i>
            </label>

        </div>
    </script>
    <script>
        // Dropzone.autoDiscover = false;
    </script>
    {{-- <script type="text/template" id="js-template-append-dropzone">
        <div class="m-dropzone__msg dz-message needsclick">
            <h3 class="m-dropzone__msg-title">{{__('Hình sản phẩm')}}</h3>
            <span class="m-dropzone__msg-desc">{{__('Vui lòng chọn hình ảnh')}}.</span>
        </div>
        <input type="hidden" id="file_image" name="product_image" value="file_name">
        <div id="temp">

        </div>
    </script> --}}
@endsection
