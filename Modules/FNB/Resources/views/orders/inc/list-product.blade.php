
@php
$index = 0;   
@endphp
<style>
    .action-table-update {
        margin-top: -21px;
        margin-right: 13px;
    }
    .action-table-update span:hover {
        cursor: pointer;
    }
    .text-bell {
        font-size: 12px;
        position: absolute;
        top: 5px;
        color: #fff;
        right: -7px;
        background: red;
        width: 15px;
        text-align: center;
        border-radius: 50%;
    }

    .block-bell img {
        width : 20px
    }
</style>
{{--@php--}}
{{--    $index = $index + 1;--}}
{{--@endphp--}}
{{--@if($index == 1)--}}
{{--    <div class="row">--}}
{{--        @endif--}}
{{--<div class="row">--}}
@foreach($list as $item)

@if($type == 'area')
        <div class="col-sm-4 mb-4 position-relative">
            <h2 class="action-table  action-table-update" style="">
{{--                <span class="fa fa-check-circle check-table"></span>--}}
{{--                <span class="la la-print print"></span>--}}
                @if($item['using_money_table'] != 0)
                    <span class="la la-print print" onclick="PopupAction.showPopupPrint('{{$item['table_id']}}')"></span>
                @endif
                @if($item['customer_request'] != 0)
                    <span class="block-bell position-relative" onclick="PopupAction.showPopupCustomerRequest('{{$item['table_id']}}')">
                        <img src="{{asset('static/backend/images/fnb/bell.png')}}">
                        <span class="text-bell">
                            {{$item['customer_request']}}
                        </span>
                    </span>
                @endif

            </h2>
            @if(!isset($item['using_order_id_table']))
                <div class="info-table info-table-select {{$tableId == $item['table_id'] ? 'info-style-1' : ($item['using_table'] != 0 ? 'info-style-3' : 'info-style-2')}}  table-{{$item['table_id']}}"
                     @if($pageAdd)
                        onclick="order.selectTable('{{$item["table_id"]}}')"
                     @endif
                     data-table="{{$item['table_id']}}">
                    <div class="contact">
                        <span class="fa	fa-user-friends friends"></span>
                        <span class="friends"> {{ $item['seats'] }} </span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center pt-2">
                        <span class="table-name">
                            {{ $item['name'] }}
                        </span>
                    </div>
                    @if($item['using_table'] != 0)
                        <div class="amount">
                            <span class="la la-tag tag-price"></span>
                            <span class="price" style="color:darkcyan"> {{number_format($item['using_money_table'])}}đ</span>
                        </div>
                    @endif

                </div>
            @else
                <a href="{{route('fnb.orders.receipt',$item['using_order_id_table']) . '?type=order'}}">
                    <div class="info-table info-table-select {{$tableId == $item['table_id'] ? 'info-style-1' : ($item['using_table'] != 0 ? 'info-style-3' : 'info-style-2')}}  table-{{$item['table_id']}}"
                         @if($pageAdd)
                         onclick="order.selectTable('{{$item["table_id"]}}')"
                         @endif
                         data-table="{{$item['table_id']}}">
                        <div class="contact">
                            <span class="fa	fa-user-friends friends"></span>
                            <span class="friends"> {{ $item['seats'] }} </span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center pt-2">
                            <span class="table-name">
                                {{ $item['name'] }}
                            </span>
                        </div>
                        @if($item['using_table'] != 0)
                            <div class="amount">
                                <span class="la la-tag tag-price"></span>
                                <span class="price" style="color:darkcyan"> {{number_format($item['using_money_table'])}}đ</span>
                            </div>
                        @endif

                    </div>
                </a>
            @endif
        </div>
@else
    @if(isset($item['is_sale']) && $item['is_sale'] == 1)
        <div class="col-xl-3" style="cursor: pointer;"
        onclick="order.append_table('{{$item['product_id']}}','{{$item['id']}}','{{($item['promotion_price'])}}','{{$item['type']}}','{{$item['product_name']}}','{{$item['code']}}', '{{$item['is_surcharge']}}','{{isset($item['inventory_management']) ? $item['inventory_management'] : 'none'}}')">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
                <div class="m-portlet__head m-portlet__head--fit">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-action">
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding: 0;">
                    <div class="m-widget19">
                        <div class="m-widget19__pic m-portlet-fit--top m-portlet-fit--sides" style="min-height-: 286px">
                            @if($item['avatar']!=null)
                                <img src="{{asset($item['avatar'])}}" alt="" style="height: 120px">
                            @else
                                <img src="{{asset('static/backend/images/default-placeholder.png')}}" alt="" style="height: 120px">
                            @endif
                            <div class="m-widget19__shadow"></div>
                        </div>

                    </div>
                    <div class="m-widget19__content">
                        <div class="m-widget19__header">
                            <div class="m-widget19__body" style="max-height: 300px;">
                                <span style="font-weight: 500;font-size: 14px;">{{$item['product_name']}}</span>
                            </div>

                        <span style="font-weight: 500;font-size: 14px; color: #f57224;">{{($item['promotion_price'])}}@lang('đ')</span><br>
                        <span style="font-weight: 500;font-size: 12px; color: #9e9e9e; text-decoration: line-through;">{{($item['price'])}}@lang('đ')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="col-xl-4" style="cursor: pointer;" onclick="order.append_table('{{$item['product_id']}}','{{$item['id']}}','{{($item['price_hidden'])}}','{{$item['type']}}','{{$item['product_name']}}','{{$item['code']}}', '{{$item['is_surcharge']}}','{{isset($item['inventory_management']) ? $item['inventory_management'] : 'none'}}')">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="w-100 block-product-new">
                        <div class="images" style="background: url('{{isset($item['avatar']) ? $item['avatar'] : asset('images/no-image.png')}}')">
{{--                            <img src="{{$item['avatar']}}" alt="">--}}
                        </div>
                        <div class="block-content">
                            <p class="prod-name-new" title="{{$item['product_name']}}">{{$item['product_name']}}</p>
                            <p class="text-danger price_product">{{($item['price'])}}@lang('đ')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endif
{{--@if($index == 4)--}}
{{--</div>--}}
{{--@php--}}
{{--    $index = 0;   --}}
{{--@endphp--}}
{{--@endif--}}
@endforeach
{{--</div>--}}

