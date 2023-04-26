@extends('layout')
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

    </style>
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .ui-autocomplete {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            float: left;
            display: none;
            min-width: 160px;
            padding: 4px 0;
            margin: 0 0 10px 25px;
            list-style: none;
            background-color: #ffffff;
            border-color: #ccc;
            border-color: rgba(0, 0, 0, 0.2);
            border-style: solid;
            border-width: 1px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;
            *border-right-width: 2px;
            *border-bottom-width: 2px;
        }

        .ui-menu-item > a.ui-corner-all {
            display: block;
            padding: 3px 15px;
            clear: both;
            font-weight: normal;
            line-height: 18px;
            color: #555555;
            white-space: nowrap;
            text-decoration: none;
        }

        .ui-state-hover, .ui-state-active {
            color: #ffffff;
            text-decoration: none;
            background-color: #0088cc;
            border-radius: 0px;
            -webkit-border-radius: 0px;
            -moz-border-radius: 0px;
            background-image: none;
        }
    </style>
    @include('admin::customer-appointment.add-refer')
    <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text" style="width: 200px">

                    </h3>
                    <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                        <span><i class="fa flaticon-plus"></i> {{__('THÊM LỊCH HẸN')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div>
                    <div onmouseover="onmouseoverAddNew()" onmouseout="onmouseoutAddNew()"
                         class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--open btn-hover-add-new"
                         m-dropdown-toggle="hover" aria-expanded="true">
                        <a href="#"
                           class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                            <i class="la la-plus m--hide"></i>
                            <i class="la la-ellipsis-h"></i>
                        </a>
                        <div class="m-dropdown__wrapper dropdow-add-new" style="z-index: 101;display: none">
                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"
                                  style="left: auto; right: 21.5px;"></span>
                            <div class="m-dropdown__inner">
                                <div class="m-dropdown__body">
                                    <div class="m-dropdown__content">
                                        <ul class="m-nav">
                                            <li class="m-nav__item">
                                                <a data-toggle="modal"
                                                   data-target="#refer" href="" class="m-nav__link">
                                                    <i class="m-nav__link-icon la la-user-plus"></i>
                                                    <span class="m-nav__link-text">{{__('Thêm người giới thiệu')}}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            {{--{!! Form::open(['route'=>'admin.service.submitAdd',"id"=>"form", 'class' => ' m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed ']) !!}--}}
            <form action="" method="post" id="formAdd" novalidate="novalidate" autocomplete="off">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-6">
                        <input type="hidden" id="customer_hidden" name="customer_hidden">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label>{{__('Số điện thoại')}}:<b class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="autocomplete" style="width:300px;">
                                        {{--<select class="form-control service" name="phone1" id="phone1"--}}
                                        {{--multiple="multiple">--}}

                                        {{--</select>--}}
                                        <input type="number" class="form-control"
                                               onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                               name="phone1" id="phone1">
                                    </div>
                                </div>
                                <span class="error-phone1" style="color: red;"></span>
                            </div>
                            <div class="col-lg-6">
                                <label>{{__('Tên khách hàng')}}:<b class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input class="form-control" name="full_name" id="full_name"
                                               placeholder="{{__('Họ và tên')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                        class="la la-user"></i></span></span>
                                    </div>
                                </div>
                                @if ($errors->has('full_name'))
                                    <span class="form-control-feedback">
                                            {{ $errors->first('full_name') }}
                                        </span>
                                    <br>
                                @endif
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="form-group col-lg-6">
                                <label>{{__('Ngày hẹn')}}:<b class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input class="form-control m-input" name="date" id="date" readonly
                                               placeholder="{{__('Hãy chọn ngày hẹn')}}" type="text"
                                               value="">
                                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                                        class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  col-lg-6">
                                <label>{{__('Giờ hẹn')}}:<b class="text-danger">*</b></label>
                                <div class="input-group m-input-group">
                                    {{--<input class="form-control" name="time" id="time" readonly--}}
                                    {{--placeholder="Hãy chọn giờ hẹn">--}}
                                    <select id="time"
                                            name="time"
                                            class="form-control" title="{{__('Chọn giờ hẹn')}}">
                                        <option></option>
                                        <option value="07:00">07:00</option>
                                        <option value="07:15">07:15</option>
                                        <option value="07:30">07:30</option>
                                        <option value="07:45">07:45</option>
                                        <option value="08:00">08:00</option>
                                        <option value="08:15">08:15</option>
                                        <option value="08:30">08:30</option>
                                        <option value="08:45">08:45</option>
                                        <option value="09:00">09:00</option>
                                        <option value="09:15">09:15</option>
                                        <option value="09:30">09:30</option>
                                        <option value="09:45">09:45</option>
                                        <option value="10:00">10:00</option>
                                        <option value="10:15">10:15</option>
                                        <option value="10:30">10:30</option>
                                        <option value="10:45">10:45</option>
                                        <option value="11:00">11:00</option>
                                        <option value="11:15">11:15</option>
                                        <option value="11:30">11:30</option>
                                        <option value="11:45">11:45</option>
                                        <option value="12:00">12:00</option>
                                        <option value="12:15">12:15</option>
                                        <option value="12:30">12:30</option>
                                        <option value="12:45">12:45</option>
                                        <option value="13:00">13:00</option>
                                        <option value="13:15">13:15</option>
                                        <option value="13:30">13:30</option>
                                        <option value="13:45">13:45</option>
                                        <option value="14:00">14:00</option>
                                        <option value="14:15">14:15</option>
                                        <option value="14:30">14:30</option>
                                        <option value="14:45">14:45</option>
                                        <option value="15:00">15:00</option>
                                        <option value="15:15">15:15</option>
                                        <option value="15:30">15:30</option>
                                        <option value="15:45">15:45</option>
                                        <option value="16:00">16:00</option>
                                        <option value="16:15">16:15</option>
                                        <option value="16:30">16:30</option>
                                        <option value="16:45">16:45</option>
                                        <option value="17:00">17:00</option>
                                        <option value="17:15">17:15</option>
                                        <option value="17:30">17:30</option>
                                        <option value="17:45">17:45</option>
                                        <option value="18:00">18:00</option>
                                        <option value="18:15">18:15</option>
                                        <option value="18:30">18:30</option>
                                        <option value="18:45">18:45</option>
                                        <option value="19:00">19:00</option>
                                        <option value="19:15">19:15</option>
                                        <option value="19:30">19:30</option>
                                        <option value="19:45">19:45</option>
                                        <option value="20:00">20:00</option>
                                        <option value="20:15">20:15</option>
                                        <option value="20:30">20:30</option>
                                        <option value="20:45">20:45</option>
                                        <option value="21:00">21:00</option>
                                        <option value="21:15">21:15</option>
                                        <option value="21:30">21:30</option>
                                        <option value="21:45">21:45</option>
                                        <option value="22:00">22:00</option>
                                        {{--@foreach($optionTime as $key=>$value)--}}
                                        {{--<option value="{{$key}}">{{$value}}</option>--}}
                                        {{--@endforeach--}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label>{{__('Nhân viên phục vụ')}}</label>
                                <div class="input-group m-input-group">
                                    <select class="form-control" name="staff_id" id="staff_id"
                                            title="{{__('Chọn nhân viên phục vụ')}}">
                                        <option></option>
                                        @foreach($optionStaff as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label>{{__('Phòng')}}</label>
                                <div class="input-group m-input-group">
                                    <select class="form-control" name="room_id" id="room_id"
                                            title="{{__('Chọn phòng')}}">
                                        <option></option>
                                        @foreach($optionRoom as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>{{__('Dịch vụ')}}:</label>
                            <div class="input-group m-input-group m-input-group--solid">
                                <select class="form-control service" name="service_id" id="service_id">
                                    <option></option>
                                    @if(count($optionService)>0)
                                        @foreach($optionService as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @if ($errors->has('service_id'))
                                <span class="form-control-feedback">
                                            {{ $errors->first('service_id') }}
                                        </span>
                                <br>
                            @endif
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label>{{__('Người giới thiệu')}}:</label>
                            <select id="search_refer" name="search_refer" style="width: 100%" lang="vi">

                            </select>
                            <div>
                                <div id="div-refer" class="input-group m-input-group m-input-group--solid">
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="input-group m-input-group m-input-group--solid">
                                <div class="btn-group btn-group-toggle source" data-toggle="buttons">
                                    <label class="btn btn-primary active " id="phone_source">
                                        <input type="radio" name="customer_appointment_source" id="option1" value="phone"
                                               autocomplete="off" checked=""> {{__('Đặt lịch trước')}}
                                    </label>
                                    <label class="btn btn-default" id="live_source">
                                        <input type="radio" name="customer_appointment_source" id="option2" value="live"
                                               autocomplete="off"> {{__('Đến trực tiếp')}}
                                    </label>
                                    {{--<label class="btn btn-default" id="cancel">--}}
                                    {{--<input type="radio" name="status" id="option3" value="cancel"--}}
                                    {{--autocomplete="off"> {{__('Hủy')}}--}}
                                    {{--</label>--}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>{{__('Trạng thái')}}:</label>
                            <div class="input-group m-input-group m-input-group--solid" >
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-success active" id="new" onclick="customer_appointment.new_click()">
                                        <input type="radio" name="status" id="option1" value="new"
                                               autocomplete="off" checked=""> {{__('Mới')}}
                                    </label>
                                    <label class="btn btn-default" id="confirm" onclick="customer_appointment.confirm_click()">
                                        <input type="radio" name="status" id="option2" value="confirm"
                                               autocomplete="off"> {{__('Xác nhận')}}
                                    </label>
                                    {{--<label class="btn btn-default" id="cancel">--}}
                                    {{--<input type="radio" name="status" id="option3" value="cancel"--}}
                                    {{--autocomplete="off"> {{__('Hủy')}}--}}
                                    {{--</label>--}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label>{{__('Ghi chú')}}</label>
                            <textarea id="description" name="description" class="form-control m-input" rows="5"
                                      cols="50">
                                     </textarea>
                        </div>


                    </div>


                </div>
                <div class="form-group m-form__group">
                    <div class="table-responsive" style="display: none" id="table">
                        <table style='text-align: center'
                               class="table table-striped m-table m-table--head-bg-primary"
                               id="table_service">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('Tên dịch vụ')}}</th>
                                <th>{{__('Thời gian')}}</th>
                                <th>{{__('Số lượng')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid m--align-right">
                        <a href="{{route('admin.customer_appointment.list-day')}}"
                           class="btn btn-danger m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10"><i
                                    class="la la-arrow-left"></i>{{__('Thoát')}}</a>

                        <div class="btn-group">
                            <button type="submit"
                                    class="btn btn-success  m-btn m-btn--icon m-btn--wide m-btn--md btn_add">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Lưu lại')}}</span>
							</span>
                            </button>
                            <button type="butt"
                                    class="btn btn-success  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(4px, -95px, 0px);">
                                <button type="submit" class="btn_add dropdown-item btn_add"><i
                                            class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}
                                </button>
                                {{--<a class="dropdown-item" href="#"><i class="la la-copy"></i> Lưu &amp; Sao chép</a>--}}
                                <button type="submit" class="btn_add dropdown-item"><i class="la la-undo"></i> {{__('Lưu')}}
                                    &amp; {{__('Đóng')}}
                                </button>
                                <div class="dropdown-divider"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {{--<input type="submit" id="btn4">--}}


        </div>

    </div>


@stop
@section('after_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/vi.js"></script>
    <script src="{{asset('static/backend/js/admin/customer-appointment/script.js')}}" type="text/javascript"></script>
    <script type="text/template" id="service-tpl">
        <tr class="service_tb">
            <td>{stt}</td>
            <td>{service_name}<input type="hidden" class="service_name_hidden" id="service_name_hidden"
                                     name="service_name_hidden"
                                     value="{service_name_id}"></td>
            <td>{time} {{__('phút')}}</td>
            <td style="width: 165px">
                <input class="quantity form-control m-input" id="quantity" name="quantity"
                       maxlength="11" type="number" style="text-align: center">
                {{--<center><span class="error_quantity" style="color: red">{{__('ABC')}}</span></center>--}}
            </td>
            <td>
                <a class='remove_service m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill'><i
                            class='la la-trash'></i></a>
            </td>
        </tr>
    </script>
@stop
