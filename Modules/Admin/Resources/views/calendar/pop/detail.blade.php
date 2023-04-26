<div class="modal fade" id="modal-detail" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg-appointment" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('CHI TIẾT DỊCH VỤ')}}
                </h5>

            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped m-table m-table--head-bg-default">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_list">{{__('TÊN KHÁCH HÀNG')}}</th>
                            <th class="tr_thead_list">{{__('SĐT')}}</th>
                            <th class="tr_thead_list">{{__('NGÀY HẸN')}}</th>
                            <th class="tr_thead_list">{{__('NGÀY KẾT THÚC')}}</th>
                            <th class="tr_thead_list">{{__('TRẠNG THÁI')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($list) > 0)
                            <?php $now = strtotime(\Carbon\Carbon::now()->format('Y-m-d H:i'));?>
                            @foreach($list as $v)
                                <tr>
                                    <td>{{$v['full_name']}}</td>
                                    <td>{{$v['phone']}}</td>
                                    <td>
                                        {{\Carbon\Carbon::parse($v['date']. ' '. $v['time'])->format('d/m/Y H:i')}}
                                    </td>
                                    <td>
                                        {{\Carbon\Carbon::parse($v['end_date']. ' '. $v['end_time'])->format('d/m/Y H:i')}}
                                    </td>
                                    <td>
                                        @if($v['status']=='new')
                                            <span class="m-badge m-badge--success m-badge--dot"></span> {{__('Mới')}}</span>
                                        @elseif($v['status']=='confirm')
                                            <span class="m-badge m-badge--accent m-badge--dot"></span> {{__('Xác nhận')}}</span>
                                        @elseif($v['status']=='cancel')
                                            <span class="m-badge m-badge--danger m-badge--dot"></span> {{__('Hủy')}}</span>
                                        @elseif($v['status']=='finish')
                                            <span class="m-badge m-badge--primary m-badge--dot"></span> {{__('Hoàn thành')}}</span>
                                        @elseif($v['status']=='wait')
                                            <span class="m-badge m-badge--warning m-badge--dot"></span> {{__('Chờ phục vụ')}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!in_array($v['status'], ['cancel', 'finish']))
                                            <?php $time = \Carbon\Carbon::parse($v['end_date'] . ' ' . $v['end_time'])->format('Y-m-d H:i')?>
                                            @if(strtotime($time) <= $now)
                                                <a href="{{route('admin.customer_appointment.receipt', $v['customer_appointment_id'])}}"
                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                   title="{{__('Thanh toán')}}">
                                                    <i class="la la-file-text"></i>
                                                </a>
                                            @else
                                                <button class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                        title="{{__('Thanh toán')}}"
                                                        onclick="customer_appointment.loadPageReceipt('{{$v['customer_appointment_id']}}')">
                                                    <i class="la la-file-text"></i>
                                                </button>
                                            @endif
                                        @endif
                                        @if(in_array('admin.customer_appointment.submitModalEdit',session('routeList')))
                                            <a href="javascript:void(0)"
                                               onclick="customer_appointment.click_modal_edit('{{$v['customer_appointment_id']}}')"
                                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                                <i class="la la-pencil"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid m--align-right w-100">
                        <a href="javascript:void(0)" data-dismiss="modal"
                           class="btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md bold-huy">
                                        <span>
                                            <i class="la la-arrow-left"></i>
                                            <span> {{__('HỦY')}}</span>
                                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>