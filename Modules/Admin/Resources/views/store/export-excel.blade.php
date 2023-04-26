<div id="myExport" class="modal fade" role="dialog">
    <div class="modal-dialog ">
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

                <form action="{{route('admin.order-status.export')}}" id="form1" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="form-group m-form__group row">
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="store_name" id="store_name" value="store_name,{{__('Tên chi nhánh')}}">
                            <label for="store_name">{{__('Tên chi nhánh')}}</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="address" id="address" value="address,{{__('Địa chỉ')}}">
                            <label for="address">{{__('Địa chỉ')}}</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="province" id="province" value="province.name as province_name,Tỉnh thành">
                            <label for="province" >Tỉnh thành</label>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="district" id="district" value="district.name as district_name,Quận huyện">
                            <label for="district" >Quận huyện</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="ward" id="ward" value="ward.name as ward_name,Phường xã">
                            <label for="ward">Phường xã</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="created_at" id="created_at" value="created_at,{{__('Ngày tạo')}}">
                            <label for="created_at">{{__('Ngày tạo')}}</label>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="is_active" id="is_active" value="is_active,{{__('Trạng thái')}}">
                            <label for="is_active">{{__('Trạng thái')}}</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="store_image" id="store_image" value="store_image,{{__('Hình ảnh')}}">
                            <label for="store_image">{{__('Hình ảnh')}}</label>
                        </div>
                        <div class="col-lg-4">
                            <input type="checkbox" class="messageCheckbox" name="updated_at" id="updated_at" value="updated_at,Ngày cập nhật">
                            <label for="updated_at">Ngày cập nhật</label>
                        </div>
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
