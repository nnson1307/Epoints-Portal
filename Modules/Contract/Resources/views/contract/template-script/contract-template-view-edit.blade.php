
<script type="text/template" id="tpl-after">
    <label class="black_title">
        @lang('Giá trị')
    </label>
    <div class="input-group">
        <input type="text" class="form-control m-input input_int" id="send_value" name="send_value">
        <div class="input-group-append">
            <span class="input-group-text">@lang('Ngày')</span>
        </div>
    </div>
</script>
<script type="text/template" id="tpl-hard">
    <label class="black_title"></label>
    <div class="row">
        <div class="col-lg-3">
            @lang('Định kỳ ngày')
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control m-input input_int" id="send_value"
                   name="send_value">
        </div>
        <div class="col-lg-2">
            @lang('mỗi')
        </div>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text" class="form-control m-input input_int" id="send_value_child"
                       name="send_value_child">
                <div class="input-group-append">
                    <span class="input-group-text">@lang('Tháng')</span>
                </div>
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="tpl-custom">
    <label class="black_title">
        @lang('Giá trị')':
    </label>
    <div class="div_add_date"></div>
    <a href="javascript:void(0)" onclick="expectedRevenue.addDate()"
       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('Thêm ngày')</span>
                                    </span>
    </a>
</script>
<script type="text/template" id="tpl-add-date">
    <div class="form-group input-group">
        <input type="text" class="form-control m-input date_picker" readonly=""
               name="date_custom">
        <div class="input-group-append">
            <button class="btn btn-secondary" type="button" onclick="expectedRevenue.removeDate(this)">
                <i class="la la-trash"></i>
            </button>
        </div>
    </div>
</script>@csrf
<script type="text/template" id="tpl-goods">
    <tr class="tr-goods tr-goods-same">
        <td>
            <button onclick="contractGoods.removeObject(this)"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                    title="{{__('Xoá')}}">
                <i class="la la-trash"></i>
            </button>
        </td>
        <td>
            <input class="form-control object_code" disabled>
            <input type="hidden" class="form-control object_name">
            <input type="hidden" class="form-control number" value="{number}">
            <input type="hidden" class="type_object">
            <input type="hidden" class="staff_id">
        </td>
        <td>
            <select class="form-control object_type" style="width:100%;"
                    onchange="contractGoods.changeObjectType(this)">
                <option></option>
                <option value="product">@lang('Sản phẩm')</option>
                <option value="service">@lang('Dịch vụ')</option>
                <option value="service_card">@lang('Thẻ dịch vụ')</option>
            </select>
            <span class="error_object_type_{number}" style="color: red;"></span>
        </td>
        <td>
            <select class="form-control object_id" style="width:100%;" disabled
                    onchange="contractGoods.changeObject(this)">
                <option></option>
            </select>
            <span class="error_object_id_{number}" style="color: red;"></span>
        </td>
        <td>
            <select class="form-control unit_id" style="width:100%;">
                <option></option>
                @foreach($optionUnit as $v)
                    <option value="{{$v['unit_id']}}">{{$v['name']}}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input class="form-control quantity input_int" value="1" onchange="contractGoods.changeQuantity(this)">
            <span class="error_quantity_{number}" style="color: red;"></span>
        </td>
        <td>
            <input class="form-control price input_float" id="price_{number}" disabled>
        </td>
        <td>
            <input class="form-control tax input_int" value="0" onchange="contractGoods.changePrice(this)">
            <span class="error_tax_{number}" style="color: red;"></span>
        </td>
        <td class="td_discount_{number}">
            <input class="form-control discount input_float" id="discount_{number}" value="0"
                   onchange="contractGoods.changePrice(this)">
        </td>
        <td>
            <input class="form-control amount input_float" disabled>
        </td>
        <td>
            <input class="form-control order_code" disabled>
        </td>
        <td>
            <input class="form-control note">
        </td>
        <td>
            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label>
                    <input class="is_applied_kpi" type="checkbox" disabled
                           checked>
                    <span></span>
                </label>
            </span>
        </td>
    </tr>
