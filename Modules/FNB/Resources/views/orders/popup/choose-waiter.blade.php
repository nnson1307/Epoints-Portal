<!-- Modal -->
<div class="modal fade" id="choose-waiter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog " role="document" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="title">{{__('NHÂN VIÊN PHỤC VỤ')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-to">
                    <span class="note-font" style="    margin-top: 12px;margin-right: 20px;">{{__('Nhân viên phục vụ')}}</span>
                    <div class="form-group m-form__group ">
                        <select class="form-control select2" id="staff_service" style="    width: 350px;"
                                name="staff_service"
                                onchange="">
                            <option value="">{{__('Chọn nhân viên')}}</option>
                            @foreach($listStaff as $item)
                                <option value="{{$item['staff_id']}}" {{$staff_id_select == $item['staff_id'] ? 'selected' : ''}}>{{$item['full_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <span class="la 	la-arrow-left"></span>
                        HỦY
                    </button>
                    <button type="button" class="btn btn-primary" onclick="order.saveStaff()">
                        <span class="la 		la-check"></span>
                        LƯU THÔNG TIN
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>