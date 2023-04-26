@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ PHIẾU BẢO TRÌ')</span>
@stop
@section('content')
    <style>
        .err {
            color: red;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('THÊM PHIẾU BẢO TRÌ')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            {!! csrf_field() !!}
            <form id="form-register">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên khách hàng'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="customer_code" name="customer_code"
                                        onchange="view.chooseCustomer(this)" style="width:100%;">
                                    <option></option>
                                    @foreach($optionCustomer as $v)
                                        <option value="{{$v['customer_code']}}"
                                                {{isset($dataLoad['customer_code']) && $dataLoad['customer_code'] == $v['customer_code'] ? 'selected' : ''}}>{{$v['customer_name']. ' - '. $v['phone']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <div class="div_choose_warranty_code" style="{{isset($dataLoad['info']) ? 'block': 'none'}};">
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                                onclick="view.modalWarrantyCard()">
                                            <i class="la la-plus"></i> @lang('Chọn phiếu bảo hành')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="div_warranty_code" style="display: none;">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Phiếu bảo hành'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="warranty_code"
                                           name="warranty_code" disabled value="123213">
                                    <input type="hidden" id="object_serial" name="object_serial">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Chi phí được bảo hành'):
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input" id="warranty_value"
                                           name="warranty_value" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Loại đối tượng'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="object_type" name="object_type"
                                            style="width:100%;" onchange="view.changeType(this)">
                                        <option></option>
                                        <option value="product" selected>@lang('Sản phẩm')</option>
                                        <option value="service">@lang('Dịch vụ')</option>
                                        <option value="service_card">@lang('Thẻ dịch vụ')</option>
                                    </select>
                                </div>
                                <input type="hidden" id="object_type_hidden" name="object_type_hidden" value="product">
                            </div>
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Tên đối tượng'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="object_type_id" name="object_type_id"
                                            style="width:100%;">
                                        <option></option>
                                    </select>
                                    <input type="hidden" id="object_code" name="object_code">
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tình trạng đối tượng bảo trì'):
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input" id="object_status" name="object_status">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                            onclick="view.modalImageBefore()">
                                        <i class="la la-plus"></i> @lang('Hình ảnh trước bảo trì')
                                    </button>
                                </div>
                                <div class="image_before image-show row"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                            onclick="view.modalImageAfter()">
                                        <i class="la la-plus"></i> @lang('Hình ảnh sau bảo trì')
                                    </button>
                                </div>
                                <div class="image_after image-show row"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên thực hiện'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="staff_id" name="staff_id" style="width:100%;">
                                    <option></option>
                                    @foreach($optionStaff as $v)
                                        <option value="{{$v['staff_id']}}">{{$v['staff_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ngày trả hàng dự kiến'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly=""
                                       placeholder="@lang('Ngày trả hàng')"
                                       id="date_estimate_delivery" name="date_estimate_delivery">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Chi phí bảo trì'):
                                </label>
                                <input type="text" class="form-control m-input" id="maintenance_cost"
                                       name="maintenance_cost" value="0" onchange="view.loadAmountPay()">
                            </div>
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Bảo hiểm chi trả'):
                                </label>
                                <input type="text" class="form-control m-input" id="insurance_pay" name="insurance_pay"
                                       value="0" onchange="view.loadAmountPay()">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nội dung bảo trì'):
                            </label>
                            <textarea class="form-control" id="maintenance_content" name="maintenance_content" rows="5">
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <table class="table table-striped m-table m-table--head-bg-default" id="table-maintenance-cost">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_list">@lang('LOẠI CHI PHÍ')</th>
                            <th class="tr_thead_list">@lang('TRỊ GIÁ')</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="form-group m-form__group">
                        <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                onclick="view.addCost()">
                            <i class="la la-plus"></i> @lang('Thêm chi phí phát sinh')
                        </button>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <h6 class="m-section__heading">@lang("Tổng tiền cần thanh toán"): <span
                                class="div_total_amount_pay">0</span></h6>
                    <input type="hidden" id="amount_pay" name="amount_pay" value="0">
                    <input type="hidden" id="total_amount_pay" name="total_amount_pay" value="0">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('maintenance')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="create.save()"
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
    <div id="my-modal"></div>
    @include('warranty::maintenance.pop.modal-image-before')
    @include('warranty::maintenance.pop.modal-image-after')
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/warranty/maintenance/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._init();
        view.dropzoneBefore();
        view.dropzoneAfter();
    </script>
    <script>
        @if(isset($dataLoad) && count($dataLoad) > 0)
        $(document).ready(function () {
            {{--view.chooseWarrantyCard('', '{{$dataLoad['warranty_card_code']}}')--}}
            view.submitChooseWarranty();
        });
        @endif
    </script>
    <script type="text/template" id="tpl-image-before">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="img-before" value="{imageName}">
            <img class="m--bg-metal m-image img-sd " src="{imageName}" alt="Hình ảnh" width="100px" height="100px">
            <span class="delete-img-sv" style="display: none;">
                <a href="javascript:void(0)" onclick="view.removeImage(this)">
                    <i class="la la-close"></i>
                </a>
            </span>
        </div>
    </script>
    <script type="text/template" id="tpl-image-after">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="img-after" value="{imageName}">
            <img class="m--bg-metal m-image img-sd " src="{imageName}" alt="Hình ảnh" width="100px" height="100px">
            <span class="delete-img-sv" style="display: block;">
                <a href="javascript:void(0)" onclick="view.removeImage(this)">
                    <i class="la la-close"></i>
                </a>
            </span>
        </div>
    </script>
    <script type="text/template" id="tpl-tr-table">
        <tr class="tr_code">
            <td>
                <select class="form-control maintenance_cost_type" style="width: 100%"
                        onchange="view.changeCostType(this)">
                    <option></option>
                    @foreach($optionCostType as $v)
                        <option value="{{$v['maintenance_cost_type_id']}}">{{$v['maintenance_cost_type_name']}}</option>
                    @endforeach
                </select>
                <input type="hidden" class="number" value="{stt}">
                <span class="error_cost_type_{stt} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control cost cost_{stt}" value="0" disabled
                       onchange="view.loadAmountPay()">
                <span class="error_cost_{stt} color_red"></span>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="view.removeTr(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                   title="Xóa">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@endsection
