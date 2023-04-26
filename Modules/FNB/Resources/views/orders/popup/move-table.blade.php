
<!-- Modal -->
<div class="modal fade" id="move-table" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-1000 " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="title">CHUYỂN BÀN</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="order_id_old" value="{{$item['order_id']}}">
                    <div class="col">
                        <span class="note-font">Từ bàn:</span>
                        <span >{{$item['areas_name']}} - {{$item['table_name']}}</span>
                    </div>
                    <div class="col">
                        <div class="price_size">
                            <span class="note-font">Số ghế:</span>
                            <span>{{$item['table_seats']}}</span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding: 0px;padding-top:30px; width:950px">
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table">
                            <thead>
                            <tr class="ss--nowrap">
                                <th class="ss--font-size-th ss--text-center">{{__('Khách hàng')}}</th>
                                <th class="ss--font-size-th  ss--text-center">{{__('Mã đơn hàng')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('Thời gian đặt')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('Số lượng món')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('Tổng tiền')}}</th>
                                <th class="ss--font-size-th ss--text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">{{$item['full_name']}}</td>
                                <td class="ss--text-center">{{$item['order_code']}}</td>
                                <td class="ss--text-center">{{\Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y')}}</td>
                                <td class="ss--text-center">{{count($listProduct)}}</td>
                                <td class="ss--text-center">{{number_format($item['amount'])}}đ</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-to">
                    <span class="note-font" style="    margin-top: 12px;margin-right: 20px;">Di chuyển đến bàn:</span>
                    <div class="form-group m-form__group ">
                        <select class="form-control select2 areas-table" id="areas-room"
                                name="areas-room"
                                onchange="order.changeArea('move-table')">
                            <option value="">Chọn khu vực</option>
                            <option value = 'all' >{{__('Tất cả')}}</option>
                            @foreach($listArea as $itemArea)
                                <option value = '{{$itemArea['area_id']}}' >{{$itemArea['area_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-select">
                    <input type="hidden" id="table_id_new" value="">
                    <div class="m-portlet__body" style="padding: 0px;padding-top:30px; width:950px">
                        <div class="table-responsive">
                            <table class="table table-striped m-table ss--header-table">
                                <thead>
                                <tr class="ss--nowrap">
                                    <th class="ss--font-size-th ss--text-center">{{__('Khu vực')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('Tên bàn')}}</th>
                                    <th class="ss--font-size-th ss--text-center">{{__('Số ghế')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="ss--font-size-13 ss--nowrap">
                                    <td colspan="5">
                                        <div class="not_find" style="text-align: center;font-weight: bold;">
                                            <span>Chưa có bàn nào</span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="la 	la-arrow-left"></span>
                    HỦY
                </button>
                <button type="button" class="btn btn-primary" onclick="order.submitMoveTable()">
                    <span class="la la-check"></span>
                    LƯU THÔNG TIN
                </button>
            </div>
        </div>
    </div>
</div>