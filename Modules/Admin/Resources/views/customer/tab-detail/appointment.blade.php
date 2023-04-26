<div class="m-demo__preview">
    <div class="row">
        <div class="col-lg-12 text-right">
            @if(in_array('admin.customer_appointment.submitModalAdd',session('routeList')))
            <a href="javascript:void(0)"
               onclick="customer_appointment.click_modal('{{Carbon\Carbon::now()->format('d/m/Y')}}', '{{$customer_id}}')"
               class="btn btn-info color_button son-mb m--margin-left-10">
                        <span>
                            <i class="la la-calendar-plus-o"></i>
                            <span>{{__('THÊM LỊCH HẸN')}}</span>
                        </span>
            </a>
        @endif
        </div>
        <div class="col-lg-12">
            @if(count($data)>0)
            <!--Begin::Timeline 2 -->
                <div class="m-list-timeline">
                    <div class="m-list-timeline__items">
                        @foreach($data as $ka => $va)
                            <div class="m-list-timeline__item">
                                <span class="m-list-timeline__badge"></span>
                                <span class="m-list-timeline__text">
                                                            <span class="sz_dt"><strong>@lang("Số khách")</strong>: {{$va['customer_quantity'] != null ? $va['customer_quantity'] : 1}}
                                                                @lang("khách")</span>
                                    @if($va['status']=='new')
                                        <span class="m-badge m-badge--success m-badge--wide m--margin-left-40"
                                            style="font-weight: bold;">{{__('Mới')}}</span>

                                    @elseif($va['status']=='confirm')
                                        <span class="m-badge m-badge--warning m-badge--wide m--margin-left-40"
                                            style="font-weight: bold;color: #fff">@lang("Xác nhận")</span>
                                    @elseif($va['status']=='wait')
                                        <span class="m-badge m-badge--danger m-badge--wide m--margin-left-40"
                                            style="font-weight: bold;">@lang("Chờ phục vụ")</span>
                                        <br>
                                    @elseif($va['status']=='cancel')
                                        <span class="m-badge m-badge--danger m-badge--wide m--margin-left-40"
                                            style="font-weight: bold;">@lang("Hủy")</span>
                                    @elseif($va['status']=='finish')
                                        <span class="m-badge m-badge--primary m-badge--wide m--margin-left-40"
                                            style="font-weight: bold;">@lang("Hoàn thành")</span>
                                    @endif
                                    @if(in_array('admin.customer_appointment.submitModalEdit',session('routeList')))
                                        <a class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        href="javascript:void(0)"
                                        onclick="customer_appointment.click_modal_edit('{{$va['customer_appointment_id']}}')">
                                                                    <i class="la la-edit"></i>
                                                                </a>
                                    @endif
                                    <br>
                                    @if (count($va['service']) > 0)
                                        @foreach($va['service'] as $itemSV)
                                            <span class="sz_dt">{{'+ '.$itemSV['service_name']}} </span>
                                            <br>
                                        @endforeach
                                    @endif

                                    @if ($va['description'] != null)
                                        @lang('Ghi chú'): {{$va['description']}}
                                    @endif
                                                        </span>
                                <span class="m-list-timeline__time sz_dt" style="width: 30%">{{$va['time']}}
                                    -{{$va['date']}}
                                                            </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!--End::Timeline 2 -->
            @else
                <div class="form-group">
                    @lang("Không có dữ liệu")
                </div>
            @endif
        </div>
    </div>
</div>