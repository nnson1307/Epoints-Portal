<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('ĐÁNH GIÁ')</th>
            <th class="tr_thead_list">@lang('HÌNH/VIDEO')</th>
            <th class="tr_thead_list">@lang('NGƯỜI ĐÁNH GIÁ')</th>
            <th class="tr_thead_list">@lang('CÚ PHÁP')</th>
            <th class="tr_thead_list">@lang('NỘI DUNG')</th>
            <th class="tr_thead_list">@lang('NGÀY ĐÁNH GIÁ')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>
                        <a href="{{route('admin.rating-order.show', $item['id'])}}">
                            {{$item['order_code']}}
                        </a>
                    </td>
                    <td>
                        @for ($i = 1; $i <= $item['rating_value']; $i++)
                            <img src="{{asset('static/backend/images/star.png')}}" alt="Hình ảnh" width="15px"
                                 height="15px">
                        @endfor
                    </td>
                    <td>
                        <?php $numberImage = 0; ?>

                        <div class="div_image_before image-show">
                            <div class="wrap-img image-show-child">
                                @if (count($item['log_image']) > 0)
                                    @foreach($item['log_image'] as $k => $v)
                                        @if ($v['type'] == "image" && $k < 4)
                                            <img class="m--bg-metal m-image img-sd myImg" src="{{$v['link']}}"
                                                 alt="Hình ảnh"
                                                 width="40px" height="40px" onclick="view.clickViewImage('{{$v['link']}}')"
                                                 style="margin-top: {{$k > 1 ? '5' : '0'}}px;">
                                        @elseif($v['type'] == "video" && $k < 4)
                                            <img src="{{asset('static/backend/images/icon-video.png')}}" alt="Hình ảnh"
                                                 width="40px"
                                                 height="40px" onclick="view.clickViewVideo('{{$v['link']}}')">
                                        @endif
                                        @if ($k > 3)
                                            <?php $numberImage++ ?>
                                        @endif
                                    @endforeach
                                @endif

                                    @if($numberImage > 0)
                                        <span style="font-size: 15px; font-weight: bold;"> + {{$numberImage}}</span>
                                    @endif
                            </div>
                        </div>
                    </td>
                    <td>{{$item['full_name']}}</td>
                    <td>
                        @if (count($item['log_suggest']) > 0)
                            @foreach($item['log_suggest'] as $k => $v)
                                {{$k > 0 ? ', ': ''}} {{$v['content_suggest']}}
                            @endforeach
                        @endif
                    </td>
                    <td>{{$item['comment']}}</td>
                    <td>
                        {{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s')}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{$LIST->links('helpers.paging') }}
