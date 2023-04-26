<div id="myExport" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export Excel</h4>
            </div>
            <div class="col-lg-12">
                <input type="checkbox" class="messageCheckbox" id="checkAll" name="checkAll">
                <label for="checkAll">Chọn tất cã</label>
            </div>
            <div class="modal-body">

                <form action="{{route('admin.order-status.export')}}" id="formexport" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="order_status_name" id="order_status_name" value="order_status_name,Tên trạng thái đơn hàng">
                            <label for="order_status_name" >Tên trạng thái </label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="order_status_description" id="order_status_description" value="order_status_description,{{__('Ghi chú')}}">
                            <label for="order_status_description" >{{__('Ghi chú')}}</label>
                        </div>
                    <div class="col-lg-4">
                        <input type="checkbox" class="messageCheckbox" name="is_active" id="is_active" value="is_active,{{__('Trạng thái')}}">
                        <label for="is_active" >{{__('Trạng thái')}}</label>
                    </div>
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="created_at" id="created_at" value="created_at,{{__('Ngày tạo')}}">
                            <label for="created_at" >{{__('Ngày tạo')}}</label>
                        </div>

                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="updated_at" id="updated_at" value="updated_at,Ngày cập nhật">
                            <label for="updated_at" >Ngày cập nhật</label>
                        </div>



                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="submit"  id="export"  class="btn btn-success" value="Export" >
                            </div>
                            <div class="col-lg-6">
                                <button type="button"  class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>
</div>
