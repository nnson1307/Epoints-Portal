<div class="modal fade" id="create-areas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="    max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="title">THÊM KHU VỰC</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="created-areas">
                <div class="modal-body">
                    <span class="note-font">Mã khu vực:<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <input id="config_content" name="area_code" type="text"
                                               class="form-control m-input class"
                                               placeholder='Nhập mã khu vực'
                                               value=""
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="note-font">Khu vực:<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <input id="config_content" name="name" type="text"
                                               class="form-control m-input class"
                                               placeholder='Nhập tên khu vực'
                                               value=""
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="note-font">Chi nhánh:<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group">
                            <select class="form-control select2" id="branch" style="    width: 650px;"
                                    name="branch_id" onchange="">
                                <option value="">Chọn chi nhánh</option>
                                @foreach($getListBranch as $k => $v)
                                    <option value='{{$v['branch_id']}}'>{{$v['branch_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <span class="note-font">Ghi chú:<b class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <input id="note-areas" name="note" type="text"
                                               class="form-control m-input class"
                                               placeholder='Nhập Ghi chú'
                                               value=""
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal-footer">
<!--                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="WorkChild.cancel()">-->
                <button type="button" class="btn btn-secondary" onclick="WorkChild.cancel()">
                    <span class="la 	la-arrow-left"></span>
                    HỦY
                </button>
                <button type="button" class="btn btn-primary" onclick="WorkChild.saveNewAreas()">
                    <span class="la 		la-check"></span>
                    LƯU THÔNG TIN
                </button>
            </div>
        </div>
    </div>
</div>