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
    <style>
        .w-10percent {
            width:10% !important;
        }

        #popup-list-serial .modal-dialog {
            max-width: 80%;
        }
        .badge-secondary i:hover {
            cursor: pointer
        }
    </style>
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
                            {{__('CHI TIẾT PHIẾU NHẬP')}}
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
                                    {{__('Mã phiếu')}}: {{$inventoryInput->code}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Kho hàng')}}: {{$inventoryInput->warehouseName}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Người tạo')}}: {{$inventoryInput->user}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Trạng thái')}}:
                            @if($inventoryInput->status=='new')
                                <span>{{__('Mới')}}</span>
                            @elseif($inventoryInput->status=='success')
                                <span>{{__('Hoàn thành')}}</span>
                            @elseif($inventoryInput->status=='draft')
                                <span>{{__('Lưu nháp')}}</span>
                            @elseif($inventoryInput->status=='cancel')
                                <span>{{__('Hủy')}}</span>
                            @elseif($inventoryInput->status=='inprogress')
                                <span>{{__('Đang xử lý')}}</span>
                            @endif
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Loại')}}: @if($inventoryInput->type=='normal')
                                <span>{{__('Thường')}}</span>
                            @elseif($inventoryInput->type=='transfer')
                                <span>{{__('Chuyển kho')}}</span>
                            @elseif($inventoryInput->type=='checking')
                                <span>{{__('Kiểm kho')}}</span>
                            @elseif($inventoryInput->type=='return')
                                <span>{{__('Hủy')}}</span>
                            @endif
                        </label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Nhà cung cấp')}}: {{$inventoryInput->supplierName}}
                        </label>

                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Ngày tạo')}}: {{(new DateTime($inventoryInput->createdAt))->format('d/m/Y')}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Ghi chú')}}: {{$inventoryInput->note}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Tổng số lượng')}}: {{$inventoryInput->total_quantity}}
                        </label>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Tổng tiền')}}: {{number_format($inventoryInput->total_money)}}
                        </label>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="table-content">
                        {{--Table version--}}
                        <div class="table-responsive">
                            <table id="add-product-version"
                                   class="table table-striped ss--header-table">
                                <thead>
                                <tr class="ss--font-size-th ss--uppercase ss--nowrap">
                                    <th>#</th>
                                    <th>{{__('Mã sản phẩm')}}</th>
                                    <th>{{__('Sản phẩm')}}</th>
                                    <th class="ss--text-center">{{__('Đơn vị tính')}}</th>
                                    <th class="ss--text-center">{{__('Giá nhập')}}</th>
                                    <th class="ss--text-center">{{__('Số lượng')}}</th>
                                    <th class="ss--text-center">{{__('Tổng tiền')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($LIST as $key=>$value)
                                    <tr class="ss--font-size-13 ss--nowrap">
                                        <td class="text-center">{{($key+1)}}</td>
                                        <td class="">{{ $value['code'] }}</td>
                                        <td class="">{{ $value['childName'] }}</td>
                                        <td class="ss--text-center">
                                            {{ $value['unitName'] }}
                                        </td>
                                        <td class="ss--text-center">
                                            {{number_format($value['currentPrice'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                        </td>
                                        <td class="ss--text-center">
                                            {{number_format($value['quantity'],0,",",".")}}
                                        </td>
                                        <td class="ss--text-center">
                                            {{number_format($value['total'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                        </td>
                                    </tr>
                                    @if(isset($listSerial[$value['inventory_input_detail_id']]) && $value['inventory_management'] == 'serial' && count($listSerial[$value['inventory_input_detail_id']]) != 0)
                                        <tr class="ss--font-size-13 ss--nowrap">
                                            <td></td>
                                            <td colspan="5">
                                                <h5 style="white-space: initial">
                                                    @foreach($listSerial[$value['inventory_input_detail_id']] as $key => $itemSerial)
                                                        @if($key <= 9)
                                                            <span class="badge badge-pill badge-secondary mr-3">{{$itemSerial['serial']}}</span>
                                                        @endif
                                                    @endforeach
                                                </h5>
                                            </td>
                                            <td class="text-center">
                                                @if(count($listSerial[$value['inventory_input_detail_id']]) > 10)
                                                    <a href="javascript:void(0)" onclick="InventoryInput.showPopupListSerial({{$value['inventory_input_detail_id']}})">{{__('Xem thêm')}}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="m-datatable m-datatable--default">
                            <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
                                @if((int)(count($data))>10)
                                    <ul class="m-datatable__pager-nav" style="float: right">
                                        @if($page>1)
                                            <li><a onclick="InventoryInput.pageClick(1)" title="First"
                                                   class="m-datatable__pager-link m-datatable__pager-link--first"
                                                   data-page="1"><i
                                                            class="la la-angle-double-left">
                                                    </i></a></li>
                                            <li><a onclick="InventoryInput.pageClick({{$page-1}})" title="Previous"
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
                                                       onclick="InventoryInput.pageClick({{ $i }})"
                                                       title="1">{{ $i }}</a></li>
                                            @else
                                                <li><a class="m-datatable__pager-link"
                                                       onclick="InventoryInput.pageClick({{ $i }})">{{ $i }}</a></li>
                                            @endif
                                        @endfor
                                        {{-- Next Page Link --}}
                                        @if($page<(int)(count($LIST)/10)+1)
                                            <li><a title="Next" class="m-datatable__pager-link"
                                                   onclick="InventoryInput.pageClick({{$page+1}})"
                                                   data-page=""><i class="la la-angle-right"></i></a></li>
                                            <li><a title="Last" onclick="InventoryInput.pageClick({{$totalPage-1}})"
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
                                    class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md">
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
    <div id="showPopup"></div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/inventory-input/list.js?v='.time())}}"
            type="text/javascript"></script>
@endsection

