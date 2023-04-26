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
    <style>
        .w-10percent {
            width:10% !important;
        }

        span.select2 {
            width:100% !important;
        }

        #popup-list-serial .modal-dialog {
            max-width: 50%;
        }
        .badge-secondary i:hover {
            cursor: pointer
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT PHIẾU KIỂM KHO')}}
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
                                    {{__('Mã phiếu')}}: {{$inventoryChecking->code}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{$inventoryChecking->code}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Kho')}}: {{$inventoryChecking->warehouseName}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{$inventoryChecking->warehouseName}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Người tạo')}}: {{$inventoryChecking->user}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{$inventoryChecking->user}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Trạng thái')}}:
                                    @if($inventoryChecking->status=='success')
                                        <span class="">{{__('Hoàn thành')}}</span>
                                    @elseif($inventoryChecking->status=='draft')
                                        <span class="">{{__('Lưu nháp')}}</span>
                                    @elseif($inventoryChecking->status=='cancel')
                                        <span class="">{{__('Hủy')}}</span>
                                    @endif
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--@if($inventoryChecking->status=='success')--}}
                            {{--<span class="m-badge m-badge--success m-badge--wide">{{__('Hoàn thành')}}</span>--}}
                            {{--@elseif($inventoryChecking->status=='draft')--}}
                            {{--<span class="m-badge m-badge--warning m-badge--wide">{{__('Lưu nháp')}}</span>--}}
                            {{--@elseif($inventoryChecking->status=='cancel')--}}
                            {{--<span class="m-badge m-badge--danger m-badge--wide">{{__('Hủy')}}</span>--}}
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
                                    {{__('Ngày kiểm tra')}}: {{(new DateTime($inventoryChecking->createdAt))->format('d/m/Y')}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<input type="text" readonly class="form-control"--}}
                            {{--value="{{(new DateTime($inventoryChecking->createdAt))->format('d/m/Y')}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>
                                    {{__('Lý do')}}: {{$inventoryChecking->reason}}
                                </label>
                            </div>
                            {{--<div class="col-lg-9">--}}
                            {{--<div class="input-group m-input-group m-input-group--solid">--}}
                            {{--<textarea readonly rows="4" cols="50" name="description"--}}
                            {{--class="form-control">{{$inventoryChecking->reason}}</textarea>--}}
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
                                   class="table table-striped m-table ss--header-table ss--nowrap">
                                <thead>
                                <tr class="ss--uppercase ss--font-size-th">
                                    <th>#</th>
                                    <th>{{__('MÃ SẢN PHẨM')}}</th>
                                    <th>{{__('SẢN PHẨM')}}</th>
                                    <th class="ss--text-center">{{__('ĐƠN VỊ TÍNH')}}</th>
                                    <th class="ss--text-center">{{__('HỆ THỐNG')}}</th>
                                    <th class="ss--text-center">{{__('THỰC TẾ')}}</th>
                                    <th class="ss--text-center">{{__('CHÊNH LỆCH')}}</th>
                                    <th class="ss--text-center">{{__('XỬ LÝ')}}</th>
                                    <th class="ss--text-center">{{__('GHI CHÚ')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($LIST))
                                    @foreach($LIST as $key=>$value)
                                        <tr class="ss--font-size-13">
                                            <td>{{($key+1)}}</td>
                                            <td >{{ $value['productCode'] }}
                                            <td style="width: 300px">{{ $value['productName'] }}
                                            </td>
                                            <td class="ss--text-center">
                                                {{ $value['unitName'] }}
                                            </td>
                                            <td class="ss--text-center">
                                                {{$value['quantityOld']}}
                                            </td>
                                            <td class="ss--text-center">
                                                {{$value['quantityNew']}}
                                            </td>
                                            <td class="ss--text-center">
                                                {{$value['quantityDifference']}}
                                            </td>
                                            <td class="ss--text-center ss--width-150">
{{--                                                @if(($value['quantityOld']<$value['quantityNew']))--}}
{{--                                                    <b class="m--font-success resolve">--}}
{{--                                                        {{__('Nhập kho')}}--}}
{{--                                                    </b>--}}
{{--                                                @elseif($value['quantityOld']>$value['quantityNew'])--}}
{{--                                                    <b class="m--font-danger resolve">--}}
{{--                                                        {{__('Xuất kho')}}--}}
{{--                                                    </b>--}}
{{--                                                @elseif($value['quantityOld']==$value['quantityNew'])--}}
{{--                                                @endif--}}
                                                @if($value['inventory_management'] != 'serial')
                                                    @if($value['quantityOld']-$value['quantityNew']>0)
                                                        <b class="m--font-danger resolve">
                                                            {{__('Xuất kho')}}
                                                        </b>
                                                    @elseif($value['typeResolve']=="input")
                                                        <b class="m--font-success resolve">
                                                            {{__('Nhập kho')}}
                                                        </b>
                                                    @else
                                                        <b class="m--font-success resolve"></b>
                                                    @endif
                                                @else
                                                    @if($value['quantityOld'] != $value['quantityNew'] || $value['total_import'] != 0)
                                                        @if($value['total_export'] != 0)
                                                            <a href="javascript:void(0)" onclick="InventoryChecking.showPopupSerialProduct(`{{$value['inventory_checking_detail_id']}}`,`{{ $value['productCode'] }}`,'export')" >
                                                                <b class="m--font-danger resolve">
{{--                                                                    {{__('Xuất kho')}} : {{$value['quantityOld'] - $value['total_export']}} <br>--}}
                                                                    {{__('Xuất kho')}} : {{$value['total_export']}} <br>
                                                                </b>
                                                            </a>
                                                        @endif
                                                        @if($value['total_import'] != 0)
                                                            <a href="javascript:void(0)" onclick="InventoryChecking.showPopupSerialProduct(`{{$value['inventory_checking_detail_id']}}`,`{{ $value['productCode'] }}`,'import')" >
                                                                <b class="m--font-success resolve">
                                                                    {{__('Nhập kho')}} : {{$value['total_import']}}
                                                                </b>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <b class="m--font-success resolve"></b>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="ss--text-center">
                                                {!! $value['note'] !!}
                                            </td>
                                        </tr>
                                        @if(isset($listSerial[$value['inventory_checking_detail_id']]) && $value['inventory_management'] == 'serial' && count($listSerial[$value['inventory_checking_detail_id']]) != 0)
                                            <tr class="ss--font-size-13 ss--nowrap">
                                                <td></td>
                                                <td colspan="7">
                                                    <h5 style="white-space: initial">
                                                        @foreach($listSerial[$value['inventory_checking_detail_id']] as $key => $itemSerial)
                                                            @if($key < 10)
                                                                @if($itemSerial['is_new'] == 1)
                                                                    <span class="badge badge-pill badge-secondary mr-3" style="background:#66C0B8">{{$itemSerial['is_default'] == 0 ? $itemSerial['inventory_checking_status_name'].' | ' : ''}}{{$itemSerial['serial']}}</span>
                                                                @else
                                                                    <span class="badge badge-pill badge-secondary mr-3">{{$itemSerial['is_default'] == 0 ? $itemSerial['inventory_checking_status_name'].' | ' : ''}}{{$itemSerial['serial']}}</span>
                                                                @endif

                                                            @endif
                                                        @endforeach
                                                    </h5>
                                                </td>
                                                <td class="text-center">
                                                    @if(count($listSerial[$value['inventory_checking_detail_id']]) > 9)
                                                        <a href="javascript:void(0)" onclick="InventoryChecking.showPopupListSerial({{$value['inventory_checking_detail_id']}})">{{__('Xem thêm')}}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
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
                                            <li><a onclick="InventoryChecking.pageClick(1)" title="First"
                                                   class="m-datatable__pager-link m-datatable__pager-link--first"
                                                   data-page="1"><i
                                                            class="la la-angle-double-left">
                                                    </i></a></li>
                                            <li><a onclick="InventoryChecking.pageClick({{$page-1}})" title="Previous"
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
                                                       onclick="InventoryChecking.pageClick({{ $i }})"
                                                       title="1">{{ $i }}</a></li>
                                            @else
                                                <li><a class="m-datatable__pager-link"
                                                       onclick="InventoryChecking.pageClick({{ $i }})">{{ $i }}</a></li>
                                            @endif
                                        @endfor
                                        {{-- Next Page Link --}}
                                        @if($page<(int)(count($LIST)/10)+1)
                                            <li><a title="Next" class="m-datatable__pager-link"
                                                   onclick="InventoryChecking.pageClick({{$page+1}})"
                                                   data-page=""><i class="la la-angle-right"></i></a></li>
                                            <li><a title="Last" onclick="InventoryChecking.pageClick({{$totalPage-1}})"
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
                                        {{__('Hiển thị')}} 0 - 0 {{__('của')}} 0
                                    @endif
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <h5>{{__('Lịch sử kiểm kho')}}</h5>
                    <div class="table-responsive table-log-update">
                        @include('admin::inventory-checking.append.table-log')
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
                                    class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
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
    <input type="hidden" value="{{$inventoryChecking['warehouse_id']}}" id="warehouse_id">
    <div id="showPopup"></div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/inventory-checking/list.js?v='.time())}}"
            type="text/javascript"></script>
@endsection