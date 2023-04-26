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


    </style>
    <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                        <span><i class="la la-edit"></i> {{__('CẬP NHẬT LỊCH HẸN')}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <input type="hidden" name="customer_appointment_id" id="customer_appointment_id"
               value="{{$item['customer_appointment_id']}}">
        <div class="m-portlet__body" style="margin-top: -50px">
            <div class="row">
                <div class="col-xl-12">
                    {{--{!! Form::open(['route'=>'admin.service.submitAdd',"id"=>"form", 'class' => ' m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed ']) !!}--}}
                    <form action="" method="post" id="formEdit" novalidate="novalidate">
                        {!! csrf_field() !!}
                        <br/>

                        <div class="row">
                            <div class="col-lg-6">
                                {{--<div class="form-group m-form__group">--}}
                                {{--<label>{{__('Khách hàng')}}</label>--}}
                                {{--<div class="input-group m-input-group m-input-group--solid">--}}
                                {{--{!! Form::select("customer_id",$optionCustomer,$item['customer_id'],["class"=>"form-control","id"=>"customer_id","autocomplete"=>"off"]) !!}--}}
                                {{--</div>--}}
                                {{--<input type="hidden" id="customer_hidden" name="customer_hidden">--}}
                                {{--</div>--}}
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-6">
                                        <label>{{__('Số điện thoại')}}:<b class="text-danger">*</b></label>
                                        <div class="input-group m-input-group">
                                            <input class="form-control" name="phone1" id="phone1" readonly
                                                   disabled="disabled"
                                                   placeholder="{{__('Số điện thoại')}}" value="{{$item['phone1']}}">
                                        </div>
                                        <span class="error-phone1" style="color: red;"></span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>{{__('Tên khách hàng')}}:<b class="text-danger">*</b></label>
                                        <div class="input-group m-input-group">
                                            <input class="form-control" name="full_name" id="full_name" readonly
                                                   disabled="disabled"
                                                   placeholder="{{__('Họ và tên')}}" value="{{$item['full_name_cus']}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-6">
                                        <label>{{__('Ngày hẹn')}}:<b class="text-danger">*</b></label>
                                        <div class="input-group m-input-group">
                                            <input class="form-control date" name="date" id="date" readonly
                                                   placeholder="{{__('Hãy chọn ngày hẹn')}}"
                                                   value="{{date('d/m/Y',strtotime($item['date_appointment']))}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>{{__('Giờ hẹn')}}:<b class="text-danger">*</b></label>
                                        <div class="input-group m-input-group">
                                            {{--{!! Form::select("customer_appointment_time_id",$optionTime,$item['customer_appointment_time_id'],["class"=>"form-control","id"=>"customer_appointment_time_id","autocomplete"=>"off"]) !!}--}}
                                            <select class="form-control" name="time" id="time">
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
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-6">
                                        <label>{{__('Nhân viên phục vụ')}}:</label>
                                        <div class="input-group m-input-group m-input-group--solid">
                                            <select class="form-control" name="staff_id" id="staff_id">
                                                @if(count($optionStaff)>0)
                                                    <option></option>
                                                    @foreach($optionStaff as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                        @if($key==$item['staff_id'])
                                                            <option selected value="{{$key}}">{{$value}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>{{__('Phòng')}}:</label>
                                        <div class="input-group m-input-group m-input-group--solid">
                                            {{--{!! Form::select("room_id",$optionRoom,$item['room_id'],["class"=>"form-control","id"=>"room_id","autocomplete"=>"off"]) !!}--}}
                                            <select class="form-control" name="room_id" id="room_id">
                                                @if(count($optionRoom)>0)
                                                    <option></option>
                                                    @foreach($optionRoom as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                        @if($key==$item['room_id'])
                                                            <option selected value="{{$key}}">{{$value}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                        @if ($errors->has('room_id'))
                                            <span class="form-control-feedback">
                                            {{ $errors->first('room_id') }}
                                        </span>
                                            <br>
                                        @endif
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
                                    <input class="form-control" type="text" readonly disabled="disabled"
                                           name="cutomer_refer" value="{{$itemRefer['full_name_refer']}}">
                                </div>
                                <div class="form-group m-form__group">
                                    <label>{{__('Trạng thái')}}:</label>
                                    <div class="input-group m-input-group m-input-group--solid" div-status>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            @if($item['status']=='new')
                                                <label class="btn-status btn btn-primary active" id="new">
                                                    <input type="radio" name="status" id="option1" value="new"
                                                           autocomplete="off" checked=""> {{__('Mới')}}
                                                </label>
                                                <label class="btn-status btn btn-default" id="confirm">
                                                    <input type="radio" name="status" id="option2" value="confirm"
                                                           autocomplete="off"> {{__('Xác nhận')}}
                                                </label>
                                                <label class="btn-status btn btn-default" id="cancel">
                                                    <input type="radio" name="status" id="option3" value="cancel"
                                                           autocomplete="off"> {{__('Hủy')}}
                                                </label>
                                            @elseif($item['status']=='confirm')
                                                <label class="btn-status btn btn-default " id="new">
                                                    <input type="radio" name="status" id="option1" value="new"
                                                           autocomplete="off" checked=""> {{__('Mới')}}
                                                </label>
                                                <label class="btn-status btn btn-primary active" id="confirm">
                                                    <input type="radio" name="status" id="option2" value="confirm"
                                                           autocomplete="off"> {{__('Xác nhận')}}
                                                </label>
                                                <label class="btn-status btn btn-default" id="cancel">
                                                    <input type="radio" name="status" id="option3" value="cancel"
                                                           autocomplete="off"> {{__('Hủy')}}
                                                </label>
                                            @else
                                                <label class="btn-status btn btn-default " id="new">
                                                    <input type="radio" name="status" id="option1" value="new"
                                                           autocomplete="off" checked=""> {{__('Mới')}}
                                                </label>
                                                <label class="btn-status btn btn-default" id="confirm">
                                                    <input type="radio" name="status" id="option2" value="confirm"
                                                           autocomplete="off"> {{__('Xác nhận')}}
                                                </label>
                                                <label class="btn-status btn btn-primary active" id="cancel">
                                                    <input type="radio" name="status" id="option3" value="cancel"
                                                           autocomplete="off"> {{__('Hủy')}}
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label>{{__('Ghi chú')}}:</label>
                                    <div class="input-group m-input-group">
                                    <textarea id="description" name="description" class="form-control m-input" rows="5"
                                              cols="50">{{$item['description']}}
                                     </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="table-responsive" id="table">
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
                                    @if(count($itemSv)>0)
                                        @foreach($itemSv as $key=>$value)
                                            <tr class="service_tb">
                                                <td>{{$key+1}}</td>
                                                <td>{{$value['service_name']}}<input type="hidden"
                                                                                     class="appointment_service_id"
                                                                                     id="appointment_service_id"
                                                                                     name="appointment_service_id"
                                                                                     value="{{$value['appointment_service_id']}}">
                                                    <input type="hidden"
                                                           class="service_id"
                                                           id="service_id"
                                                           name="service_id"
                                                           value="{{$value['service_id']}}">
                                                </td>
                                                <td>{{$value['time']}} {{__('phút')}}</td>
                                                <td style="width: 165px"><input class="quantity form-control m-input" id="quantity"
                                                           name="quantity"
                                                           maxlength="11" type="number"
                                                           style="text-align: center"
                                                           value="{{$value['quantity']}}">
                                                    {{--<center><span class="error_quantity" style="color: red">{{__('ABC')}}</span></center>--}}
                                                </td>
                                                <td>
                                                    <a class='remove_service m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill'><i
                                                                class='la la-trash'></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions--solid m--align-right">
                                <a href="{{route('admin.customer_appointment.list-day')}}"
                                   class="btn btn-danger m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10"><i
                                            class="la la-arrow-left"></i>{{__('Thoát')}}</a>
                                <a href="{{route('admin.customer_appointment.receipt',$item['customer_appointment_id'])}}"
                                   class="btn btn-success m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">{{__('Thanh toán')}}</a>
                                <div class="btn-group">
                                    <button type="button" id="btn_edit"
                                            class="btn btn-primary  m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-check"></i>
							<span>{{__('Cập nhật')}}</span>
							</span>
                                    </button>
                                    {{--<button type="butt" class="btn btn-primary  dropdown-toggle dropdown-toggle-split m-btn m-btn--md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                                    {{--</button>--}}
                                    {{--<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">--}}
                                    {{--<a class="dropdown-item" href="#"><i class="la la-plus"></i> {{__('Lưu')}} &amp; {{__('Tạo mới')}}</a>--}}
                                    {{--<a class="dropdown-item" href="#"><i class="la la-copy"></i> {{__('Lưu')}} &amp; Sao chép</a>--}}
                                    {{--<a class="dropdown-item" href="#"><i class="la la-undo"></i> {{__('Lưu')}} &amp; {{__('Đóng')}}</a>--}}
                                    {{--<div class="dropdown-divider"></div>--}}
                                    {{--<a class="dropdown-item" href="#"><i class="la la-close"></i> {{__('Hủy')}}</a>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </div>
                    </form>
                    {{--<input type="submit" id="btn4">--}}
                </div>
            </div>
        </div>

    </div>


@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/customer-appointment/edit.js')}}" type="text/javascript"></script>
    <script type="text/template" id="service-tpl">
        <tr class="service_tb_add">
            <td>{stt}</td>
            <td>{service_name}<input type="hidden" class="service_name_hidden" id="service_name_hidden"
                                     name="service_name_hidden"
                                     value="{service_name_id}"></td>
            <td>{time} {{__('phút')}}</td>
            <td style="width: 165px"><input class="quantity form-control m-input" id="quantity" name="quantity"
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
