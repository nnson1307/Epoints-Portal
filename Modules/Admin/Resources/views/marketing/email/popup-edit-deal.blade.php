
<div class="modal fade show" id="modal-edit" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA DEAL')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-edit">
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Pipeline'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="pipeline_code" name="pipeline_code"
                                        style="width:100%;">
                                    <option></option>
                                    @foreach($optionPipeline as $v)
                                        <option value="{{$v['pipeline_code']}}"
                                                {{$item['pipeline_code']==$v['pipeline_code'] ? 'selected': ''}}>
                                            {{$v['pipeline_name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Hành trình'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control journey" id="journey_code" name="journey_code"
                                        style="width:100%;">
                                    <option></option>
                                    @foreach($optionJourney as $v)
                                        <option value="{{$v['journey_code']}}"
                                                {{$item['journey_code'] == $v['journey_code'] ? 'selected': ''}}>{{$v['journey_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Tổng tiền'):
                            </label>
                            <div class="input-group" id="amount-remove">
                                <input type="text" class="form-control m-input" id="amount" name="amount"
                                       value="{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Ngày kết thúc dự kiến'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input" id="end_date_expected"
                                       name="end_date_expected"
                                       value="{{\Carbon\Carbon::parse($item['closing_date'])->format('d/m/Y')}}"
                                       placeholder="@lang('Chọn ngày kết thúc dự kiến')">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-striped m-table m-table--head-bg-default" id="table_add">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('Loại')</th>
                                <th class="tr_thead_list">@lang('Sản phẩm')</th>
                                <th class="tr_thead_list">@lang('Giá')</th>
                                <th class="tr_thead_list">@lang('Số lượng')</th>
                                <th class="tr_thead_list">@lang('Giảm giá')</th>
                                <th class="tr_thead_list">@lang('Tổng tiền')</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody style="font-size: 13px" class="append-object">
                            @foreach($listObject as $key => $value)
                                <tr class="add-object">
                                    <td style="width:15%;">
                                        <select class="form-control object_type" style="width:100%;"
                                                onchange="dealEmail.changeObjectType(this)">
                                            <option></option>
                                            <option value="product" {{$value['object_type'] == 'product' ? 'selected' : ''}}>
                                                @lang('Sản phẩm')
                                            </option>
                                            <option value="service" {{$value['object_type'] == 'service' ? 'selected' : ''}}>
                                                @lang('Dịch vụ')
                                            </option>
                                            <option value="service_card" {{$value['object_type'] == 'service_card' ? 'selected' : ''}}>
                                                @lang('Thẻ dịch vụ')
                                            </option>
                                        </select>
                                        <span class="error_object_type color_red"></span>
                                    </td>
                                    <td style="width:25%;">
                                        <select class="form-control object_code" style="width:100%;"
                                                onchange="dealEmail.changeObject(this)">
                                            <option></option>
                                            <option value="{{$value['object_code']}}" selected>{{$value['object_name']}}</option>
                                        </select>
                                        <span class="error_object color_red"></span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control m-input object_price" name="object_price" style="background-color: white;"
                                               value="{{number_format($value['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}" readonly>
                                        <input type="hidden" class="object_id" name="object_id" value="{{$value['object_id']}}">
                                    </td>
                                    <td style="width: 9%">
                                        <input type="text" class="form-control m-input btn-ct-input object_quantity" name="object_quantity"
                                               style="text-align: center"
                                               value="{{number_format($value['quantity'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control m-input object_discount" name="object_discount"
                                               value="{{number_format($value['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control m-input object_amount" name="object_amount"  style="background-color: white;"
                                               value="{{number_format($value['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}" readonly>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="dealEmail.removeObject(this)"
                                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                           title="@lang('Xóa')"><i class="la la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                onclick="dealEmail.addObject()">
                            <i class="la la-plus"></i> @lang('THÊM')
                        </button>
                    </div>
                    <input type="hidden" id="deal_id" value="{{$item['email_deal_id']}}">
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button type="button" onclick="edit.closeModalDeal()"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button" onclick="edit.saveModalDeal()"
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