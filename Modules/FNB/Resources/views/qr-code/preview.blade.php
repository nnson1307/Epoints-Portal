@if($page == 'created')
<div style="width: 300px ; margin: auto">
    <svg viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%; background-color: rgb(255, 255, 255);">
        {!! $detailFrame['image'] !!}
        @if(isset($text))
            <g transform="{{$detailFrame['transform_text']}}"><style>
                    .small38{
                        fill:{{$color}};
                        font-size:25px;
                        font-family: {{isset($font) ? $font : 'Roboto, sans-serif'}};
                    }</style>
                <text x="0" y="-11" text-anchor="middle" class="small38">{{$text}}</text>
            </g>
        @endif

        <g transform="{{$detailFrame['transform_qr_code']}}" fill="none">
            @if(isset($logo))
                <svg width="300" height="300">
                    <style>.background-color{ fill: transparent; }.dot-color{ fill: #000000; }.corners-square-color-0-0{ fill: #000000; }.corners-dot-color-0-0{ fill: #000000; }.corners-square-color-1-0{ fill: #000000; }.corners-dot-color-1-0{ fill: #000000; }.corners-square-color-0-1{ fill: #000000; }.corners-dot-color-0-1{ fill: #000000; }</style>
                    <image
                            width="280"
                            height="280"
                            xlink:href="data:image/png;base64,{!!
                            base64_encode(QrCode::format('png')
                            ->merge($logo, 0.3, true)
                            ->size(400)->errorCorrection('H')
                            ->generate($config['value']))
                        !!}"
                    />
                </svg>
            @else
                {!! QrCode::size(280)->generate($config['value']); !!}
            @endif
        </g>
    </svg>
</div>
@else
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    </head>
    <body>

    <div class="container-fluid">
        @foreach($listQr as $item)
            <div class=" block-qr mb-5" style="width:45%; display:inline-block;text-align:center">
                <svg viewBox="0 0 350 350" xmlns="http://www.w3.org/2000/svg" width="80%" data-code="{{$item['code']}}">
                    @if($item['frames_frames_id'] != 1)
                        {!! $item['frames_image'] !!}
                    @endif
                    @if(isset($item['template_content']))
                        <g transform="{{$item['transform_text']}}">
                            <style>
                                .small38{
                                    fill:{{$item['template_color']}};
                                    font-size:25px;
                                    font-family: {{isset($item['font_value']) ? $item['font_value'] : 'Roboto, sans-serif'}} ;
                                }</style>
                            <text x="0" y="-11" text-anchor="middle" class="small38">
                                {{$item['template_content']}}
                            </text>

                        </g>
                    @endif

                    <g transform="{{$item['transform_qr_code']}}" >
                        @if(isset($item['template_logo']))
                            <svg width="300" height="300">
                                <style>.background-color{ fill: transparent; }.dot-color{ fill: #000000; }.corners-square-color-0-0{ fill: #000000; }.corners-dot-color-0-0{ fill: #000000; }.corners-square-color-1-0{ fill: #000000; }.corners-dot-color-1-0{ fill: #000000; }.corners-square-color-0-1{ fill: #000000; }.corners-dot-color-0-1{ fill: #000000; }</style>
                                <image
                                        width="280"
                                        height="280"
                                        xlink:href="data:image/png;base64,{!!
                                                                        base64_encode(QrCode::format('png')
                                                                        ->merge($item['template_logo'], 0.3, true)
                                                                        ->size(400)->errorCorrection('H')
                                                                        ->generate($config['value']))
                                                                    !!}"
                                />
                            </svg>
                        @else
                            {!! QrCode::size(300)->generate($item['url']); !!}
                        @endif
                    </g>
                    <g transform = "translate(150, 360)scale(1.5)">
                        <style>
                            .small39{
                                {{--fill:{{$item['template_color']}};--}}
                                font-size:25px;
                                font-family: {{isset($item['font_value']) ? $item['font_value'] : 'Roboto, sans-serif'}} ;
                            }</style>
                        <text x="0" y="-11" text-anchor="middle" class="small39">
                            {{$item['areas_name']}} - {{$item['table_name']}}
                        </text>
                    </g>
                </svg>
            </div>
        @endforeach
    </div>
    </body>
    </html>
@endif