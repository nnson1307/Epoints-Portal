@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ PHIẾU BẢO DƯỠNG')</span>
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
                        @lang('CHỈNH SỬA PHIẾU BẢO DƯỠNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            {!! csrf_field() !!}
            <form id="form-edit">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên đưa đi bảo dưỡng'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="staff_id" name="staff_id"
                                        style="width:100%;" {{$isUpdate == 0 ? 'disabled': ''}}>
                                    <option></option>
                                    @foreach($optionStaff as $v)
                                        <option value="{{$v['staff_id']}}" {{$item['staff_id'] == $v['staff_id'] ? 'selected': ''}}>{{$v['staff_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Loại đối tượng'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="object_type" name="object_type"
                                            style="width:100%;"
                                            onchange="view.changeType(this)" {{$isUpdate == 0 ? 'disabled': ''}}>
                                        <option></option>
                                        <option value="product" {{$item['object_type'] == 'product' ? 'selected' : ''}}>@lang('Sản phẩm')</option>
                                        <option value="service" {{$item['object_type'] == 'service' ? 'selected' : ''}}>@lang('Dịch vụ')</option>
                                        <option value="service_card" {{$item['object_type'] == 'service_card' ? 'selected' : ''}}>@lang('Thẻ dịch vụ')</option>
                                    </select>
                                    <input type="hidden" id="object_type_hidden" name="object_type_hidden" value="{{$v['object_type']}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Tên đối tượng'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="object_id" name="object_id"
                                            style="width:100%;" {{$isUpdate == 0 ? 'disabled': ''}}>
                                        <option></option>
                                        @if($item['object_id'] != null)
                                            <option value="{{$item['object_id']}}"
                                                    selected>{{$item['object_name']}}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" id="object_code" name="object_code"
                                           value="{{$item['object_code']}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tình trạng đối tượng bảo dưỡng'):
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input" id="object_status" name="object_status"
                                       value="{{$item['object_status']}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                @if($isUpdate != 0)
                                    <div class="form-group m-form__group">
                                        <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                                onclick="view.modalImageBefore()">
                                            <i class="la la-plus"></i> @lang('Hình ảnh trước bảo dưỡng')
                                        </button>
                                    </div>
                                @endif
                                <div class="image_before image-show">
                                    @if(count($imageBefore) > 0)
                                        @foreach($imageBefore as $v)
                                            <div class="wrap-img image-show-child">
                                                <input type="hidden" name="img-before" value="{{$v}}">
                                                <img class="m--bg-metal m-image img-sd " src="{{$v}}" alt="Hình ảnh"
                                                     width="100px" height="100px">
                                                <span class="delete-img-sv"
                                                      style="display: {{$isUpdate == 0 ? 'none': 'block'}};">
                                                    <a href="javascript:void(0)" onclick="view.removeImage(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                @if($isUpdate != 0)
                                    <div class="form-group m-form__group">
                                        <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                                onclick="view.modalImageAfter()">
                                            <i class="la la-plus"></i> @lang('Hình ảnh sau bảo dưỡng')
                                        </button>
                                    </div>
                                @endif
                                <div class="image_after image-show">
                                    @if(count($imageAfter) > 0)
                                        @foreach($imageAfter as $v)
                                            <div class="wrap-img image-show-child">
                                                <input type="hidden" name="img-after" value="{{$v}}">
                                                <img class="m--bg-metal m-image img-sd " src="{{$v}}" alt="Hình ảnh"
                                                     width="100px" height="100px">
                                                <span class="delete-img-sv"
                                                      style="display: {{$isUpdate == 0 ? 'none': 'block'}};">
                                                    <a href="javascript:void(0)" onclick="view.removeImage(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ngày đưa đi bảo dưỡng'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly=""
                                       placeholder="@lang('Ngày trả hàng')" id="repair_date"
                                       name="repair_date" {{$isUpdate == 0 ? 'disabled': ''}}
                                       value="{{\Carbon\Carbon::parse($item['repair_date'])->format('d/m/Y H:i')}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Chi phí bảo dưỡng'):
                                </label>
                                <input type="text" class="form-control m-input" id="repair_cost" name="repair_cost"
                                       value="{{number_format($item['repair_cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                       onchange="view.loadAmountPay()" {{$isUpdate == 0 ? 'disabled': ''}}>
                            </div>
                            <div class="col-lg-6">
                                <label class="black_title">
                                    @lang('Bảo hiểm chi trả'):
                                </label>
                                <input type="text" class="form-control m-input" id="insurance_pay" name="insurance_pay"
                                       value="{{number_format($item['insurance_pay'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                       onchange="view.loadAmountPay()" {{$isUpdate == 0 ? 'disabled': ''}}>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nội dung bảo dưỡng'):
                            </label>
                            <textarea class="form-control" id="repair_content" name="repair_content" rows="5">{{$item['repair_content']}}</textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái'):
                            </label>
                            <div class="input-group">
                                <select class="form-control" id="status" name="status"
                                        style="width:100%;">
                                    <option></option>
                                    @if ($item['status'] == 'new')
                                        <option value="new" {{$item['status'] == 'new' ? 'selected' : ''}}>@lang('Mới')</option>
                                    @endif
                                    @if (in_array($item['status'], ['new', 'received']))
                                        <option value="received" {{$item['status'] == 'received' ? 'selected' : ''}}>@lang('Đã nhận hàng')</option>
                                    @endif
                                    @if (in_array($item['status'], ['new', 'received', 'processing']))
                                        <option value="processing" {{$item['status'] == 'processing' ? 'selected' : ''}}>@lang('Đang xử lý')</option>
                                    @endif
                                    @if (in_array($item['status'], ['new', 'received', 'processing', 'ready_delivery']))
                                        <option value="ready_delivery" {{$item['status'] == 'ready_delivery' ? 'selected' : ''}}>@lang('Sẵn sàng trả hàng')</option>
                                    @endif
                                    @if (in_array($item['status'], ['new', 'received', 'processing', 'ready_delivery', 'finish']))
                                        <option value="finish" {{$item['status'] == 'finish' ? 'selected' : ''}}>@lang('Hoàn tất')</option>
                                    @endif
                                    @if ($item['status'] != 'finish')
                                        <option value="cancel" {{$item['status'] == 'cancel' ? 'selected' : ''}}>@lang('Đã hủy')</option>
                                    @endif
                                </select>
                            </div>
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
                        @if (count($cost) > 0)
                            @foreach($cost as $k => $v)
                                <tr class="tr_code">
                                    <td>
                                        <select class="form-control maintenance_cost_type" style="width: 100%"
                                                onchange="view.changeCostType(this)" {{$isUpdate == 0 ? 'disabled': ''}}>
                                            <option></option>
                                            @foreach($optionCostType as $v1)
                                                <option value="{{$v1['maintenance_cost_type_id']}}" {{$v1['maintenance_cost_type_id'] == $v['maintenance_cost_type'] ? 'selected': ''}}>
                                                    {{$v1['maintenance_cost_type_name']}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" class="number" value="{{$k+1}}">
                                        <span class="error_cost_type_{{$k+1}} color_red"></span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control cost cost_{{$k+1}}"
                                               value="{{number_format($v['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                               onchange="view.loadAmountPay()" {{$isUpdate == 0 ? 'disabled': ''}}>
                                        <span class="error_cost_{{$k+1}} color_red"></span>
                                    </td>
                                    <td>
                                        @if ($isUpdate != 0)
                                            <a href="javascript:void(0)" onclick="view.removeTr(this)"
                                               class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                               title="Xóa">
                                                <i class="la la-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if ($isUpdate != 0)
                        <div class="form-group m-form__group">
                            <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                                    onclick="view.addCost()">
                                <i class="la la-plus"></i> @lang('Thêm chi phí phát sinh')
                            </button>
                        </div>
                    @endif
                </div>
                <div class="form-group m-form__group">
                    <h6 class="m-section__heading">@lang("Tổng tiền cần thanh toán"): <span
                                class="div_total_amount_pay">{{number_format($item['total_pay'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                    </h6>
                    <input type="hidden" id="amount_pay" name="amount_pay" value="{{$item['amount_pay']}}">
                    <input type="hidden" id="total_amount_pay" name="total_amount_pay"
                           value="{{$item['total_pay']}}">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('repair')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button"
                            onclick="edit.save('{{$item['repair_id']}}', '{{$item['repair_code']}}')"
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
    @include('warranty::repair.pop.modal-image-before')
    @include('warranty::repair.pop.modal-image-after')
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
    <script src="{{asset('static/backend/js/warranty/repair/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._init();
        view.dropzoneBefore();
        view.dropzoneAfter();
        stt = {{count($cost)}};
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
