<div class="modal fade show" id="modal-create" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document" style="max-width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('TẠO DEAL')
                </h5>
                <a href="javascript:void(0)" onclick="create.popupCreateLead()"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM KHÁCH HÀNG TIỀM NĂNG')</span>
                                    </span>
                </a>
            </div>
            <div class="modal-body">
                <form id="form-create">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên deal'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="deal_name" name="deal_name"
                                           placeholder="@lang('Nhập tên deal')">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Người sở hữu deal'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    @if(Auth::user()->is_admin)
                                        <select class="form-control" id="staff" name="staff"
                                                style="width:100%;">
                                            <option></option>
                                            @foreach($optionStaff as $v)
                                                @if($v['staff_id'] == Auth()->id())
                                                    <option value="{{Auth()->id()}}" selected>{{Auth()->user()->full_name}}</option>
                                                @else
                                                    <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="form-control" id="staff" name="staff"
                                                style="width:100%;">
                                            <option value="{{Auth()->id()}}" selected>{{Auth()->user()->full_name}}</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Loại Khách hàng'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="type_customer" name="type_customer"
                                            style="width:100%;"
                                            onchange="customerDealCreate.clearCustomerContact();">
                                        <option value="customer" selected>@lang('Khách hàng')</option>
                                        <option value="lead">@lang('Khách hàng tiềm năng')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Khách hàng'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="customer_code" name="customer_code"
                                            style="width:100%;" onchange="customerDealCreate.loadContact()">
                                        <option></option>

                                        @if (count($infoUser) > 0)
                                            <option value="{{$infoUser['user_code']}}" selected>{{$infoUser['full_name']}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group" id="customer_contact_code-remove">
                                <label class="black_title">
                                    @lang('Liên hệ'):
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="customer_contact_code" name="customer_contact_code"
                                            style="width:100%;">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Số điện thoại'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input phone" id="add_phone" name="add_phone"
                                       placeholder="@lang('Số điện thoại')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Pipeline'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="pipeline_code" name="pipeline_code"
                                            style="width:100%;">
                                        <option></option>
                                        @foreach($optionPipeline as $v)
                                            <option value="{{$v['pipeline_code']}}">{{$v['pipeline_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Hành trình'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control journey" id="journey_code" name="journey_code"
                                            style="width:100%;">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Chi nhánh'):
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="branch_code" name="branch_code"
                                            style="width:100%;">
                                        <option></option>
                                        @foreach($optionBranches as $v)
                                            <option value="{{$v['branch_code']}}">{{$v['branch_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tag'):
                                </label>
                                <div>
                                    <select multiple class="form-control" id="tag_id" name="tag_id" style="width:100%;">
                                        @foreach($optionTag as $v)
                                            <option value="{{$v['tag_id']}}">{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Nguồn đơn hàng'):
                                </label>
                                <div class="input-group">
                                    <select class="form-control tag" id="order_source" name="order_source"
                                            style="width:100%;">
                                        <option></option>
                                        @foreach($optionOrderSource as $v)
                                            <option value="{{$v['order_source_id']}}">{{$v['order_source_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tổng tiền'):
                                </label>
                                <div class="input-group" id="amount-remove">
                                    <input type="text" class="form-control m-input" id="amount" name="amount">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Xác suất'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="probability" name="probability"
                                           placeholder="@lang('Nhập xác suất')">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Ngày kết thúc dự kiến'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="end_date_expected" name="end_date_expected"
                                           placeholder="@lang('Chọn ngày kết thúc dự kiến')">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Chi tiết deal'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="deal_description" name="deal_description"
                                           placeholder="@lang('Nhập chi tiết deal')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-striped m-table m-table--head-bg-default" id="table_add">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('Loại')</th>
                                <th class="tr_thead_list">@lang('Đối tượng')</th>
                                <th class="tr_thead_list">@lang('Giá')</th>
                                <th class="tr_thead_list">@lang('Số lượng')</th>
                                <th class="tr_thead_list">@lang('Giảm giá')</th>
                                <th class="tr_thead_list">@lang('Tổng tiền')</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody style="font-size: 13px" class="append-object">

                            </tbody>
                        </table>
                        <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                onclick="viewCusDeal.addObject()">
                            <i class="la la-plus"></i> @lang('THÊM')
                        </button>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button type="button" onclick="customerDealCreate.cancelDeal()"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="customerDealCreate.save()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text-template" id="tpl-object">
    <tr class="add-object">
        <td style="width:15%;">
            <select class="form-control object_type" style="width:100%;"
                onchange="view.changeObjectType(this)">
                <option></option>
                <option value="product">@lang('Sản phẩm')</option>
                <option value="service">@lang('Dịch vụ')</option>
                <option value="service_card">@lang('Thẻ dịch vụ')</option>
            </select>
            <span class="error_object_type color_red"></span>

            <input type="hidden" class="object_id" name="object_id">
            <input type="hidden" class="stt_row" value="{stt}">
        </td>
        <td style="width:25% !important;">
            <select class="form-control object_code" style="width:100%;"
                onchange="view.changeObject(this)">
                <option></option>
            </select>
            <span class="error_object color_red"></span>
        </td>
        <td class="td_object_price_{stt}">
            <input type="text" class="form-control m-input object_price" name="object_price" style="background-color: white;"
                   id="object_price_{stt}" value="">
        </td>
        <td style="width: 145px !important;">
            <input type="text" class="form-control m-input btn-ct-input object_quantity" name="object_quantity"
                   id="object_quantity_{stt}" style="text-align: center; height: 30px !important" value="">
        </td>
        <td>
            <input type="text" class="form-control m-input object_discount" name="object_discount"
                   id="object_discount_{stt}" value="">
        </td>
        <td>
            <input type="text" class="form-control m-input object_amount" name="object_amount" style="background-color: white;"
                   id="object_amount_{stt}" value="" readonly>
        </td>
        <td>
            <a href="javascript:void(0)" onclick="view.removeObject(this)"
               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
               title="@lang('Xóa')"><i class="la la-trash"></i>
            </a>
        </td>
    </tr>
</script>

<script type="text-template" id="tpl-object-price">
    <input type="text" class="form-control m-input object_price" name="object_price" style="background-color: white;"
           id="object_price_{stt}" value="{price}">
</script>