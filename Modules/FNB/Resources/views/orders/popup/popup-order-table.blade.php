<!-- Modal -->
<div class="modal fade" id="popup-order-table" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog " role="document" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="title">{{__('DANH SÁCH ĐƠN HÀNG')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">#</th>
                                <th class="tr_thead_list">{{__('Mã đơn hàng')}}</th>
                                <th class="tr_thead_list"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $key => $item)
                                    <tr>
                                        <td></td>
                                        <td>{{$item['order_code']}}</td>
                                        <td><span class="la la-print print"  onclick="print_bill.print('{{$item['order_id']}}')"></span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <span class="la 	la-arrow-left"></span>
                        HỦY
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>