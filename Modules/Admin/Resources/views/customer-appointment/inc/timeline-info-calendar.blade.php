<form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
    <div class="m-portlet__body">
        @if(isset($list_default))
            @foreach($list_default as $item)
                <div class="form-group m-form__group">
                    <input type="hidden" id="time_hide" value="{{\Carbon\Carbon::parse($item->time)->format('H:i')}}">
                    <input type="hidden" id="type_hide" value="{{$item['customer_appointment_type']}}">
                    <div class="m-widget4 w-100">
                        <!--begin::Widget 14 Item-->
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--pic">
                                @if($item['customer_avatar']!=null)
                                    <img src="{{$item['customer_avatar']}}" height="52px" width="52px">
                                @else
                                    <img src="{{asset('static/backend/images/image-user.png')}}">
                                @endif
                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title m-font-uppercase">
                                    @if(in_array('admin.customer.detail',session('routeList')))
                                        <a href="{{route("admin.customer.detail",$item['customer_id'])}}"
                                           target="_blank">
                                            {{$item['full_name_cus']}}
                                        </a>
                                    @else
                                        {{$item['full_name_cus']}}
                                    @endif
                                </span><br>
                                <span class="m-widget4__sub m--font-bold"> <i
                                            class="flaticon-support m--margin-right-5"></i> {{$item['phone1']}}</span><br>

                                <span class="m-widget4__sub">
                                    @if($item['status']=='new')
                                        <span class="m--font-success m--font-bold"> <span
                                                    class="m-badge m-badge--success m-badge--dot"></span> {{__('Mới')}}</span>
                                    @elseif($item['status']=='confirm')
                                        <span class="m--font-accent m--font-bold"> <span
                                                    class="m-badge m-badge--accent m-badge--dot"></span> {{__('Xác nhận')}}</span>
                                    @elseif($item['status']=='cancel')
                                        <span class="m--font-danger m--font-bold"> <span
                                                    class="m-badge m-badge--danger m-badge--dot"></span> {{__('Hủy')}}</span>
                                    @elseif($item['status']=='finish')
                                        <span class="m--font-primary m--font-bold"> <span
                                                    class="m-badge m-badge--primary m-badge--dot"></span> {{__('Hoàn thành')}}</span>
                                    @elseif($item['status']=='wait')
                                        <span class="m--font-warning m--font-bold"> <span
                                                    class="m-badge m-badge--warning m-badge--dot"></span> {{__('Chờ phục vụ')}}</span>
                                    @elseif($item['status']=='processing')
                                        <span class="m--font-info m--font-bold"> <span
                                                    class="m-badge m-badge--info m-badge--dot"></span> {{__('Đang thực hiện')}}</span>
                                    @endif
                                                    </span>
                            </div>
                            <div class="m-widget4__ext">

                                @if(in_array($item['customer_appointment_type'], ['appointment', 'booking']))
                                    <a href="#"
                                       class="m-btn  btn btn-sm btn-primary m--font-boldest color_button">{{__('Đặt lịch trước')}}</a>
                                @elseif($item['customer_appointment_type'] == 'direct')
                                    <a href="#"
                                       class="m-btn  btn btn-sm btn-primary m--font-boldest color_button">{{__('Đến trực tiếp')}}</a>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label><i class="la la-calendar"></i> {{__('Ngày hẹn')}}:</label>
                        <input readonly disabled="disabled" type="text"
                               class="form-control m-input"
                               value="{{\Carbon\Carbon::parse($item['date_appointment'])->format('d/m/Y')}}">
                    </div>
                    <div class="col-lg-6">
                        <label><i class="la la-clock-o"></i> {{__('Giờ hẹn')}}:</label>
                        {{--{!! Form::select("customer_appointment_time_id",$optionTime,$item['customer_appointment_time_id'],["class"=>"form-control time","id"=>"customer_appointment_time_id","autocomplete"=>"off"]) !!}--}}
                        <input type="text" class="form-control" id="time_detail" name="time"
                               value="{{\Carbon\Carbon::parse($item['time'])->format('H:i')}}" disabled>
                    </div>

                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label><i class="la la-users"></i> {{__('Số khách phục vụ')}}:</label>
                        <input readonly disabled="disabled" type="text"
                               class="form-control m-input"
                               value="{{$item['customer_quantity']}}">
                    </div>
                    <div class="col-lg-6">
                        <label><i class="la la-home"></i> {{__('Nguồn lịch hẹn')}}:</label>
                        <input readonly disabled="disabled" type="text"
                               class="form-control m-input"
                               value="{{$item['appointment_source_name']}}">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-6">
                        <label><i class="la la-sitemap"></i> {{__('Chi nhánh')}}:</label>
                        <input readonly disabled="disabled" type="text"
                               class="form-control m-input"
                               value="{{$item['branch_name']}}">
                    </div>
                    <div class="col-lg-6">
                        <label><i class="la la-sitemap"></i> {{__('Dịch vụ')}}:</label>
                        <textarea class="form-control" disabled>{{$object_name}}</textarea>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-lg-12">
                        <label><i class="la la-sitemap"></i> {{__('Nhân viên')}}:</label>
                        <select class="form-control staff" name="staff_id" id="staff_id" multiple disabled>
                            <option></option>
                            @if(count($array_staff)>0)
                                @foreach($array_staff as $key=>$objectStaff)
                                    <option selected value="{{$objectStaff}}">{{$objectStaff}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                   
                </div>
            @endforeach
        @endif
    </div>
    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
        <div class="m-form__actions m-form__actions--solid">

            <div class=" m--align-right">
                @foreach($list_default as $item)
                    @if(in_array('admin.customer_appointment.submitModalEdit',session('routeList')))
                        <a href="javascript:void(0)"
                           onclick="customer_appointment.click_modal_edit('{{$item['customer_appointment_id']}}')"
                           class="btn btn-danger bold-huy  bte_app class_edit"><i
                                    class="la la-pencil"></i> {{__('CHỈNH SỬA')}} </a>
                    @endif
                    @if($item['status']=='new')
                        @if(in_array('admin.customer_appointment.submitEdit',session('routeList')))
                            <button onclick="click_detail.save('{{$item['customer_appointment_id']}}')" type="button"
                                    class="btn btn-info color_button son-mb class_save">
                                <i class="la la-check"></i> {{__('XÁC NHẬN LỊCH HẸN')}}
                            </button>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</form>