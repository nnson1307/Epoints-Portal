<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Cập nhật nguồn khách hàng</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <form id="formEdit">
        <div class="modal-body">
            <input type="hidden" id="idHidden">
            <div class="form-group">
                <label>
                    {{__('Tên sản phẩm')}}:
                </label>
                <select id="product_id" name="product_id" class="form-control">
                    @foreach($PRODUCT as $key=>$value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>
                    {{__('Tên hình')}}:
                </label>
                <input id="name" name="name" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>
                    {{__('Loại hình')}}:
                </label>
                <select id="type" name="type" class="form-control col-sm-4">
                    <option value="mobile">{{__('Mobile')}}</option>
                    <option value="desktop">{{__('Desktop')}}</option>
                </select>
            </div>
            <input type="hidden" value="0" id="close">
            <div class="modal-footer">
                <button type="button" onclick="productImage.submitEdit()" title="Cập nhật" class="btn btn-primary"> {{__('Cập nhật')}}
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Hủy')}}</button>
            </div>
        </div>
    </form>
</div>