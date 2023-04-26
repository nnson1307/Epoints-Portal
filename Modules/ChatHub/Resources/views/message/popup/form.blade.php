
<div id="modal-card">
    <div class="modal" id="kt_modal_card" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLabel">
                        Cập nhật thông tin
                    </h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 style="color: #1d6ef3">{{$customer['full_name']}}</h5><br>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="form-control-labelform-control-label">Email</label>
                            <input type="email" value="{{$customer['email']}}" class="form-control cus-email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="form-control-labelcontrol-label">Số điện thoại</label>
                            <input type="text" value="{{$customer['phone']}}" class="form-control cus-phone" placeholder="Số điện thoại">
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="inputPassword3" class="form-control-labelcontrol-label">Địa chỉ</label>--}}
{{--                            <input type="text" value="{{$customer['address']}}" class="form-control cus-address" placeholder="Nhập địa chỉ">--}}
{{--                        </div>   --}}
                        <div class="form-group">
                            <label for="inputPassword3" class="form-control-labelcontrol-label">Giới tính</label>
                            <select class="form-control cus-gender">
                                <option value="male" @if($customer['gender']=='male')selected @endif>Nam</option>
                                <option value="female" @if($customer['gender']=='female')selected @endif>Nữ</option>
                            </select>
                        </div>                              
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="message.updateCustomer()" class="btn btn-primary">
                        Xác nhận
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    
                </div>
            </div>
        </div>
    </div>
</div>
