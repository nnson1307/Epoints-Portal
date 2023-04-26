<div class="block-qr">
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