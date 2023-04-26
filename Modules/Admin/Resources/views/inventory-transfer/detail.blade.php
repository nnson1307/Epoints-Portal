@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                   <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT PHIẾU CHUYỂN KHO')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Mã phiếu')}}: {{$inventoryTransfer->transferCode}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{$inventoryTransfer->transferCode}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Kho xuất')}}: {{$inventoryTransfer->warehouseFrom}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{$inventoryTransfer->warehouseTo}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Kho nhập')}}: {{$inventoryTransfer->warehouseTo}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{$inventoryTransfer->warehouseFrom}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Người tạo')}}: {{$inventoryTransfer->user}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{$inventoryTransfer->user}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Trạng thái')}}:
                                    @if($inventoryTransfer->status=='new')
                                        <span class="">{{__('Mới')}}</span>
                                    @elseif($inventoryTransfer->status=='success')
                                        <span class="">{{__('Hoàn thành')}}</span>
                                    @elseif($inventoryTransfer->status=='draft')
                                        <span class="">{{__('Lưu nháp')}}</span>
                                    @elseif($inventoryTransfer->status=='cancel')
                                        <span class="">{{__('Hủy')}}</span>
                                    @elseif($inventoryTransfer->status=='inprogress')
                                        <span class="">{{__('Đang xử lý')}}</span>
                                    @endif
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--@if($inventoryTransfer->status=='new')--}}
                            {{--<span class="m-badge m-badge--info m-badge--wide">Mới</span>--}}
                            {{--@elseif($inventoryTransfer->status=='success')--}}
                            {{--<span class="m-badge m-badge--success m-badge--wide">Hoàn thành</span>--}}
                            {{--@elseif($inventoryTransfer->status=='draft')--}}
                            {{--<span class="m-badge m-badge--warning m-badge--wide">Lưu nháp</span>--}}
                            {{--@elseif($inventoryTransfer->status=='cancel')--}}
                            {{--<span class="m-badge m-badge--danger m-badge--wide">Hủy</span>--}}
                            {{--@elseif($inventoryTransfer->status=='inprogress')--}}
                            {{--<span class="m-badge m-badge--primary m-badge--wide">Đang xử lý</span>--}}
                            {{--@endif--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Ngày xuất')}}: {{(new DateTime($inventoryTransfer->transferAt))->format('d/m/Y')}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{(new DateTime($inventoryTransfer->transferAt))->format('d/m/Y')}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Ngày nhập')}}: {{(new DateTime($inventoryTransfer->approvedAt))->format('d/m/Y')}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{(new DateTime($inventoryTransfer->approvedAt))->format('d/m/Y')}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Lý do')}}: {{$inventoryTransfer->note}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<textarea readonly rows="4" cols="50" name="description"--}}
                            {{--class="form-control">{{$inventoryTransfer->note}}</textarea>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="table-content">
                        {{--Table version--}}
                        <div class="table-responsive">
                            <table id="add-product-version"
                                   class="table table-striped m-table ss--header-table">
                                <thead>
                                <tr class="ss--font-size-th ss--nowrap">
                                    <th>#</th>
                                    <th>{{__('SẢN PHẨM')}}</th>
                                    <th class="ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
                                    <th class="ss--text-center">{{__('GIÁ NHẬP')}}</th>
                                    <th class="ss--text-center">{{__('SỐ LƯỢNG')}}</th>
                                    <th class="ss--text-center">{{__('TỔNG TIỀN')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($LIST))
                                    @foreach($LIST as $key=>$value)
                                        <tr class="ss--font-size-13">
                                            <td>{{($key+1)}}</td>
                                            <td style="width: 300px">{{ $value['productName'] }}
                                            </td>
                                            <td class="ss--text-center">
                                                {{--<div class="input-group m-input-group m-input-group--air">--}}
                                                {{--<input style="text-align: center" readonly type="text"--}}
                                                {{--class="form-control"--}}
                                                {{--value="{{ $value['unitName'] }}">--}}
                                                {{--</div>--}}
                                                {{ $value['unitName'] }}
                                            </td>
                                            <td class="ss--text-center">
                                                {{--<div class="input-group m-input-group m-input-group--air">--}}
                                                {{--<input style="text-align: right" type="text" readonly--}}
                                                {{--class="form-control"--}}
                                                {{--value="{{number_format($value['currentPrice'],0,",",",")}}">--}}
                                                {{--</div>--}}
                                                {{number_format($value['currentPrice'],0,",",",")}}
                                            </td>
                                            <td class="ss--text-center">
                                                {{--<div class="input-group m-input-group m-input-group--air">--}}
                                                {{--<input style="text-align: center" type="text" readonly--}}
                                                {{--class="form-control"--}}
                                                {{--value="{{number_format($value['quantity'],0,",",".")}}">--}}
                                                {{--</div>--}}
                                                {{number_format($value['quantity'],0,",",".")}}
                                            </td>
                                            <td class="ss--text-center">
                                                {{--<div class="input-group m-input-group m-input-group--air">--}}
                                                {{--<input style="text-align: right" type="text" readonly--}}
                                                {{--class="form-control"--}}
                                                {{--value="{{number_format($value['total'],0,",",",")}}">--}}
                                                {{--</div>--}}
                                                {{number_format($value['total'],0,",",",")}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="m-datatable m-datatable--default">
                            <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
                                @if((int)(count($data))>10)
                                    <ul class="m-datatable__pager-nav" style="float: right">
                                        @if($page>1)
                                            <li><a onclick="InventoryTransfer.pageClick(1)" title="First"
                                                   class="m-datatable__pager-link m-datatable__pager-link--first"
                                                   data-page="1"><i
                                                            class="la la-angle-double-left">
                                                    </i></a></li>
                                            <li><a onclick="InventoryTransfer.pageClick({{$page-1}})" title="Previous"
                                                   class="m-datatable__pager-link m-datatable__pager-link--prev"><i
                                                            class="la la-angle-left"></i></a></li>
                                        @else
                                            <li><a title="First"
                                                   class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled"
                                                   disabled="disabled"><i class="la la-angle-double-left"></i></a></li>
                                            <li><a title="Previous"
                                                   class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled"
                                                   disabled="disabled"><i class="la la-angle-left"></i></a></li>
                                        @endif
                                        <?php
                                        $totalPage = 0;
                                        if (is_int(count($data) / 10) == true) {
                                            $totalPage = (count($data) / 10) + 1;
                                        } else {
                                            $totalPage = (int)(count($data) / 10) + 2;
                                        }
                                        ?>
                                        @for ($i=1;$i<$totalPage;$i++)
                                            @if($i==$page)
                                                <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                                                       onclick="InventoryTransfer.pageClick({{ $i }})"
                                                       title="1">{{ $i }}</a></li>
                                            @else
                                                <li><a class="m-datatable__pager-link"
                                                       onclick="InventoryTransfer.pageClick({{ $i }})">{{ $i }}</a></li>
                                            @endif
                                        @endfor
                                        {{-- Next Page Link --}}
                                        @if($page<(int)(count($LIST)/10)+1)
                                            <li><a title="Next" class="m-datatable__pager-link"
                                                   onclick="InventoryTransfer.pageClick({{$page+1}})"
                                                   data-page=""><i class="la la-angle-right"></i></a></li>
                                            <li><a title="Last" onclick="InventoryTransfer.pageClick({{$totalPage-1}})"
                                                   class="m-datatable__pager-link m-datatable__pager-link--last"
                                                   data-page=""><i class="la la-angle-double-right"></i></a></li>
                                        @else
                                            <li><a title="Next"
                                                   class="m-datatable__pager-link m-datatable__pager-link--disabled"
                                                   disabled="disabled"
                                                   data-page=""><i class="la la-angle-right"></i></a></li>
                                            <li><a title="Last"
                                                   class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled"
                                                   disabled="disabled"
                                                   data-page=""><i class="la la-angle-double-right"></i></a></li>
                                        @endif
                                    </ul>
                                @endif
                                <div class="m-datatable__pager-info" style="float: left">
                                <span class="m-datatable__pager-detail">
                                    @if(count($LIST)>0)
                                        {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}}
                                        {{__('của')}} {{ count($data) }}
                                    @else
                                        {{__('Hiển thị 0 - 0 của 0')}}
                                    @endif
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                        <div class="m-form__actions m--align-right">
                            <button onclick="location.href='{{route('admin.product-inventory')}}'"
                                    data-dismiss="modal"
                                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-arrow-left"></i>
                                    <span>{{__('HỦY')}}</span>
                                    </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{$id}}" id="id">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/inventory-transfer/list.js?v='.time())}}"
            type="text/javascript"></script>
@endsection
