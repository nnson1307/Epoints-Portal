@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services-card.png')}}" alt="" style="height: 20px;">
        {{__('THẺ DỊCH VỤ')}}
    </span>
@endsection
@section('content')
    <style>
        .img {
            border-radius: 10px;
            vertical-align: middle;
            width: 130px;
            height: 130px;
            margin: 2px;
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
                        {{__('CHI TIẾT THẺ DỊCH VỤ')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-4 ss--font-size-13">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Nhóm thẻ')}}: </label> <label for="">{{$serviceCard['group_name']}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Tên thẻ')}}: </label> <label>{{$serviceCard['card_name']}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ss--font-size-13">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Mã định danh')}}: </label> <label>{{$serviceCard['code']}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Giá bán')}}: </label> <label>{{number_format($serviceCard['price'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    {{__('VNĐ')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            {{__('Phụ thu')}}:
                        </label>
                        <div class="row">
                            <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input disabled id="is_surcharge" name="is_surcharge"
                                                   type="checkbox" {{$serviceCard['is_surcharge']==1?'checked':''}}>
                                            <span></span>
                                        </label>
                                    </span>
                            </div>
                            <div class="col-lg-6 m--margin-top-5">
                                <i>{{__('Chọn để kích hoạt phụ thu')}}</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" style="margin-bottom: 0;"
                        role="tablist">
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link active son" data-toggle="tab" href="#m_tabs_6_1"
                               role="tab">
                                <h7>{{__('THẺ ĐÃ BÁN')}}</h7>
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link son" data-toggle="tab" href="#m_tabs_6_3" role="tab">
                                <h7>{{__('THẺ ĐÃ SỬ DỤNG')}}</h7>
                            </a>
                        </li>
                    </ul>
                    <div class="bd-ct">
                        <div class="tab-content">
                            <div class="tab-pane active list-service-card " id="m_tabs_6_1"
                                 role="tabpanel">
                                <div class="table-responsive">
                                    <table id="add-product-version"
                                           class="table table-striped m-table ss--header-table ss--nowrap">
                                        <thead>
                                        <tr class="ss--font-size-th">
                                            <th>#</th>
                                            <th>{{__('MÃ THẺ DỊCH VỤ')}}</th>
                                            <th class="ss--text-center">{{__('KHÁCH HÀNG')}}</th>
                                            <th class="ss--text-center">{{__('CHI NHÁNH')}}</th>
                                            <th class="ss--text-center">{{__('NGÀY BÁN')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($arrayAllServiceCard as $key=>$value)
                                            <tr class="ss--font-size-13">
                                                <td>{{($key+1)}}</td>
                                                <td>
                                                    @if($value['isActived']==1)
                                                        {{ $value['code'] }}
                                                    @else
                                                        ****************
                                                    @endif
                                                </td>
                                                <td class="ss--text-center ss--font-size-13">{{ $value['customer'] }}</td>
                                                <td class="ss--text-center ss--font-size-13">{{ $value['branch'] }}</td>
                                                <td class="ss--text-center ss--font-size-13">{{ $value['createdAt'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row ss--m--margin-top--20 m--margin-bottom-20">
                                    <div class="m-datatable m-datatable--default col-lg-12">
                                        <div class="m-datatable__pager m-datatable--paging-loaded">
                                            @if(count($data)>10)
                                                <ul class="m-datatable__pager-nav" style="float: right">
                                                    @if(count($data)>0)
                                                        @if($page>1)
                                                            <li><a onclick="firstAndLastPage(1)" title="First"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--first"
                                                                   data-page="1"><i
                                                                            class="la la-angle-double-left">
                                                                    </i></a></li>
                                                            <li><a onclick="firstAndLastPage({{$page-1}})"
                                                                   title="Previous"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--prev"><i
                                                                            class="la la-angle-left"></i></a></li>
                                                        @else
                                                            <li><a title="First"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled"
                                                                   disabled="disabled"><i
                                                                            class="la la-angle-double-left"></i></a>
                                                            </li>
                                                            <li><a title="Previous"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled"
                                                                   disabled="disabled"><i class="la la-angle-left"></i></a>
                                                            </li>
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
                                                            @if($i==1)
                                                                <li>
                                                                    <a class="m-datatable__pager-link m-datatable__pager-link--active"
                                                                       onclick="pageClick(this)"
                                                                       title="1">{{ $i }}</a></li>
                                                            @else
                                                                <li><a class="m-datatable__pager-link"
                                                                       onclick="pageClick(this)">{{ $i }}</a></li>
                                                            @endif
                                                        @endfor
                                                        @if($page<(int)(count($arrayAllServiceCard)/10)+1)
                                                            <li><a title="Next" class="m-datatable__pager-link"
                                                                   onclick="firstAndLastPage({{$page+1}})"
                                                                   data-page=""><i class="la la-angle-right"></i></a>
                                                            </li>
                                                            <li><a title="Last"
                                                                   onclick="firstAndLastPage({{$totalPage-1}})"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--last"
                                                                   data-page=""><i class="la la-angle-double-right"></i></a>
                                                            </li>
                                                        @else
                                                            <li><a title="Next"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--disabled"
                                                                   disabled="disabled"
                                                                   data-page=""><i class="la la-angle-right"></i></a>
                                                            </li>
                                                            <li><a title="Last"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled"
                                                                   disabled="disabled"
                                                                   data-page=""><i class="la la-angle-double-right"></i></a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            @endif
                                            <div class="m-datatable__pager-info" style="float: left">
                                                <span class="m-datatable__pager-detail">
                                                    @if(count($data)>0)
                                                        {{__('Hiển thị')}} {{($page-1)*10+1}}
                                                        - {{($page-1)*10 + count($arrayAllServiceCard)}}
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
                            <div class="tab-pane" id="m_tabs_6_3" role="tabpanel">
                                <div id="card-used">
                                    <div class="table-responsive">
                                        <table id="add-product-version"
                                               class="table table-striped m-table ss--header-table ss--nowrap">
                                            <thead>
                                            <tr class="ss--font-size-th">
                                                <th>#</th>
                                                <th>{{__('MÃ THẺ DỊCH VỤ')}}</th>
                                                <th class="ss--text-center">{{__('KHÁCH HÀNG')}}</th>
                                                <th class="ss--text-center">{{__('NGÀY SỬ DỤNG')}}</th>
                                                <th class="ss--text-center">{{__('TRẠNG THÁI')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($arrayAllCardUsed as $key=>$value)
                                                <tr class="ss--font-size-13">
                                                    <td>{{($key+1)}}</td>
                                                    <td>{{ $value['card_code'] }}</td>
                                                    <td class="ss--text-center">{{ $value['full_name'] }}</td>
                                                    <td class="ss--text-center">{{date_format(new DateTime($value['day_use'] ), 'd/m/Y')}}</td>
                                                    <td class="ss--text-center">
                                                        @if(strtotime(date("Y-m-d")) > strtotime(date_format(new DateTime($value['expired_date']), 'Y-m-d')) )
                                                            <b class="m--font-danger">{{__('Hết hạn')}}</b>
                                                        @else
                                                            <b class="m--font-success">{{__('Đang sử dụng')}}</b>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row ss--m--margin-top--20 m--margin-bottom-20">
                                        <div class="m-datatable m-datatable--default col-lg-12">
                                            <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
                                                @if(count($dataCardUsed)>10)
                                                    <ul class="m-datatable__pager-nav" style="float: right">
                                                        @if($page>1)
                                                            <li><a onclick="CardUsed.firstAndLastPage(1)" title="First"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--first"
                                                                   data-page="1"><i
                                                                            class="la la-angle-double-left">
                                                                    </i></a></li>
                                                            <li><a onclick="CardUsed.firstAndLastPage({{$page-1}})"
                                                                   title="Previous"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--prev"><i
                                                                            class="la la-angle-left"></i></a></li>
                                                        @else
                                                            <li><a title="First"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled"
                                                                   disabled="disabled"><i
                                                                            class="la la-angle-double-left"></i></a>
                                                            </li>
                                                            <li><a title="Previous"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled"
                                                                   disabled="disabled"><i class="la la-angle-left"></i></a>
                                                            </li>
                                                        @endif
                                                        <?php
                                                        $totalPage2 = 0;
                                                        if (is_int(count($dataCardUsed) / 10) == true) {
                                                            $totalPage2 = (count($dataCardUsed) / 10) + 1;
                                                        } else {
                                                            $totalPage2 = (int)(count($dataCardUsed) / 10) + 2;
                                                        }
                                                        ?>
                                                        @for ($i=1;$i<$totalPage2;$i++)
                                                            @if($i==$page)
                                                                <li>
                                                                    <a class="m-datatable__pager-link m-datatable__pager-link--active"
                                                                       onclick="CardUsed.pageClick(this)"
                                                                       title="1">{{ $i }}</a></li>
                                                            @else
                                                                <li><a class="m-datatable__pager-link"
                                                                       onclick="CardUsed.pageClick(this)">{{ $i }}</a>
                                                                </li>
                                                            @endif
                                                        @endfor
                                                        {{-- Next Page Link --}}
                                                        @if($page<(int)(count($arrayAllServiceCard)/10)+1)
                                                            <li><a title="Next" class="m-datatable__pager-link"
                                                                   onclick="CardUsed.firstAndLastPage({{$page+1}})"
                                                                   data-page=""><i class="la la-angle-right"></i></a>
                                                            </li>
                                                            <li><a title="Last"
                                                                   onclick="CardUsed.firstAndLastPage({{$totalPage2-1}})"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--last"
                                                                   data-page=""><i class="la la-angle-double-right"></i></a>
                                                            </li>
                                                        @else
                                                            <li><a title="Next"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--disabled"
                                                                   disabled="disabled"
                                                                   data-page=""><i class="la la-angle-right"></i></a>
                                                            </li>
                                                            <li><a title="Last"
                                                                   class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled"
                                                                   disabled="disabled"
                                                                   data-page=""><i class="la la-angle-double-right"></i></a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                @endif
                                                <div class="m-datatable__pager-info" style="float: left">
                                                    <span class="m-datatable__pager-detail">
                                                        @if(count($arrayAllCardUsed)>0)
                                                            {{__('Hiển thị')}} {{($page-1)*10+1}}
                                                            - {{($page-1)*10 + count($arrayAllCardUsed)}}
                                                            {{__('của')}} {{ count($dataCardUsed) }}
                                                        @else
                                                            {{__('Hiển thị 0 - 0 của 0')}}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m--margin-bottom-20"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.service-card')}}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                           <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>{{__('HỦY')}}</span>
                                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="idCard" value="{{$idCard}}">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card/detail.js')}}" type="text/javascript"></script>
@stop
