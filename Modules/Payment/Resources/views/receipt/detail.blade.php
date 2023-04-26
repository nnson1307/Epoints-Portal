@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ PHIẾU THU')</span>
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
                        @lang('CHI TIẾT PHIẾU THU')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row form-group">
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Loại phiếu thu'):
                        </label>
                        <div class="input-group">
                            <input class="form-control" value="{{$item['receipt_type_name']}}" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Thông tin người trả tiền'):
                            </label>
                            <div class="input-group">
                                @if ($item['object_type'] != 'debt' && $item['order_id'] === 0)
                                    <input class="form-control" value=" {{$item['object_accounting_type_name']}}" disabled>
                                @elseif ($item['object_type'] == 'debt')
                                    <input class="form-control" value="{{__('Công nợ')}}" disabled>
                                @else
                                    <input class="form-control" value="{{__('Khách hàng')}}" disabled>
                                @endif

                            </div>
                        </div>

                        <div class="form-group m-form__group col-lg-6">
                            <label class="black_title">
                                @lang('Chọn người trả tiền'):
                            </label>
                            <div class="input-group">
                                @if ($item['object_type'] != 'debt' && $item['order_id'] === 0)
                                    <input class="form-control" value=" {{$item['object_accounting_name']}}" disabled>
                                @elseif ($item['object_type'] == 'debt')
                                    <input class="form-control" value="{{$item['customer_name_debt']}}" disabled>
                                @else
                                    <input class="form-control" value=" {{$item['customer_name']}}" disabled>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Số tiền'):<b class="text-danger"> *</b>
                        </label>
                        <input type="text" class="form-control m-input format-money" disabled
                               id="money" name="money" value="{{$item['amount']}}"
                               placeholder="@lang('Nhập số tiền')">
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nội dung thu'):
                        </label>
                        <input type="text" class="form-control m-input" value="{{$item['note']}}" disabled
                               id="note" name="note" placeholder="@lang('Nhập nội dung thu')">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-group m-form__group bdt_order bdb_order">
                    <div class="m-section__content">
                        <div class="table-responsive">
                            <table class="table table-striped m-table">
                                <thead style="white-space: nowrap;">
                                <tr>
                                    <th class="tr_thead_od_detail">{{__('HÌNH THỨC THANH TOÁN')}}</th>
                                    <th class="tr_thead_od_detail">{{__('TIỀN THANH TOÁN')}}</th>
                                    <th class="tr_thead_od_detail">@lang('Ngày thanh toán')</th>
                                </tr>
                                </thead>
                                <tbody style="font-size: 12px">
                                @foreach($detail as $v)
                                    <tr>
                                        <td>
                                            {{$v['payment_method_name']}}
                                        </td>
                                        <td>
                                            {{number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                        </td>
                                        <td>{{$v['created_at'] != null ? \Carbon\Carbon::parse($v['created_at'])->format('d/m/Y H:i') : ''}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('receipt')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('QUAY VỀ')</span>
                            </span>
                    </a>

                    @if ($item['type_insert'] == 'manual' && $item['status'] == 'unpaid')
                        <a type="button" href="{{route('receipt.edit', $item['receipt_id'])}}"
                           class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-edit"></i>
                                <span>@lang('CHỈNH SỬA')</span>
                        </span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
@endsection
