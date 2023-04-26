<div id="formExportExcel" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export Excel</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route('services.export-excel')}}" id="formExport" method="POST"
                      enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group m-form__group row">
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' class='roles' id="service_name" name="service_name" value="sv.service_name,Tên dịch vụ">Tên
                                dịch vụ</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="service_code" name="service_code" value="sv.service_code,Mã dịch vụ">Mã
                                dịch
                                vụ</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="service_time_id" name="service_time_id"
                                          value="service_time.time as time,Thời gian sử dụng dịch vụ"
                                >Thời
                                gian sử dụng</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="description" name="description" value="sv.description,Mô tả"
                                >Mô
                                tả</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="services_image" name="services_image"
                                          value="sv.services_image,Hình ảnh"
                                >Hình
                                ảnh</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="detail" name="detail" value="sv.detail,Chi tiết">Chi
                                tiết</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="is_active" name="is_active" value="sv.is_active,Tình trạng">Trạng
                                thái</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="is_delete" name="is_delete" value="sv.is_delete,Đã xóa">Đã
                                xóa</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="created_at" name="created_at" value="sv.created_at,Thời gian tạo"
                                >Thời
                                gian tạo</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="updated_at" name="updated_at" value="sv.updated_at,Thời gian chỉnh sửa"
                                >Thời
                                gian sửa</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="created_by" name="created_by" value="sv.created_by,Người tạo"
                                >Người
                                tạo</label>
                        </div>
                        <div class="col-lg-3">
                            <label><input type="checkbox" class='roles' id="updated_by" name="updated_by" value="sv.updated_by,Người sửa"
                                >Người
                                chỉnh sửa</label>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                    <div class="pull-right">
                        <input class="btn btn-success" type="submit" value="Export">
                        <input class="btn btn-primary" type="button" value="Check all" id="checkAll">
                        <input type="button" class="btn btn-danger" data-dismiss="modal" value="Close">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