</script>
<script type="text/template" id="tpl-goods-order">
    <tr class="tr-goods tr-goods-same">
        <td class="td_remove">
            <button onclick="contractGoods.removeObject(this)"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                    title="{{__('Xoá')}}">
                <i class="la la-trash"></i>
            </button>
        </td>
        <td>
            <input class="form-control object_code" disabled>
            <input type="hidden" class="form-control object_name">
            <input type="hidden" class="form-control number" value="{number}">
            <input type="hidden" class="type_object">
            <input type="hidden" class="staff_id">
        </td>
        <td>
            <select class="form-control object_type" style="width:100%;"
                    onchange="contractGoods.changeObjectType(this)">
                <option></option>
                <option value="product">@lang('Sản phẩm')</option>
                <option value="service">@lang('Dịch vụ')</option>
                <option value="service_card">@lang('Thẻ dịch vụ')</option>
                <option value="product_gift">@lang('Sản phẩm (quà tặng)')</option>
                <option value="service_gift">@lang('Dịch vụ (quà tặng)')</option>
                <option value="service_card_gift">@lang('Thẻ dịch vụ (quà tặng)')</option>
            </select>
            <span class="error_object_type_{number}" style="color: red;"></span>
        </td>
        <td>
            <select class="form-control object_id" style="width:100%;" disabled
                    onchange="contractGoods.changeObject(this, 1)">
                <option></option>
            </select>
            <span class="error_object_id_{number}" style="color: red;"></span>
        </td>
        <td>
            <select class="form-control unit_id" style="width:100%;">
                <option></option>
                @foreach($optionUnit as $v)
                    <option value="{{$v['unit_id']}}">{{$v['name']}}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input class="form-control quantity input_int" value="1" onchange="contractGoods.changeQuantity(this)">
            <span class="error_quantity_{number}" style="color: red;"></span>
        </td>
        <td>
            <input class="form-control price input_float" id="price_{number}" disabled>
        </td>
        <td>
            <input class="form-control tax input_int" value="0" onchange="contractGoods.changePrice(this)">
            <span class="error_tax_{number}" style="color: red;"></span>
        </td>
        <td class="td_discount_{number}">
            <input class="form-control discount input_float" id="discount_{number}" value="0"
                   onchange="contractGoods.changePrice(this)">
        </td>
        <td>
            <input class="form-control amount input_float" disabled>
        </td>
        <td>
            <input class="form-control order_code" disabled>
        </td>
        <td>
            <input class="form-control note">
        </td>
        <td>
            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label>
                    <input class="is_applied_kpi" type="checkbox" disabled
                           checked>
                    <span></span>
                </label>
            </span>
        </td>
    </tr>
</script>
<script type="text/template" id="tpl-edit-goods">
    <a href="javascript:void(0)" onclick="contractGoods.clickEdit(this)"
       class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
       title="{{__('Sửa')}}">
        <i class="la la-edit"></i>
    </a>
</script>
<script type="text/template" id="tpl-save-goods">
    <a href="javascript:void(0)" onclick="contractGoods.clickSave(this)"
       class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill btn-save-goods"
       title="{{__('Sửa')}}">
        <i class="la la-check"></i>
    </a>
</script>
<script type="text/template" id="payment_method_tpl">
    <div class="row mt-3 method div_payment_method_{id}">
        <label class="col-lg-6 font-13">{label}:</label>
        <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
            <input style="color: #008000" class="form-control m-input" placeholder="{{__('Nhập giá tiền')}}"
                   aria-describedby="basic-addon1"
                   name="payment_method" id="payment_method_{id}" value="0">
            <div class="input-group-append">
                    <span class="input-group-text">{{__('VNĐ')}}
                    </span>
            </div>
        </div>
    </div>
</script>