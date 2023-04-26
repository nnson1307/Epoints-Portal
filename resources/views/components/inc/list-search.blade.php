<div class="m-widget4" id="search_wig4">
<?php
$color = ["success", "brand", "danger", "accent", "warning", "metal", "primary", "info"];
?>
@if(count($DATA)>0)
    @foreach($DATA as $item)
        @php($num = rand(0,7))
        <!--begin::Widget 14 Item-->
            @if(isset($item['code']) && $item['code'] != '')
                <div class="m-widget4__item" onclick="Layout.clickDetail('{{$item['id']}}','{{$item['code']}}')">
                    @else
                        <div class="m-widget4__item"
                             onclick="Layout.clickDetail('{{$item['id']}}','{{$item['name']}}')">
                            @endif
                            @if(isset($item['code']) && $item['code'] != '')
                                <span style="width: 150px;">
                                <div class="m-card-user m-card-user--sm">
                                <div class="m-card-user__pic">
                                <div class="m-card-user__no-photo m--bg-fill-{{$color[$num]}}">
                                <span>
                                    @if(isset($item['code']) && $item['code'] != '')
                                        {{substr(str_slug($item['code']),0,2)}}
                                    @else
                                        {{substr(str_slug($item['name']),0,1)}}
                                    @endif
                                </span>
                                </div>
                                </div>
                                </div>
                                </span>
                            @else
                                @if($item['customer_avatar']!=null)
                                    <div class="m-card-user m-card-user--sm">
                                        <div class="m-card-user__pic">
                                            <img src="/{{$item['customer_avatar']}}"
                                                 onerror="this.onerror=null;this.src='https://placehold.it/30x30/00a65a/ffffff/&text=' +
                                                         '{{substr(str_slug($item['name']),0,1)}}';"
                                                 class="m--img-rounded m--marginless" alt="photo" width="40px"
                                                 height="40px">
                                        </div>
                                    </div>
                                @else
                                    <span style="width: 150px;">
                                <div class="m-card-user m-card-user--sm">
                                <div class="m-card-user__pic">
                                <div class="m-card-user__no-photo m--bg-fill-{{$color[$num]}}">
                                <span>
                                    @if(isset($item['code']) && $item['code'] != '')
                                        {{substr(str_slug($item['code']),0,2)}}
                                    @else
                                        {{substr(str_slug($item['name']),0,1)}}
                                    @endif
                                </span>
                                </div>
                                </div>
                                </div>
                                </span>
                                @endif
                            @endif
                            <div class="m-widget4__info">
                                @if(isset($item['name']) && isset($item['name']) != '')
                                    <span class="m-widget4__title">
                        <span class="m--font-transform-u">{{$item['name']}}</span>
                        </span><br>
                                @else
                                    <span class="m-widget4__title">
                        <span class="m--font-transform-u">{{__('Khách hàng vãng lai')}}</span>
                        </span><br>
                                @endif
                                @if(isset($item['phone']) && $item['phone'] != '')
                                    <span class="m-widget4__sub m--valign-middle">
                                         <i class="flaticon-support"></i>
                                        <span class="m--font-boldest">{{$item['phone']}}
                                            @if(isset($item['code']) && isset($item['code']) != '')
                                                -
                                            @endif
                                        </span>
                                    </span>
                                @endif
                                @if(isset($item['code']) && isset($item['code']) != '')
                                    <span class="m-widget4__sub m--valign-middle">
                                 @lang('Mã'): <span class="m--font-boldest">{{$item['code']}}</span>
                        </span><br>
                                @endif
                            </div>
                        </div>

                        <!--end::Widget 14 Item-->
                        @endforeach
                        @else
                            <span class="m-list-search__result-message">
            @lang('Không tìm thấy dữ liệu')
        </span>
                        @endif
                </div>
</div>

