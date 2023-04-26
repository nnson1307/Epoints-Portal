<div class="m-section__content m--padding-top-10 m--padding-bottom-10">
    <div class="table-responsive">
        <table class="table m-table m-table--head-separator-metal" id="all-table">
            <thead>
            <tr class="tr_thead_group">
                <th class="tr_thead_group">{{__('TỔNG LỊCH HẸN')}}</th>
                <th class="tr_thead_group">{{__('MỚI ĐẶT')}}</th>
                <th class="tr_thead_group">{{__('ĐÃ XÁC NHẬN')}}</th>
                <th class="tr_thead_group">{{__('CHỜ PHỤC VỤ')}}</th>
                <th class="tr_thead_group">{{__('HOÀN THÀNH')}}</th>
            </tr>
            </thead>
            <tbody>
            <tr class="total_day tr_tbody_group">
                <td class="total_sum">
                    @if(count($dateGroup)>0)
                        {{$dateGroup[0]['number']}}
                    @else
                        0
                    @endif
                </td>
                <td class="total_new">
                    @if(count($dateGroupNew)>0)
                        {{$dateGroupNew[0]['number']}}
                    @else
                        0
                    @endif
                </td>
                <td class="total_confirm">
                    @if(count($dateGroupConfirm)>0)
                        {{$dateGroupConfirm[0]['number']}}
                    @else
                        0
                    @endif
                </td>
                <td class="total_wait">
                    @if(count($dateGroupWait)>0)
                        {{$dateGroupWait[0]['number']}}
                    @else
                        0
                    @endif
                </td>
                <td class="total_finish">
                    @if(count($dateGroupFinish)>0)
                        {{$dateGroupFinish[0]['number']}}
                    @else
                        0
                    @endif
                </td>

            </tr>
            </tbody>
        </table>
    </div>

</div>
@if(count($list)>0)
    <div class="m-timeline-2">
        <div class="m-timeline-2__items  m--padding-top-25 m--padding-bottom-30">
            <?php
            $a = array();
            ?>

            @foreach($list as $item)
                <div class="m-timeline-2__item m--margin-bottom-30">
            <span class="m-timeline-2__item-time">
                @if(!in_array($item['time'],$a))
                    <?php
                    $a[] = $item['time'];
                    ?>
                    {{$item['time']}}
                @endif
            </span>
                    <div class="m-timeline-2__item-cricle">
                        @if($item['status']=='new')
                            <i class="fa fa-genderless m--font-success"></i>
                        @elseif($item['status']=='confirm')
                            <i class="fa fa-genderless m--font-accent"></i>
                        @elseif($item['status']=='cancel')
                            <i class="fa fa-genderless m--font-danger"></i>
                        @elseif($item['status']=='finish')
                            <i class="fa fa-genderless m--font-primary"></i>
                        @elseif($item['status']=='wait')
                            <i class="fa fa-genderless m--font-warning"></i>
                        @elseif($item['status']=='processing')
                            <i class="fa fa-genderless m--font-info"></i>
                        @endif
                    </div>
                    <div class="m-timeline-2__item-text  m--padding-top-5 w-100">
                        <div class="m-widget4 w-100">
                            <!--begin::Widget 14 Item-->
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--pic">
                                    @if($item['avatar']!=null)
                                        <img src="{{asset($item['avatar'])}}" height="52px" width="52px">
                                    @else
                                        <img src="{{asset('static/backend/images/image-user.png')}}" alt="">
                                    @endif

                                </div>
                                <div class="m-widget4__info">
                                                    <span class="m-widget4__title m-font-uppercase">
                                                         {{$item['full_name']}}
                                                    </span><br>

                                    <span class="m-widget4__sub m--font-boldest"><i
                                                class="flaticon-support m--margin-right-5"></i> {{$item['phone']}}</span><br>
                                    <span class="m-widget4__sub m--font-boldest"><i
                                                class="flaticon-notes m--margin-right-5"></i> {{$item['description']}}</span><br>

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
                                    @if(in_array('admin.customer_appointment.detail',session('routeList')))
                                        <input type="hidden" class="id_appointment" value="{{$item['id']}}">
                                        <a href="javascript:void(0)"
                                           onclick="click_detail.detail_click('{{$item['id']}}')"
                                           class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary detail_btn_{{$item['id']}}">
                                            @lang('Chi tiết')
                                            <i class="la la-angle-right"></i> </a>
                                    @endif
                                </div>
                            </div>
                            <!--end::Widget 14 Item-->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <span id="null_day">{{__('Hôm nay không có lịch hẹn')}}</span>
@endif
<div class="ps__rail-x" style="left: 0px; bottom: -1px;">
    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
</div>
<div class="ps__rail-y" style="top: 1px; height: 380px; right: 4px;">
    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 277px;"></div>
</div>
