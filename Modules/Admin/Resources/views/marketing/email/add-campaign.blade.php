@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-email.png')}}" alt="" style="height: 20px;"> {{__('EMAIL')}}</span>
@stop
@section('content')

    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('THÊM CHIẾN DỊCH')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-add">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">

                            <label class="black-title">{{__('Tên chiến dịch')}}:<b class="text-danger">*</b></label>

                            <input class="form-control" id="name" name="name"
                                   placeholder="{{__('Hãy nhập tên chiến dịch')}}...">
                            <span class="error_slug" style="color: #ff0000"></span>


                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">{{__('Chi nhánh')}}:<b class="text-danger">*</b></label>
                            <div class="input-group">
                                <select name="branch_id" id="branch_id" class="form-control m-input"
                                        style="width: 100%">
                                    <option value="">{{__('Chọn chi nhánh')}}</option>
                                    @foreach($optionBranch as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">{{__('Chi phí chiến dịch')}}:<b class="text-danger">*</b></label>
                            <div class="input-group m-input-group">
                                <input name="cost" id="cost"
                                       class="form-control m-input class"
                                       placeholder="{{__('Hãy nhập chi phí cho chiến dịch')}}"
                                       aria-describedby="basic-addon1">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Cho phép tạo deal'):<b class="text-danger">*</b>
                            </label>
                            <div>
                                 <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" id="is_deal_created" name="is_deal_created"
                                        onchange="add.changeCreateDeal();"
                                               class="manager-btn">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group m-form__group" id="popup_create_deal" hidden>
                            <a href="javascript:void(0)" onclick="add.popupCreateLead()" class="btn  btn-sm m-btn m-btn--icon btn-add-phone2 color">
                                <i class="la la-plus"></i>@lang('Thêm thông tin deal')
                            </a>
                        </div>
                        <div class="form-group m-form__group">
                            <label>{{__('Tham số')}}:</label>
                            <div class="row">
                                <div class="col-md-5 col-xs-6 m--margin-top-10">
                                    <button type="button" class="btn btn-secondary active param_email_auto"
                                            onclick="add.append_para('{name}')"
                                            style="width: 100%">{{__('Tên khách hàng')}}
                                    </button>
                                </div>
                                <div class="col-md-5 col-xs-6 m--margin-top-10">
                                    <button type="button" class="btn btn-secondary active param_email_auto"
                                            onclick="add.append_para('{full_name}')"
                                            style="width: 100%">{{__('Họ & Tên')}}
                                    </button>
                                </div>
                                <div class="col-md-5 col-xs-6 m--margin-top-10">
                                    <button type="button" class="btn btn-secondary active param_email_auto"
                                            onclick="add.append_para('{gender}')"
                                            style="width: 100%">{{__('Giới tính')}}
                                    </button>
                                </div>
                                <div class="col-md-5 col-xs-6 m--margin-top-10">
                                    <button type="button" class="btn btn-secondary active param_email_auto"
                                            onclick="add.append_para('{birthday}')"
                                            style="width: 100%">{{__('Ngày sinh')}}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black-title">{{__('Nội dung mẫu')}}:</label>
                            <div class="m-scrollable m-scroller ps ps--active-y scroll_son" data-scrollable="true"
                                 style="height: 280px; overflow: hidden;">
                                <div class="content" id="content" name="content"></div>
                            </div>
                            {{--<textarea class="form-control" cols="50" rows="15" id="content"--}}
                            {{--name="content">--}}
                            {{----}}
                            {{--</textarea>--}}
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label>{{__('Thời gian gửi')}}:<b class="text-danger">*</b></label>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <div class="input-group date">
                                <input type="text" readonly class="form-control m-input" placeholder="{{__('Chọn ngày gửi')}}"
                                       id="day_sent" name="day_sent" value="{{date('d/m/Y')}}">
                                <div class="input-group-append">
                        <span class="input-group-text">
                        <i class="la la-calendar"></i>
                        </span>
                                </div>

                            </div>
                            <span class="error_time" style="color: #ff0000">

                            </span>
                            <div class="m-checkbox-list m--margin-top-10">
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success">
                                    <input type="checkbox" id="is_now" name="is_now" value="0"> {{__('Gửi ngay')}}
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group  col-lg-6">
                            <div class="input-group timepicker">
                                <input class="form-control m-input" id="time_sent" name="time_sent" readonly=""
                                       placeholder="Chọn giờ gửi..."
                                       type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid m--align-right">
                        <a href="{{route('admin.email')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button" onclick="add.submit_add()"
                                class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add m--margin-left-10">
							<span>
							<i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>

        </form>
        <div id="my-modal-create">

        </div>
        <input type="hidden" id="load-modal-create" value="0">
    </div>

@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/marketing/email/add.js')}}" type="text/javascript"></script>

    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="dealEmail.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25%;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="dealEmail.changeObject(this)">
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
                <a href="javascript:void(0)" onclick="dealEmail.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop