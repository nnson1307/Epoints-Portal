@php
    $index = 0;
@endphp

@foreach($list as $item)
    @php
        $index = $index + 1;
    @endphp
    @if($index == 1)
        <div class="row">
            @endif
            @if($type == 'member_card')
                <div class="col-xl-3" style="cursor: pointer;"
                     onclick="order.append_table_card('{{ $item['customer_service_card_id'] }}','0','member_card','{{ $item['card_name'] }}','{{ $item['count_using'] }}','{{ $item['card_code'] }}',this)">
                    <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
                        <div class="m-portlet__head m-portlet__head--fit">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-action">
                                    {{-- <button type="button" class="btn btn-sm m-btn--pill  btn-brand">Blog</button> --}}
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body" style="padding: 0;">
                            <div class="m-widget19">
                                <div class="m-widget19__pic m-portlet-fit--top m-portlet-fit--sides"
                                     style="min-height-: 286px">
                                    @if($item['image']!=null)
                                        <img src="{{asset($item['image'])}}" alt="" style="height: 120px">
                                    @else
                                        <img src="{{asset('static/backend/images/default-placeholder.png')}}" alt=""
                                             style="height: 120px">
                                    @endif
                                    <div class="m-widget19__shadow"></div>
                                </div>

                            </div>
                            <div class="m-widget19__content">
                                <div class="m-widget19__header">
                                    <div class="m-widget19__body" style="max-height: 300px;">
                                        <span style="font-weight: 500;font-size: 14px;">{{$item['card_name']}}</span>
                                    </div>

                                    <span style="font-weight: 500;font-size: 14px; color: #f57224;">@lang('Còn') ({{($item['count_using'])}}) @lang('lần')</span><br>
                                    {{-- <span style="font-weight: 500;font-size: 12px; color: #9e9e9e; text-decoration: line-through;">{{($item['price'])}}@lang('đ')</span> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @if(isset($item['is_sale']) && $item['is_sale'] == 1)
                    <div class="col-xl-3" style="cursor: pointer;"
                         onclick="order.append_table('{{$item['id']}}','{{($item['promotion_price'])}}','{{$item['type']}}','{{$item['name']}}','{{$item['code']}}', '{{$item['is_surcharge']}}','{{isset($item['inventory_management']) ? $item['inventory_management'] : 'none'}}', '{{$item['is_object_attach']}}')">
                        <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
                            <div class="m-portlet__head m-portlet__head--fit">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-action">
                                        {{-- <button type="button" class="btn btn-sm m-btn--pill  btn-brand">Blog</button> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body" style="padding: 0;">
                                <div class="m-widget19">
                                    <div class="m-widget19__pic m-portlet-fit--top m-portlet-fit--sides"
                                         style="min-height-: 286px">
                                        @if($item['avatar']!=null)
                                            <img src="{{asset($item['avatar'])}}" alt="" style="height: 120px">
                                        @else
                                            <img src="{{asset('static/backend/images/default-placeholder.png')}}" alt=""
                                                 style="height: 120px">
                                        @endif
                                        <div class="m-widget19__shadow"></div>
                                    </div>

                                </div>
                                <div class="m-widget19__content">
                                    <div class="m-widget19__header">
                                        <div class="m-widget19__body" style="max-height: 300px;">
                                            <span style="font-weight: 500;font-size: 14px;">{{$item['name']}}</span>
                                        </div>

                                        <span style="font-weight: 500;font-size: 14px; color: #f57224;">{{($item['promotion_price'])}}@lang('đ')</span><br>
                                        <span style="font-weight: 500;font-size: 12px; color: #9e9e9e; text-decoration: line-through;">{{($item['price'])}}@lang('đ')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-xl-3" style="cursor: pointer;"
                         onclick="order.append_table('{{$item['id']}}','{{($item['price_hidden'])}}','{{$item['type']}}','{{$item['name']}}','{{$item['code']}}', '{{$item['is_surcharge']}}','{{isset($item['inventory_management']) ? $item['inventory_management'] : 'none'}}', '{{$item['is_object_attach']}}')">
                        <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
                            <div class="m-portlet__head m-portlet__head--fit">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-action">
                                        {{-- <button type="button" class="btn btn-sm m-btn--pill  btn-brand">Blog</button> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body" style="padding: 0;">
                                <div class="m-widget19">
                                    <div class="m-widget19__pic m-portlet-fit--top m-portlet-fit--sides"
                                         style="min-height-: 286px">
                                        @if($item['avatar']!=null)
                                            <img src="{{asset($item['avatar'])}}" alt="" style="height: 120px">
                                        @else
                                            <img src="{{asset('static/backend/images/default-placeholder.png')}}" alt=""
                                                 style="height: 120px">
                                        @endif

                                        <div class="m-widget19__shadow"></div>
                                    </div>

                                </div>
                                <div class="m-widget19__content">
                                    <div class="m-widget19__header">
                                        <div class="m-widget19__body" style="max-height: 300px;">
                                            <span style="font-weight: 500;font-size: 14px;">{{$item['name']}}</span>
                                        </div>
                                        <span style="font-weight: 500;font-size: 14px; color: #f57224;">{{($item['price'])}}@lang('đ')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            @if($index == 4)
        </div>
        @php
            $index = 0;
        @endphp
    @endif
@endforeach


